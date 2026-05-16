<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\SizingCaptureMethod;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Order\OrderSizingPhotoController;
use App\Jobs\AutoCancelOrderJob;
use App\Jobs\SendPaymentReminderJob;
use App\Mail\OrderPlaced;
use App\Models\Customer;
use App\Models\Order;
use App\Notifications\NewOrderNotification;
use App\Models\OrderItem;
use App\Models\Product;
use App\Settings\StoreSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class OrderController extends Controller
{
    // ── Step 1: Sizing ────────────────────────────────────────────────────────

    /**
     * GET /order/start/{slug?}
     * Show the sizing options step.
     */
    public function start(Request $request, ?string $slug = null): View
    {
        // If a slug was provided (coming from product page), seed the bag in session.
        if ($slug) {
            // Merge this product into the session bag if not already present.
            $bag = session('order_form.bag', []);
            if (empty($bag)) {
                // Minimal product representation — real name/price served by JS bag API.
                // We rely on client-side bag data (localStorage) to fill in full details.
                // The slug is enough to identify what the customer is ordering.
                session(['order_form.bag' => [['slug' => $slug, 'qty' => 1]]]);
            }
        }

        $bag        = session('order_form.bag', []);
        $hasSizing  = session('order_form.sizing_method') !== null;
        $isReturning = session('order_form.is_returning', false);

        return view('order.start', compact('bag', 'hasSizing', 'isReturning', 'slug'));
    }

    /**
     * POST /order/start
     * Receive bag JSON from the checkout drawer, store in session, redirect back to start.
     */
    public function initFromBag(Request $request): RedirectResponse
    {
        $request->validate([
            'bag' => ['required', 'string'],
        ]);

        $bag = json_decode($request->input('bag'), true);

        if (! is_array($bag) || empty($bag)) {
            return redirect()->route('shop')->withErrors(['bag' => 'Your bag appears to be empty.']);
        }

        session(['order_form.bag' => $bag]);

        return redirect()->route('order.start');
    }

    /**
     * POST /order/start/sizing
     * Save the selected sizing method and redirect accordingly.
     */
    public function storeSizing(Request $request): RedirectResponse
    {
        $rules = [
            'sizing_method' => ['required', 'in:live_camera,upload,whatsapp_pending'],
        ];

        if ($request->input('sizing_method') === 'upload') {
            $rules['photo_fingers'] = ['required', 'file', 'mimes:jpeg,jpg,png,heic,heif,webp', 'max:8192'];
            $rules['photo_thumb']   = ['required', 'file', 'mimes:jpeg,jpg,png,heic,heif,webp', 'max:8192'];
        }

        $request->validate($rules);

        $method = $request->input('sizing_method');
        session(['order_form.sizing_method' => $method]);

        // Seed a demo bag if none exists (allows testing the full flow without
        // going through the shop first). Replaced by real bag data in production
        // when the customer arrives via the shop → bag drawer → checkout path.
        if (! session('order_form.bag')) {
            session(['order_form.bag' => [
                ['name' => 'Custom Set', 'price_pkr' => 2800, 'qty' => 1, 'tier' => 'signature'],
            ]]);
        }

        if ($method === 'live_camera') {
            return redirect()->route('order.camera');
        }

        // Process uploaded photos when the customer chose "upload" on the start page.
        if ($method === 'upload') {
            $this->processSizingUploads($request);
        }

        // Upload / WhatsApp: move to step 2.
        return redirect()->route('order.details');
    }

    /**
     * Save sizing photo files (from the start-page upload inputs) into temp storage
     * and persist their paths in the session for attachToOrder() to pick up later.
     */
    private function processSizingUploads(Request $request): void
    {
        if (! session('order_form.sizing_session_id')) {
            session(['order_form.sizing_session_id' => Str::ulid()]);
        }
        $sessionId = session('order_form.sizing_session_id');

        $storedPaths = [];
        $manager     = new ImageManager(new Driver());

        $map = [
            'photo_fingers'       => 'fingers',
            'photo_thumb'         => 'thumb',
            'photo_fingers_other' => 'fingers_other',
            'photo_thumb_other'   => 'thumb_other',
        ];

        foreach ($map as $field => $type) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $file     = $request->file($field);
            $filename = Str::ulid() . '.jpg';
            $dir      = "sizing/temp/{$sessionId}";

            Storage::disk('local')->makeDirectory($dir);
            $image    = $manager->read($file->getRealPath())->toJpeg(92);
            $fullPath = storage_path("app/private/{$dir}/{$filename}");
            $image->save($fullPath);

            $storedPaths[] = [
                'path'       => "{$dir}/{$filename}",
                'photo_type' => $type,
                'mime_type'  => 'image/jpeg',
                'file_size'  => filesize($fullPath) ?: null,
            ];
        }

        if (! empty($storedPaths)) {
            session(['order_form.sizing_photos' => $storedPaths]);
        }
    }

    /**
     * POST /order/start/customer-lookup
     * AJAX: Look up returning customer by phone or email.
     */
    public function customerLookup(Request $request)
    {
        $request->validate(['contact' => ['required', 'string', 'max:150']]);

        $customer = Customer::findByContact(trim($request->input('contact')));

        if (! $customer || ! $customer->has_sizing_on_file) {
            return response()->json(['found' => false]);
        }

        // Store returning-customer data in session.
        session([
            'order_form.is_returning'   => true,
            'order_form.customer_id'    => $customer->id,
            'order_form.sizing_method'  => SizingCaptureMethod::FromProfile->value,
            'order_form.customer' => [
                'name'    => $customer->name,
                'email'   => $customer->email,
                'phone'   => $customer->phone,
                'address' => $customer->default_shipping_address,
                'city'    => $customer->city,
                'postal'  => $customer->postal_code,
                'notes'   => '',
            ],
        ]);

        return response()->json([
            'found'    => true,
            'name'     => $customer->name,
            'last_order' => $customer->orders()->latest()->value('order_number'),
        ]);
    }

    // ── Step 2: Details ───────────────────────────────────────────────────────

    /**
     * GET /order/details
     */
    public function details(Request $request): View|RedirectResponse
    {
        if (! session('order_form.bag')) {
            return redirect()->route('order.start');
        }

        $bag         = session('order_form.bag', []);
        $isReturning = session('order_form.is_returning', false);
        $prefill     = session('order_form.customer', []);
        $totals      = $this->calculateTotals($bag, $isReturning);

        return view('order.details', compact('bag', 'isReturning', 'prefill', 'totals'));
    }

    /**
     * POST /order/details
     */
    public function storeDetails(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:150'],
            'phone'      => ['required', 'string', 'max:30'],
            'address'    => ['required', 'string', 'max:500'],
            'city'       => ['required', 'string', 'max:100'],
            'city_other' => ['nullable', 'string', 'max:100'],
            'postal'     => ['nullable', 'string', 'max:20'],
            'notes'      => ['nullable', 'string', 'max:1000'],
        ]);

        $city = $validated['city'] === 'Other' ? ($validated['city_other'] ?? 'Other') : $validated['city'];

        session([
            'order_form.customer' => [
                'name'    => $validated['name'],
                'email'   => $validated['email'],
                'phone'   => $validated['phone'],
                'address' => $validated['address'],
                'city'    => $city,
                'postal'  => $validated['postal'] ?? '',
                'notes'   => $validated['notes'] ?? '',
            ],
        ]);

        return redirect()->route('order.payment');
    }

    // ── Step 3: Payment ───────────────────────────────────────────────────────

    /**
     * GET /order/payment
     */
    public function payment(Request $request): View|RedirectResponse
    {
        if (! session('order_form.bag') || ! session('order_form.customer')) {
            return redirect()->route('order.start');
        }

        $bag         = session('order_form.bag', []);
        $isReturning = session('order_form.is_returning', false);
        $customer    = session('order_form.customer');
        $totals      = $this->calculateTotals($bag, $isReturning);
        $sizingMethod = session('order_form.sizing_method', 'whatsapp_pending');

        return view('order.payment', compact('bag', 'isReturning', 'customer', 'totals', 'sizingMethod'));
    }

    /**
     * POST /order/payment — create the order and redirect to confirmation.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'payment_method' => ['required', 'in:jazzcash,easypaisa,bank_transfer'],
        ]);

        if (! session('order_form.bag') || ! session('order_form.customer')) {
            return redirect()->route('order.start');
        }

        // Re-verify the bag against the database BEFORE pricing anything.
        // Customers control localStorage; treating any submitted price as authoritative is unsafe.
        $verifiedBag = $this->verifyBag(session('order_form.bag', []));
        if (empty($verifiedBag)) {
            return redirect()->route('shop')->withErrors(['bag' => 'Your bag is empty or contains items that are no longer available.']);
        }

        $isReturning = session('order_form.is_returning', false);
        $customer    = session('order_form.customer');
        $totals      = $this->calculateTotals($verifiedBag, $isReturning);
        $method      = PaymentMethod::from($request->input('payment_method'));
        $sizingMethod = SizingCaptureMethod::tryFrom(session('order_form.sizing_method', 'whatsapp_pending'));

        $order = DB::transaction(function () use ($verifiedBag, $isReturning, $customer, $totals, $method, $sizingMethod) {
            // Find or create customer record.
            $customerId = session('order_form.customer_id');
            $customerRecord = $customerId
                ? Customer::find($customerId)
                : Customer::firstOrCreate(
                    ['email' => $customer['email']],
                    [
                        'name'    => $customer['name'],
                        'phone'   => $customer['phone'],
                        'whatsapp'=> $customer['phone'],
                        'city'    => $customer['city'],
                        'default_shipping_address' => $customer['address'],
                        'postal_code' => $customer['postal'] ?? null,
                    ]
                );

            // Create the order with server-verified totals.
            $order = Order::create([
                'order_number'         => Order::generateOrderNumber(),
                'customer_id'          => $customerRecord->id,
                'customer_name'        => $customer['name'],
                'customer_email'       => $customer['email'],
                'customer_phone'       => $customer['phone'],
                'shipping_address'     => $customer['address'],
                'city'                 => $customer['city'],
                'postal_code'          => $customer['postal'] ?? null,
                'notes'                => $customer['notes'] ?? null,
                'subtotal_pkr'         => $totals['subtotal'],
                'reorder_discount_pkr' => $totals['discount'],
                'shipping_pkr'         => $totals['shipping'],
                'total_pkr'            => $totals['total'],
                'requires_advance'     => $totals['requires_advance'],
                'is_returning_customer'=> $isReturning,
                'payment_method'       => $method->value,
                'payment_status'       => PaymentStatus::Awaiting->value,
                'status'               => OrderStatus::New->value,
                'sizing_capture_method'=> $sizingMethod?->value,
            ]);

            // Create order items from the verified bag (server-side prices).
            // We intentionally don't write product_id — that column is typed
            // unsignedBigInteger but products use ULID PKs. We track the
            // product via product_slug_snapshot instead. (Cleanup pending.)
            foreach ($verifiedBag as $item) {
                OrderItem::create([
                    'order_id'                => $order->id,
                    'product_name_snapshot'   => $item['name'],
                    'product_tier_snapshot'   => $item['tier'],
                    'product_slug_snapshot'   => $item['slug'],
                    'unit_price_pkr'          => $item['price_pkr'],
                    'qty'                     => $item['qty'],
                ]);
            }

            // Update customer stats.
            $customerRecord->increment('total_orders');
            $customerRecord->increment('lifetime_value_pkr', $totals['total']);
            $customerRecord->update(['last_ordered_at' => now()]);

            return $order->fresh(['items']);
        });

        // Attach sizing photos from temp session storage → permanent order directory.
        OrderSizingPhotoController::attachToOrder($order);

        // Mark customer as having sizing on file if photos were attached.
        if ($order->sizingPhotos()->count() > 0 && $order->customer_id) {
            Customer::where('id', $order->customer_id)->update(['has_sizing_on_file' => true]);
        }

        // Send order confirmation email (non-blocking).
        try {
            Mail::to($order->customer_email)->send(new OrderPlaced($order));
        } catch (\Throwable $e) {
            Log::error('OrderPlaced mail failed', ['order' => $order->id, 'error' => $e->getMessage()]);
        }

        // Push notification to admin for new order. Notification class implements
        // ShouldQueue, so this enqueues one job per user and doesn't block the
        // request. chunkById avoids loading all admins into memory.
        try {
            \App\Models\User::query()->chunkById(50, function ($users) use ($order) {
                foreach ($users as $u) {
                    $u->notify(new NewOrderNotification($order));
                }
            });
        } catch (\Throwable $e) {
            Log::error('NewOrderNotification push failed', ['order' => $order->id, 'error' => $e->getMessage()]);
        }

        // Dispatch payment reminder jobs (24h and 48h) and auto-cancel job (72h).
        SendPaymentReminderJob::dispatch($order->id, 1)->delay(now()->addHours(24));
        SendPaymentReminderJob::dispatch($order->id, 2)->delay(now()->addHours(48));
        AutoCancelOrderJob::dispatch($order->id)->delay(now()->addHours(72));

        // Authorize this session to view the confirmation + tracking + proof upload for this order.
        $this->authorizeOrderForSession($order->id);

        // Clear the order form session data but keep the last order reference.
        session()->forget(['order_form.bag', 'order_form.customer', 'order_form.sizing_method',
                           'order_form.is_returning', 'order_form.customer_id']);

        return redirect()->route('order.confirm', $order->id);
    }

    /**
     * Re-fetch products from the database and rebuild the bag with verified
     * server-side prices, names, and tiers. Items without a slug or with an
     * inactive/unknown product are dropped — never trust client bag pricing.
     */
    private function verifyBag(array $bag): array
    {
        $slugs = collect($bag)
            ->pluck('slug')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($slugs)) {
            return [];
        }

        $products = Product::whereIn('slug', $slugs)
            ->where('is_active', true)
            ->get()
            ->keyBy('slug');

        $verified = [];
        foreach ($bag as $item) {
            $slug    = $item['slug']    ?? null;
            $product = $slug ? $products->get($slug) : null;
            if (! $product) {
                continue;
            }

            $qty = max(1, min(10, (int) ($item['qty'] ?? 1))); // hard-cap qty 1-10 per line

            $verified[] = [
                'product_id' => $product->id,
                'slug'       => $product->slug,
                'name'       => $product->name,
                'tier'       => is_string($product->tier) ? $product->tier : ($product->tier?->value ?? null),
                'price_pkr'  => (int) $product->price_pkr,
                'qty'        => $qty,
                'image'      => $product->cover_image
                    ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->cover_image)
                    : null,
            ];
        }

        return $verified;
    }

    /**
     * Add an order UUID to the session allowlist of orders this visitor
     * may view confirmation/tracking pages for, and upload proof to.
     */
    public static function authorizeOrderForSession(string $orderId): void
    {
        $allowed = session('order_form.authorized_orders', []);
        if (! in_array($orderId, $allowed, true)) {
            $allowed[] = $orderId;
        }
        // Keep only the most recent 10 — defensive bound for shared/public devices.
        $allowed = array_slice($allowed, -10);

        session(['order_form.authorized_orders' => $allowed]);
        session(['order_form.last_order_id' => $orderId]);
    }

    public static function sessionMayViewOrder(string $orderId): bool
    {
        return in_array(
            $orderId,
            session('order_form.authorized_orders', []),
            true
        );
    }

    // ── Confirmation ──────────────────────────────────────────────────────────

    /**
     * GET /order/confirm/{order}
     *
     * Only the session that placed (or successfully looked up) this order
     * may view the confirmation page. Anyone else is bounced to the
     * tracking lookup form so they can authenticate via order# + contact.
     */
    public function confirm(Request $request, string $orderId): View|RedirectResponse
    {
        if (! self::sessionMayViewOrder($orderId)) {
            return redirect()->route('track')
                ->withErrors(['lookup' => 'Please look up your order using the form below.']);
        }

        $order = Order::with(['items', 'paymentProofs'])->findOrFail($orderId);

        $settings      = app(StoreSettings::class);
        $isBridalTrio  = $order->items->contains(fn ($i) => $i->product_tier_snapshot === 'bridal_trio');
        $leadTimeDays  = $isBridalTrio
            ? $settings->lead_time_bridal_days
            : $settings->lead_time_standard_days;

        return view('order.confirm', compact('order', 'isBridalTrio', 'leadTimeDays'));
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Calculate order totals from the session bag. */
    private function calculateTotals(array $bag, bool $isReturning): array
    {
        $settings = app(StoreSettings::class);

        $subtotal = array_sum(array_map(
            fn ($i) => ((int) ($i['price_pkr'] ?? 0)) * ((int) ($i['qty'] ?? 1)),
            $bag
        ));

        $discountRate  = max(0, $settings->reorder_discount_percent) / 100;
        $discount      = $isReturning ? (int) round($subtotal * $discountRate) : 0;
        $afterDiscount = $subtotal - $discount;

        $freeAbove = $settings->shipping_free_above;
        $shipping  = ($freeAbove > 0 && $afterDiscount >= $freeAbove)
            ? 0
            : max(0, $settings->shipping_flat_pkr);

        $total           = $afterDiscount + $shipping;
        $requiresAdvance = $total >= max(0, $settings->advance_threshold_pkr);

        $isBridalTrio = collect($bag)->contains(fn ($i) => ($i['tier'] ?? '') === 'bridal_trio');

        return [
            'subtotal'         => $subtotal,
            'discount'         => $discount,
            'shipping'         => $shipping,
            'total'            => $total,
            'requires_advance' => $requiresAdvance,
            'isBridalTrio'     => $isBridalTrio,
        ];
    }
}
