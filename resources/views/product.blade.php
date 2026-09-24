@extends('layouts.app')

@php
    $tierValue  = $product->tier?->value ?? '';
    $tierLabel  = $product->tier?->label() ?? '';
    $badgeClass = match($tierValue) {
        'everyday'                    => 'bg-paper/90 backdrop-blur-sm text-stone',
        'signature'                   => 'bg-shell/95 backdrop-blur-sm text-graphite',
        'glam'                        => 'bg-graphite/90 backdrop-blur-sm text-bone',
        'bridal_single','bridal_trio' => 'bg-gold/95 backdrop-blur-sm text-ink',
        default                       => 'bg-paper/90 backdrop-blur-sm text-stone',
    };
    // Gallery images sorted: cover first, then by sort_order
    $galleryImages = $product->images
        ->sortBy(fn($img) => $img->path === $product->cover_image ? 0 : ($img->sort_order ?? 999));
    $firstImg = $galleryImages->first();
    $imgSrc   = $firstImg ? asset('storage/' . $firstImg->path) : ($product->cover_image ? asset('storage/' . $product->cover_image) : '');
    $imgAlt   = e($product->name) . ' — ' . $tierLabel . ' tier custom-fit press-on nails';
    // F4 — schema.org availability: MadeToOrder is the correct vocabulary
    // value for handmade-on-demand items. "PreOrder" implies a future
    // release date, which isn't our model.
    $schemaAvailability = match($product->stock_status?->value ?? '') {
        'sold_out'      => 'https://schema.org/OutOfStock',
        'made_to_order' => 'https://schema.org/MadeToOrder',
        default         => 'https://schema.org/InStock',
    };
    $waText     = urlencode('Hello Nails by Mona, I\'m interested in ' . $product->name . '.');
    // Per-product lead time wins; fall back to the standard setting. The "min"
    // is a 2-day fuzzy window for the public copy (e.g. "5–7 days") with the
    // standard lead time as the floor so we never quote something faster than
    // the studio actually delivers.
    $leadTime    = $product->lead_time_days ?? (int) $settings->lead_time_standard_days;
    $leadMin     = max((int) $settings->lead_time_standard_days, $leadTime - 2);
    $bridalLead  = (int) $settings->lead_time_bridal_days;
    $stockLabel  = $product->stock_status?->label() ?? 'Made to Order';
@endphp

@push('head')
<style>
  .tab-panel { display: none; }
  .tab-panel.active { display: block; }
  .faq-answer { display: none; }
  /* Thumbnail selection — outline is NOT clipped by parent overflow-x:auto */
  .thumb-btn { outline: 2px solid transparent; outline-offset: 2px; transition: outline-color 0.15s ease; }
  .thumb-btn:hover { outline-color: rgba(191,164,206,0.45); }
  .thumb-btn.active { outline-color: #BFA4CE; }
</style>
@endpush

@php
    // F2 + F3 — Product + BreadcrumbList + (optional) FAQPage in one @graph
    // so they merge with the global Organization schema in <x-seo>. Adds
    // image and sku (F3) which Google requires for Product rich results.
    $productSchema = [
        '@type'       => 'Product',
        'name'        => $product->name,
        'description' => $product->description ?? $product->name,
        'sku'         => $product->slug,
        'brand'       => ['@type' => 'Brand', 'name' => 'Nails by Mona'],
        'offers'      => [
            '@type'         => 'Offer',
            'priceCurrency' => 'PKR',
            'price'         => (string) $product->price_pkr,
            'availability'  => $schemaAvailability,
            'url'           => route('product', $product->slug),
        ],
    ];
    if ($imgSrc) {
        $productSchema['image'] = $imgSrc;
    }

    $breadcrumbSchema = [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Shop', 'item' => route('shop')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $product->name, 'item' => route('product', $product->slug)],
        ],
    ];

    $graph = [$productSchema, $breadcrumbSchema];
    if (! empty($faqs) && $faqs->count() > 0) {
        $graph[] = [
            '@type'      => 'FAQPage',
            'mainEntity' => $faqs->map(fn ($f) => [
                '@type'          => 'Question',
                'name'           => $f->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer],
            ])->values()->all(),
        ];
    }

    $productPageSchema = json_encode(
        ['@context' => 'https://schema.org', '@graph' => $graph],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );
@endphp

