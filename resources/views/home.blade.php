@extends('layouts.app')

@php
    $homeSchema = json_encode([
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type' => 'WebSite',
                'name'  => 'Nails by Mona',
                'url'   => config('app.url'),
                'potentialAction' => [
                    '@type'       => 'SearchAction',
                    'target'      => config('app.url') . '/shop?q={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    // Redesign 2026-09-24 — docs/ux/home-redesign-2026-09.md.
    // Ad visitors (mostly Instagram, on phones) left in ~6 s without seeing a
    // design or a price. This page puts both in the first screen.
    $fromPrice = 'Rs. ' . number_format($minPrice ?? 2000);
    $heroWidths = [768, 1280, 1920];

    $tierBadge = fn (?string $tier) => match ($tier) {
        'signature'                   => 'bg-shell/95 backdrop-blur-sm text-graphite',
        'glam'                        => 'bg-graphite/90 backdrop-blur-sm text-bone',
        'bridal_single','bridal_trio' => 'bg-gold/95 backdrop-blur-sm text-ink',
        default                       => 'bg-paper/90 backdrop-blur-sm text-stone',
    };

    $pill = 'shrink-0 font-sans text-eyebrow uppercase tracking-widest rounded-full px-5 py-2 border transition-all duration-200';
    $pillOff = 'border-hairline text-stone hover:border-ink hover:text-ink';
@endphp

@section('seo')
    <x-seo
        title="Nails by Mona — Custom-Fit Press-On Gel Nails, Pakistan"
        description="Handmade, custom-fit press-on gel nails. Sized from two photos of your nails. Wudu-friendly. Reusable 3–5×. Sets from {{ $fromPrice }}, shipped across Pakistan."
        :schema="$homeSchema"
    />
@endsection

@push('head')
    {{-- Start the hero (LCP) download before the CSS/JS is parsed. --}}
    <link rel="preload" as="image" type="image/webp" fetchpriority="high"
          imagesrcset="@foreach($heroWidths as $w){{ asset('images/hero-home-red-matte-'.$w.'.webp') }} {{ $w }}w{{ $loop->last ? '' : ', ' }}@endforeach"
          imagesizes="(min-width: 768px) 50vw, 100vw">
    <style>
      #sticky-cta { transform: translateY(110%); transition: transform .3s ease; }
      #sticky-cta.show { transform: translateY(0); }
      .faq-item[open] .faq-icon { transform: rotate(45deg); }
      .faq-item summary::-webkit-details-marker { display: none; }
      .no-scrollbar { scrollbar-width: none; } .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════
     1 — HERO   · BG: bone
═══════════════════════════════════════════ --}}
<x-page-hero
    image="hero-home-red-matte"
    :widths="$heroWidths"
    position="center 40%"
    badge="Handmade in Mirpur"
    alt="Matte deep-red almond press-on gel nails — handmade by Nails by Mona in Mirpur">

    <h1 class="font-serif text-display-lg lg:text-display-xl text-ink">Press-on nails, made to fit your hands.</h1>
    <p class="font-sans text-body md:text-body-lg text-graphite mt-4 max-w-md">
        Sized from two photos of your nails. Reusable three to five times. <span class="text-ink font-medium whitespace-nowrap">Sets from {{ $fromPrice }}.</span>
    </p>
    <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-3">
        <a href="#collection" class="inline-flex items-center gap-2.5 bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full px-8 py-3.5 md:px-9 md:py-4 transition-colors duration-200">
            Shop the collection
            <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="40" y1="128" x2="216" y2="128"/><polyline points="144 56 216 128 144 200"/></svg>
        </a>
        <a href="{{ route('bridal') }}" class="hidden sm:inline font-sans text-caption font-medium text-graphite hover:text-ink underline-offset-4 hover:underline transition-colors duration-200">Bridal Trio &rarr;</a>
    </div>
    <ul class="hidden md:flex gap-8 mt-10">
        <li class="flex items-center gap-2.5 font-sans text-caption text-stone">
            <svg class="w-5 h-5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="216" y1="40" x2="40" y2="216"/><polyline points="40 152 40 216 104 216"/><polyline points="152 40 216 40 216 104"/></svg>
            Custom-fit</li>
        <li class="flex items-center gap-2.5 font-sans text-caption text-stone">
            <svg class="w-5 h-5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M128,24S32,96,32,152a96,96,0,0,0,192,0C224,96,128,24,128,24Z"/></svg>
            Wudu-friendly</li>
        <li class="flex items-center gap-2.5 font-sans text-caption text-stone">
            <svg class="w-5 h-5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M48,128a80,80,0,0,1,144-48"/><polyline points="184 32 192 80 144 88"/><path d="M208,128a80,80,0,0,1-144,48"/><polyline points="72 224 64 176 112 168"/></svg>
            Reusable 3&ndash;5&times;</li>
    </ul>
</x-page-hero>


{{-- ═══════════════════════════════════════════
     2 — THE COLLECTION   · BG: shell
     Own background band so it reads as a separate group from the hero,
     while still starting inside the first phone screen. Card + pills are
     the /shop ones.
═══════════════════════════════════════════ --}}
<section id="collection" class="bg-shell border-t border-hairline/70 pt-10 pb-14 md:pt-20 md:pb-28 scroll-mt-16">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="flex items-center justify-between gap-4 mb-4">
      <p class="font-sans text-eyebrow text-lavender uppercase">The collection</p>
      <a href="{{ route('shop') }}" class="shrink-0 font-sans text-caption font-medium text-lavender-ink hover:underline underline-offset-4">View all {{ $productCount }} &rarr;</a>
    </div>
    <h2 class="font-serif text-display text-ink">New &amp; loved designs.</h2>

    {{-- Shortcuts into the filtered shop (/shop?filter=…) --}}
    <nav class="no-scrollbar -mx-6 px-6 lg:mx-0 lg:px-0 mt-5 flex gap-2 overflow-x-auto" aria-label="Shop by style">
      <a href="{{ route('shop', ['filter' => 'everyday']) }}"  class="{{ $pill }} {{ $pillOff }}">Everyday</a>
      <a href="{{ route('shop', ['filter' => 'signature']) }}" class="{{ $pill }} {{ $pillOff }}">Signature</a>
      <a href="{{ route('shop', ['filter' => 'glam']) }}"      class="{{ $pill }} {{ $pillOff }}">Glam</a>
      <a href="{{ route('shop', ['filter' => 'bridal']) }}"    class="{{ $pill }} {{ $pillOff }}">Bridal</a>
      <a href="{{ route('shop', ['filter' => 'under3000']) }}" class="{{ $pill }} {{ $pillOff }}">Under Rs.&nbsp;3,000</a>
    </nav>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 mt-5">
      @foreach($homeProducts as $product)
        @php
          $tierValue = $product->tier?->value ?? '';
          $tierLabel = $product->tier?->label() ?? '';
          $imgSrc    = img_variant($product->cover_image);
        @endphp
        {{-- 8 on phones + desktop; 6 on tablets so the 3-column grid has no half row --}}
        <article class="{{ $loop->index >= 6 ? 'md:max-lg:hidden ' : '' }}flex flex-col bg-paper rounded-2xl overflow-hidden group shadow-card hover:shadow-card-hover transition-shadow duration-300">
          <a href="{{ route('product', $product->slug) }}">
            <div class="relative overflow-hidden" style="aspect-ratio:1/1; background:linear-gradient(145deg,#EAE3D9 0%,#DDD3C7 100%)">
              @if($imgSrc)
              <img src="{{ $imgSrc }}" alt="{{ $product->name }} — {{ $tierLabel }} tier custom-fit press-on nails"
                   class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-700 ease-out"
                   width="400" height="400" loading="{{ $loop->index < 4 ? 'eager' : 'lazy' }}" decoding="async">
              @endif
              @if($tierLabel)
              <span class="absolute top-3 left-3 font-sans text-eyebrow uppercase tracking-widest px-3 py-1.5 {{ $tierBadge($tierValue) }} rounded-full">{{ $tierLabel }}</span>
              @endif
            </div>
          </a>
          <div class="flex flex-col flex-1 px-4 md:px-5 pt-4 pb-4">
            <a href="{{ route('product', $product->slug) }}">
              <h3 class="font-serif text-ink mb-1 leading-snug capitalize" style="font-size:1.125rem; font-weight:300">{{ trim($product->name) }}</h3>
            </a>
            <p class="font-sans font-medium text-lavender tabular-nums mb-3 mt-auto" style="font-size:1rem">Rs. {{ number_format($product->price_pkr) }}</p>
            <button class="add-to-bag w-full bg-lavender hover:bg-lavender-dark text-white font-sans text-caption font-medium tracking-wide rounded-full py-2.5 transition-colors duration-200"
              data-name="{{ trim($product->name) }}"
              data-price="{{ $product->price_pkr }}"
              data-tier="{{ $tierValue }}"
              data-slug="{{ $product->slug }}"
              data-image="{{ $imgSrc }}">
              Add to bag
            </button>
          </div>
        </article>
      @endforeach
    </div>

    <div class="mt-10 text-center">
      <a href="{{ route('shop') }}" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto font-sans font-medium text-ink rounded-full px-9 py-3.5 border border-ink/20 hover:border-ink/40 transition-colors duration-200">
        See all {{ $productCount }} designs
      </a>
    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     3 — WHY THEY FIT   · BG: paper
═══════════════════════════════════════════ --}}
<section class="bg-paper border-y border-hairline py-14 md:py-28">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="md:text-center mb-10 md:mb-16">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Why they fit</p>
      <h2 class="font-serif text-display-lg text-ink">Sized to your nails, not a standard pack.</h2>
      <div class="h-0.5 w-10 bg-lavender mt-5 md:mx-auto"></div>
    </div>

    <div class="relative">
      <div class="hidden md:block absolute h-px bg-hairline" style="top:28px; left:16.66%; right:16.66%" aria-hidden="true"></div>
      <ol class="grid md:grid-cols-3 gap-8 md:gap-10">
        @foreach([
            ['01', 'Choose a design', 'From the collection — or send a picture of your dream set and we\'ll make it for you.'],
            ['02', 'Send 2 sizing photos', 'Fingers, then thumb — each with a coin for scale. Our camera guide walks you through it in about 90 seconds.'],
            ['03', 'Handmade & delivered', 'Ready in ' . $settings->lead_time_standard_days . '–7 days and tracked to your door. If your first set doesn\'t sit right, we refit it free.'],
        ] as [$num, $title, $text])
        <li class="flex md:flex-col md:items-center md:text-center gap-5 md:gap-0">
          <div class="shrink-0 w-14 h-14 rounded-full bg-paper border border-hairline flex items-center justify-center md:mb-5 relative z-10">
            <span class="font-serif text-lavender leading-none" style="font-size:1.25rem">{{ $num }}</span>
          </div>
          <div>
            <h3 class="font-sans font-semibold text-ink mb-1.5" style="font-size:0.9375rem">{{ $title }}</h3>
            <p class="font-sans text-caption text-stone leading-relaxed">{{ $text }}</p>
          </div>
        </li>
        @endforeach
      </ol>
    </div>

    <div class="mt-10 md:text-center">
      <a href="{{ route('size-guide') }}" class="font-sans text-caption font-medium text-lavender-ink hover:underline underline-offset-4">See the size guide &rarr;</a>
    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     4 — WHY PRESS-ONS   · BG: bone
