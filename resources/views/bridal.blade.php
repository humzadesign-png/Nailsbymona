@extends('layouts.app')

@push('head')
<style>
  .faq-answer { display: none; }
  .faq-answer.open { display: block; }
</style>
@endpush

@section('seo')
    <x-seo
        title="Bridal Press-On Nails Pakistan — Mehendi, Baraat &amp; Valima Trio | Nails by Mona"
        description="The Bridal Trio — three coordinated custom-fit press-on nail sets for Mehendi, Baraat, and Valima. One fitting. One shipment. Handmade in Mirpur. From Rs. 11,000."
        :schema="json_encode([
            '@context' => 'https://schema.org',
            '@type'    => 'Product',
            'name'     => 'Bridal Trio — Mehendi, Baraat & Valima',
            'brand'    => ['@type' => 'Brand', 'name' => 'Nails by Mona'],
            'offers'   => [
                '@type'         => 'Offer',
                'priceCurrency' => 'PKR',
                'price'         => '11000',
                'availability'  => 'https://schema.org/InStock',
            ],
        ])"
    />
@endsection

@section('content')

{{-- ═══════════════════════════════════════════════
     SECTION 1 — HERO
     BG: bridal-bg (warm champagne)
═══════════════════════════════════════════════ --}}
<section class="relative min-h-[70vh] md:min-h-[80vh] flex items-center overflow-hidden">

  <!-- Background -->
  <div class="absolute inset-0 z-0" style="background: linear-gradient(150deg, #EAE3D9 0%, #F4EFE8 100%)">
    <img
      src=""
      alt="Bridal press-on nails on hands resting on red velvet wedding fabric"
      class="absolute inset-0 w-full h-full object-cover"
      onerror="this.remove()"
      width="1920" height="1080">
    <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(234,227,217,0.6) 0%, rgba(234,227,217,0.2) 50%, transparent 100%)"></div>
  </div>

  <!-- Frosted editorial card — centered -->
  <div class="relative z-10 w-full max-w-7xl mx-auto px-6 lg:px-10 py-24 flex justify-center md:justify-start">
    <div class="w-full max-w-[540px] bg-paper/85 backdrop-blur-[14px] rounded-2xl border border-white/40 shadow-2xl shadow-ink/10 p-10 md:p-14">

      <p class="font-sans text-eyebrow text-lavender uppercase mb-5 tracking-[0.22em]">
        For the Wedding
      </p>

      <h1 class="font-serif text-display-xl text-ink mb-7 leading-[0.93] max-w-[16ch]" style="font-variation-settings:'opsz' 144,'SOFT' 30">
        Your wedding nails, for all three nights.
      </h1>

      <p class="font-sans text-body-lg text-graphite mb-10 max-w-[400px]">
        The Bridal Trio &mdash; Mehendi, Baraat, and Valima &mdash; as one coordinated order. One fitting. One shipment. Handmade by Mona.
      </p>

      <div class="flex flex-wrap items-center gap-3">
        <button
          class="inline-flex items-center gap-2.5 bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full px-9 py-4 transition-colors duration-200 add-to-bag-bridal"
          style="font-size:1rem"
          data-name="Bridal Trio Package"
          data-price="11000">
          Add Trio to bag
          <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M40,72H216a8,8,0,0,1,8,8.83l-12.43,112a8,8,0,0,1-8,7.17H52.4a8,8,0,0,1-8-7.17L32,80.83A8,8,0,0,1,40,72Z"/>
            <path d="M88,104V72a40,40,0,0,1,80,0v32"/>
          </svg>
        </button>
        <a
          href="https://wa.me/{{ $settings->whatsappForWaMe() }}?text=Hello%20Nails%20by%20Mona%2C%20I%27m%20interested%20in%20the%20Bridal%20Trio%20for%20my%20wedding."
          class="font-sans text-caption text-stone hover:text-lavender-ink underline-offset-4 hover:underline transition-colors duration-200">
          Get help &rarr;
        </a>
      </div>

    </div>
  </div>

</section>