@section('seo')
    <x-seo
        :title="($product->meta_title ?: $product->name . ' — Custom-Fit Press-On Nails Pakistan | Nails by Mona')"
        :description="($product->meta_description ?: 'Handmade ' . strtolower($product->name) . ' press-on gel nails, custom-sized to your measurements. ' . $tierLabel . ' tier. Ships in ' . $leadMin . '–' . $leadTime . ' days across Pakistan. Free first refit.')"
        :ogImage="$imgSrc ?: null"
        :schema="$productPageSchema"
    />
@endsection

@section('content')

{{-- BREADCRUMB + PRODUCT DETAIL --}}
<section class="bg-bone py-10 md:py-16">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <!-- Breadcrumb -->
    <nav class="mb-8" aria-label="Breadcrumb">
      <ol class="flex items-center gap-2 font-sans text-caption text-stone">
        <li><a href="{{ route('home') }}" class="hover:text-ink transition-colors duration-200">Home</a></li>
        <li aria-hidden="true"><span class="text-ash">&rsaquo;</span></li>
        <li><a href="{{ route('shop') }}" class="hover:text-ink transition-colors duration-200">Shop</a></li>
        <li aria-hidden="true"><span class="text-ash">&rsaquo;</span></li>
        <li class="text-graphite font-medium">{{ $product->name }}</li>
      </ol>
    </nav>

    <!-- 2-col layout -->
    <div class="grid lg:grid-cols-[3fr_2fr] gap-12 lg:gap-16">

      <!-- LEFT — Gallery -->
      <div>
        <!-- Main image -->
        <div class="img-wrap-fallback rounded-2xl overflow-hidden aspect-square mb-4" id="main-img-wrap">
          @if($imgSrc)
          <img id="main-product-img"
            src="{{ $imgSrc }}"
            alt="{{ $imgAlt }}"
            class="w-full h-full object-cover hover:scale-[1.02] transition-transform duration-700"
            onerror="this.remove()" width="800" height="800" loading="eager" fetchpriority="high">
          @endif
        </div>
        <!-- Thumbnails -->
        <div class="flex gap-3 overflow-x-auto py-2">
          @foreach($galleryImages as $img)
          @php
            $src     = asset('storage/' . $img->path);
            $isCover = $img->path === $product->cover_image;
          @endphp
          <button class="thumb-btn shrink-0 w-20 h-20 rounded-xl overflow-hidden img-wrap-fallback {{ $isCover ? 'active' : '' }}" data-src="{{ $src }}">
            <img src="{{ img_variant($img->path) }}" alt="{{ e($img->alt ?: $product->name) }}" class="w-full h-full object-cover" onerror="this.remove()" width="80" height="80" loading="lazy">
          </button>
          @endforeach
        </div>
      </div>

      <!-- RIGHT — Product Info -->
      <div class="pt-1">
        <!-- Tier badge -->
        <span class="inline-block mb-4 font-sans text-eyebrow uppercase tracking-widest px-3 py-1 rounded-full {{ $badgeClass }}">{{ $tierLabel }}</span>

        <h1 class="font-serif text-display text-ink mb-3 leading-tight" style="font-variation-settings:'opsz' 144,'SOFT' 30">
          {{ $product->name }}
        </h1>

        <!-- Price + stock -->
        <div class="flex items-center gap-4 mb-5">
          <span class="font-sans font-semibold text-lavender tabular-nums" style="font-size:1.5rem">{{ $product->formattedPrice() }}</span>
          <span class="font-sans text-caption text-stone">{{ $stockLabel }} &middot; Ships in {{ $leadMin }}&ndash;{{ $leadTime }} days</span>
        </div>

        <!-- Short description -->
        <p class="font-sans text-body text-graphite leading-relaxed mb-7">
          {{ $product->description }}
        </p>

        <!-- Add to bag -->
        <button id="add-to-bag-btn"
          class="w-full flex items-center justify-center gap-2.5 bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full px-6 py-4 transition-colors duration-200 mb-4"
          style="font-size:1rem"
          data-name="{{ $product->name }}"
          data-price="{{ $product->price_pkr }}"
          data-slug="{{ $product->slug }}"
          data-tier="{{ $tierValue }}"
          data-image="{{ $imgSrc }}">
          Add to bag
          <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="14" stroke-linecap="round" stroke-linejoin="round"><path d="M40,72H216a8,8,0,0,1,8,8.83l-12.43,112a8,8,0,0,1-8,7.17H52.4a8,8,0,0,1-8-7.17L32,80.83A8,8,0,0,1,40,72Z"/><path d="M88,104V72a40,40,0,0,1,80,0v32"/></svg>
        </button>

        <!-- Get help -->
        <div class="text-center mb-8">
          <a href="https://wa.me/{{ $settings->whatsappForWaMe() }}?text={{ $waText }}"
             class="font-sans text-caption text-stone hover:text-lavender-ink underline-offset-4 hover:underline transition-colors duration-200">
            Get help with this design &rarr;
          </a>
        </div>

        <!-- Trust signals -->
        <div class="border-t border-hairline pt-6 grid grid-cols-3 gap-4">
          <div class="text-center">
            <div class="text-lavender mb-2 flex justify-center">
              <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><line x1="216" y1="40" x2="40" y2="216"/><polyline points="40 152 40 216 104 216"/><polyline points="152 40 216 40 216 104"/></svg>
            </div>
            <p class="font-sans text-caption text-stone leading-tight" style="font-size:0.75rem">Custom-fit to your measurements</p>
          </div>
          <div class="text-center">
            <div class="text-lavender mb-2 flex justify-center">
              <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M128,24S32,96,32,152a96,96,0,0,0,192,0C224,96,128,24,128,24Z"/></svg>
            </div>
            <p class="font-sans text-caption text-stone leading-tight" style="font-size:0.75rem">Free first refit</p>
          </div>
          <div class="text-center">
            <div class="text-lavender mb-2 flex justify-center">
              <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><rect x="32" y="80" width="192" height="144" rx="8"/><path d="M168,80V56a40,40,0,0,0-80,0V80"/></svg>
            </div>
            <p class="font-sans text-caption text-stone leading-tight" style="font-size:0.75rem">JazzCash &middot; EasyPaisa &middot; Bank</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- TABS — About / Sizing & Fit / Care & Reuse --}}
