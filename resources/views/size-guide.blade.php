@extends('layouts.app')

@php
    $sizeGuideSchema = json_encode([
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type'       => 'HowTo',
                'name'        => 'How to Take Sizing Photos for Custom Press-On Nails',
                'description' => 'Two close-up photos — fingers and thumb — with any coin as a size reference. Takes about 90 seconds.',
                'step'        => [
                    ['@type' => 'HowToStep', 'name' => 'Get ready',               'text' => 'Find a dark cloth — a sweater sleeve, a cushion cover, or a dark towel. Plain dark surfaces make nail edges stand out. Have your phone and any coin in reach.'],
                    ['@type' => 'HowToStep', 'name' => 'Photo 1 — Your fingers',  'text' => 'Lay your hand flat on the cloth. Tuck your thumb behind so just the four fingers show in a row. Place a coin above your middle fingernail and snap straight overhead.'],
                    ['@type' => 'HowToStep', 'name' => 'Photo 2 — Your thumb',    'text' => 'Lay your thumb flat on the cloth, extended out. Place the coin above your thumbnail and snap.'],
                    ['@type' => 'HowToStep', 'name' => 'Optional — Other hand',   'text' => 'For most customers two photos are enough. For a perfect fit, take the same two photos for your other hand.'],
                ],
            ],
            [
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',       'item' => route('home')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Size Guide', 'item' => route('size-guide')],
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

@section('seo')
<x-seo
    title="Press-On Nail Size Guide — How to Measure for Custom Fit | Nails by Mona"
    description="Two close-up photos — fingers and thumb — with any coin. That's it. Our live camera guide walks you through both shots in about 90 seconds."
    :schema="$sizeGuideSchema"
/>
@endsection

@section('content')

<!-- HERO -->
<section class="bg-paper py-16 md:py-24 border-b border-hairline/50">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <nav class="mb-8" aria-label="Breadcrumb">
      <ol class="flex items-center gap-2 font-sans text-caption text-stone">
        <li><a href="{{ route('home') }}" class="hover:text-ink transition-colors duration-200">Home</a></li>
        <li aria-hidden="true"><span class="text-ash">›</span></li>
        <li class="text-graphite font-medium">Size Guide</li>
      </ol>
    </nav>
    <div class="max-w-2xl">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-5 tracking-[0.2em]">Sizing Guide</p>
      <h1 class="font-serif text-display-lg text-ink mb-6" style="font-variation-settings:'opsz' 144,'SOFT' 30">
        Getting your size right &mdash; about 90 seconds.
      </h1>
      <p class="font-sans text-body-lg text-graphite mb-10 max-w-lg">
        No tape measures, no guessing. Just your phone camera, any coin, and two close-up photos &mdash; one of your fingers, one of your thumb.
      </p>
      <div class="flex flex-wrap items-center gap-3">
        <a href="{{ route('order.start') }}" class="inline-flex items-center gap-2.5 bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full px-8 py-4 transition-colors duration-200" style="font-size:1rem">
          Start your order
          <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round"><line x1="40" y1="128" x2="216" y2="128"/><polyline points="144 56 216 128 144 200"/></svg>
        </a>
        <a href="https://wa.me/{{ ltrim($settings->whatsapp_number, '+') }}?text=Hello%20Nails%20by%20Mona%2C%20I%20need%20help%20with%20my%20sizing%20photos."
           class="font-sans text-caption text-stone hover:text-lavender-ink underline-offset-4 hover:underline transition-colors duration-200">
          Get help &rarr;
        </a>
      </div>
    </div>
  </div>
</section>


<!-- WHAT YOU'LL NEED -->
<section class="bg-paper py-14 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <p class="font-sans text-eyebrow text-lavender uppercase mb-3">What you'll need</p>
    <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">Before you start.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-12"></div>

    <div class="grid md:grid-cols-3 gap-8 mb-10">

      <div class="flex gap-5 items-start">
        <div class="shrink-0 w-12 h-12 rounded-2xl bg-lavender-wash flex items-center justify-center text-lavender">
          <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M208,112a80,80,0,1,1-80-80A80,80,0,0,1,208,112Z"/><path d="M152,112H128V72"/><path d="M206.85,185.15l34,34"/></svg>
        </div>
        <div>
          <h3 class="font-sans font-semibold text-ink mb-2" style="font-size:1rem">Your smartphone</h3>
          <p class="font-sans text-body text-stone">The back camera gives better image quality than the front. Any phone from the last five years works fine.</p>
        </div>
      </div>

      <div class="flex gap-5 items-start">
        <div class="shrink-0 w-12 h-12 rounded-2xl bg-lavender-wash flex items-center justify-center text-lavender">
          <svg class="w-6 h-6" viewBox="0 0 256 256" fill="currentColor"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm40-88a40,40,0,1,1-40-40A40,40,0,0,1,168,128Z"/></svg>
        </div>
        <div>
          <h3 class="font-sans font-semibold text-ink mb-2" style="font-size:1rem">Any coin</h3>
          <p class="font-sans text-body text-stone">A Rs. 5 or Rs. 10 coin is ideal, but any coin of known size works &mdash; just tell me which one when you submit. The coin sits above your nails in each photo and gives me a known size to measure your nail widths against.</p>
        </div>
      </div>

      <div class="flex gap-5 items-start">
        <div class="shrink-0 w-12 h-12 rounded-2xl bg-lavender-wash flex items-center justify-center text-lavender">
          <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><circle cx="128" cy="60" r="32"/><line x1="128" y1="28" x2="128" y2="8"/><line x1="128" y1="92" x2="128" y2="112"/><line x1="88" y1="40" x2="72" y2="24"/><line x1="168" y1="40" x2="184" y2="24"/><path d="M64,152c0-35.35,28.65-64,64-64s64,28.65,64,64"/><path d="M32,152H224"/></svg>
        </div>
        <div>
          <h3 class="font-sans font-semibold text-ink mb-2" style="font-size:1rem">Natural light</h3>
          <p class="font-sans text-body text-stone">A window in daylight is ideal. Avoid harsh overhead lighting or direct flash &mdash; it creates shadows that obscure nail edges.</p>
        </div>
      </div>

    </div>

    <p class="font-sans text-body text-stone italic">That's genuinely it. No printer. No ruler. No measuring tape. No trip to a salon.</p>

  </div>
</section>


<!-- 4-STEP GUIDE -->
<section class="bg-shell py-14 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <p class="font-sans text-eyebrow text-lavender uppercase mb-3">The photos</p>
    <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">Two close-ups, one minute.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-8"></div>

    <p class="font-sans text-body-lg text-graphite leading-relaxed max-w-2xl mb-14">
      I size your nails by reading their width directly off the coin in each photo. To do that I need to see the nails up close &mdash; not your whole hand from the wrist out. So: two close-up photos, one for your fingers, one for your thumb.
    </p>

    <div class="space-y-16">

      <!-- Step 1 — Get ready -->
      <div class="grid md:grid-cols-2 gap-10 items-center">
        <div class="img-wrap-fallback rounded-2xl aspect-[4/3] overflow-hidden">
          <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJ32yAAfsHl2sbXiBXZLY3IKtRwIkwWXvKcHiU-VwXu3GcBZS-OCOQtkAzs0rwKgW4Dxp9-Z2au4aru1Nu0BtdA5S-uZ-NUYxfvCRB_EPlJ78I5QkkZiWeSOyecrlfT0sA2Kwo2P3RsYdsqE_IW2xtSSrJ7oKPp3RpON2gXOphhVK9Cf3kYPdg_HLtT-hDSpv_Dk7TIiOOMCwZD8yjf2AGWErYtoydiByeD07Oucov2MbMli1OY4mgn38QBbTbMGRf_-KaN1SZYy0"
               alt="Hand laid flat on a dark cloth, ready for sizing photos"
               class="w-full h-full object-cover" loading="lazy" onerror="this.remove()" width="600" height="450">
        </div>
        <div>
          <div class="flex items-center gap-4 mb-5">
            <span class="font-serif text-lavender" style="font-size:3rem; font-weight:300; line-height:1; font-variation-settings:'opsz' 144,'SOFT' 30">01</span>
            <h3 class="font-sans text-h3 font-medium text-ink">Get ready</h3>
          </div>
          <p class="font-sans text-body text-graphite leading-relaxed mb-4">
            Find a dark cloth &mdash; a sweater sleeve, a cushion cover, or a dark towel works perfectly. Plain dark surfaces make your nail edges stand out clearly. Have your phone and any coin in reach. Find a window with daylight if you can.
          </p>
          <div class="bg-paper rounded-xl px-5 py-4 border border-hairline/60">
            <p class="font-sans text-caption font-medium text-ink mb-1">Tip</p>
            <p class="font-sans text-caption text-stone">Avoid busy patterned backgrounds (carpet, printed fabric). The live-camera guide reads them as clutter and the alignment border stays red.</p>
          </div>
        </div>
      </div>

      <!-- Step 2 — Photo 1: Fingers -->
      <div class="grid md:grid-cols-2 gap-10 items-center">
        <div class="order-2 md:order-1">
          <div class="flex items-center gap-4 mb-5">
            <span class="font-serif text-lavender" style="font-size:3rem; font-weight:300; line-height:1; font-variation-settings:'opsz' 144,'SOFT' 30">02</span>
            <h3 class="font-sans text-h3 font-medium text-ink">Photo 1 &mdash; Your fingers</h3>
          </div>
          <p class="font-sans text-body text-graphite leading-relaxed mb-4">
            Lay your hand flat on the cloth. Tuck your thumb behind so just the four fingers show in a flat row. Place a coin above your middle fingernail &mdash; close enough that I can see both the coin and all four nails clearly in one frame. Hold your phone straight overhead and snap.
          </p>
          <div class="bg-paper rounded-xl px-5 py-4 border border-hairline/60">
            <p class="font-sans text-caption font-medium text-ink mb-1">Tip</p>
            <p class="font-sans text-caption text-stone">The coin should sit just above your nail bed, not on top of your fingertip. Touching is fine &mdash; covering the nail isn&rsquo;t.</p>
          </div>
        </div>
        {{-- Sizing fingers guide illustration --}}
        <div class="order-1 md:order-2 rounded-2xl aspect-[4/3] bg-shell flex items-center justify-center p-10">
          <img src="{{ asset('icons/sizing-fingers.svg') }}"
               alt="Four fingers laid flat with coin above middle finger — sizing overlay guide"
               class="w-full h-full object-contain opacity-90" width="400" height="480">
        </div>
      </div>

      <!-- Step 3 — Photo 2: Thumb -->
      <div class="grid md:grid-cols-2 gap-10 items-center">
        {{-- Sizing thumb guide illustration --}}
        <div class="rounded-2xl aspect-[4/3] bg-shell flex items-center justify-center p-10">
          <img src="{{ asset('icons/sizing-thumb.svg') }}"
               alt="Thumb extended flat with coin above thumbnail — sizing overlay guide"
               class="w-full h-full object-contain opacity-90" width="300" height="480">
        </div>
        <div>
          <div class="flex items-center gap-4 mb-5">
            <span class="font-serif text-lavender" style="font-size:3rem; font-weight:300; line-height:1; font-variation-settings:'opsz' 144,'SOFT' 30">03</span>
            <h3 class="font-sans text-h3 font-medium text-ink">Photo 2 &mdash; Your thumb</h3>
          </div>
          <p class="font-sans text-body text-graphite leading-relaxed mb-4">
            Now lay your thumb flat on the cloth, extended out. Place the coin above your thumbnail &mdash; same idea, same close distance. Snap.
          </p>
          <div class="bg-paper rounded-xl px-5 py-4 border border-hairline/60">
            <p class="font-sans text-caption font-medium text-ink mb-1">Tip</p>
            <p class="font-sans text-caption text-stone">Thumbs are usually the trickiest fit because they curve more than fingers. A clear photo here saves you a refit later.</p>
          </div>
        </div>
      </div>

      <!-- Step 4 — Optional: Other hand + checklist -->
      <div class="grid md:grid-cols-2 gap-10 items-center">
        <div class="order-2 md:order-1">
          <div class="flex items-center gap-4 mb-5">
            <span class="font-serif text-lavender" style="font-size:3rem; font-weight:300; line-height:1; font-variation-settings:'opsz' 144,'SOFT' 30">04</span>
            <h3 class="font-sans text-h3 font-medium text-ink">Optional &mdash; your other hand</h3>
          </div>
          <p class="font-sans text-body text-graphite leading-relaxed mb-5">
            For most customers, two photos are enough. Most hands are symmetric within half a millimetre, well within the press-on fit tolerance &mdash; so I size both hands from the photos you&rsquo;ve already given me.
          </p>
          <p class="font-sans text-body text-graphite leading-relaxed mb-5">
            Want a perfect fit? Take the same two photos for your other hand at the end of the live-camera flow (it adds about 60 seconds). Either way, the <strong class="font-medium text-ink">first refit is on me</strong>, no questions, if anything doesn&rsquo;t sit right when you receive the set.
          </p>
          <p class="font-sans text-caption font-medium text-ink mb-3 mt-8">Quick check before you submit:</p>
          <ul class="space-y-3">
            <li class="flex items-center gap-3">
              <span class="w-5 h-5 rounded-full bg-success/15 text-success flex items-center justify-center shrink-0">
                <svg class="w-3 h-3" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
              </span>
              <span class="font-sans text-body text-graphite">Coin visible and not covering any nail</span>
            </li>
            <li class="flex items-center gap-3">
              <span class="w-5 h-5 rounded-full bg-success/15 text-success flex items-center justify-center shrink-0">
                <svg class="w-3 h-3" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
              </span>
              <span class="font-sans text-body text-graphite">All four nails inside the overlay (in the fingers photo)</span>
            </li>
            <li class="flex items-center gap-3">
              <span class="w-5 h-5 rounded-full bg-success/15 text-success flex items-center justify-center shrink-0">
                <svg class="w-3 h-3" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
              </span>
              <span class="font-sans text-body text-graphite">Even lighting &mdash; no harsh shadows across the nail row</span>
            </li>
            <li class="flex items-center gap-3">
              <span class="w-5 h-5 rounded-full bg-success/15 text-success flex items-center justify-center shrink-0">
                <svg class="w-3 h-3" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
              </span>
              <span class="font-sans text-body text-graphite">Both photos in focus &mdash; not blurry</span>
            </li>
          </ul>
        </div>
        <div class="order-1 md:order-2 img-wrap-fallback rounded-2xl aspect-[4/3] overflow-hidden">
          <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBDM56k-cNLcm1pLfZWCiicUPXBqU7H3c1oUllyCdN-Y_2eBZW3kgijSB2C7n-_o7LCQxNz0JbQ0vAlhxE5yJlkgxrgWZ0Lp-oT2o6TNmjJz1siyflScqxA1nfRcVT6EFC8Wd7Nj0AqjpHfvz2EcYbv4pBdktBP3CTCY37bV33r6hJdlC0N5N0rNGjN12fD4CMBFn89YqKPnCGnJ9iVvlEGXgYG7RLMNlpuLt7Yrwp3BLLmXm2ixFGGKk"
               alt="Pair of good sizing photos — fingers and thumb both well-framed with coin"
               class="w-full h-full object-cover" loading="lazy" onerror="this.remove()" width="600" height="450">
        </div>
      </div>

    </div>
  </div>
</section>


<!-- GOOD VS BAD EXAMPLES -->
<section class="bg-paper py-14 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Quick Reference</p>
    <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">What I can and can't work with.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-12"></div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">

      <!-- Good 1 -->
      <div class="relative rounded-2xl overflow-hidden img-wrap-fallback aspect-square">
        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJ32yAAfsHl2sbXiBXZLY3IKtRwIkwWXvKcHiU-VwXu3GcBZS-OCOQtkAzs0rwKgW4Dxp9-Z2au4aru1Nu0BtdA5S-uZ-NUYxfvCRB_EPlJ78I5QkkZiWeSOyecrlfT0sA2Kwo2P3RsYdsqE_IW2xtSSrJ7oKPp3RpON2gXOphhVK9Cf3kYPdg_HLtT-hDSpv_Dk7TIiOOMCwZD8yjf2AGWErYtoydiByeD07Oucov2MbMli1OY4mgn38QBbTbMGRf_-KaN1SZYy0" alt="Good overhead shot" class="w-full h-full object-cover" loading="lazy" onerror="this.remove()" width="400" height="400">
        <span class="absolute top-3 left-3 flex items-center gap-1.5 bg-success/90 text-white font-sans text-eyebrow uppercase px-3 py-1.5 rounded-full">
          <svg class="w-3 h-3" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
          Good
        </span>
        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-ink/60 to-transparent p-4">
          <p class="font-sans text-white" style="font-size:0.75rem">Fingers photo &mdash; four nails flat in a row, coin above middle nail, dark cloth backdrop</p>
        </div>
      </div>

      <!-- Good 2 -->
      <div class="relative rounded-2xl overflow-hidden img-wrap-fallback aspect-square">
        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBDM56k-cNLcm1pLfZWCiicUPXBqU7H3c1oUllyCdN-Y_2eBZW3kgijSB2C7n-_o7LCQxNz0JbQ0vAlhxE5yJlkgxrgWZ0Lp-oT2o6TNmjJz1siyflScqxA1nfRcVT6EFC8Wd7Nj0AqjpHfvz2EcYbv4pBdktBP3CTCY37bV33r6hJdlC0N5N0rNGjN12fD4CMBFn89YqKPnCGnJ9iVvlEGXgYG7RLMNlpuLt7Yrwp3BLLmXm2ixFGGKk" alt="Good window light" class="w-full h-full object-cover" loading="lazy" onerror="this.remove()" width="400" height="400">
        <span class="absolute top-3 left-3 flex items-center gap-1.5 bg-success/90 text-white font-sans text-eyebrow uppercase px-3 py-1.5 rounded-full">
          <svg class="w-3 h-3" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
          Good
        </span>
        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-ink/60 to-transparent p-4">
          <p class="font-sans text-white" style="font-size:0.75rem">Thumb photo &mdash; thumb extended flat, coin above thumbnail, in focus</p>
        </div>
      </div>

      <!-- Good 3 -->
      <div class="relative rounded-2xl overflow-hidden img-wrap-fallback aspect-square">
        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCJHuKIDTuXFsAXPlXB1Bl1LKYZ9Q_5UWEHiJ7l5C5SuSXoQQxC-fC7s8KSJTgXzUvJXgMR0BbcGsLqRrflFJw4fXKlBEtdnTN9bv0uVzgJU4-N1U8EqoRAezXCRNBe3NM3zGjTON0JDnLwxl8K_wvbnFJNP-0w84MUk2PMCvTInivLMZvdq0L6hpuRXjTWpPLvCYNbhrgLvpT8WFoP-rMlpBp6aVUxfB5BWCY6fNhMoZO2zIWzCmEUns" alt="In focus, full hand" class="w-full h-full object-cover" loading="lazy" onerror="this.remove()" width="400" height="400">
        <span class="absolute top-3 left-3 flex items-center gap-1.5 bg-success/90 text-white font-sans text-eyebrow uppercase px-3 py-1.5 rounded-full">
          <svg class="w-3 h-3" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
          Good
        </span>
        <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-ink/60 to-transparent p-4">
          <p class="font-sans text-white" style="font-size:0.75rem">Both photos sharp &mdash; nail edges and coin clearly visible</p>
        </div>
      </div>

      <!-- Bad 1 -->
      <div class="relative rounded-2xl overflow-hidden aspect-square" style="background:linear-gradient(135deg,#D5D0C8,#C8C4BB)">
        <span class="absolute top-3 left-3 flex items-center gap-1.5 bg-danger/90 text-white font-sans text-eyebrow uppercase px-3 py-1.5 rounded-full">
          <svg class="w-3 h-3" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round"><line x1="200" y1="56" x2="56" y2="200"/><line x1="200" y1="200" x2="56" y2="56"/></svg>
          Avoid
        </span>
        <div class="absolute inset-0 flex items-center justify-center p-6 text-center">
          <p class="font-sans text-stone" style="font-size:0.9rem">Too far away &mdash; fingers and coin small in frame, nail edges blurry, hard to measure</p>
        </div>
      </div>

      <!-- Bad 2 -->
      <div class="relative rounded-2xl overflow-hidden aspect-square" style="background:linear-gradient(135deg,#D5D0C8,#C8C4BB)">
        <span class="absolute top-3 left-3 flex items-center gap-1.5 bg-danger/90 text-white font-sans text-eyebrow uppercase px-3 py-1.5 rounded-full">
          <svg class="w-3 h-3" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round"><line x1="200" y1="56" x2="56" y2="200"/><line x1="200" y1="200" x2="56" y2="56"/></svg>
          Avoid
        </span>
        <div class="absolute inset-0 flex items-center justify-center p-6 text-center">
          <p class="font-sans text-stone" style="font-size:0.9rem">Coin missing or covering a nail &mdash; can&rsquo;t calculate scale, nail width hidden</p>
        </div>
      </div>

      <!-- Bad 3 -->
      <div class="relative rounded-2xl overflow-hidden aspect-square" style="background:linear-gradient(135deg,#A8A4A0,#989490)">
        <span class="absolute top-3 left-3 flex items-center gap-1.5 bg-danger/90 text-white font-sans text-eyebrow uppercase px-3 py-1.5 rounded-full">
          <svg class="w-3 h-3" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round"><line x1="200" y1="56" x2="56" y2="200"/><line x1="200" y1="200" x2="56" y2="56"/></svg>
          Avoid
        </span>
        <div class="absolute inset-0 flex items-center justify-center p-6 text-center">
          <p class="font-sans text-white" style="font-size:0.9rem">Busy patterned backdrop &mdash; the live-camera alignment border stays red; switch to plain dark cloth</p>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- LIVE CAMERA GUIDE -->
<section class="bg-bone py-14 md:py-20 border-t border-hairline/50">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="grid md:grid-cols-2 gap-12 items-center">

      <div>
        <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Even easier</p>
        <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">Use our in-app camera guide.</h2>
        <div class="h-0.5 w-10 bg-lavender mb-7"></div>
        <p class="font-sans text-body text-graphite leading-relaxed mb-5">
          When you place an order, there&rsquo;s a live camera screen built right into the process. It walks you through both photos in a single session &mdash; fingers first, then thumb &mdash; with an on-screen guide and a green/red border that shows you when each frame is well-framed. The guide does the framing for you.
        </p>
        <p class="font-sans text-body text-graphite leading-relaxed mb-8">
          Works on most Android and iPhone cameras from the last few years. If your camera isn&rsquo;t supported, the upload option works exactly the same &mdash; just two file inputs.
        </p>
        <a href="{{ route('order.sizing') }}" class="inline-flex items-center gap-2.5 bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full px-8 py-4 transition-colors duration-200" style="font-size:1rem">
          Try the camera guide
          <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round"><line x1="40" y1="128" x2="216" y2="128"/><polyline points="144 56 216 128 144 200"/></svg>
        </a>
      </div>

      <!-- Phone mockup -->
      <div class="flex justify-center">
        <div class="relative w-[260px]">
          <div class="rounded-[2.5rem] border-[8px] border-stone/40 bg-graphite overflow-hidden shadow-2xl aspect-[9/19]">
            {{-- Live camera overlay mockup — dark bg, U-shaped finger guides, matching home.blade.php phone --}}
            <div class="w-full h-full bg-graphite relative flex flex-col items-center justify-center gap-3">
              {{-- Progress strip --}}
              <div class="absolute top-0 left-0 right-0 flex items-center gap-2 px-3 pt-3">
                <p class="font-sans text-bone/70 shrink-0" style="font-size:7px; letter-spacing:0.12em">PHOTO 1 OF 2 &mdash; FINGERS</p>
                <div class="flex-1 h-[3px] rounded-full bg-bone/15 overflow-hidden">
                  <div class="h-full bg-lavender rounded-full" style="width:50%"></div>
                </div>
              </div>
              {{-- Brightness pill --}}
              <div class="absolute px-2 py-0.5 rounded-full bg-success/80 flex items-center gap-1" style="top:2.2rem">
                <span class="text-bone" style="font-size:7px">&#10003;</span>
                <span class="font-sans uppercase text-bone" style="font-size:7px; letter-spacing:0.1em">Good lighting</span>
              </div>
              {{-- Sizing overlay — U-shaped finger guides matching sizing-fingers.svg --}}
              <svg viewBox="0 0 140 156" class="w-[55%]" fill="none" style="margin-top:1rem">
                <rect x="5" y="5" width="130" height="146" rx="5" stroke="#3F6E4A" stroke-width="2" opacity="0.85"/>
                <circle cx="82" cy="36" r="13" stroke="#BFA4CE" stroke-width="1.5" stroke-dasharray="4 3" opacity="0.9"/>
                <text x="82" y="40" text-anchor="middle" font-family="sans-serif" font-size="8" fill="#BFA4CE" stroke="none" opacity="0.9">&#8360;</text>
                <path d="M29,156 L29,100 Q29,92 37,92 Q46,92 46,100 L46,156" stroke="#BFA4CE" stroke-width="1.5" stroke-dasharray="4 3" opacity="0.85"/>
                <path d="M51,156 L51,88 Q51,80 60,80 Q68,80 68,88 L68,156" stroke="#BFA4CE" stroke-width="1.5" stroke-dasharray="4 3" opacity="0.85"/>
                <path d="M73,156 L73,81 Q73,73 82,73 Q90,73 90,81 L90,156" stroke="#BFA4CE" stroke-width="1.5" stroke-dasharray="4 3" opacity="0.85"/>
                <path d="M95,156 L95,90 Q95,82 104,82 Q112,82 112,90 L112,156" stroke="#BFA4CE" stroke-width="1.5" stroke-dasharray="4 3" opacity="0.85"/>
                <text x="82" y="57" text-anchor="middle" font-family="sans-serif" font-size="5" fill="#BFA4CE" stroke="none" opacity="0.6" letter-spacing="0.8">COIN ABOVE NAILS</text>
              </svg>
              {{-- Shutter button --}}
              <div class="w-10 h-10 rounded-full border border-bone/25 flex items-center justify-center mt-1">
                <div class="w-7 h-7 rounded-full bg-bone/15"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- RETURNING CUSTOMERS + HELP -->
<section class="bg-paper py-14">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="grid md:grid-cols-2 gap-8">

      <div class="bg-shell rounded-2xl p-8">
        <h3 class="font-sans text-h3 font-medium text-ink mb-3">Already ordered before? Your sizing is on file.</h3>
        <p class="font-sans text-body text-graphite mb-6">
          If you&rsquo;ve ordered from me before and provided sizing photos, I keep your measurements saved. When you reorder, just mention your name and previous order number &mdash; no re-measuring needed. Reorders also get a 10% discount.
        </p>
        <a href="https://wa.me/{{ ltrim($settings->whatsapp_number, '+') }}?text=Hello%20Nails%20by%20Mona%2C%20I%27d%20like%20to%20reorder%20%E2%80%94%20I%20have%20sizing%20on%20file%20from%20a%20previous%20order."
           class="inline-flex items-center gap-2 font-sans text-caption font-medium text-lavender-ink hover:text-lavender underline-offset-4 hover:underline transition-colors duration-200">
          WhatsApp to reorder &rarr;
        </a>
      </div>

      <div class="bg-shell rounded-2xl p-8">
        <h3 class="font-sans text-h3 font-medium text-ink mb-3">Not sure if your photos are right?</h3>
        <p class="font-sans text-body text-graphite mb-6">
          If you&rsquo;re unsure whether either photo will work &mdash; maybe the lighting was off or you couldn&rsquo;t get the coin in the right spot &mdash; just send them to me on WhatsApp before placing your order. I&rsquo;ll tell you immediately whether they&rsquo;re usable or if we need a retake.
        </p>
        <a href="https://wa.me/{{ ltrim($settings->whatsapp_number, '+') }}?text=Hello%20Nails%20by%20Mona%2C%20I%27d%20like%20to%20check%20my%20sizing%20photos%20before%20I%20place%20my%20order."
           class="inline-flex items-center gap-2 font-sans text-caption font-medium text-lavender-ink hover:text-lavender underline-offset-4 hover:underline transition-colors duration-200">
          Send photo for review &rarr;
        </a>
      </div>

    </div>
  </div>
</section>

@endsection