{{-- ═══════════════════════════════════════════════
     SECTION 2 — THREE NIGHTS
     BG: paper
═══════════════════════════════════════════════ --}}
<section class="bg-paper py-20 md:py-28">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <div class="text-center mb-14">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-4">The Collection</p>
      <h2 class="font-serif text-display-lg text-ink" style="font-variation-settings:'opsz' 144,'SOFT' 30">Three nights. Three looks.</h2>
      <div class="h-0.5 w-10 bg-lavender mt-5 mx-auto"></div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

      <!-- Mehendi Panel -->
      <div class="rounded-2xl overflow-hidden group">
        <div class="overflow-hidden rounded-t-2xl img-wrap-fallback relative" style="aspect-ratio:3/4; background:linear-gradient(150deg,#C4B8D2,#EAE3D9,#FBF8F2)">
          <img
            src=""
            alt="Mehendi night bridal press-on nails &mdash; intricate henna-inspired gold designs"
            class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700"
            onerror="this.remove()"
            width="480" height="640"
            loading="lazy">
          <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <p class="font-serif text-gold/40 text-4xl italic" style="font-variation-settings:'opsz' 144,'SOFT' 60">Mehendi</p>
          </div>
        </div>
        <div class="p-6 bg-paper border border-t-0 border-hairline rounded-b-2xl">
          <h3 class="font-serif text-ink mb-3" style="font-size:1.5rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">Mehendi</h3>
          <div class="h-0.5 w-8 bg-lavender my-3"></div>
          <p class="font-sans text-caption text-stone leading-relaxed">
            Earthy ochres and warm golds &mdash; designed to complement the henna on your hands. Fine floral patterns that celebrate the first night of the most beautiful chapter.
          </p>
        </div>
      </div>

      <!-- Baraat Panel -->
      <div class="rounded-2xl overflow-hidden group">
        <div class="overflow-hidden rounded-t-2xl img-wrap-fallback relative" style="aspect-ratio:3/4; background:linear-gradient(150deg,#8B2535,#5C1520,#3A0A10)">
          <img
            src=""
            alt="Baraat night bridal press-on nails &mdash; rich deep reds and gold accents"
            class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700"
            onerror="this.remove()"
            width="480" height="640"
            loading="lazy">
          <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <p class="font-serif text-bone/30 text-4xl italic" style="font-variation-settings:'opsz' 144,'SOFT' 60">Baraat</p>
          </div>
        </div>
        <div class="p-6 bg-paper border border-t-0 border-hairline rounded-b-2xl">
          <h3 class="font-serif text-ink mb-3" style="font-size:1.5rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">Baraat</h3>
          <div class="h-0.5 w-8 bg-lavender my-3"></div>
          <p class="font-sans text-caption text-stone leading-relaxed">
            Deep reds, burgundies, and intricate 3D crystals for the main event. Dramatic enough to photograph beautifully, precise enough to feel like art on your hands.
          </p>
        </div>
      </div>

      <!-- Valima Panel -->
      <div class="rounded-2xl overflow-hidden group">
        <div class="overflow-hidden rounded-t-2xl img-wrap-fallback relative" style="aspect-ratio:3/4; background:linear-gradient(150deg,#E8D8C8,#F0E8E0,#F8F4EE)">
          <img
            src=""
            alt="Valima night bridal press-on nails &mdash; soft blush and ivory with gold details"
            class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700"
            onerror="this.remove()"
            width="480" height="640"
            loading="lazy">
          <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <p class="font-serif text-stone/30 text-4xl italic" style="font-variation-settings:'opsz' 144,'SOFT' 60">Valima</p>
          </div>
        </div>
        <div class="p-6 bg-paper border border-t-0 border-hairline rounded-b-2xl">
          <h3 class="font-serif text-ink mb-3" style="font-size:1.5rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">Valima</h3>
          <div class="h-0.5 w-8 bg-lavender my-3"></div>
          <p class="font-sans text-caption text-stone leading-relaxed">
            Soft blush, ivory, and champagne &mdash; lighter and luminous for the reception. Understated elegance that transitions naturally from the celebrations before it.
          </p>
        </div>
      </div>

    </div>

  </div>
</section>


