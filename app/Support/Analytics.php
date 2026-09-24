<?php

namespace App\Support;

use App\Models\Order;

/**
 * Server-side queue of GA4 / Clarity events.
 *
 * The checkout is a chain of POST → redirect steps, so events are recorded in
 * the session when something happens (checkout started, order placed …) and
 * sent by the next page that renders resources/views/partials/analytics.blade.php.
 * Each event is sent once — the partial pulls the queue.
 *
 * GA4 recommended e-commerce names are used where they exist (begin_checkout,
 * add_shipping_info, purchase) so the standard GA reports and the automatic
 * "purchase" key event work.
 */
class Analytics
{
    private const KEY = 'analytics.events';

    public static function queue(string $name, array $params = []): void
    {
        session()->push(self::KEY, ['name' => $name, 'params' => $params]);
    }

    /** @return array<int, array{name: string, params: array}> */
    public static function pull(): array
    {
        return session()->pull(self::KEY, []);
    }

    /** GA4 `items` + value from a session bag (list of bag lines). */
    public static function bagParams(array $bag): array
    {
        $items = [];
        $value = 0;

        foreach ($bag as $line) {
            $price = (int) ($line['price_pkr'] ?? 0);
            $qty   = max(1, (int) ($line['qty'] ?? 1));
            $value += $price * $qty;

            $items[] = [
                'item_id'       => (string) ($line['slug'] ?? ''),
                'item_name'     => (string) ($line['name'] ?? ''),
                'item_category' => (string) ($line['tier'] ?? ''),
                'price'         => $price,
                'quantity'      => $qty,
            ];
        }

        return ['currency' => 'PKR', 'value' => $value, 'items' => $items];
    }

    public static function purchaseParams(Order $order): array
    {
        return [
            'transaction_id' => $order->order_number,
            'currency'       => 'PKR',
            'value'          => (int) $order->total_pkr,
            'shipping'       => (int) $order->shipping_pkr,
            'payment_type'   => $order->payment_method?->value,
            'items'          => $order->items->map(fn ($i) => [
                'item_id'       => (string) $i->product_slug_snapshot,
                'item_name'     => (string) $i->product_name_snapshot,
                'item_category' => (string) $i->product_tier_snapshot,
                'price'         => (int) $i->unit_price_pkr,
                'quantity'      => (int) $i->qty,
            ])->values()->all(),
        ];
    }
}