═══════════════════════════════════════════ --}}
<section class="bg-bone py-14 md:py-28">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="mb-10 md:mb-14">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Why press-ons</p>
      <h2 class="font-serif text-display-lg text-ink">The salon look, without the salon.</h2>
      <div class="h-0.5 w-10 bg-lavender mt-5"></div>
    </div>

    <div class="grid md:grid-cols-3 gap-4 md:gap-6">
      <div class="bg-paper border border-hairline/70 rounded-2xl p-6 md:p-8">
        <span class="text-lavender" aria-hidden="true">
          <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M48,128a80,80,0,0,1,144-48"/><polyline points="184 32 192 80 144 88"/><path d="M208,128a80,80,0,0,1-144,48"/><polyline points="72 224 64 176 112 168"/></svg>
        </span>
        <h3 class="font-sans font-semibold text-ink mt-5 mb-1.5" style="font-size:0.9375rem">One set, worn 3&ndash;5 times</h3>
        <p class="font-sans text-caption text-stone leading-relaxed">From {{ $fromPrice }} a set — compared with Rs. 2,500–5,000 at the salon every three weeks.</p>
      </div>
      <div class="bg-paper border border-hairline/70 rounded-2xl p-6 md:p-8">
        <span class="text-lavender" aria-hidden="true">
          <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M128,216S24,160,24,94A54,54,0,0,1,128,74h0A54,54,0,0,1,232,94C232,160,128,216,128,216Z"/></svg>
        </span>
        <h3 class="font-sans font-semibold text-ink mt-5 mb-1.5" style="font-size:0.9375rem">Kind to your natural nails</h3>
        <p class="font-sans text-caption text-stone leading-relaxed">No drilling and no acetone soaks. They come off with warm water in minutes.</p>
      </div>
      <div class="bg-paper border border-hairline/70 rounded-2xl p-6 md:p-8">
        <span class="text-lavender" aria-hidden="true">
          <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M128,24S32,96,32,152a96,96,0,0,0,192,0C224,96,128,24,128,24Z"/></svg>
        </span>
        <h3 class="font-sans font-semibold text-ink mt-5 mb-1.5" style="font-size:0.9375rem">Wudu-friendly</h3>
        <p class="font-sans text-caption text-stone leading-relaxed">Take them off before wudu, put them back after — the reason Nails by Mona began. <a href="{{ route('blog.post', 'muslim-women-press-on-nails-wudu') }}" class="font-medium text-lavender-ink hover:underline underline-offset-4 whitespace-nowrap">Read the story &rarr;</a></p>
      </div>
    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     5 — BRIDAL TRIO   · BG: bridal-bg (champagne)