{{-- ═══════════════════════════════════════════════
     SECTION 3 — WHAT'S INCLUDED + PRICING
     BG: shell
═══════════════════════════════════════════════ --}}
<section class="bg-shell py-20 md:py-28">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <div class="text-center mb-14">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-4">The Package</p>
      <h2 class="font-serif text-display-lg text-ink" style="font-variation-settings:'opsz' 144,'SOFT' 30">Everything you need, in one order.</h2>
      <div class="h-0.5 w-10 bg-lavender mt-5 mx-auto"></div>
    </div>

    <div class="grid md:grid-cols-2 gap-10 items-start">

      <!-- Checklist -->
      <div>
        <ul class="space-y-5">

          <li class="flex items-start gap-4">
            <span class="shrink-0 mt-0.5 w-6 h-6 rounded-full bg-lavender-wash flex items-center justify-center">
              <svg class="w-3.5 h-3.5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="22" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="40 144 96 200 216 80"/>
              </svg>
            </span>
            <div>
              <p class="font-sans font-semibold text-ink" style="font-size:0.9375rem">Three coordinated sets (36 nails total + spares)</p>
              <p class="font-sans text-caption text-stone mt-0.5">Mehendi, Baraat, and Valima &mdash; each designed as part of a cohesive story for your wedding.</p>
            </div>
          </li>

          <li class="flex items-start gap-4">
            <span class="shrink-0 mt-0.5 w-6 h-6 rounded-full bg-lavender-wash flex items-center justify-center">
              <svg class="w-3.5 h-3.5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="22" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="40 144 96 200 216 80"/>
              </svg>
            </span>
            <div>
              <p class="font-sans font-semibold text-ink" style="font-size:0.9375rem">One sizing session &mdash; profile saved for reorders</p>
              <p class="font-sans text-caption text-stone mt-0.5">Your nail profile stays on file. Future orders &mdash; even after the wedding &mdash; arrive perfectly fitted without repeating the process.</p>
            </div>
          </li>

          <li class="flex items-start gap-4">
            <span class="shrink-0 mt-0.5 w-6 h-6 rounded-full bg-lavender-wash flex items-center justify-center">
              <svg class="w-3.5 h-3.5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="22" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="40 144 96 200 216 80"/>
              </svg>
            </span>
            <div>
              <p class="font-sans font-semibold text-ink" style="font-size:0.9375rem">One shipment &mdash; all three sets together</p>
              <p class="font-sans text-caption text-stone mt-0.5">Carefully packed and shipped via TCS. Track your order in real time.</p>
            </div>
          </li>

          <li class="flex items-start gap-4">
            <span class="shrink-0 mt-0.5 w-6 h-6 rounded-full bg-lavender-wash flex items-center justify-center">
              <svg class="w-3.5 h-3.5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="22" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="40 144 96 200 216 80"/>
              </svg>
            </span>
            <div>
              <p class="font-sans font-semibold text-ink" style="font-size:0.9375rem">Premium magnetic box with satin lining</p>
              <p class="font-sans text-caption text-stone mt-0.5">Name-labelled compartments for each event. A keepsake as much as a package.</p>
            </div>
          </li>

          <li class="flex items-start gap-4">
            <span class="shrink-0 mt-0.5 w-6 h-6 rounded-full bg-lavender-wash flex items-center justify-center">
              <svg class="w-3.5 h-3.5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="22" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="40 144 96 200 216 80"/>
              </svg>
            </span>
            <div>
              <p class="font-sans font-semibold text-ink" style="font-size:0.9375rem">Mini glue + prep kit included</p>
              <p class="font-sans text-caption text-stone mt-0.5">Everything you need to apply and remove each set at home. No salon visit required.</p>
            </div>
          </li>

          <li class="flex items-start gap-4">
            <span class="shrink-0 mt-0.5 w-6 h-6 rounded-full bg-lavender-wash flex items-center justify-center">
              <svg class="w-3.5 h-3.5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="22" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="40 144 96 200 216 80"/>
              </svg>
            </span>
            <div>
              <p class="font-sans font-semibold text-ink" style="font-size:0.9375rem">Free first refit guarantee</p>
              <p class="font-sans text-caption text-stone mt-0.5">If a set doesn't sit right for any reason, we'll refit it free. No questions asked.</p>
            </div>
          </li>

          <li class="flex items-start gap-4">
            <span class="shrink-0 mt-0.5 w-6 h-6 rounded-full bg-lavender-wash flex items-center justify-center">
              <svg class="w-3.5 h-3.5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="22" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="40 144 96 200 216 80"/>
              </svg>
            </span>
            <div>
              <p class="font-sans font-semibold text-ink" style="font-size:0.9375rem">WhatsApp support through your whole wedding timeline</p>
              <p class="font-sans text-caption text-stone mt-0.5">Questions at any stage &mdash; from order placement to Valima morning &mdash; answered promptly.</p>
            </div>
          </li>

        </ul>
      </div>

      <!-- Pricing card -->
      <div class="bg-paper rounded-2xl border border-hairline p-8 shadow-card sticky top-24">

        <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Bridal Trio</p>

        <p class="font-serif text-ink mb-2" style="font-size:clamp(2rem,3.5vw,2.75rem); font-weight:300; line-height:1.1; font-variation-settings:'opsz' 144,'SOFT' 30">
          Rs. 11,000 &ndash; 13,500
        </p>
        <p class="font-sans text-caption text-stone mb-6">depending on design complexity</p>

        <div class="h-px bg-hairline mb-5"></div>

        <div class="space-y-2 mb-5">
          <div class="flex justify-between">
            <span class="font-sans text-caption text-stone">Bridal Single (one event)</span>
            <span class="font-sans text-caption text-graphite">Rs. 5,000 &ndash; 6,500</span>
          </div>
          <div class="flex justify-between">
            <span class="font-sans text-caption text-stone">Three singles separately</span>
            <span class="font-sans text-caption text-graphite line-through">Rs. 15,000 &ndash; 19,500</span>
          </div>
        </div>

        <p class="font-sans text-caption text-stone italic mb-6">
          The Trio saves 10&ndash;15% vs ordering three singles &mdash; and one less thing to coordinate during wedding planning.
        </p>

        <div class="h-px bg-hairline mb-6"></div>

        <button
          class="inline-flex items-center justify-center gap-2 w-full bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full py-4 transition-colors duration-200 add-to-bag-bridal"
          style="font-size:1rem"
          data-name="Bridal Trio Package"
          data-price="11000">
          Order your Bridal Trio
        </button>

        <p class="font-sans text-caption text-stone text-center mt-4">
          Full advance required &middot; TCS delivery &middot; Free refit
        </p>

      </div>

    </div>

  </div>
