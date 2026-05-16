<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Order\OrderPaymentProofController;
use App\Http\Controllers\Order\OrderSizingPhotoController;
use App\Http\Controllers\Order\OrderTrackingController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\ShopController;
use App\Enums\UgcPlacement;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Notifications\NewMessageNotification;
use App\Models\Product;
use App\Models\UgcPhoto;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

// ── Public marketing pages ────────────────────────────────────────────────────
Route::get('/', function () {
    $ugcPhotos = UgcPhoto::with('product')
        ->where('placement', UgcPlacement::HomeGrid)
        ->where('is_published', true)
        ->where('face_visible', false)
        ->orderBy('sort_order')
        ->get();

    $featuredProducts = Product::where('is_active', true)
        ->where('is_featured', true)
        ->orderBy('sort_order')
        ->limit(6)
        ->get();

    if ($featuredProducts->count() < 3) {
        $featuredProducts = Product::where('is_active', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();
    }

    return view('home', compact('ugcPhotos', 'featuredProducts'));
})->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('product');
Route::get('/bridal', fn () => view('bridal'))->name('bridal');
Route::get('/size-guide', fn () => view('size-guide'))->name('size-guide');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/contact', fn () => view('contact'))->name('contact');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.post');
Route::post('/subscribe', [BlogController::class, 'subscribe'])->middleware('throttle:5,1')->name('subscribe');

// ── Legal / informational pages ───────────────────────────────────────────────
Route::view('/privacy',  'legal.privacy')->name('privacy');
Route::view('/terms',    'legal.terms')->name('terms');
Route::view('/shipping', 'legal.shipping')->name('shipping');

// ── Contact form submission ───────────────────────────────────────────────────
Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name'    => ['required', 'string', 'max:100'],
        'email'   => ['required', 'email', 'max:150'],
        'phone'   => ['nullable', 'string', 'max:30'],
        'subject' => ['required', 'string', 'max:100'],
        'message' => ['required', 'string', 'max:2000'],
    ]);

    $msg = ContactMessage::create($validated);
    try {
        \App\Models\User::all()->each->notify(new NewMessageNotification($msg));
    } catch (\Throwable $e) {
        \Log::error('NewMessageNotification push failed', ['error' => $e->getMessage()]);
    }
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

// ── Sitemap ───────────────────────────────────────────────────────────────────
Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create()
        ->add(Url::create(route('home'))->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
        ->add(Url::create(route('shop'))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
        ->add(Url::create(route('bridal'))->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
        ->add(Url::create(route('about'))->setPriority(0.7)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
        ->add(Url::create(route('contact'))->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
        ->add(Url::create(route('size-guide'))->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
        ->add(Url::create(route('blog'))->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

    Product::where('is_active', true)->each(function ($p) use ($sitemap) {
        $sitemap->add(Url::create(route('product', $p->slug))->setPriority(0.85)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)->setLastModificationDate($p->updated_at));
    });

    BlogPost::where('is_published', true)->orderByDesc('published_at')->each(function ($post) use ($sitemap) {
        // Prefer the later of published_at and updated_at — published_at is more meaningful
        // for crawlers but updated_at catches small edits without re-publishing.
        $lastmod = $post->published_at && $post->updated_at
            ? ($post->published_at->gt($post->updated_at) ? $post->published_at : $post->updated_at)
            : ($post->updated_at ?? $post->published_at);
        $sitemap->add(Url::create(route('blog.post', $post->slug))->setPriority(0.75)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)->setLastModificationDate($lastmod));
    });

    return response($sitemap->render(), 200, ['Content-Type' => 'application/xml; charset=utf-8']);
})->name('sitemap');

// ── RSS feed ──────────────────────────────────────────────────────────────────
Route::get('/feed.xml', function () {
    $posts = BlogPost::where('is_published', true)
        ->orderByDesc('published_at')
        ->limit(20)
        ->get();

    $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
    $xml .= '<channel>' . "\n";
    $xml .= '<title>The Journal — Nails by Mona</title>' . "\n";
    $xml .= '<link>' . route('blog') . '</link>' . "\n";
    $xml .= '<description>Nail care, bridal guides, and press-on tutorials from Mona\'s studio in Mirpur.</description>' . "\n";
    $xml .= '<language>en-pk</language>' . "\n";
    $xml .= '<atom:link href="' . route('feed') . '" rel="self" type="application/rss+xml" />' . "\n";

    foreach ($posts as $post) {
        $xml .= '<item>' . "\n";
        $xml .= '<title><![CDATA[' . $post->title . ']]></title>' . "\n";
        $xml .= '<link>' . route('blog.post', $post->slug) . '</link>' . "\n";
        $xml .= '<guid isPermaLink="true">' . route('blog.post', $post->slug) . '</guid>' . "\n";
        $xml .= '<pubDate>' . ($post->published_at ?? $post->created_at)->toRssString() . '</pubDate>' . "\n";
        $xml .= '<description><![CDATA[' . e($post->excerpt) . ']]></description>' . "\n";
        $xml .= '<category>' . e($post->category->label()) . '</category>' . "\n";
        $xml .= '</item>' . "\n";
    }

    $xml .= '</channel>' . "\n";
    $xml .= '</rss>';

    return response($xml, 200, ['Content-Type' => 'application/rss+xml; charset=utf-8']);
})->name('feed');

// ── Tracking — standalone lookup page (nav search icon links here)
Route::get('/track',                     [OrderTrackingController::class, 'index'])->name('track');
Route::get('/order/{order}/track',       [OrderTrackingController::class, 'show'])->name('order.track');
Route::post('/order/track/lookup',       [OrderTrackingController::class, 'lookup'])->name('order.track.lookup')->middleware('throttle:5,1');

// ── PWA push subscription (admin only) ───────────────────────────────────────
Route::post('/admin/push-subscription', [PushSubscriptionController::class, 'store'])
    ->middleware(['web', 'auth'])
    ->name('push.subscription.store');

// ── Private file serving (admin only — sizing photos, payment proofs) ────────
// Files live on the `local` (private) disk and are NEVER web-accessible.
// This route is the only way to read them. Auth check happens inside
// PrivateFileController::show() to avoid the default Authenticate
// middleware trying to redirect to a non-existent `login` route.
Route::get('/admin/files/{category}/{order}/{filename}',
        [\App\Http\Controllers\Admin\PrivateFileController::class, 'show'])
    ->where('category', 'sizing|payment-proofs')
    ->where('filename', '[A-Za-z0-9._-]+')
    ->middleware(['web'])
    ->name('admin.private-file');
