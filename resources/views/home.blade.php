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
@endphp

@section('seo')
    <x-seo
        title="Nails by Mona — Custom-Fit Press-On Gel Nails, Pakistan"
        description="Handmade, custom-fit press-on gel nails. Live-camera sizing. Wudu-friendly. Reusable 3–5×. Shipped across Pakistan from Mirpur."
        :schema="$homeSchema"
    />
@endsection

@section('content')

{{-- ═══════════════════════════════════════════
     SECTION 1 — HERO
     BG: bone (full-bleed image)
═══════════════════════════════════════════ --}}
<section class="relative min-h-[80vh] md:min-h-[88vh] flex items-center overflow-hidden">

    {{-- Background image + overlays --}}
    <div class="absolute inset-0 z-0 img-wrap-dark-fallback">
        <img
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJ32yAAfsHl2sbXiBXZLY3IKtRwIkwWXvKcHiU-VwXu3GcBZS-OCOQtkAzs0rwKgW4Dxp9-Z2au4aru1Nu0BtdA5S-uZ-NUYxfvCRB_EPlJ78I5QkkZiWeSOyecrlfT0sA2Kwo2P3RsYdsqE_IW2xtSSrJ7oKPp3RpON2gXOphhVK9Cf3kYPdg_HLtT-hDSpv_Dk7TIiOOMCwZD8yjf2AGWErYtoydiByeD07Oucov2MbMli1OY4mgn38QBbTbMGRf_-KaN1SZYy0"
            alt="Close-up of hands wearing custom-fit handmade press-on nails, made in Mirpur"
            class="absolute inset-0 w-full h-full object-cover"
            loading="eager"
            onerror="this.remove()"
            width="1920" height="1080">
        <div class="absolute inset-0 bg-gradient-to-r from-ink/35 via-ink/15 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink/15 via-transparent to-transparent"></div>
    </div>

    {{-- Frosted editorial card --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 py-24 w-full">
        <div class="w-full max-w-[560px] bg-paper/82 backdrop-blur-[14px] rounded-2xl border border-white/35 shadow-2xl shadow-ink/15 p-10 md:p-14">

            <div class="flex flex-wrap gap-2 mb-6">
                <span class="inline-flex items-center rounded-full border border-lavender/40 bg-lavender/10 px-3 py-1 font-sans font-medium text-lavender-ink"
                      style="font-size:0.72rem;letter-spacing:0.06em">
                    Handmade in Mirpur
                </span>
                <span class="inline-flex items-center rounded-full border border-lavender/40 bg-lavender/10 px-3 py-1 font-sans font-medium text-lavender-ink"
                      style="font-size:0.72rem;letter-spacing:0.06em">
                    Made to fit
                </span>
            </div>

            <h1 class="font-serif text-display-xl text-ink mb-7 max-w-[14ch]">
                Custom-fit press-on nails,<br>made for your hands.
            </h1>

            <p class="font-sans text-body-lg text-graphite mb-10 max-w-[400px]">
                Handmade gel sets, sized from two close-up photos of your fingers and thumb. Wudu-friendly. Reusable three to five times. Shipped across Pakistan.
            </p>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('shop') }}"
                   class="inline-flex items-center gap-2.5 bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full px-9 py-4 transition-colors duration-200" style="font-size:1rem">
                    Browse the collection
                    <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="40" y1="128" x2="216" y2="128"/>
                        <polyline points="144 56 216 128 144 200"/>
                    </svg>
                </a>
                <a href="{{ route('bridal') }}"
                   class="inline-flex items-center gap-2 border border-ink/70 text-ink hover:bg-ink hover:text-bone font-sans text-caption font-medium tracking-wide rounded-full px-7 py-4 transition-colors duration-200">
                    Bridal Trio &rarr;
                </a>
            </div>

        </div>
    </div>

</section>


