<?php

use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderPaymentProofController;
use App\Http\Controllers\Order\OrderSizingPhotoController;
use App\Http\Controllers\Order\OrderTrackingController;
use App\Http\Controllers\ShopController;
use App\Enums\UgcPlacement;
use App\Models\ContactMessage;
use App\Models\UgcPhoto;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ── Public marketing pages ────────────────────────────────────────────────────
Route::get('/', function () {
    $ugcPhotos = UgcPhoto::with('product')
        ->where('placement', UgcPlacement::HomeGrid)
        ->where('is_published', true)
        ->where('face_visible', false)
        ->orderBy('sort_order')
        ->get();
    return view('home', compact('ugcPhotos'));
})->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('product');
Route::get('/bridal', fn () => view('bridal'))->name('bridal');
Route::get('/size-guide', fn () => view('size-guide'))->name('size-guide');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/contact', fn () => view('contact'))->name('contact');
Route::get('/blog', fn () => view('blog'))->name('blog');
Route::get('/blog/{slug}', fn () => view('blog-post'))->name('blog.post');

// ── Contact form submission ───────────────────────────────────────────────────
Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name'    => ['required', 'string', 'max:100'],
        'email'   => ['required', 'email', 'max:150'],
        'phone'   => ['nullable', 'string', 'max:30'],
        'subject' => ['required', 'string', 'max:100'],
        'message' => ['required', 'string', 'max:2000'],
    ]);

    ContactMessage::create($validated);
    session()->flash('contact_success', true);
    return redirect(route('contact') . '#message-sent');
})->middleware('throttle:5,1')->name('contact.submit');

// ── Order flow (Phase 2) ──────────────────────────────────────────────────────

// Step 1 — Sizing
Route::get('/order/start/{slug?}',       [OrderController::class, 'start'])->name('order.start');
Route::post('/order/bag-init',           [OrderController::class, 'initFromBag'])->name('order.bag.init');
Route::post('/order/start/sizing',       [OrderController::class, 'storeSizing'])->name('order.start.sizing');
Route::post('/order/start/lookup',       [OrderController::class, 'customerLookup'])->name('order.start.lookup')->middleware('throttle:10,1');

// Sizing capture (live camera / upload)
Route::get('/order/sizing-capture',      [OrderController::class, 'start'])->name('order.sizing'); // Redirects back to step 1 with camera modal
Route::get('/order/camera',              fn () => view('order.sizing-capture'))->name('order.camera');
Route::post('/order/sizing-photos',      [OrderSizingPhotoController::class, 'store'])->name('order.sizing.upload');

// Step 2 — Details
Route::get('/order/details',             [OrderController::class, 'details'])->name('order.details');
Route::post('/order/details',            [OrderController::class, 'storeDetails'])->name('order.details.post');

// Step 3 — Payment
Route::get('/order/payment',             [OrderController::class, 'payment'])->name('order.payment');
Route::post('/order/payment',            [OrderController::class, 'store'])->name('order.store')->middleware('throttle:5,1');

// Confirmation
Route::get('/order/confirm/{order}',     [OrderController::class, 'confirm'])->name('order.confirm');
Route::post('/order/{order}/proof',      [OrderPaymentProofController::class, 'store'])->name('order.proof');

// Tracking — standalone lookup page (nav search icon links here)
Route::get('/track',                     [OrderTrackingController::class, 'index'])->name('track');
Route::get('/order/{order}/track',       [OrderTrackingController::class, 'show'])->name('order.track');
Route::post('/order/track/lookup',       [OrderTrackingController::class, 'lookup'])->name('order.track.lookup')->middleware('throttle:10,1');