═══════════════════════════════════════════ --}}
<section class="bg-bridal-bg py-14 md:py-28">
  <div class="max-w-7xl mx-auto px-6 lg:px-10 grid md:grid-cols-2 gap-8 md:gap-14 items-center">
    <a href="{{ route('bridal') }}" class="group block relative rounded-2xl overflow-hidden aspect-[4/3] md:aspect-[4/5]" style="background:linear-gradient(135deg,#8B7355,#2A1F14)">
      <picture>
        <source type="image/webp" srcset="{{ asset('images/bridal-baraat-beaded-480.webp') }} 480w, {{ asset('images/bridal-baraat-beaded-960.webp') }} 960w" sizes="(min-width: 768px) 50vw, 100vw">
        <img src="{{ asset('images/bridal-baraat-beaded-960.jpg') }}" alt="Sheer nude bridal press-on nails carpeted in gold beadwork — Baraat"
             class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700" style="object-position:center 35%" loading="lazy" decoding="async">
      </picture>
      <span class="absolute top-4 left-4 font-sans text-eyebrow uppercase tracking-widest px-3 py-1.5 bg-gold/95 text-ink backdrop-blur-sm rounded-full">Bridal Trio</span>
    </a>
    <div>
      <p class="font-sans text-eyebrow uppercase mb-4 text-gold-deep">For the wedding</p>
      <h2 class="font-serif text-display-lg text-ink">Mehendi, Baraat &amp; Valima. One fitting.</h2>
      <div class="h-0.5 w-10 bg-gold mt-5 mb-6"></div>
      <p class="font-sans text-body text-graphite max-w-md">Three coordinated sets in a keepsake box — sized once, shipped together. Order at least four weeks before your Mehendi.</p>
      <p class="font-sans text-ink mt-5"><span class="font-serif text-display">Rs. 10,000</span> <span class="font-sans text-caption text-stone">for all three nights</span></p>
      <a href="{{ route('bridal') }}" class="mt-7 inline-flex items-center gap-2.5 font-sans font-medium text-ink rounded-full px-8 py-3.5 border border-ink/20 hover:border-ink/40 transition-colors duration-200">
        See the Bridal Trio
        <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="40" y1="128" x2="216" y2="128"/><polyline points="144 56 216 128 144 200"/></svg>
      </a>
    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     6 — WORN ACROSS PAKISTAN (real UGC)   · BG: paper
     ->published() in the route = is_published AND face_visible = false.