{{-- ═══════════════════════════════════════════
     SECTION 2 — TRUST BAR
     BG: paper
═══════════════════════════════════════════ --}}
<section class="bg-paper border-y border-hairline">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid grid-cols-2 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-hairline/70">

            <div class="flex items-start gap-4 py-9 md:pr-10">
                <span class="text-lavender shrink-0 mt-0.5" aria-hidden="true">
                    <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="216" y1="40" x2="40" y2="216"/>
                        <polyline points="40 152 40 216 104 216"/>
                        <polyline points="152 40 216 40 216 104"/>
                    </svg>
                </span>
                <div>
                    <p class="font-sans font-semibold text-ink" style="font-size:0.875rem">Custom-fit sizing</p>
                    <p class="font-sans text-caption text-stone mt-1">Sized to your exact fingers</p>
                </div>
            </div>

            <div class="flex items-start gap-4 py-9 md:px-10">
                <span class="text-lavender shrink-0 mt-0.5" aria-hidden="true">
                    <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M128,24S32,96,32,152a96,96,0,0,0,192,0C224,96,128,24,128,24Z"/>
                    </svg>
                </span>
                <div>
                    <p class="font-sans font-semibold text-ink" style="font-size:0.875rem">Wudu-friendly</p>
                    <p class="font-sans text-caption text-stone mt-1">Remove &amp; reapply with ease</p>
                </div>
            </div>

            <div class="flex items-start gap-4 py-9 md:px-10">
                <span class="text-lavender shrink-0 mt-0.5" aria-hidden="true">
                    <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M48,128a80,80,0,0,1,144-48"/>
                        <polyline points="184 32 192 80 144 88"/>
                        <path d="M208,128a80,80,0,0,1-144,48"/>
                        <polyline points="72 224 64 176 112 168"/>
                    </svg>
                </span>
                <div>
                    <p class="font-sans font-semibold text-ink" style="font-size:0.875rem">Reusable 3&ndash;5&times;</p>
                    <p class="font-sans text-caption text-stone mt-1">Gentle on natural nails</p>
                </div>
            </div>

            <div class="flex items-start gap-4 py-9 md:pl-10">
                <span class="text-lavender shrink-0 mt-0.5" aria-hidden="true">
                    <svg class="w-6 h-6" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M220,136v72a8,8,0,0,1-8,8H44a8,8,0,0,1-8-8V136"/>
                        <path d="M232,80H24a0,0,0,0,0,0,0v48a8,8,0,0,0,8,8H224a8,8,0,0,0,8-8V80A0,0,0,0,0,232,80Z"/>
                        <line x1="128" y1="136" x2="128" y2="216"/>
                        <path d="M93.2,80l10.42-50a8,8,0,0,1,7.82-6.36H144.56A8,8,0,0,1,152.38,30l10.42,50"/>
                    </svg>
                </span>
                <div>
                    <p class="font-sans font-semibold text-ink" style="font-size:0.875rem">Shipped Pakistan-wide</p>
                    <p class="font-sans text-caption text-stone mt-1">From Mirpur to your door</p>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 3 — FIT DIFFERENCE
     BG: bone
═══════════════════════════════════════════ --}}
<section class="py-20 md:py-28 bg-bone overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid md:grid-cols-2 gap-14 md:gap-20 items-center">

            {{-- Phone mockup --}}
            <div class="order-2 md:order-1 flex justify-center md:justify-start">
                <div class="relative w-[220px] md:w-[256px]">
                    <div class="absolute inset-0 translate-y-4 bg-lavender/10 blur-3xl rounded-full"></div>
                    <div class="relative w-full" style="aspect-ratio:9/19">
                        <div class="absolute inset-0 bg-ink rounded-[36px] border-[7px] border-stone/40 shadow-2xl shadow-ink/40 overflow-hidden">
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[80px] h-[24px] bg-ink rounded-b-2xl z-10"></div>
                            <div class="absolute inset-0 bg-gradient-to-b from-graphite to-ink flex flex-col items-center justify-center pt-8 pb-8 px-5">
                                <div class="absolute top-3 left-3 right-3 flex items-center gap-2">
                                    <p class="font-sans text-bone/70" style="font-size:8px; letter-spacing:0.12em">PHOTO 1 OF 2 &mdash; FINGERS</p>
                                    <div class="flex-1 h-[3px] rounded-full bg-bone/15 overflow-hidden">
                                        <div class="h-full bg-lavender" style="width:50%"></div>
                                    </div>
                                </div>
                                {{-- Guide overlay: U-shaped finger outlines + coin, matching sizing-fingers.svg --}}
                                <div class="w-full flex-1 relative flex items-center justify-center">
                                    <svg viewBox="0 0 140 156" class="w-full max-w-[120px]" fill="none">
                                        {{-- Green alignment border --}}
                                        <rect x="5" y="5" width="130" height="146" rx="5" stroke="#3F6E4A" stroke-width="2" opacity="0.85"/>
                                        {{-- Coin circle above middle finger --}}
                                        <circle cx="82" cy="36" r="13" stroke="#BFA4CE" stroke-width="1.5" stroke-dasharray="4 3" opacity="0.85"/>
                                        <text x="82" y="40" text-anchor="middle" font-family="sans-serif" font-size="8" fill="#BFA4CE" stroke="none" opacity="0.85">&#8360;</text>
                                        {{-- Pinky — leftmost, shortest (U-shape open at bottom) --}}
                                        <path d="M29,156 L29,100 Q29,92 37,92 Q46,92 46,100 L46,156" stroke="#BFA4CE" stroke-width="1.5" stroke-dasharray="4 3" opacity="0.8"/>
                                        {{-- Ring finger --}}
                                        <path d="M51,156 L51,88 Q51,80 60,80 Q68,80 68,88 L68,156" stroke="#BFA4CE" stroke-width="1.5" stroke-dasharray="4 3" opacity="0.8"/>
                                        {{-- Middle finger — tallest, coin sits above it --}}
                                        <path d="M73,156 L73,81 Q73,73 82,73 Q90,73 90,81 L90,156" stroke="#BFA4CE" stroke-width="1.5" stroke-dasharray="4 3" opacity="0.8"/>
                                        {{-- Index finger --}}
                                        <path d="M95,156 L95,90 Q95,82 104,82 Q112,82 112,90 L112,156" stroke="#BFA4CE" stroke-width="1.5" stroke-dasharray="4 3" opacity="0.8"/>
                                        <text x="82" y="57" text-anchor="middle" font-family="sans-serif" font-size="5" fill="#BFA4CE" stroke="none" opacity="0.6" letter-spacing="0.8">COIN ABOVE NAILS</text>
                                    </svg>
                                </div>
                                <div class="absolute top-12 left-1/2 -translate-x-1/2 px-2 py-0.5 rounded-full bg-success/85 flex items-center gap-1">
                                    <span class="text-bone" style="font-size:7px">&#10003;</span>
                                    <span class="font-sans uppercase text-bone" style="font-size:7px; letter-spacing:0.1em">Good lighting</span>
                                </div>
                                <p class="font-sans uppercase text-center text-bone/40" style="font-size:8px; letter-spacing:0.18em">Tap when guide is green</p>
                                <div class="mt-4 w-11 h-11 rounded-full border-[1.5px] border-bone/25 flex items-center justify-center">
                                    <div class="w-8 h-8 rounded-full bg-bone/15"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 w-2/3 h-3 bg-ink/15 blur-xl rounded-full"></div>
                </div>
            </div>

            {{-- Text column --}}
            <div class="order-1 md:order-2">
                <p class="font-sans text-eyebrow text-lavender uppercase mb-4">The fit difference</p>
                <h2 class="font-serif text-display-lg text-ink mb-5">Finally, nails that<br>actually fit.</h2>
                <div class="h-0.5 w-10 bg-lavender mb-8"></div>
                <p class="font-sans text-body-lg text-graphite mb-5">
                    Most press-ons come in one of twenty-four standard sizes. Yours don&rsquo;t. We size each set from two close-up photos &mdash; your fingers in one, your thumb in the other, with a coin above the nails for scale. Real fingers, real shape, measured to the millimetre.
                </p>
                <p class="font-sans text-body text-stone mb-10">
                    If your first set doesn&rsquo;t sit right, we&rsquo;ll refit it free. No fine print, no asking nicely.
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('size-guide') }}"
                       class="inline-flex items-center gap-2 border border-ink text-ink hover:bg-ink hover:text-bone font-sans text-caption font-medium tracking-wide rounded-full px-7 py-3 transition-colors duration-200">
                        How sizing works
                    </a>
                    <a href="{{ route('size-guide') }}"
                       class="font-sans text-caption text-stone hover:text-lavender-ink underline-offset-4 hover:underline transition-colors duration-200">
                        View guide &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 4 — COLLECTION
     BG: shell