<section class="bg-paper py-14">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <!-- Tab buttons — proper WAI-ARIA tablist (F11). Screen readers
         announce these as tabs and respect the selected/active state. -->
    <div class="flex border-b border-hairline mb-8 overflow-x-auto" role="tablist" aria-label="Product details">
      <button type="button" class="tab-btn font-sans font-medium text-caption border-b-2 border-lavender text-ink pb-3 pr-8 mr-0 whitespace-nowrap"
              data-tab="about" role="tab" aria-selected="true" aria-controls="tab-about" id="tabbtn-about">About this set</button>
      <button type="button" class="tab-btn font-sans font-medium text-caption border-b-2 border-transparent text-stone hover:text-ink pb-3 px-8 transition-colors duration-200 whitespace-nowrap"
              data-tab="sizing" role="tab" aria-selected="false" aria-controls="tab-sizing" id="tabbtn-sizing">Sizing &amp; Fit</button>
      <button type="button" class="tab-btn font-sans font-medium text-caption border-b-2 border-transparent text-stone hover:text-ink pb-3 pl-8 transition-colors duration-200 whitespace-nowrap"
              data-tab="care" role="tab" aria-selected="false" aria-controls="tab-care" id="tabbtn-care">Care &amp; Reuse</button>
    </div>

    <!-- Tab panels -->
    <div class="max-w-2xl">

      <div id="tab-about" class="tab-panel active" role="tabpanel" aria-labelledby="tabbtn-about">
        <p class="font-sans text-body-lg text-graphite leading-relaxed mb-6">
          {!! nl2br(e($product->description)) !!}
        </p>
        <div class="bg-shell rounded-xl p-5">
          <p class="font-sans text-caption text-stone font-medium mb-1">Ships in</p>
          <p class="font-sans text-body text-graphite">{{ $leadMin }}&ndash;{{ $leadTime }} days from sizing confirmation</p>
        </div>
      </div>

      <div id="tab-sizing" class="tab-panel" role="tabpanel" aria-labelledby="tabbtn-sizing">
        <p class="font-sans text-body-lg text-graphite leading-relaxed mb-5">
          This set is made to fit your specific nails &mdash; not a generic size. Before I begin making your set, you'll share a quick photo of your hand using my sizing guide.
        </p>
        <a href="{{ route('size-guide') }}" class="inline-flex items-center gap-2 font-sans text-caption font-medium text-lavender-ink hover:text-lavender transition-colors duration-200 underline-offset-4 underline mb-6">
          How sizing works &rarr;
        </a>
        <div class="bg-shell rounded-xl p-5 mb-5">
          <p class="font-sans font-medium text-ink mb-1" style="font-size:0.875rem">Already ordered before?</p>
          <p class="font-sans text-caption text-stone">I keep your measurements on file. Just mention it when you order and I'll use your saved profile &mdash; no re-measuring needed.</p>
        </div>
      </div>

      <div id="tab-care" class="tab-panel" role="tabpanel" aria-labelledby="tabbtn-care">
        <ul class="space-y-4 mb-6">
          <li class="flex items-start gap-3">
            <span class="text-lavender mt-0.5 shrink-0">
              <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
            </span>
            <p class="font-sans text-body text-graphite">Apply on clean, dry nails &mdash; use the prep pad included. Don't skip this step; it's 80% of how long they last.</p>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-lavender mt-0.5 shrink-0">
              <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
            </span>
            <p class="font-sans text-body text-graphite">Press firmly for 30 seconds from base to tip, working out air bubbles. Hold, don't just press.</p>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-lavender mt-0.5 shrink-0">
              <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
            </span>
            <p class="font-sans text-body text-graphite">Avoid soaking in water for the first hour after application &mdash; the glue needs time to fully cure.</p>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-lavender mt-0.5 shrink-0">
              <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
            </span>
            <p class="font-sans text-body text-graphite">To remove: soak in warm water for 10&ndash;15 minutes, then gently lift from the side. Never force or peel &mdash; that damages the natural nail.</p>
          </li>
          <li class="flex items-start gap-3">
            <span class="text-lavender mt-0.5 shrink-0">
              <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
            </span>
            <p class="font-sans text-body text-graphite">Store in the original box. With careful removal, you'll get 3&ndash;5 wears from this set.</p>
          </li>
        </ul>
        <a href="{{ route('blog.post', 'how-to-apply-press-on-nails') }}" class="font-sans text-caption text-lavender-ink hover:text-lavender underline-offset-4 hover:underline transition-colors duration-200">
          Full application guide &rarr;
        </a>
      </div>

    </div>
  </div>