</section>


{{-- ═══════════════════════════════════════════════
     SECTION 4 — TIMELINE
     BG: paper
═══════════════════════════════════════════════ --}}
<section class="bg-paper py-20 md:py-28">
  <div class="max-w-5xl mx-auto px-6 lg:px-10">

    <div class="text-center mb-14">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Planning Ahead</p>
      <h2 class="font-serif text-display-lg text-ink" style="font-variation-settings:'opsz' 144,'SOFT' 30">Order at least 4 weeks before your Mehendi.</h2>
      <div class="h-0.5 w-10 bg-lavender mt-5 mx-auto"></div>
    </div>

    <!-- Desktop: horizontal; Mobile: vertical -->
    <div class="hidden md:grid grid-cols-5 gap-4 relative">
      <!-- Connector line -->
      <div class="absolute top-6 left-[10%] right-[10%] h-px bg-hairline" aria-hidden="true"></div>

      <div class="text-center relative">
        <div class="w-12 h-12 rounded-full bg-bone border border-hairline flex items-center justify-center mx-auto mb-4 relative z-10">
          <span class="font-serif text-lavender leading-none" style="font-size:1.125rem; font-variation-settings:'opsz' 144,'SOFT' 30">01</span>
        </div>
        <h3 class="font-sans font-semibold text-ink mb-1" style="font-size:0.875rem">Place order</h3>
        <p class="font-sans text-caption text-stone">4+ weeks before Mehendi</p>
      </div>

      <div class="text-center relative">
        <div class="w-12 h-12 rounded-full bg-bone border border-hairline flex items-center justify-center mx-auto mb-4 relative z-10">
          <span class="font-serif text-lavender leading-none" style="font-size:1.125rem; font-variation-settings:'opsz' 144,'SOFT' 30">02</span>
        </div>
        <h3 class="font-sans font-semibold text-ink mb-1" style="font-size:0.875rem">Design confirmed</h3>
        <p class="font-sans text-caption text-stone">1&ndash;2 days via WhatsApp</p>
      </div>

      <div class="text-center relative">
        <div class="w-12 h-12 rounded-full bg-bone border border-hairline flex items-center justify-center mx-auto mb-4 relative z-10">
          <span class="font-serif text-lavender leading-none" style="font-size:1.125rem; font-variation-settings:'opsz' 144,'SOFT' 30">03</span>
        </div>
        <h3 class="font-sans font-semibold text-ink mb-1" style="font-size:0.875rem">Made by Mona</h3>
        <p class="font-sans text-caption text-stone">10&ndash;14 working days</p>
      </div>

      <div class="text-center relative">
        <div class="w-12 h-12 rounded-full bg-bone border border-hairline flex items-center justify-center mx-auto mb-4 relative z-10">
          <span class="font-serif text-lavender leading-none" style="font-size:1.125rem; font-variation-settings:'opsz' 144,'SOFT' 30">04</span>
        </div>
        <h3 class="font-sans font-semibold text-ink mb-1" style="font-size:0.875rem">TCS delivery</h3>
        <p class="font-sans text-caption text-stone">2&ndash;4 days nationwide</p>
      </div>

      <div class="text-center relative">
        <div class="w-12 h-12 rounded-full bg-lavender-wash border border-lavender flex items-center justify-center mx-auto mb-4 relative z-10">
          <span class="font-serif text-lavender leading-none" style="font-size:1.125rem; font-variation-settings:'opsz' 144,'SOFT' 30">05</span>
        </div>
        <h3 class="font-sans font-semibold text-ink mb-1" style="font-size:0.875rem">Buffer for refit</h3>
        <p class="font-sans text-caption text-stone">1 week before Mehendi</p>
      </div>

    </div>

    <!-- Mobile: vertical steps -->
    <div class="md:hidden space-y-8">

      <div class="flex gap-5 items-start">
        <div class="w-12 h-12 rounded-full bg-bone border border-hairline flex items-center justify-center shrink-0">
          <span class="font-serif text-lavender leading-none" style="font-size:1.125rem; font-variation-settings:'opsz' 144,'SOFT' 30">01</span>
        </div>
        <div class="pt-2">
          <h3 class="font-sans font-semibold text-ink mb-1" style="font-size:0.9375rem">Place order</h3>
          <p class="font-sans text-caption text-stone">4+ weeks before Mehendi</p>
        </div>
      </div>

      <div class="flex gap-5 items-start">
        <div class="w-12 h-12 rounded-full bg-bone border border-hairline flex items-center justify-center shrink-0">
          <span class="font-serif text-lavender leading-none" style="font-size:1.125rem; font-variation-settings:'opsz' 144,'SOFT' 30">02</span>
        </div>
        <div class="pt-2">
          <h3 class="font-sans font-semibold text-ink mb-1" style="font-size:0.9375rem">Design confirmed</h3>
          <p class="font-sans text-caption text-stone">1&ndash;2 days via WhatsApp</p>
        </div>
      </div>

      <div class="flex gap-5 items-start">
        <div class="w-12 h-12 rounded-full bg-bone border border-hairline flex items-center justify-center shrink-0">
          <span class="font-serif text-lavender leading-none" style="font-size:1.125rem; font-variation-settings:'opsz' 144,'SOFT' 30">03</span>
        </div>
        <div class="pt-2">
          <h3 class="font-sans font-semibold text-ink mb-1" style="font-size:0.9375rem">Made by Mona</h3>
          <p class="font-sans text-caption text-stone">10&ndash;14 working days</p>
        </div>
      </div>

      <div class="flex gap-5 items-start">
        <div class="w-12 h-12 rounded-full bg-bone border border-hairline flex items-center justify-center shrink-0">
          <span class="font-serif text-lavender leading-none" style="font-size:1.125rem; font-variation-settings:'opsz' 144,'SOFT' 30">04</span>
        </div>
        <div class="pt-2">
          <h3 class="font-sans font-semibold text-ink mb-1" style="font-size:0.9375rem">TCS delivery</h3>
          <p class="font-sans text-caption text-stone">2&ndash;4 days nationwide</p>
        </div>
      </div>

      <div class="flex gap-5 items-start">
        <div class="w-12 h-12 rounded-full bg-lavender-wash border border-lavender flex items-center justify-center shrink-0">
          <span class="font-serif text-lavender leading-none" style="font-size:1.125rem; font-variation-settings:'opsz' 144,'SOFT' 30">05</span>
        </div>
        <div class="pt-2">
          <h3 class="font-sans font-semibold text-ink mb-1" style="font-size:0.9375rem">Buffer for refit</h3>
          <p class="font-sans text-caption text-stone">1 week before Mehendi</p>
        </div>
      </div>

    </div>

    <p class="font-sans text-caption text-stone italic text-center mt-10 max-w-2xl mx-auto">
      I only take a limited number of bridal orders each month to ensure every set receives the care it deserves. During peak wedding season (October&ndash;March), orders are often placed 6&ndash;8 weeks in advance.
    </p>

  </div>