═══════════════════════════════════════════ --}}
<section class="py-20 md:py-28 bg-shell">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="flex items-end justify-between mb-14">
            <div>
                <p class="font-sans text-eyebrow text-lavender uppercase mb-4">The Collection</p>
                <h2 class="font-serif text-display-lg text-ink">New &amp; loved designs.</h2>
                <div class="h-0.5 w-10 bg-lavender mt-5"></div>
            </div>
            <a href="{{ route('shop') }}"
               class="hidden md:inline-flex items-center gap-1 font-sans text-caption text-stone hover:text-lavender-ink underline-offset-4 hover:underline transition-colors duration-200">
                View all designs &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 md:gap-6">

            {{-- Product 1 — Minimalist Gold --}}
            <article class="bg-paper rounded-2xl overflow-hidden group shadow-card hover:shadow-card-hover transition-shadow duration-300">
                <div class="relative overflow-hidden img-wrap-fallback" style="aspect-ratio:4/5">
                    <img
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDP4a_xebqoAeloV7ZuDk7wzesNllI3BEQfOeIbrIaTyLvVQrMO1DFfaE7rtefvgiDeyIrts60rBIMiQ4mEGgVO1d1_HTkOcZhK4_rdQjzOfVZXusUcsKVYg9flnzsSjxmvlHUYA3CMHplPUMuKmcXT54JCPUWpqUXvfC-pvVksTnoWL5lQHJrrR9U1wbwv7IX_PhKgJx6GYSuF-yiq2-gKtB79eM2NSAbCcHZYCtuPRZ7k6Gf3OL4Juif4eiE_eGvEXPoptVLeBr4"
                        alt="The Minimalist Gold — Signature tier custom press-on nail set with gold foil accents"
                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-700 ease-out"
                        loading="lazy" onerror="this.remove()" width="400" height="500">
                    <span class="absolute top-4 left-4 font-sans text-eyebrow uppercase tracking-widest px-3 py-1.5 bg-shell/95 backdrop-blur-sm text-graphite rounded-full">Signature</span>
                    <div class="absolute inset-0 flex items-end justify-center pb-5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span class="font-sans text-caption font-medium text-white tracking-wide bg-ink/70 backdrop-blur-sm rounded-full px-5 py-2">View details &rarr;</span>
                    </div>
                </div>
                <a href="{{ route('product', 'the-minimalist-gold') }}" class="block px-6 py-5">
                    <h3 class="font-serif text-ink mb-2 leading-snug" style="font-size:1.25rem; font-weight:300">The Minimalist Gold</h3>
                    <p class="font-sans font-medium text-lavender tabular-nums" style="font-size:1.125rem">From Rs.&nbsp;3,500</p>
                </a>
            </article>

            {{-- Product 2 — Rose Lace Bridal --}}
            <article class="bg-paper rounded-2xl overflow-hidden group shadow-card hover:shadow-card-hover transition-shadow duration-300">
                <div class="relative overflow-hidden img-wrap-fallback" style="aspect-ratio:4/5">
                    <img
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuANxF6ktqUX0bmvPfvJZX7net-WcEzFXjyyl0TZYFZu65dmPUOUDbAn2GLK7RpgABaw1_kfqEScBQoslu83ALrdIwXuFbrwaPsiSckbhnkJeF7jaob8TrHQNy67ADlONyWPGChq5fIjKgTeTWfQeAEbM20oFk9KlCUDQjihzKlgFyTS1nnP5sEQZfBU1pzjzkl-aRt3vnVB7i9RMsFaVlhCWE9-nk8jcGuWRvP7PDy9Jagyr6Xs6Qm3n5dKJywwcc03DY9BYL0W-rI"
                        alt="Rose Lace Bridal — Bridal tier custom press-on nail set with delicate lace detail"
                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-700 ease-out"
                        loading="lazy" onerror="this.remove()" width="400" height="500">
                    <span class="absolute top-4 left-4 font-sans text-eyebrow uppercase tracking-widest px-3 py-1.5 bg-gold/95 backdrop-blur-sm text-ink rounded-full">Bridal</span>
                    <div class="absolute inset-0 flex items-end justify-center pb-5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span class="font-sans text-caption font-medium text-white tracking-wide bg-ink/70 backdrop-blur-sm rounded-full px-5 py-2">View details &rarr;</span>
                    </div>
                </div>
                <a href="{{ route('product', 'rose-lace-bridal') }}" class="block px-6 py-5">
                    <h3 class="font-serif text-ink mb-2 leading-snug" style="font-size:1.25rem; font-weight:300">Rose Lace Bridal</h3>
                    <p class="font-sans font-medium text-lavender tabular-nums" style="font-size:1.125rem">From Rs.&nbsp;5,500</p>
                </a>
            </article>

            {{-- Product 3 — Midnight Velvet --}}
            <article class="bg-paper rounded-2xl overflow-hidden group shadow-card hover:shadow-card-hover transition-shadow duration-300 sm:col-span-2 md:col-span-1">
                <div class="relative overflow-hidden img-wrap-fallback" style="aspect-ratio:4/5">
                    <img
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCL70arPTXmNVmSYl6-46OBPhWVtBJgwnWzUbsGZFDL2f0yeBClHhMkldZeCbgJ8eoy9T9nX5EB_86IWvNThMKfLi6LFCvp3kTogBcoASi2YGRZxoY_EgpX9Cu0qbs9gJCGQnDtePAI5TuaPq7KI5OMiwq8uz0CRgegC4L5IB1YAwxJbn9eAvs7r9BBBLs0CL_98w5KUaiMoKZOCrQ4XEnPpe_-ZZMHy01cfxoN4WhK7aPy7P03WmyXfl2jrwFWBEyAfGKygY0ek0k"
                        alt="Midnight Velvet — Glam tier custom press-on nail set with deep velvet finish"
                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-[1.04] transition-transform duration-700 ease-out"
                        loading="lazy" onerror="this.remove()" width="400" height="500">
                    <span class="absolute top-4 left-4 font-sans text-eyebrow uppercase tracking-widest px-3 py-1.5 bg-graphite/95 backdrop-blur-sm text-bone rounded-full">Glam</span>
                    <div class="absolute inset-0 flex items-end justify-center pb-5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span class="font-sans text-caption font-medium text-white tracking-wide bg-ink/70 backdrop-blur-sm rounded-full px-5 py-2">View details &rarr;</span>
                    </div>
                </div>
                <a href="{{ route('product', 'midnight-velvet') }}" class="block px-6 py-5">
                    <h3 class="font-serif text-ink mb-2 leading-snug" style="font-size:1.25rem; font-weight:300">Midnight Velvet</h3>
                    <p class="font-sans font-medium text-lavender tabular-nums" style="font-size:1.125rem">From Rs.&nbsp;4,800</p>
                </a>
            </article>

        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('shop') }}"
               class="inline-flex items-center gap-2 border border-graphite/50 text-graphite hover:border-ink hover:text-ink font-sans text-caption font-medium tracking-wide rounded-full px-8 py-3.5 transition-colors duration-200">
                View all designs
                <svg class="w-3.5 h-3.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="40" y1="128" x2="216" y2="128"/>
                    <polyline points="144 56 216 128 144 200"/>
                </svg>
            </a>
        </div>

    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 5 — BRIDAL TRIO
     BG: bridal-bg (champagne)