═══════════════════════════════════════════ --}}
@if($ugcPhotos->isNotEmpty())
<section class="bg-paper py-14 md:py-28">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="mb-8 md:mb-12">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Worn across Pakistan</p>
      <h2 class="font-serif text-display-lg text-ink">Real customers. Real&nbsp;hands.</h2>
      <div class="h-0.5 w-10 bg-lavender mt-5"></div>
    </div>
  </div>
  <div class="max-w-7xl mx-auto lg:px-10">
    <div class="no-scrollbar flex md:grid md:grid-cols-4 gap-4 md:gap-6 overflow-x-auto snap-x snap-mandatory scroll-px-6 px-6 lg:px-0">
      @foreach($ugcPhotos->take(4) as $photo)
      <figure class="snap-start shrink-0 w-[72%] sm:w-[44%] md:w-auto">
        <a href="{{ $photo->product ? route('product', $photo->product->slug) : route('shop') }}" class="block rounded-2xl overflow-hidden" style="aspect-ratio:4/5; background:linear-gradient(135deg,#EAE3D9,#FBF8F2)">
          <img src="{{ img_variant($photo->image_path) }}" alt="{{ $photo->alt }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
        </a>
        <figcaption class="mt-3 font-sans text-caption text-graphite leading-snug line-clamp-2">{{ $photo->alt }}</figcaption>
      </figure>
      @endforeach
    </div>
  </div>