</section>


{{-- ═══════════════════════════════════════════════
     SECTION 5 — BRIDAL GALLERY
     BG: bone
═══════════════════════════════════════════════ --}}
<section class="bg-bone py-20 md:py-28">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <div class="text-center mb-12">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-4">From the Collection</p>
      <h2 class="font-serif text-display-lg text-ink" style="font-variation-settings:'opsz' 144,'SOFT' 30">From Mona's bridal collection.</h2>
      <div class="h-0.5 w-10 bg-lavender mt-5 mx-auto"></div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">

      <!-- Tile 1 — tall -->
      <div class="rounded-2xl overflow-hidden img-wrap-fallback relative row-span-2 md:row-span-2" style="aspect-ratio:2/3; background:linear-gradient(150deg,#C4B8D2 0%,#EAE3D9 100%)">
        <img src="" alt="Bridal press-on nails with gold floral detailing &mdash; Mehendi night" class="w-full h-full object-cover" onerror="this.remove()" width="400" height="600" loading="lazy">
        <div class="absolute inset-0 flex items-end p-4 pointer-events-none">
          <span class="font-sans text-eyebrow uppercase tracking-widest text-stone/40">Mehendi</span>
        </div>
      </div>

      <!-- Tile 2 -->
      <div class="rounded-2xl overflow-hidden img-wrap-fallback relative" style="aspect-ratio:1/1; background:linear-gradient(135deg,#8B2535,#5C1520)">
        <img src="" alt="Deep red and crystal bridal press-on nails &mdash; Baraat night" class="w-full h-full object-cover" onerror="this.remove()" width="400" height="400" loading="lazy">
        <div class="absolute inset-0 flex items-end p-4 pointer-events-none">
          <span class="font-sans text-eyebrow uppercase tracking-widest text-bone/30">Baraat</span>
        </div>
      </div>

      <!-- Tile 3 -->
      <div class="rounded-2xl overflow-hidden img-wrap-fallback relative" style="aspect-ratio:1/1; background:linear-gradient(135deg,#E8D8C8,#F0E8E0)">
        <img src="" alt="Soft blush and ivory bridal press-on nails &mdash; Valima reception" class="w-full h-full object-cover" onerror="this.remove()" width="400" height="400" loading="lazy">
        <div class="absolute inset-0 flex items-end p-4 pointer-events-none">
          <span class="font-sans text-eyebrow uppercase tracking-widest text-stone/40">Valima</span>
        </div>
      </div>

      <!-- Tile 4 -->
      <div class="rounded-2xl overflow-hidden img-wrap-fallback" style="aspect-ratio:1/1; background:linear-gradient(135deg,#EAE3D9,#FBF8F2)">
        <img src="" alt="Handmade bridal press-on nails with intricate hand-painted details" class="w-full h-full object-cover" onerror="this.remove()" width="400" height="400" loading="lazy">
      </div>

      <!-- Tile 5 -->
      <div class="rounded-2xl overflow-hidden img-wrap-fallback" style="aspect-ratio:1/1; background:linear-gradient(135deg,#5C1520,#8B2535,#A84050)">
        <img src="" alt="3D crystal embellishments on deep red bridal press-on nails" class="w-full h-full object-cover" onerror="this.remove()" width="400" height="400" loading="lazy">
      </div>

      <!-- Tile 6 -->
      <div class="rounded-2xl overflow-hidden img-wrap-fallback" style="aspect-ratio:1/1; background:linear-gradient(150deg,#E0C8B0,#F4EFE8)">
        <img src="" alt="Elegant champagne and pearl bridal press-on nails detail" class="w-full h-full object-cover" onerror="this.remove()" width="400" height="400" loading="lazy">
      </div>

    </div>

    <p class="font-sans text-caption text-center text-stone mt-6">
      <a href="{{ route('shop') }}" class="hover:text-lavender-ink underline-offset-4 hover:underline transition-colors duration-200">More bridal designs in the shop &rarr;</a>
    </p>

  </div>