═══════════════════════════════════════════ --}}
<section class="py-20 md:py-28 bg-bridal-bg">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid md:grid-cols-2 gap-12 md:gap-20 items-center">

            {{-- Text column --}}
            <div>
                <p class="font-sans text-eyebrow text-lavender uppercase mb-5">For the wedding</p>
                <h2 class="font-serif text-display-lg text-ink mb-5 leading-[1.0]">
                    Three nights.<br>One fitting.<br>Three coordinated looks.
                </h2>
                <div class="h-0.5 w-10 bg-lavender mb-8"></div>
                <p class="font-sans text-body-lg text-graphite mb-3">
                    The Bridal Trio is built for Mehendi, Baraat, and Valima &mdash; three sets, sized once, packaged in a magnetic keepsake box with a handwritten name card and prep kit.
                </p>
                <p class="font-sans text-body text-stone mb-10">
                    Order four weeks before your mehendi. Starting from <span class="text-gold-deep font-medium tracking-tight">Rs.&nbsp;11,000</span>.
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('bridal') }}"
                       class="inline-flex items-center gap-2.5 bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full px-9 py-4 transition-colors duration-200" style="font-size:1rem">
                        Discover the Trio
                        <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="40" y1="128" x2="216" y2="128"/>
                            <polyline points="144 56 216 128 144 200"/>
                        </svg>
                    </a>
                    <a href="{{ route('size-guide') }}"
                       class="font-sans text-caption text-stone hover:text-lavender-ink underline-offset-4 hover:underline transition-colors duration-200">
                        Sizing &amp; fit guide &rarr;
                    </a>
                </div>
            </div>

            {{-- Bridal image --}}
            <div class="rounded-2xl overflow-hidden shadow-2xl shadow-ink/20"
                 style="aspect-ratio:3/4; background:linear-gradient(150deg,#EDE2C8 0%,#D9C28A 40%,#B8924A 100%)">
                <img
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuAMDjMPFXgmgvFReuLT1rq0rhVxtsqEr42UQqTmYLcjUihKexyK-z6bF4jMc2FT4t0v6r1VP2z6smwQlyKQsqlV9-EqBsIsISm9Kt4_BhQH7N6Uk6MVq0JA0rKFxN9wUuWp0OFyt9258JJBFvDa85Md5U-L74wbcnwWfMOv5CzCQWTM4-Z5UYZbccbpqT-q1pCsmHqpi4tyJMXPGOQmU0hQVN899lKjUthcadruE4Tt_jW8W2ZvBMow3FXqsLnKUH_ej49SsBaJvhs"
                    alt="Bridal Trio nail sets — three coordinated looks for Mehendi, Baraat, and Valima"
                    class="w-full h-full object-cover"
                    loading="lazy" onerror="this.remove()" width="600" height="800">
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 6 — WORN ACROSS PAKISTAN (UGC)
     BG: bone
