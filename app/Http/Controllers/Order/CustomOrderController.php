<?php

namespace App\Http\Controllers\Order;

use App\Enums\CustomOrderStatus;
use App\Enums\SizingCaptureMethod;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomOrderRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Private custom-order links — /custom/{token}.
 *
 * Mona agrees a design with a customer in Instagram/WhatsApp DMs, creates a
 * CustomOrderRequest in the admin panel, and sends the link. The customer
 * lands here, sees their design + quote, then continues into the normal
 * order flow (camera sizing → details → payment). OrderController reads
 * `order_form.custom_request_id` from the session and prices the bag from
 * the request instead of the products table.
 */
class CustomOrderController extends Controller
{
    /** Session keys that belong to one checkout attempt. */
    public const ORDER_FORM_KEYS = [
        'order_form.bag', 'order_form.customer', 'order_form.sizing_method',
        'order_form.is_returning', 'order_form.customer_id',
        'order_form.sizing_photos', 'order_form.sizing_session_id',
        'order_form.custom_request_id',
    ];

    /** GET /custom/{token} */
    public function show(string $token): View
    {
        $request = CustomOrderRequest::where('token', $token)->firstOrFail();

        if ($request->opened_at === null) {
            $request->forceFill(['opened_at' => now()])->save();
        }

        // Placed already from this browser? Let them jump straight to the order.
        $placedOrderId = $request->status === CustomOrderStatus::Completed
            && $request->order_id
            && OrderController::sessionMayViewOrder($request->order_id)
                ? $request->order_id
                : null;

        $savedCustomer = $this->customerWithSizing($request);

        return view('order.custom', [
            'customRequest' => $request,
            'imageUrls'     => $request->referenceImageUrls(),
            'hasSavedSizing'=> $savedCustomer !== null,
            'placedOrderId' => $placedOrderId,
        ]);
    }

    /**
     * POST /custom/{token}/begin
     * Seed the checkout session from the request and hand off to the order flow.
     */
    public function begin(Request $httpRequest, string $token): RedirectResponse
    {
        $request = CustomOrderRequest::where('token', $token)->firstOrFail();

        if (! $request->isUsable()) {
            return redirect()->route('custom-order.show', $token);
        }

        $validated = $httpRequest->validate([
            'sizing' => ['required', 'in:camera,saved'],
        ]);

        // Start clean — a leftover shop bag must never mix into a custom order.
        session()->forget(self::ORDER_FORM_KEYS);

        $savedCustomer = $this->customerWithSizing($request);

        session([
            'order_form.custom_request_id' => $request->id,
            'order_form.bag'               => [$request->toBagItem()],
            'order_form.customer'          => [
                'name'    => $request->customer_name,
                'email'   => $request->customer_email ?: $savedCustomer?->email,
                // Details form shows a fixed +92 prefix — prefill the local 10 digits.
                'phone'   => Customer::normalizePhoneTail($request->customer_phone) ?: $request->customer_phone,
                'address' => $savedCustomer?->default_shipping_address,
                'city'    => $savedCustomer?->city,
                'postal'  => $savedCustomer?->postal_code,
                'notes'   => '',
            ],
        ]);

        if ($validated['sizing'] === 'saved' && $savedCustomer) {
            session([
                'order_form.is_returning'  => true,
                'order_form.customer_id'   => $savedCustomer->id,
                'order_form.sizing_method' => SizingCaptureMethod::FromProfile->value,
            ]);

            return redirect()->route('order.details');
        }

        return redirect()->route('order.camera');
    }

    /** Existing customer (matched by phone, then email) who already has sizing photos on file. */
    private function customerWithSizing(CustomOrderRequest $request): ?Customer
    {
        foreach ([$request->customer_phone, $request->customer_email] as $contact) {
            if (! $contact) {
                continue;
            }
            $customer = Customer::findByContact($contact);
            if ($customer?->has_sizing_on_file) {
                return $customer;
            }
        }

        return null;
    }
}