</section>
@endif


{{-- ═══════════════════════════════════════════
     7 — ORDERING WITH CONFIDENCE   · BG: shell
     Delivery terms live here (no announcement bar — see the design doc).
═══════════════════════════════════════════ --}}
@php
    $freeAbove = (int) $settings->shipping_free_above;
    $trust = [
        ['JazzCash, EasyPaisa or bank', 'Pay the way you already do. Confirmed by email within 24 hours.',
         '<rect x="24" y="56" width="208" height="144" rx="8"/><line x1="24" y1="96" x2="232" y2="96"/><line x1="160" y1="160" x2="192" y2="160"/>'],
        ['Tracked delivery, Pakistan-wide', 'Rs. ' . number_format($settings->shipping_flat_pkr) . ' anywhere in Pakistan' . ($freeAbove > 0 ? ' — free on orders over Rs. ' . number_format($freeAbove) : '') . '.',
         '<path d="M220,136v72a8,8,0,0,1-8,8H44a8,8,0,0,1-8-8V136"/><path d="M232,80H24v48a8,8,0,0,0,8,8H224a8,8,0,0,0,8-8V80Z"/><line x1="128" y1="136" x2="128" y2="216"/><path d="M93.2,80l10.42-50a8,8,0,0,1,7.82-6.36H144.56A8,8,0,0,1,152.38,30l10.42,50"/>'],
        ['Free first refit', 'If a nail doesn\'t sit right, we remake that size.',
         '<line x1="216" y1="40" x2="40" y2="216"/><polyline points="40 152 40 216 104 216"/><polyline points="152 40 216 40 216 104"/>'],
    ];
@endphp
<section class="bg-shell border-y border-hairline">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="grid md:grid-cols-2 lg:grid-cols-4 md:gap-x-10 divide-y md:divide-y-0 lg:divide-x divide-hairline/70">
      @foreach($trust as $i => [$title, $text, $icon])
      <div class="flex items-start gap-4 py-8 md:py-10 lg:py-12 {{ $i === 0 ? 'lg:pr-10' : 'lg:px-10' }}">
        <span class="text-lavender shrink-0 mt-0.5" aria-hidden="true">
          <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
        </span>
        <div>
          <p class="font-sans font-semibold text-ink" style="font-size:0.875rem">{{ $title }}</p>
          <p class="font-sans text-caption text-stone mt-1">{{ $text }}</p>
        </div>
      </div>
      @endforeach
      <div class="flex items-start gap-4 py-8 md:py-10 lg:py-12 lg:pl-10">
        <span class="text-lavender shrink-0 mt-0.5" aria-hidden="true">
          <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M152.61,165.49a48,48,0,0,1-62.1-62.1A8,8,0,0,1,93.8,99.46l13.6,21.84a8,8,0,0,1-1.21,9.62L98.91,138.6a40,40,0,0,0,18.49,18.49l7.68-7.28a8,8,0,0,1,9.62-1.21L156.54,162.2A8,8,0,0,1,152.61,165.49Z"/><path d="M128,32a96,96,0,0,0-83.32,143.51L32.27,224l49.71-12.49A96,96,0,1,0,128,32Z"/></svg>
        </span>
        <div>
          <p class="font-sans font-semibold text-ink" style="font-size:0.875rem">Questions before you order?</p>
          <p class="font-sans text-caption text-stone mt-1">Customer care on WhatsApp. <a href="{{ route('contact') }}" class="font-medium text-lavender-ink hover:underline underline-offset-4">Get help &rarr;</a></p>
        </div>
      </div>
    </div>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     8 — BEFORE YOU ORDER (FAQ)   · BG: bone
     Answers match the FAQ table (FaqSeeder).