═══════════════════════════════════════════ --}}
<section class="py-20 md:py-28 bg-bone">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="text-center mb-14">
            <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Worn across Pakistan</p>
            <h2 class="font-serif text-display-lg text-ink">Real customers. Real hands.</h2>
            <div class="h-0.5 w-10 bg-lavender mt-5 mx-auto"></div>
        </div>

        @if($ugcPhotos->isNotEmpty())
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4">
            @foreach($ugcPhotos as $photo)
            @php
                $src  = asset('storage/' . $photo->image_path);
                $href = $photo->product ? route('product', $photo->product->slug) : route('shop');
            @endphp
            <a href="{{ $href }}"
               class="{{ $loop->first ? 'row-span-1 md:row-span-2 aspect-square md:aspect-auto' : 'aspect-square' }} block overflow-hidden rounded-2xl relative group"
               style="background:linear-gradient(135deg,#EAE3D9,#FBF8F2)">
                <img src="{{ $src }}" alt="{{ e($photo->alt) }}"
                     class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700"
                     loading="lazy" onerror="this.parentElement.style.display='none'"
                     width="400" height="{{ $loop->first ? 800 : 400 }}">
                <div class="absolute inset-0 bg-gradient-to-t from-ink/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-1 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <p class="font-sans text-white text-sm leading-snug line-clamp-2">{{ $photo->alt }}</p>
                    @if($photo->product)
                    <span class="mt-1 inline-flex items-center gap-1 font-sans text-white/80 text-xs uppercase tracking-wider">
                        Shop this set
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @endif

    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 7 — STUDIO TEASER
     BG: paper