</section>


{{-- FAQ — DB-driven (Faq admin resource); falls back to a hardcoded set
     so the section never renders empty while Mona's filling the table.
     ARIA: aria-expanded/aria-controls on the trigger so screen readers
     announce open/closed state and which panel each button toggles. --}}
@php
    // Fallback FAQ set (matches the original hardcoded copy). Used only
    // when the faqs admin table has no active General-category rows.
    $fallbackFaqs = collect([
        (object) ['id' => 'fb1', 'question' => 'Will these nails fall off?',
            'answer' => 'I use a brush-on nail glue that bonds firmly with proper preparation. Most customers get 7–10 days of wear. If you follow the prep steps (clean, dry nails + prep pad), they hold up well through hand-washing, showering, and daily activity.'],
        (object) ['id' => 'fb2', 'question' => 'What if the sizing is wrong?',
            'answer' => "That's exactly what the free first-refit guarantee is for. If your first order doesn't fit perfectly, I resize it at no charge. I'd rather take the extra time to get it right."],
        (object) ['id' => 'fb3', 'question' => 'How long does it take?',
            'answer' => "Custom sets take {$leadMin}–{$leadTime} days from the day I confirm your sizing. Bridal sets take around {$bridalLead} days. I'll confirm your exact timeline over WhatsApp before I start."],
        (object) ['id' => 'fb4', 'question' => 'Can I reuse these?',
            'answer' => 'Yes — with careful removal (soak off, never force), most customers get 3–5 wears from a set. I include care and storage instructions with every order.'],
        (object) ['id' => 'fb5', 'question' => 'How do I pay?',
            'answer' => "JazzCash, EasyPaisa, and bank transfer. Account details are sent automatically on your confirmation page after ordering — you upload a payment screenshot and I verify it within a few hours. No Cash on Delivery."],
    ]);
    $faqsToShow = ($faqs ?? null) && $faqs->count() > 0 ? $faqs : $fallbackFaqs;
