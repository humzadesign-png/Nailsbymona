<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    /**
     * GET /track
     *
     * Standalone order lookup page — linked from the nav search icon.
     * Renders the track view with no order pre-loaded.
     */
    public function index(): View
    {
        $fromSession = false;
        $order       = null;
        $timeline    = [];
        $whatsappMsg = urlencode('Hello Nails by Mona, I have a question about my order.');

        return view('order.track', compact('order', 'timeline', 'fromSession', 'whatsappMsg'));
    }

    /**
     * GET /order/{order}/track
     *
     * If the customer is arriving from the session (just placed order),
     * skip the lookup form and show tracking directly.
     * Otherwise, show the lookup form.
     */
    public function show(Request $request, string $orderId): View
    {
        $order = Order::with(['items', 'sizingPhotos', 'paymentProofs'])->findOrFail($orderId);

        // Was this order just placed by the current session? If so, skip lookup.
        $fromSession = session('order_form.last_order_id') === $orderId;

        // Build timeline data.
        $timeline = $this->buildTimeline($order);

        $whatsappMsg = urlencode("Hello Nails by Mona, I have a question about order {$order->order_number}.");

        return view('order.track', compact('order', 'timeline', 'fromSession', 'whatsappMsg'));
    }

    /**
     * POST /order/track/lookup
     *
     * Rate-limited lookup. Validates order_number + contact match.
     * Returns JSON for AJAX (or redirect for non-JS).
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'order_number' => ['required', 'string', 'max:20'],
            'contact'      => ['required', 'string', 'max:150'],
        ]);

        $order = Order::where('order_number', strtoupper(trim($request->input('order_number'))))
            ->first();

        if (! $order) {
            return $this->lookupFailed($request);
        }

        // Verify the contact matches (email or phone).
        $contact = trim($request->input('contact'));
        $matched = $order->customer_email === $contact
            || $order->customer_phone === $contact
            || str_replace(['-', ' '], '', $order->customer_phone) === str_replace(['-', ' '], '', $contact);

        if (! $matched) {
            return $this->lookupFailed($request);
        }

        // Store in session so the tracking page can skip the form.
        session(['order_form.last_order_id' => $order->id]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'redirect' => route('order.track', $order->id)]);
        }

        return redirect()->route('order.track', $order->id);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function lookupFailed(Request $request)
    {
        $message = "We couldn't find an order with those details. Please check your order number — it starts with NBM and was in your confirmation email.";

        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => $message], 422);
        }

        return back()->withErrors(['lookup' => $message]);
    }

    /** Build a 5-node timeline array for the tracking view. */
    private function buildTimeline(Order $order): array
    {
        $status = $order->status;

        // Map enum values to a numeric index (0-based).
        $statusIndex = match($status) {
            OrderStatus::New          => 0,
            OrderStatus::Confirmed    => 1,
            OrderStatus::InProduction => 2,
            OrderStatus::Shipped      => 3,
            OrderStatus::Delivered    => 4,
            OrderStatus::Cancelled    => -1,
        };

        $nodes = [
            [
                'label'    => 'Order Placed',
                'sublabel' => $order->created_at->format('j M Y, g:ia'),
                'state'    => $statusIndex >= 0 ? 'completed' : 'future',
            ],
            [
                'label'    => 'Payment Received',
                'sublabel' => $order->payment_status === PaymentStatus::Awaiting
                    ? 'Awaiting payment verification'
                    : ($order->confirmed_at ? $order->confirmed_at->format('j M Y') : '—'),
                'state'    => match(true) {
                    $statusIndex >= 1                                     => 'completed',
                    $order->payment_status === PaymentStatus::Awaiting    => 'awaiting_payment',
                    default                                               => 'future',
                },
            ],
            [
                'label'    => 'In Production',
                'sublabel' => $statusIndex === 2 ? 'Mona is making your set' : '—',
                'state'    => match(true) {
                    $statusIndex > 2  => 'completed',
                    $statusIndex === 2 => 'current',
                    default           => 'future',
                },
            ],
            [
                'label'    => 'Shipped',
                'sublabel' => $order->shipped_at ? $order->shipped_at->format('j M Y') : '—',
                'state'    => match(true) {
                    $statusIndex > 3  => 'completed',
                    $statusIndex === 3 => 'current',
                    default           => 'future',
                },
                'tracking_url'    => $order->courierTrackingUrl(),
                'tracking_number' => $order->tracking_number,
                'courier_label'   => $order->courier?->label(),
            ],
            [
                'label'    => 'Delivered',
                'sublabel' => $order->delivered_at ? $order->delivered_at->format('j M Y') : '—',
                'state'    => $statusIndex === 4 ? 'completed' : 'future',
            ],
        ];

        return $nodes;
    }
}