═══════════════════════════════════════════ --}}
<section class="bg-bone py-14 md:py-28">
  <div class="max-w-3xl mx-auto px-6 lg:px-10">
    <div class="mb-8 md:mb-12 md:text-center">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Before you order</p>
      <h2 class="font-serif text-display-lg text-ink">Good questions.</h2>
      <div class="h-0.5 w-10 bg-lavender mt-5 md:mx-auto"></div>
    </div>

    <div class="border-t border-hairline">
      @foreach([
          ['How do you get my size?', 'After you choose a design, our camera guide helps you take two close-up photos — fingers, then thumb — with a coin for scale. It takes about 90 seconds, and every nail is measured from them.'],
          ['What if they don\'t fit?', 'Your first refit is free. Send us a photo and we\'ll remake the sizes that don\'t sit right.'],
          ['How long do they last?', 'With good prep, 5–10 days per wear — and each set can be worn three to five times. Care instructions come in the box.'],
          ['How do I pay?', 'JazzCash, EasyPaisa or bank transfer. Every set is made to measure, so orders are paid in full before we begin — you upload a screenshot and we confirm by email.'],
      ] as [$q, $a])
      <details class="faq-item border-b border-hairline">
        <summary class="flex items-center justify-between gap-6 py-5 cursor-pointer list-none">
          <span class="font-sans font-medium text-ink" style="font-size:0.9375rem">{{ $q }}</span>
          <svg class="faq-icon w-4 h-4 shrink-0 text-stone transition-transform duration-200" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="16" stroke-linecap="round" aria-hidden="true"><line x1="40" y1="128" x2="216" y2="128"/><line x1="128" y1="40" x2="128" y2="216"/></svg>
        </summary>
        <p class="font-sans text-caption text-stone leading-relaxed pb-5 -mt-1 pr-10">{{ $a }}</p>
      </details>
      @endforeach
    </div>

    <p class="font-sans text-caption text-stone mt-6 md:text-center">More answers on the <a href="{{ route('contact') }}" class="font-medium text-lavender-ink hover:underline underline-offset-4">Help page</a>.</p>
  </div>
</section>


{{-- ═══════════════════════════════════════════
     STICKY BAR (phones) — appears once the hero scrolls away,
     hides over the footer.
═══════════════════════════════════════════ --}}
<div id="sticky-cta" class="md:hidden fixed inset-x-0 bottom-0 z-40 bg-paper/95 backdrop-blur-md border-t border-hairline px-6 pt-3 flex items-center gap-4" style="padding-bottom:max(0.75rem, env(safe-area-inset-bottom))">
  <div class="flex-1 min-w-0">
    <p class="font-sans text-eyebrow text-stone uppercase tracking-widest">Custom-fit sets</p>
    <p class="font-sans font-medium text-ink mt-1">from {{ $fromPrice }}</p>
  </div>
  <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full px-6 py-3 transition-colors duration-200">
    Shop now
    <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="40" y1="128" x2="216" y2="128"/><polyline points="144 56 216 128 144 200"/></svg>
  </a>
</div>

@endsection

@push('scripts')
<script>
$(function () {
  // Add to bag — same handler shape as /shop; NbmBag.add dedupes by slug,
  // opens the drawer and fires the add_to_cart analytics event.
  $(document).on('click', '.add-to-bag', function (e) {
    e.preventDefault();
    const $btn = $(this);
    if (! $btn.data('slug')) return;
    window.NbmBag.add({
      slug:      $btn.data('slug'),
      name:      $btn.data('name'),
      price_pkr: +$btn.data('price'),
      tier:      ($btn.data('tier') || '') + '',
      image:     $btn.data('image') || '',
    });
    const text = $btn.text();
    $btn.text('Added ✓').prop('disabled', true);
    setTimeout(function () { $btn.text(text).prop('disabled', false); }, 1400);
  });

  // Sticky bar: visible once the hero is out of view, hidden over the footer.
  const bar = document.getElementById('sticky-cta');
  const hero = document.getElementById('hero');
  const footer = document.querySelector('footer');
  if (bar && hero && 'IntersectionObserver' in window) {
    let heroGone = false, atFooter = false;
    const sync = () => bar.classList.toggle('show', heroGone && ! atFooter);
    new IntersectionObserver(([e]) => { heroGone = ! e.isIntersecting; sync(); }).observe(hero);
    if (footer) new IntersectionObserver(([e]) => { atFooter = e.isIntersecting; sync(); }).observe(footer);
  }
});
</script>
@endpush