@endphp
<section class="bg-shell py-14">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="max-w-2xl">

      <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Questions</p>
      <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">Questions about this set.</h2>
      <div class="h-0.5 w-10 bg-lavender mb-10"></div>

      <div class="space-y-0 border-t border-hairline">
        @foreach($faqsToShow as $faq)
          @php $panelId = 'faq-panel-' . $faq->id; @endphp
          <div class="faq-item border-b border-hairline">
            <button type="button" class="faq-trigger w-full flex items-center justify-between py-5 text-left"
                    aria-expanded="false" aria-controls="{{ $panelId }}">
              <span class="font-sans font-medium text-ink pr-6" style="font-size:0.9375rem">{{ $faq->question }}</span>
              <svg class="faq-icon w-4 h-4 text-stone shrink-0 transition-transform duration-200" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" aria-hidden="true">
                <line x1="40" y1="128" x2="216" y2="128"/>
                <line class="faq-plus-vertical" x1="128" y1="40" x2="128" y2="216"/>
              </svg>
            </button>
            <div id="{{ $panelId }}" class="faq-answer pb-5" role="region">
              <p class="font-sans text-body text-graphite">{!! nl2br(e($faq->answer)) !!}</p>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</section>


{{-- RELATED PRODUCTS --}}
@if($related->isNotEmpty())
<section class="bg-paper py-14 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Also from the studio</p>
    <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">You might also like.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($related as $rp)
      @php
        $rpTierValue = $rp->tier?->value ?? '';
        $rpTierLabel = $rp->tier?->label() ?? '';
        $rpBadgeClass = match($rpTierValue) {
            'everyday'                    => 'bg-shell/80 text-graphite',
            'signature'                   => 'bg-shell/95 text-graphite',
            'glam'                        => 'bg-graphite/95 text-bone',
            'bridal_single','bridal_trio' => 'bg-gold/95 text-ink',
            default                       => 'bg-shell/80 text-graphite',
        };
        $rpImg = img_variant($rp->cover_image);
      @endphp
      <article class="bg-paper rounded-2xl overflow-hidden border border-hairline/60 hover:shadow-card-hover transition-shadow duration-300 group">
        <a href="{{ route('product', $rp->slug) }}">
          <div class="img-wrap-fallback aspect-square overflow-hidden">
            @if($rpImg)
            <img src="{{ $rpImg }}" alt="{{ e($rp->name) }} {{ $rpTierLabel }} press-on nails" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700" onerror="this.remove()" width="400" height="400" loading="lazy">
            @endif
          </div>
          <div class="p-5">
            <span class="inline-block font-sans text-eyebrow uppercase px-2.5 py-1 rounded-full {{ $rpBadgeClass }} mb-3">{{ $rpTierLabel }}</span>
            <h3 class="font-serif text-ink leading-snug mb-1" style="font-size:1.1rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">{{ $rp->name }}</h3>
            <p class="font-sans font-medium text-lavender tabular-nums" style="font-size:0.9375rem">{{ $rp->formattedPrice() }}</p>
          </div>
        </a>
      </article>
      @endforeach
    </div>
  </div>
</section>
@endif


{{-- RELATED READING --}}
<section class="bg-bone py-14 border-t border-hairline/50">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">Further reading.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>
    <div class="grid sm:grid-cols-2 gap-6 max-w-2xl">
      <a href="{{ route('blog.post', 'muslim-women-press-on-nails-wudu') }}" class="group flex gap-4 p-5 bg-paper rounded-2xl border border-hairline/60 hover:shadow-card transition-shadow duration-300">
        <div class="w-20 h-20 rounded-xl shrink-0 overflow-hidden bg-shell flex items-center justify-center">
          <svg class="w-8 h-8 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="10" stroke-linecap="round" stroke-linejoin="round"><path d="M128,24S32,96,32,152a96,96,0,0,0,192,0C224,96,128,24,128,24Z"/></svg>
        </div>
        <div>
          <p class="font-sans text-eyebrow text-lavender uppercase mb-1">Tutorials</p>
          <h3 class="font-serif text-ink leading-snug group-hover:text-lavender-ink transition-colors duration-200" style="font-size:0.95rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">Can Muslim Women Wear Press-On Nails?</h3>
        </div>
      </a>
      <a href="{{ route('blog.post', 'how-to-apply-press-on-nails') }}" class="group flex gap-4 p-5 bg-paper rounded-2xl border border-hairline/60 hover:shadow-card transition-shadow duration-300">
        <div class="w-20 h-20 rounded-xl shrink-0 overflow-hidden bg-shell flex items-center justify-center">
          <svg class="w-8 h-8 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="10" stroke-linecap="round" stroke-linejoin="round"><path d="M200,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24ZM80,128a8,8,0,0,1,0-16h96a8,8,0,0,1,0,16Zm0-40a8,8,0,0,1,0-16h96a8,8,0,0,1,0,16Zm96,80H80a8,8,0,0,1,0-16h96a8,8,0,0,1,0,16Z"/></svg>
        </div>
        <div>
          <p class="font-sans text-eyebrow text-lavender uppercase mb-1">Tutorials</p>
          <h3 class="font-serif text-ink leading-snug group-hover:text-lavender-ink transition-colors duration-200" style="font-size:0.95rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">How to Apply Press-On Nails &mdash; 7 Steps</h3>
        </div>
      </a>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