═══════════════════════════════════════════ --}}
<section class="py-20 md:py-28 bg-paper">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="grid md:grid-cols-2 gap-12 md:gap-24 items-center">

            {{-- Text column --}}
            <div>
                <p class="font-sans text-eyebrow text-lavender uppercase mb-4">From the studio</p>
                <h2 class="font-serif text-display-lg text-ink mb-5">A small studio.<br>A steady hand.</h2>
                <div class="h-0.5 w-10 bg-lavender mb-9"></div>

                <div class="relative pl-6 border-l-2 border-lavender/30 space-y-6 mb-10">
                    <p class="font-serif text-body-lg text-graphite italic leading-loose" style="font-variation-settings:'opsz' 144,'SOFT' 60">
                        &ldquo;Nails by Mona started as a personal quest&thinsp;&mdash;&thinsp;I wanted beautiful nails I could actually wear as a practicing Muslim. In my studio in Mirpur, I hand-paint every single set.&rdquo;
                    </p>
                    <p class="font-serif text-body text-stone italic leading-loose" style="font-variation-settings:'opsz' 144,'SOFT' 60">
                        &ldquo;Self-care shouldn&rsquo;t be loud or synthetic. It should be a quiet moment of artistry you carry with you.&rdquo;
                    </p>
                </div>

                <a href="{{ route('about') }}"
                   class="inline-flex items-center gap-1 font-sans text-caption text-graphite hover:text-lavender-ink underline-offset-4 hover:underline transition-colors duration-200">
                    Read the studio story &rarr;
                </a>
            </div>

            {{-- Studio image + signature --}}
            <div class="relative">
                <div class="overflow-hidden rounded-2xl shadow-card img-wrap-fallback"
                     style="aspect-ratio:4/5; background:linear-gradient(150deg,#EAE3D9 0%,#F4EFE8 50%,#FBF8F2 100%)">
                    <img
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCRNrh2VamPvaM4JFu7dU4Va-_DRV5XtI77RJyYyrHyZQtLhDs9Jyq55MwfH1kaPESe2unFgGa1f7BMg9gQ_5aFIXFcALZMrhQp5AWq2kz_3UzmBWlSiHU6darUNcjQTP6njSjtMjEUtnDUxqVSE6BWvPKtAgUMOihilWJN5VV6MFyFsh6UkAYlOe-b7I3dsNwqumi09UlwfJCDpBXx-JrHuQntQQ6Xqi1sL5dFejTNG3pugBxgPZ2N5y4hLSBFqBIWTFKy2yT9ofg"
                        alt="Hands painting press-on nails in the Nails by Mona studio, Mirpur AJK"
                        class="w-full h-full object-cover"
                        loading="lazy" onerror="this.remove()" width="480" height="600">
                </div>
                {{-- Handwritten signature badge --}}
                <div class="absolute bottom-5 right-5 bg-bone/95 backdrop-blur-sm border border-hairline rounded-xl px-4 py-3 shadow-card">
                    <span class="font-serif text-2xl text-ink italic" style="font-variation-settings:'opsz' 144,'SOFT' 100">Mona</span>
                </div>
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 8 — HOW IT WORKS
     BG: shell
═══════════════════════════════════════════ --}}
<section class="py-20 md:py-28 bg-shell">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="text-center mb-16 md:mb-20">
            <p class="font-sans text-eyebrow text-lavender uppercase mb-4">How it works</p>
            <h2 class="font-serif text-display-lg text-ink">From your hand to your hands.</h2>
            <div class="h-0.5 w-10 bg-lavender mt-5 mx-auto"></div>
        </div>

        <div class="steps-grid grid grid-cols-2 md:grid-cols-4 gap-10 md:gap-8">

            <div class="relative">
                <div class="w-14 h-14 rounded-full bg-paper border border-hairline flex items-center justify-center mb-5 relative z-10">
                    <span class="font-serif text-lavender leading-none" style="font-size:1.25rem">01</span>
                </div>
                <h3 class="font-sans font-semibold text-ink mb-2" style="font-size:0.9375rem">Choose a design</h3>
                <p class="font-sans text-caption text-stone leading-relaxed">Browse the collection or describe your dream set.</p>
            </div>

            <div class="relative">
                <div class="w-14 h-14 rounded-full bg-paper border border-hairline flex items-center justify-center mb-5 relative z-10">
                    <span class="font-serif text-lavender leading-none" style="font-size:1.25rem">02</span>
                </div>
                <h3 class="font-sans font-semibold text-ink mb-2" style="font-size:0.9375rem">Send 2 sizing photos</h3>
                <p class="font-sans text-caption text-stone leading-relaxed">Fingers, then thumb &mdash; each with a coin for scale. About 90 seconds.</p>
            </div>

            <div class="relative">
                <div class="w-14 h-14 rounded-full bg-paper border border-hairline flex items-center justify-center mb-5 relative z-10">
                    <span class="font-serif text-lavender leading-none" style="font-size:1.25rem">03</span>
                </div>
                <h3 class="font-sans font-semibold text-ink mb-2" style="font-size:0.9375rem">We make &amp; ship</h3>
                <p class="font-sans text-caption text-stone leading-relaxed">Hand-painted in Mirpur. 5&ndash;9 working days to your door.</p>
            </div>

            <div class="relative">
                <div class="w-14 h-14 rounded-full bg-paper border border-hairline flex items-center justify-center mb-5 relative z-10">
                    <span class="font-serif text-lavender leading-none" style="font-size:1.25rem">04</span>
                </div>
                <h3 class="font-sans font-semibold text-ink mb-2" style="font-size:0.9375rem">Wear &amp; reuse</h3>
                <p class="font-sans text-caption text-stone leading-relaxed">Apply with brush-on glue. Reuse three to five times.</p>
            </div>

        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 9 — PRICING TABLE
     BG: bone
