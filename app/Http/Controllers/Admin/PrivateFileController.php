<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Serves customer-uploaded files (sizing photos, payment proofs) from
 * the private `local` disk to authenticated admin users only.
 *
 * Files never live on a publicly-served disk. The only path that
 * exposes them is this controller, gated by the panel auth middleware.
 */
class PrivateFileController extends Controller
{
    private const ALLOWED_CATEGORIES = ['sizing', 'payment-proofs'];

    public function show(Request $request, string $category, string $orderId, string $filename): Response|BinaryFileResponse|StreamedResponse|RedirectResponse
    {
        // Require an authenticated admin. Redirect to Filament login if not.
        if (! Auth::check()) {
            return redirect('/admin/login');
        }

        // Whitelist the category — no arbitrary disk paths.
        if (! in_array($category, self::ALLOWED_CATEGORIES, true)) {
            abort(404);
        }

        // Sanity-check filename — must not contain path separators.
        if (str_contains($filename, '/') || str_contains($filename, '\\') || str_contains($filename, '..')) {
            abort(404);
        }

        // Confirm the order exists (and abort 404 if not — avoids leaking via timing).
        Order::findOrFail($orderId);

        $path = "{$category}/{$orderId}/{$filename}";

        if (! Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->response($path);
    }
}