</section>


{{-- ═══════════════════════════════════════════════
     SECTION 6 — COMPARISON TABLE
     BG: paper
═══════════════════════════════════════════════ --}}
<section class="bg-paper py-20 md:py-28">
  <div class="max-w-4xl mx-auto px-6 lg:px-10">

    <div class="text-center mb-12">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Why Press-Ons</p>
      <h2 class="font-serif text-display-lg text-ink" style="font-variation-settings:'opsz' 144,'SOFT' 30">Why brides are choosing press-ons over acrylics.</h2>
      <div class="h-0.5 w-10 bg-lavender mt-5 mx-auto"></div>
    </div>

    <div class="bg-paper border border-hairline rounded-2xl overflow-hidden shadow-card">
      <table class="w-full text-left">
        <thead>
          <tr class="border-b border-hairline bg-shell/50">
            <th class="px-6 md:px-8 py-4 font-sans text-eyebrow uppercase text-stone tracking-widest w-2/5"></th>
            <th class="px-6 md:px-8 py-4 font-sans text-eyebrow uppercase text-lavender tracking-widest">Bridal Trio</th>
            <th class="px-6 md:px-8 py-4 font-sans text-eyebrow uppercase text-stone tracking-widest">Salon Acrylics</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-hairline/70">
          <tr class="hover:bg-shell/20 transition-colors duration-150">
            <td class="px-6 md:px-8 py-4 font-sans font-medium text-ink" style="font-size:0.875rem">Cost for 3 events</td>
            <td class="px-6 md:px-8 py-4 font-sans font-medium text-lavender-ink" style="font-size:0.875rem">Rs. 11,000 &ndash; 13,500</td>
            <td class="px-6 md:px-8 py-4 font-sans text-stone" style="font-size:0.875rem">Rs. 7,500 &ndash; 15,000+</td>
          </tr>
          <tr class="hover:bg-shell/20 transition-colors duration-150">
            <td class="px-6 md:px-8 py-4 font-sans font-medium text-ink" style="font-size:0.875rem">Nail damage risk</td>
            <td class="px-6 md:px-8 py-4 font-sans font-medium text-lavender-ink" style="font-size:0.875rem">None &mdash; no chemicals</td>
            <td class="px-6 md:px-8 py-4 font-sans text-stone" style="font-size:0.875rem">High &mdash; thinning, peeling</td>
          </tr>
          <tr class="hover:bg-shell/20 transition-colors duration-150">
            <td class="px-6 md:px-8 py-4 font-sans font-medium text-ink" style="font-size:0.875rem">Custom fit</td>
            <td class="px-6 md:px-8 py-4 font-sans font-medium text-lavender-ink" style="font-size:0.875rem">Sized to your exact hands</td>
            <td class="px-6 md:px-8 py-4 font-sans text-stone" style="font-size:0.875rem">Depends on the salon</td>
          </tr>
          <tr class="hover:bg-shell/20 transition-colors duration-150">
            <td class="px-6 md:px-8 py-4 font-sans font-medium text-ink" style="font-size:0.875rem">Pre-wedding stress</td>
            <td class="px-6 md:px-8 py-4 font-sans font-medium text-lavender-ink" style="font-size:0.875rem">Arrive ready, apply in 5 min</td>
            <td class="px-6 md:px-8 py-4 font-sans text-stone" style="font-size:0.875rem">3+ hours per session</td>
          </tr>
          <tr class="hover:bg-shell/20 transition-colors duration-150">
            <td class="px-6 md:px-8 py-4 font-sans font-medium text-ink" style="font-size:0.875rem">Reusable after</td>
            <td class="px-6 md:px-8 py-4 font-sans font-medium text-lavender-ink" style="font-size:0.875rem">Yes &mdash; 3&ndash;5&times; per set</td>
            <td class="px-6 md:px-8 py-4 font-sans text-stone" style="font-size:0.875rem">No</td>
          </tr>
        </tbody>
      </table>
    </div>

    <p class="font-sans text-caption text-stone italic text-center mt-6">
      These are honest comparisons &mdash; the right choice depends on your preference and circumstance. If you have questions, reach out via WhatsApp.
    </p>

  </div>
