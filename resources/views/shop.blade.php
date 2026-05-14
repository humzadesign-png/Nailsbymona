@extends('layouts.app')

@push('head')
<style>
  .filter-pill.active {
    background-color: #BFA4CE;
    color: #fff;
    border-color: #BFA4CE;
  }
</style>
@endpush

@php
    $shopSchema = json_encode([
        '@context' => 'https://schema.org',
        '@type'    => 'ItemList',
        'name'     => 'Shop Press-On Nails — Nails by Mona',
        'url'      => route('shop'),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

@section('seo')
    <x-seo
        title="Shop Press-On Nails Pakistan — Custom-Fit Gel Sets | Nails by Mona"
        description="Browse custom-fit handmade press-on gel nail sets. Everyday, Signature, Glam, and Bridal tiers. Sized from two close-up photos (fingers + thumb) using our live camera guide. Shipped across Pakistan."
        :schema="$shopSchema"
    />
@endsection

@section('content')

{{-- ═══════════════════════════════════════════════
     SECTION 1 — MINIMAL HERO STRIP
     BG: shell
═══════════════════════════════════════════════ --}}
<section class="bg-shell py-14 md:py-16">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <p class="font-sans text-eyebrow text-lavender uppercase mb-4">The Collection</p>
    <h1 class="font-serif text-display-lg text-ink mb-4" style="font-variation-settings:'opsz' 144,'SOFT' 30">Find your perfect set.</h1>
    <div class="h-0.5 w-10 bg-lavender mb-5"></div>
    <p class="font-sans text-body text-stone max-w-xl">Every design is custom-fit to your nails. Browse by occasion, style, or price.</p>
  </div>
</section>


{{-- ═══════════════════════════════════════════════
     SECTION 2 — STICKY FILTER BAR
     BG: paper
═══════════════════════════════════════════════ --}}
<div class="sticky top-[72px] z-40 bg-paper border-b border-hairline">
  <div class="max-w-7xl mx-auto px-6 lg:px-10 py-4 flex flex-wrap items-center justify-between gap-3">

    <!-- Filter pills -->
    <div class="flex items-center gap-2 flex-wrap" role="group" aria-label="Filter by tier">
      <button class="filter-pill active font-sans text-eyebrow uppercase tracking-widest rounded-full px-5 py-2 border border-lavender bg-lavender text-white transition-all duration-200 cursor-pointer" data-filter="all">All</button>
      <button class="filter-pill font-sans text-eyebrow uppercase tracking-widest rounded-full px-5 py-2 border border-hairline text-stone hover:border-ink hover:text-ink transition-all duration-200 cursor-pointer" data-filter="everyday">Everyday</button>
      <button class="filter-pill font-sans text-eyebrow uppercase tracking-widest rounded-full px-5 py-2 border border-hairline text-stone hover:border-ink hover:text-ink transition-all duration-200 cursor-pointer" data-filter="signature">Signature</button>
      <button class="filter-pill font-sans text-eyebrow uppercase tracking-widest rounded-full px-5 py-2 border border-hairline text-stone hover:border-ink hover:text-ink transition-all duration-200 cursor-pointer" data-filter="glam">Glam</button>
      <button class="filter-pill font-sans text-eyebrow uppercase tracking-widest rounded-full px-5 py-2 border border-hairline text-stone hover:border-ink hover:text-ink transition-all duration-200 cursor-pointer" data-filter="bridal">Bridal</button>
    </div>

    <!-- Sort select -->
    <select id="sort-select" aria-label="Sort products" class="bg-paper border border-hairline rounded-xl px-3 py-2 font-sans text-caption text-graphite appearance-none cursor-pointer focus:outline-none focus:border-lavender transition-colors duration-200">
      <option value="featured">Featured</option>
      <option value="price-asc">Price: Low to High</option>
      <option value="price-desc">Price: High to Low</option>
    </select>

  </div>
</div>


{{-- ═══════════════════════════════════════════════
     SECTION 3 — PRODUCT GRID
     BG: paper
═══════════════════════════════════════════════ --}}
<section class="bg-paper py-14">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <div id="product-grid" class="grid grid-cols-2 md:grid-cols-3 gap-5 md:gap-6">

      @forelse($products as $product)
      @php
          $tierValue = $product->tier?->value ?? '';
          $tierLabel = $product->tier?->label() ?? '';
          $filterKey = str_starts_with($tierValue, 'bridal') ? 'bridal' : $tierValue;
          $badgeClass = match($tierValue) {
              'everyday'                   => 'bg-paper/90 backdrop-blur-sm text-stone',
              'signature'                  => 'bg-shell/95 backdrop-blur-sm text-graphite',
              'glam'                       => 'bg-graphite/90 backdrop-blur-sm text-bone',
              'bridal_single','bridal_trio'=> 'bg-gold/95 backdrop-blur-sm text-ink',
              default                      => 'bg-paper/90 backdrop-blur-sm text-stone',
          };
          $imgSrc = $product->cover_image ? asset('storage/' . $product->cover_image) : '';
          $imgAlt = e($product->name) . ' — ' . $tierLabel . ' tier custom-fit press-on nails';
      @endphp
      <article class="product-card bg-paper rounded-2xl overflow-hidden group shadow-card hover:shadow-card-hover transition-shadow duration-300"
               data-tier="{{ $filterKey }}" data-price="{{ $product->price_pkr }}">
        <a href="{{ route('product', $product->slug) }}">
          <div class="relative overflow-hidden" style="aspect-ratio:1/1; background:linear-gradient(145deg,#EAE3D9 0%,#DDD3C7 100%)">
            @if($imgSrc)
            <img src="{{ $imgSrc }}" alt="{{ $imgAlt }}"
                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-700 ease-out"
                 onerror="this.parentElement.classList.add('no-img')" width="400" height="400" loading="lazy">
            @else
            {{-- Placeholder shown when no product photo is uploaded yet --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 select-none">
              <svg class="w-14 h-14 text-stone/30" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M32 8C24 8 18 16 18 28C18 38 22 46 32 54C42 46 46 38 46 28C46 16 40 8 32 8Z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                <path d="M26 22C26 18 28.5 16 32 16C35.5 16 38 18 38 22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
              <span class="font-sans text-stone/40 uppercase tracking-widest" style="font-size:0.65rem">Photo coming soon</span>
            </div>
            @endif
            <span class="absolute top-3 left-3 font-sans text-eyebrow uppercase tracking-widest px-3 py-1.5 {{ $badgeClass }} rounded-full">{{ $tierLabel }}</span>
            <div class="absolute inset-0 flex items-end justify-center pb-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
              <span class="font-sans text-caption font-medium text-white tracking-wide bg-ink/70 backdrop-blur-sm rounded-full px-5 py-2">View details &rarr;</span>
            </div>
          </div>
        </a>
        <div class="px-5 py-4">
          <h3 class="font-serif text-ink mb-1 leading-snug capitalize" style="font-size:1.125rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">{{ $product->name }}</h3>
          <p class="font-sans font-medium text-lavender tabular-nums mb-3" style="font-size:1rem">Rs. {{ number_format($product->price_pkr) }}</p>
          <button class="add-to-bag w-full bg-lavender hover:bg-lavender-dark text-white font-sans text-caption font-medium tracking-wide rounded-full py-2.5 transition-colors duration-200"
            data-name="{{ e($product->name) }}"
            data-price="{{ $product->price_pkr }}"
            data-tier="{{ $tierLabel }}"
            data-slug="{{ $product->slug }}"
            data-image="{{ $imgSrc }}">
            Add to bag
          </button>
        </div>
      </article>
      @empty
      {{-- No active products yet — empty state handled by #no-results below --}}
      @endforelse

    </div>

    <!-- Empty state (shown when filter has no results) -->
    <div id="no-results" class="hidden py-20 text-center">
      <p class="font-serif text-display text-stone mb-3" style="font-variation-settings:'opsz' 144,'SOFT' 30">No sets found.</p>
      <p class="font-sans text-body text-stone mb-6">Try a different filter, or browse all designs.</p>
      <button class="filter-pill font-sans text-caption text-stone border border-hairline hover:border-ink hover:text-ink rounded-full px-6 py-2.5 transition-colors duration-200" data-filter="all">Show all designs</button>
    </div>

  </div>
</section>


{{-- ═══════════════════════════════════════════════
     SECTION 4 — TRUST STRIP
     BG: shell
═══════════════════════════════════════════════ --}}
<section class="bg-shell py-12">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">

      <div class="flex flex-col items-start gap-3">
        <span class="text-lavender" aria-hidden="true">
          <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
            <line x1="216" y1="40" x2="40" y2="216"/><polyline points="40 152 40 216 104 216"/><polyline points="152 40 216 40 216 104"/>
          </svg>
        </span>
        <div>
          <p class="font-sans font-semibold text-ink mb-1" style="font-size:0.875rem">All sets custom-fit</p>
          <p class="font-sans text-caption text-stone">Sized from two close-ups &mdash; fingers + thumb.</p>
          <a href="{{ route('size-guide') }}" class="font-sans text-caption text-lavender hover:text-lavender-ink underline-offset-4 hover:underline transition-colors duration-200 mt-1 inline-block">How sizing works &rarr;</a>
        </div>
      </div>

      <div class="flex flex-col items-start gap-3">
        <span class="text-lavender" aria-hidden="true">
          <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"/><rect x="2" y="2" width="20" height="20" rx="5" transform="scale(10,10) translate(-0.5,-0.5)"/>
          </svg>
        </span>
        <div>
          <p class="font-sans font-semibold text-ink mb-1" style="font-size:0.875rem">Free first refit</p>
          <p class="font-sans text-caption text-stone">If it doesn't sit right, we fix it. Every order.</p>
        </div>
      </div>

      <div class="flex flex-col items-start gap-3">
        <span class="text-lavender" aria-hidden="true">
          <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
            <path d="M220,136v72a8,8,0,0,1-8,8H44a8,8,0,0,1-8-8V136"/>
            <path d="M232,80H24a0,0,0,0,0,0,0v48a8,8,0,0,0,8,8H224a8,8,0,0,0,8-8V80A0,0,0,0,0,232,80Z"/>
            <line x1="128" y1="136" x2="128" y2="216"/>
            <path d="M93.2,80l10.42-50a8,8,0,0,1,7.82-6.36H144.56A8,8,0,0,1,152.38,30l10.42,50"/>
          </svg>
        </span>
        <div>
          <p class="font-sans font-semibold text-ink mb-1" style="font-size:0.875rem">Ships 5&ndash;9 working days</p>
          <p class="font-sans text-caption text-stone">Handmade in Mirpur, shipped nationwide.</p>
        </div>
      </div>

      <div class="flex flex-col items-start gap-3">
        <span class="text-lavender" aria-hidden="true">
          <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
            <rect x="32" y="80" width="192" height="128" rx="8"/>
            <path d="M88,80V56a40,40,0,0,1,80,0V80"/>
          </svg>
        </span>
        <div>
          <p class="font-sans font-semibold text-ink mb-1" style="font-size:0.875rem">JazzCash &middot; EasyPaisa &middot; Bank</p>
          <p class="font-sans text-caption text-stone">Secure payment, verified by Mona within 24h.</p>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════════
     SECTION 5 — BRIDAL CALLOUT
     BG: bridal-bg
═══════════════════════════════════════════════ --}}
<section class="bg-bridal-bg py-16 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="grid md:grid-cols-2 gap-12 md:gap-20 items-center">

      <!-- Text column -->
      <div>
        <p class="font-sans text-eyebrow text-lavender uppercase mb-5">For the wedding</p>
        <h2 class="font-serif text-display-lg text-ink mb-5 leading-[1.0]" style="font-variation-settings:'opsz' 144,'SOFT' 30">
          Mehendi.<br>Baraat.<br>Valima.
        </h2>
        <div class="h-0.5 w-10 bg-lavender mb-7"></div>
        <p class="font-sans text-body-lg text-graphite mb-3">
          The Bridal Trio covers all three nights as one coordinated order. One fitting. One shipment.
        </p>
        <p class="font-sans text-body text-stone mb-8">
          Three sets, sized once, packaged in a magnetic keepsake box. From <span class="text-gold-deep font-medium">Rs. 11,000</span>.
        </p>
        <a href="{{ route('bridal') }}" class="inline-flex items-center gap-2.5 bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full px-8 py-4 transition-colors duration-200" style="font-size:1rem">
          See the Bridal Trio
          <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="40" y1="128" x2="216" y2="128"/><polyline points="144 56 216 128 144 200"/>
          </svg>
        </a>
      </div>

      <!-- Image -->
      <div class="overflow-hidden rounded-2xl shadow-2xl shadow-ink/20 img-wrap-fallback" style="aspect-ratio:3/4; background:linear-gradient(150deg,#EDE2C8 0%,#D9C28A 40%,#B8924A 100%)">
        <img
          src="https://lh3.googleusercontent.com/aida-public/AB6AXuAMDjMPFXgmgvFReuLT1rq0rhVxtsqEr42UQqTmYLcjUihKexyK-z6bF4jMc2FT4t0v6r1VP2z6smwQlyKQsqlV9-EqBsIsISm9Kt4_BhQH7N6Uk6MVq0JA0rKFxN9wUuWp0OFyt9258JJBFvDa85Md5U-L74wbcnwWfMOv5CzCQWTM4-Z5UYZbccbpqT-q1pCsmHqpi4tyJMXPGOQmU0hQVN899lKjUthcadruE4Tt_jW8W2ZvBMow3FXqsLnKUH_ej49SsBaJvhs"
          alt="Bridal Trio nail sets — three coordinated looks for Mehendi, Baraat, and Valima"
          class="w-full h-full object-cover"
          onerror="this.remove()"
          width="600" height="800"
          loading="lazy">
      </div>

    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
$(function () {

  // ── Filter pills ─────────────────────────────────
  $(document).on('click', '.filter-pill', function () {
    const filter = $(this).data('filter');

    // Update active pill styling
    $('.filter-pill').removeClass('active bg-lavender text-white border-lavender')
      .addClass('text-stone border-hairline');
    $(this).addClass('active bg-lavender text-white border-lavender')
      .removeClass('text-stone border-hairline');

    // Show/hide cards
    const $cards = $('.product-card');
    if (filter === 'all') {
      $cards.show();
    } else {
      $cards.each(function () {
        const tier = $(this).data('tier');
        $(this)[tier === filter ? 'show' : 'hide']();
      });
    }

    // Empty state
    const visible = $cards.filter(':visible').length;
    $('#no-results').toggleClass('hidden', visible > 0);
  });

  // ── Add to bag ───────────────────────────────────
  $(document).on('click', '.add-to-bag', function (e) {
    e.preventDefault();
    const $btn  = $(this);
    const name  = $btn.data('name');
    const price = +$btn.data('price');
    const image = $btn.data('image') || '';

    const items = window.NbmBag.get();
    const existing = items.find(i => i.name === name);
    if (existing) {
      existing.qty++;
    } else {
      items.push({ name, price_pkr: price, qty: 1, image });
    }
    window.NbmBag.save(items);

    // Visual feedback
    const originalText = $btn.text();
    $btn.text('Added ✓').prop('disabled', true);
    setTimeout(function () {
      $btn.text(originalText).prop('disabled', false);
    }, 1400);

    window.NbmBag.open();
  });

});
</script>
@endpush