$(function () {

  // ── Add to bag ───────────────────────────────────
  // Includes `tier` (enum value, e.g. "bridal_trio") so the payment page's
  // isBridalTrio detection works before verifyBag() runs server-side. The
  // server always re-verifies tier from the products table at order placement.
  $('#add-to-bag-btn').on('click', function () {
    const btn  = $(this);
    const slug = btn.data('slug');
    if (! slug) return;
    window.NbmBag.add({
      slug:      slug,
      name:      btn.data('name'),
      price_pkr: +btn.data('price'),
      tier:     (btn.data('tier') || '') + '',
      image:     btn.data('image') || '',
    });
  });

  // ── Image gallery thumbnails + swipe ─────────────
  const $thumbBtns = $('.thumb-btn');

  function setActiveThumb($thumb) {
    $thumbBtns.removeClass('active');
    $thumb.addClass('active');
    $('#main-product-img').attr('src', $thumb.data('src'));
    $thumb[0].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
  }

  $thumbBtns.on('click', function () { setActiveThumb($(this)); });

  // Touch swipe on main image — native listeners so passive:true actually applies
  let swipeStartX = 0;
  const mainWrap = document.getElementById('main-img-wrap');
  if (mainWrap) {
    mainWrap.addEventListener('touchstart', function (e) {
      swipeStartX = e.touches[0].clientX;
    }, { passive: true });
    mainWrap.addEventListener('touchend', function (e) {
      const dx = e.changedTouches[0].clientX - swipeStartX;
      if (Math.abs(dx) < 35) return;
      const $active = $thumbBtns.filter('.active');
      const idx = $thumbBtns.index($active);
      if (dx < 0 && idx < $thumbBtns.length - 1) setActiveThumb($($thumbBtns[idx + 1]));
      else if (dx > 0 && idx > 0) setActiveThumb($($thumbBtns[idx - 1]));
    });
  }

  // ── Tab switching ────────────────────────────────
  // aria-selected mirrors visual state so screen-reader users know which
  // tab is current (F11 from the audit).
  $('.tab-btn').on('click', function () {
    const tabId = $(this).data('tab');
    $('.tab-btn').removeClass('border-lavender text-ink').addClass('border-transparent text-stone')
                 .attr('aria-selected', 'false');
    $(this).addClass('border-lavender text-ink').removeClass('border-transparent text-stone')
           .attr('aria-selected', 'true');
    $('.tab-panel').removeClass('active');
    $('#tab-' + tabId).addClass('active');
  });

  // ── FAQ accordion ────────────────────────────────
  // aria-expanded mirrors the visual open/closed state so screen
  // readers announce it correctly (F10 from the audit).
  $('.faq-trigger').on('click', function () {
    const $item   = $(this).closest('.faq-item');
    const $answer = $item.find('.faq-answer');
    const isOpen  = $answer.is(':visible');

    // Close every panel first, both visually and for ARIA.
    $('.faq-answer').slideUp(180);
    $('.faq-icon').css('transform', '');
    $('.faq-trigger').attr('aria-expanded', 'false');

    if (!isOpen) {
      $answer.slideDown(180);
      $(this).find('.faq-icon').css('transform', 'rotate(45deg)');
      $(this).attr('aria-expanded', 'true');
    }
  });

});
</script>
@endpush