═══════════════════════════════════════════ --}}
<section class="py-20 md:py-28 bg-bone">
    <div class="max-w-3xl mx-auto px-6 lg:px-10">

        <div class="text-center mb-12">
            <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Pricing</p>
            <h2 class="font-serif text-display-lg text-ink">Sets for every occasion.</h2>
            <div class="h-0.5 w-10 bg-lavender mt-5 mx-auto"></div>
        </div>

        <div class="bg-paper border border-hairline rounded-2xl overflow-hidden shadow-card">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-hairline">
                        <th class="px-7 py-4 font-sans text-eyebrow uppercase text-stone tracking-widest">Collection Tier</th>
                        <th class="px-7 py-4 font-sans text-eyebrow uppercase text-stone tracking-widest text-right">Starting price</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline/70">

                    <tr class="hover:bg-shell/30 transition-colors duration-150">
                        <td class="px-7 py-5">
                            <p class="font-serif text-ink leading-tight mb-1" style="font-size:1.125rem; font-weight:300">Everyday Essential</p>
                            <p class="font-sans text-caption text-stone">Solid colours, matte or gloss finish.</p>
                        </td>
                        <td class="px-7 py-5 text-right">
                            <p class="font-sans font-medium text-lavender tabular-nums" style="font-size:1rem">Rs.&nbsp;1,800</p>
                        </td>
                    </tr>

                    <tr class="hover:bg-shell/30 transition-colors duration-150">
                        <td class="px-7 py-5">
                            <p class="font-serif text-ink leading-tight mb-1" style="font-size:1.125rem; font-weight:300">Signature Art</p>
                            <p class="font-sans text-caption text-stone">Ombr&eacute;, gold foil, custom patterns.</p>
                        </td>
                        <td class="px-7 py-5 text-right">
                            <p class="font-sans font-medium text-lavender tabular-nums" style="font-size:1rem">Rs.&nbsp;2,500</p>
                        </td>
                    </tr>

                    <tr class="hover:bg-shell/30 transition-colors duration-150">
                        <td class="px-7 py-5">
                            <p class="font-serif text-ink leading-tight mb-1" style="font-size:1.125rem; font-weight:300">Glamour Collection</p>
                            <p class="font-sans text-caption text-stone">3D art, crystals, hand-painted ombr&eacute;.</p>
                        </td>
                        <td class="px-7 py-5 text-right">
                            <p class="font-sans font-medium text-lavender tabular-nums" style="font-size:1rem">Rs.&nbsp;3,800</p>
                        </td>
                    </tr>

                    {{-- Bridal Trio — highlighted row --}}
                    <tr class="bg-lavender-wash">
                        <td class="px-7 py-5">
                            <div class="flex items-start gap-3">
                                <div>
                                    <div class="flex items-center gap-2.5 mb-1">
                                        <p class="font-serif text-ink leading-tight" style="font-size:1.125rem; font-weight:300">Bridal Trio Package</p>
                                        <span class="font-sans font-semibold uppercase tracking-wider text-white bg-lavender rounded-full px-2 py-0.5 shrink-0" style="font-size:9px">Flagship</span>
                                    </div>
                                    <p class="font-sans text-caption text-lavender-ink">Mehendi &middot; Baraat &middot; Valima &mdash; three sets, one fitting.</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-7 py-5 text-right">
                            <p class="font-sans font-semibold text-lavender tabular-nums" style="font-size:1.125rem">Rs.&nbsp;11,000+</p>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <p class="font-sans text-caption text-stone text-center mt-6">
            Payments accepted via JazzCash, EasyPaisa, and Bank Transfer.
        </p>

    </div>
</section>