</section>


{{-- ═══════════════════════════════════════════════
     SECTION 7 — BRIDAL FAQs
     BG: shell
═══════════════════════════════════════════════ --}}
<section class="bg-shell py-20 md:py-28">
  <div class="max-w-3xl mx-auto px-6 lg:px-10">

    <div class="text-center mb-12">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-4">FAQs</p>
      <h2 class="font-serif text-display-lg text-ink" style="font-variation-settings:'opsz' 144,'SOFT' 30">Questions brides ask me.</h2>
      <div class="h-0.5 w-10 bg-lavender mt-5 mx-auto"></div>
    </div>

    <div class="space-y-3" id="faq-list">

      <div class="bg-paper rounded-2xl border border-hairline overflow-hidden">
        <button class="faq-toggle w-full flex items-start justify-between gap-4 px-6 py-5 text-left hover:bg-shell/30 transition-colors duration-200" aria-expanded="false">
          <span class="font-sans font-medium text-ink" style="font-size:0.9375rem">Can I choose different designs for all three nights?</span>
          <svg class="chevron w-4 h-4 text-stone shrink-0 mt-0.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="64 96 128 160 192 96"/>
          </svg>
        </button>
        <div class="faq-answer px-6 pb-5">
          <p class="font-sans text-caption text-stone leading-relaxed">
            Yes &mdash; that's the whole point. Each of the three sets in the Trio is designed specifically for that night. You can go from warm gold henna patterns for Mehendi to bold deep reds for Baraat to soft ivory for Valima. We'll discuss your preferences via WhatsApp before production begins, so every set matches your outfits and your aesthetic.
          </p>
        </div>
      </div>

      <div class="bg-paper rounded-2xl border border-hairline overflow-hidden">
        <button class="faq-toggle w-full flex items-start justify-between gap-4 px-6 py-5 text-left hover:bg-shell/30 transition-colors duration-200" aria-expanded="false">
          <span class="font-sans font-medium text-ink" style="font-size:0.9375rem">Do I need an in-person fitting?</span>
          <svg class="chevron w-4 h-4 text-stone shrink-0 mt-0.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="64 96 128 160 192 96"/>
          </svg>
        </button>
        <div class="faq-answer px-6 pb-5">
          <p class="font-sans text-caption text-stone leading-relaxed">
            No &mdash; everything happens remotely. During checkout, you&rsquo;ll take two close-up photos using our live camera guide: one of your fingers laid flat with a coin above the middle finger, one of your thumb extended with a coin above the thumbnail. For brides who want a perfect fit, you can opt in to add the same two photos for your other hand. We&rsquo;ve been doing this for two years; the process works. And if anything doesn&rsquo;t sit right when you receive the sets, we&rsquo;ll refit for free before your wedding.
          </p>
        </div>
      </div>

      <div class="bg-paper rounded-2xl border border-hairline overflow-hidden">
        <button class="faq-toggle w-full flex items-start justify-between gap-4 px-6 py-5 text-left hover:bg-shell/30 transition-colors duration-200" aria-expanded="false">
          <span class="font-sans font-medium text-ink" style="font-size:0.9375rem">Can I adjust the design after I've placed my order?</span>
          <svg class="chevron w-4 h-4 text-stone shrink-0 mt-0.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="64 96 128 160 192 96"/>
          </svg>
        </button>
        <div class="faq-answer px-6 pb-5">
          <p class="font-sans text-caption text-stone leading-relaxed">
            Yes, within 48 hours of placing your order. After the design is confirmed and production begins, changes may not be possible. That's why I always discuss everything on WhatsApp first &mdash; we go through your outfit colours, preferences, and any reference images before I start. Changes after production begins may incur a small charge depending on the extent.
          </p>
        </div>
      </div>

      <div class="bg-paper rounded-2xl border border-hairline overflow-hidden">
        <button class="faq-toggle w-full flex items-start justify-between gap-4 px-6 py-5 text-left hover:bg-shell/30 transition-colors duration-200" aria-expanded="false">
          <span class="font-sans font-medium text-ink" style="font-size:0.9375rem">Can my family members order together for a group discount?</span>
          <svg class="chevron w-4 h-4 text-stone shrink-0 mt-0.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="64 96 128 160 192 96"/>
          </svg>
        </button>
        <div class="faq-answer px-6 pb-5">
          <p class="font-sans text-caption text-stone leading-relaxed">
            Absolutely. If the bride, sisters, and the mother of the bride all order together, message us on WhatsApp to arrange a group order. For three or more coordinated sets, we offer a small discount and will coordinate the designs so the family's nails complement each other beautifully in wedding photos.
          </p>
        </div>
      </div>

      <div class="bg-paper rounded-2xl border border-hairline overflow-hidden">
        <button class="faq-toggle w-full flex items-start justify-between gap-4 px-6 py-5 text-left hover:bg-shell/30 transition-colors duration-200" aria-expanded="false">
          <span class="font-sans font-medium text-ink" style="font-size:0.9375rem">Is full payment required upfront for a Bridal Trio?</span>
          <svg class="chevron w-4 h-4 text-stone shrink-0 mt-0.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="64 96 128 160 192 96"/>
          </svg>
        </button>
        <div class="faq-answer px-6 pb-5">
          <p class="font-sans text-caption text-stone leading-relaxed">
            Yes &mdash; the Bridal Trio requires full advance payment before production begins. This protects both of us: I can source materials, plan the timeline, and dedicate the capacity your order requires without uncertainty. Payment is accepted via JazzCash, EasyPaisa, or Bank Transfer. Once payment is verified, you'll receive a confirmation and your place in the production queue is secured.
          </p>
        </div>
      </div>

    </div>

    <div class="mt-10 text-center">
      <p class="font-sans text-caption text-stone mb-4">Still have questions?</p>
      <a
        href="https://wa.me/{{ $settings->whatsappForWaMe() }}?text=Hello%20Nails%20by%20Mona%2C%20I%27m%20interested%20in%20the%20Bridal%20Trio%20for%20my%20wedding."
        class="inline-flex items-center gap-2 border border-ink text-ink hover:bg-ink hover:text-bone font-sans text-caption font-medium tracking-wide rounded-full px-7 py-3 transition-colors duration-200">
        <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="14" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M152.61,165.49a48,48,0,0,1-62.1-62.1A8,8,0,0,1,93.8,99.46l13.6,21.84a8,8,0,0,1-1.21,9.62L98.91,138.6a40,40,0,0,0,18.49,18.49l7.68-7.28a8,8,0,0,1,9.62-1.21L156.54,162.2A8,8,0,0,1,152.61,165.49Z"/>
          <path d="M128,32a96,96,0,0,0-83.32,143.51L32.27,224l49.71-12.49A96,96,0,1,0,128,32Z"/>
        </svg>
        Get help on WhatsApp
      </a>
    </div>

  </div>
</section>

@endsection

@push('scripts')
<script>
$(function () {

  // ── Add to bag — Bridal Trio ─────────────────────
  $(document).on('click', '.add-to-bag-bridal', function () {
    const name      = $(this).data('name')  || 'Bridal Trio Package';
    const price_pkr = parseInt($(this).data('price') || '11000', 10);
    const items = window.NbmBag.get();
    const existing = items.find(i => i.name === name);
    if (existing) {
      existing.qty++;
    } else {
      items.push({ name, price_pkr, qty: 1, image: '' });
    }
    window.NbmBag.save(items);
    window.NbmBag.open();
  });

  // ── FAQ accordion ────────────────────────────────
  $(document).on('click', '.faq-toggle', function () {
    const $btn    = $(this);
    const $answer = $btn.next('.faq-answer');
    const isOpen  = $btn.hasClass('open');

    // Close all
    $('.faq-toggle').removeClass('open').attr('aria-expanded', 'false');
    $('.faq-answer').removeClass('open');

    if (!isOpen) {
      $btn.addClass('open').attr('aria-expanded', 'true');
      $answer.addClass('open');
    }
  });

});
</script>
@endpush
