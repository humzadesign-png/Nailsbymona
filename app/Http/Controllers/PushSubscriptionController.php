<?php

namespace App\Http\Controllers;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Http\Request;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        // The route is already gated by `auth` middleware, but defence in
        // depth: only Filament-eligible users may subscribe to admin push
        // notifications. If/when customer accounts are introduced later,
        // this prevents a logged-in customer from registering against an
        // admin push topic.
        $user = $request->user();
        if (! $user || ! $user instanceof FilamentUser) {
            return response()->json(['ok' => false, 'reason' => 'forbidden'], 403);
        }

        $request->validate([
            'endpoint'    => 'required|url',
            'keys.auth'   => 'required|string',
            'keys.p256dh' => 'required|string',
        ]);

        $user->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'],
            $request->keys['auth'],
        );

        return response()->json(['ok' => true]);
    }
}