{{-- ═══════════════════════════════════════════
     SECTION 10 — JOURNAL TEASER
     BG: paper
═══════════════════════════════════════════ --}}
<section class="py-20 md:py-28 bg-paper">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="flex items-end justify-between mb-14">
            <div>
                <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Journal</p>
                <h2 class="font-serif text-display-lg text-ink">Reading from the studio.</h2>
                <div class="h-0.5 w-10 bg-lavender mt-5"></div>
            </div>
            <a href="{{ route('blog') }}"
               class="hidden md:inline-flex items-center gap-1 font-sans text-caption text-stone hover:text-lavender-ink underline-offset-4 hover:underline transition-colors duration-200">
                All posts &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:gap-6">

            {{-- Post 1 --}}
            <article class="group rounded-2xl overflow-hidden border border-hairline bg-paper hover:shadow-card transition-shadow duration-300">
                <a href="{{ route('blog.post', 'how-to-apply-press-on-nails') }}" class="block h-full">
                    <div class="overflow-hidden img-wrap-fallback" style="aspect-ratio:16/10; background:linear-gradient(135deg,#EAE3D9,#FBF8F2)">
                        <img
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDk2pETKUps7piL1DaUFDJL6N_sVyMGqWgJDzXAoWK6Q6_Skwg0wvz9CiRTC4SHLeUg44fLyX4ztqqDHhq4K8F0KKr8m-Z7KfaTcUpPzwbRwBP_X8HZ-XNvQFkJNhUc25wvEJKsJ5MXaKGcqd1_WzxevpVhMISVyYQeL3nI6LemGImSyjOYD2Vc75oWCsMM1x-ujvEdqTbDbyw1GAfznmUfIg39ZsaHLH8I4ACvy3To4waBjT_osvk7IfFeVSqLhl_LmOlIiw8uF08"
                            alt="Seven-step guide to applying press-on nails at home"
                            class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700 ease-out"
                            loading="lazy" onerror="this.remove()" width="400" height="250">
                    </div>
                    <div class="p-6">
                        <p class="font-sans text-eyebrow uppercase tracking-widest text-stone mb-3">Tutorials</p>
                        <h3 class="font-serif text-ink mb-4 group-hover:text-lavender-ink transition-colors duration-200 leading-snug" style="font-size:1.125rem; font-weight:300">
                            How to apply press-on nails &mdash; a foolproof seven-step guide.
                        </h3>
                        <span class="inline-flex items-center gap-1 font-sans text-caption text-stone group-hover:text-lavender-ink transition-colors duration-200">
                            Read &rarr;
                        </span>
                    </div>
                </a>
            </article>

            {{-- Post 2 --}}
            <article class="group rounded-2xl overflow-hidden border border-hairline bg-paper hover:shadow-card transition-shadow duration-300">
                <a href="{{ route('blog.post', 'bridal-nail-trends-pakistan-2026') }}" class="block h-full">
                    <div class="overflow-hidden img-wrap-fallback" style="aspect-ratio:16/10; background:linear-gradient(135deg,#E8E1D8,#EAE3D9)">
                        <img
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDbcmMEHEVAr5UahZcYPX4Nw52pDfss7W2JzlMoKDepQMFt61gT_mrBPgylD949gN831b_upslG2gRAaODIoGPuwLIlPNbRL_w4SKqn9IZFWdvBTj8014EEzUcY5K5wdhvUw2YG3X8efZX9e6HgJjwqpJVM1zBd-QFSefspHJ9_pqD-MQLuY2apuFCeylOWVhyYFrPAnZE7C3XN632yDiD5h1lhz4TDXfT3UNctpPG3rXcwnrkIq9DbCRcO__1LdouBN_RWma2Yq_Y"
                            alt="Bridal nail trends in Pakistan for 2026"
                            class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700 ease-out"
                            loading="lazy" onerror="this.remove()" width="400" height="250">
                    </div>
                    <div class="p-6">
                        <p class="font-sans text-eyebrow uppercase tracking-widest text-stone mb-3">Bridal</p>
                        <h3 class="font-serif text-ink mb-4 group-hover:text-lavender-ink transition-colors duration-200 leading-snug" style="font-size:1.125rem; font-weight:300">
                            Bridal nail trends in Pakistan for 2026.
                        </h3>
                        <span class="inline-flex items-center gap-1 font-sans text-caption text-stone group-hover:text-lavender-ink transition-colors duration-200">
                            Read &rarr;
                        </span>
                    </div>
                </a>
            </article>

            {{-- Post 3 --}}
            <article class="group rounded-2xl overflow-hidden border border-hairline bg-paper hover:shadow-card transition-shadow duration-300">
                <a href="{{ route('blog.post', 'press-on-nails-wudu-muslim-women') }}" class="block h-full">
                    <div class="overflow-hidden img-wrap-fallback" style="aspect-ratio:16/10; background:linear-gradient(135deg,#F4EFE8,#EAE3D9)">
                        <img
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDQVdKsSA6Auceeu4keYdlNXRX9E9G5lIWVC0ahx_8o0h6TR9G5lIWVC0ahx_8o0h6TykTYT-lJykTYT-lJpRrKhsg"
                            alt="Can Muslim women wear press-on nails? Wudu and nail care explained"
                            class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700 ease-out"
                            loading="lazy" onerror="this.remove()" width="400" height="250">
                    </div>
                    <div class="p-6">
                        <p class="font-sans text-eyebrow uppercase tracking-widest text-stone mb-3">Care</p>
                        <h3 class="font-serif text-ink mb-4 group-hover:text-lavender-ink transition-colors duration-200 leading-snug" style="font-size:1.125rem; font-weight:300">
                            Can Muslim women wear press-on nails? A simple solution.
                        </h3>
                        <span class="inline-flex items-center gap-1 font-sans text-caption text-stone group-hover:text-lavender-ink transition-colors duration-200">
                            Read &rarr;
                        </span>
                    </div>
                </a>
            </article>

        </div>

        <div class="mt-10 text-center md:hidden">
            <a href="{{ route('blog') }}"
               class="font-sans text-caption text-stone hover:text-lavender-ink underline-offset-4 hover:underline">
                All posts &rarr;
            </a>
        </div>

    </div>
</section>

@endsection
