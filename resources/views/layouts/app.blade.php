<!DOCTYPE html>
<html lang="en-PK" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Per-page SEO meta (title, description, OG, JSON-LD) --}}
    @yield('seo')

    {{-- Google Fonts — Fraunces (display serif) + DM Sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,SOFT@9..144,300..600,30..100&family=DM+Sans:opsz,wght@9..40,300..600&display=swap" rel="stylesheet">

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('logo-text.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- RSS autodiscovery for the journal --}}
    <link rel="alternate" type="application/rss+xml" title="Nails by Mona — Journal" href="{{ route('feed') }}">

    {{-- Vite — Tailwind v4 + jQuery --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Page-specific head content --}}
    @stack('head')

    {{-- Google Analytics 4 --}}
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ZC5X3P3PT4"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-ZC5X3P3PT4');
    </script>

    {{-- Microsoft Clarity --}}
    <script>
        (function(c,l,a,r,i,t,y){
            c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
            t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
            y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
        })(window,document,"clarity","script","wrhay4fga3");
    </script>
</head>

<body class="bg-bone text-graphite antialiased">

{{-- ═══════════════════════════════════════════
     HEADER — sticky glass nav
═══════════════════════════════════════════ --}}
<header class="sticky top-0 z-50 bg-bone/90 backdrop-blur-md border-b border-hairline/60">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 h-[72px] flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('home') }}" aria-label="Nails by Mona — Home" class="shrink-0 flex items-center">
            <img
                src="{{ asset('logo-text.svg') }}"
                alt="Nails by Mona"
                class="h-[46px] w-auto"
                width="220" height="77"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
            <span class="font-serif text-lavender tracking-tight hidden" style="font-size:1.5rem; line-height:1">
                Nails by Mona
            </span>
        </a>

        {{-- Desktop nav (≥1024px) --}}
        <nav class="hidden lg:flex items-center gap-9" aria-label="Primary">
            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'nav-link-active' : 'nav-link' }}">Home</a>
            <a href="{{ route('shop') }}"
               class="{{ request()->routeIs('shop', 'product') ? 'nav-link-active' : 'nav-link' }}">Shop</a>
            <a href="{{ route('bridal') }}"
               class="{{ request()->routeIs('bridal') ? 'nav-link-active' : 'nav-link' }}">Bridal</a>
            <a href="{{ route('about') }}"
               class="{{ request()->routeIs('about') ? 'nav-link-active' : 'nav-link' }}">About</a>
            <a href="{{ route('blog') }}"
               class="{{ request()->routeIs('blog', 'blog.post') ? 'nav-link-active' : 'nav-link' }}">Journal</a>
            <a href="{{ route('contact') }}"
               class="{{ request()->routeIs('contact') ? 'nav-link-active' : 'nav-link' }}">Help</a>
        </nav>

        {{-- Right utilities --}}
        <div class="flex items-center gap-5">
            {{-- Bag toggle --}}
            <button id="bag-toggle" aria-label="Open bag"
                    class="relative p-0.5 text-stone hover:text-ink transition-colors duration-200">
                <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor"
                     stroke-width="14" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    {{-- Bag body: clean rectangle --}}
                    <rect x="32" y="104" width="192" height="120" rx="8"/>
                    {{-- Handle: arches above the bag opening (sweep=1 = clockwise in SVG Y-down = visually upward) --}}
                    <path d="M88,104V72a40,40,0,0,1,80,0V104"/>
                </svg>
                <span id="bag-count"
                      class="absolute -top-2 -right-2 hidden min-w-[17px] h-[17px] px-[3px] rounded-full bg-lavender text-white font-sans text-[9px] font-semibold items-center justify-center leading-none">
                    0
                </span>
            </button>

            {{-- Hamburger (mobile / tablet) --}}
            <button id="mobile-menu-toggle" aria-label="Open menu"
                    class="lg:hidden p-0.5 text-stone hover:text-ink transition-colors duration-200">
                <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor"
                     stroke-width="14" stroke-linecap="round" aria-hidden="true">
                    <line x1="40" y1="80"  x2="216" y2="80"/>
                    <line x1="40" y1="128" x2="216" y2="128"/>
                    <line x1="40" y1="176" x2="216" y2="176"/>
                </svg>
            </button>
        </div>

    </div>
</header>


{{-- ═══════════════════════════════════════════
     MOBILE MENU OVERLAY
═══════════════════════════════════════════ --}}
<div id="mobile-menu" aria-hidden="true" class="fixed inset-0 z-[60] hidden bg-bone overflow-y-auto">

    <div class="h-[72px] flex items-center justify-between px-6 border-b border-hairline">
        <a href="{{ route('home') }}" class="flex items-center shrink-0">
            <img src="{{ asset('logo-text.svg') }}" alt="Nails by Mona" class="h-10 w-auto" width="180" height="63"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
            <span class="font-serif text-xl text-lavender tracking-tight hidden">Nails by Mona</span>
        </a>
        <button id="mobile-menu-close" aria-label="Close menu"
                class="p-1 text-stone hover:text-ink transition-colors duration-200">
            <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor"
                 stroke-width="14" stroke-linecap="round" aria-hidden="true">
                <line x1="200" y1="56"  x2="56"  y2="200"/>
                <line x1="200" y1="200" x2="56"  y2="56"/>
            </svg>
        </button>
    </div>

    <nav class="px-8 pt-12 pb-8 flex flex-col gap-6" aria-label="Primary">
        <a href="{{ route('home') }}"
           class="font-serif text-display text-ink hover:text-lavender-ink transition-colors duration-200">Home</a>
        <a href="{{ route('shop') }}"
           class="font-serif text-display text-ink hover:text-lavender-ink transition-colors duration-200">Shop</a>
        <a href="{{ route('bridal') }}"
           class="font-serif text-display text-ink hover:text-lavender-ink transition-colors duration-200">Bridal</a>
        <a href="{{ route('about') }}"
           class="font-serif text-display text-ink hover:text-lavender-ink transition-colors duration-200">About</a>
        <a href="{{ route('blog') }}"
           class="font-serif text-display text-ink hover:text-lavender-ink transition-colors duration-200">Journal</a>
    </nav>

    <div class="px-8 pt-8 pb-4 border-t border-hairline flex flex-col gap-4">
        <a href="{{ route('contact') }}"
           class="font-sans text-eyebrow uppercase tracking-widest text-stone hover:text-ink transition-colors duration-200">Help</a>
        <a href="{{ route('size-guide') }}"
           class="font-sans text-eyebrow uppercase tracking-widest text-stone hover:text-ink transition-colors duration-200">Size Guide</a>
        <a href="{{ route('track') }}"
           class="font-sans text-eyebrow uppercase tracking-widest text-stone hover:text-ink transition-colors duration-200">Track Order</a>
        <a href="{{ route('blog') }}"
           class="font-sans text-eyebrow uppercase tracking-widest text-stone hover:text-ink transition-colors duration-200">Care &amp; Reuse</a>
    </div>

    <div class="px-8 mt-10 mb-12 flex items-center gap-6">
        <a href="https://instagram.com/{{ $settings->instagram_handle }}" aria-label="Instagram"
           class="text-stone hover:text-ink transition-colors duration-200" target="_blank" rel="noopener">
            <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor"
                 stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="32" y="32" width="192" height="192" rx="48"/>
                <circle cx="128" cy="128" r="40"/>
                <circle cx="180" cy="76" r="10" fill="currentColor" stroke="none"/>
            </svg>
        </a>
        <a href="https://wa.me/{{ $settings->whatsappForWaMe() }}?text=Hello%20Nails%20by%20Mona%2C%20I%27d%20like%20to%20enquire%20about%20a%20custom%20set."
           aria-label="WhatsApp" class="text-stone hover:text-ink transition-colors duration-200"
           target="_blank" rel="noopener">
            <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor"
                 stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M152.61,165.49a48,48,0,0,1-62.1-62.1A8,8,0,0,1,93.8,99.46l13.6,21.84a8,8,0,0,1-1.21,9.62L98.91,138.6a40,40,0,0,0,18.49,18.49l7.68-7.28a8,8,0,0,1,9.62-1.21L156.54,162.2A8,8,0,0,1,152.61,165.49Z"/>
                <path d="M128,32a96,96,0,0,0-83.32,143.51L32.27,224l49.71-12.49A96,96,0,1,0,128,32Z"/>
            </svg>
        </a>
    </div>

</div>


{{-- ═══════════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════════ --}}
<main>
    @yield('content')
</main>


{{-- ═══════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════ --}}
<footer class="bg-footer-bg py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">

        <div class="grid md:grid-cols-4 gap-10 md:gap-8 mb-14">

            {{-- Col 1 — Brand --}}
            <div class="md:col-span-1">
                <a href="{{ route('home') }}" class="inline-flex items-center mb-5">
                    <img src="{{ asset('logo-text.svg') }}" alt="Nails by Mona" class="h-9 w-auto"
                         width="160" height="56"
                         style="filter: brightness(0) invert(1); opacity:0.85"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                    <span class="font-serif text-xl tracking-tight hidden" style="color:rgba(244,239,232,0.85)">
                        Nails by Mona
                    </span>
                </a>
                <p class="font-sans text-caption mb-6 leading-relaxed" style="color:rgba(244,239,232,0.45)">
                    Handmade in Mirpur.<br>Shipped across Pakistan.
                </p>
                <div class="flex items-center gap-4">
                    <a href="https://instagram.com/{{ $settings->instagram_handle }}" aria-label="Instagram"
                       class="transition-colors duration-200 hover:opacity-100"
                       style="color:rgba(244,239,232,0.40)" target="_blank" rel="noopener">
                        <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor"
                             stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="32" y="32" width="192" height="192" rx="48"/>
                            <circle cx="128" cy="128" r="40"/>
                            <circle cx="180" cy="76" r="10" fill="currentColor" stroke="none"/>
                        </svg>
                    </a>
                    <a href="https://wa.me/{{ $settings->whatsappForWaMe() }}?text=Hello%20Nails%20by%20Mona%2C%20I%27d%20like%20to%20enquire%20about%20a%20custom%20set."
                       aria-label="WhatsApp" class="transition-colors duration-200"
                       style="color:rgba(244,239,232,0.40)" target="_blank" rel="noopener">
                        <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor"
                             stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M152.61,165.49a48,48,0,0,1-62.1-62.1A8,8,0,0,1,93.8,99.46l13.6,21.84a8,8,0,0,1-1.21,9.62L98.91,138.6a40,40,0,0,0,18.49,18.49l7.68-7.28a8,8,0,0,1,9.62-1.21L156.54,162.2A8,8,0,0,1,152.61,165.49Z"/>
                            <path d="M128,32a96,96,0,0,0-83.32,143.51L32.27,224l49.71-12.49A96,96,0,1,0,128,32Z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Col 2 — Shop --}}
            <div>
                <p class="font-sans text-eyebrow uppercase tracking-widest mb-6"
                   style="color:rgba(244,239,232,0.30)">Shop</p>
                <ul class="space-y-3.5">
                    <li><a href="{{ route('shop') }}"
                           class="font-sans text-caption transition-colors duration-200 hover:opacity-100"
                           style="color:rgba(244,239,232,0.55)">All designs</a></li>
                    <li><a href="{{ route('bridal') }}"
                           class="font-sans text-caption transition-colors duration-200 hover:opacity-100"
                           style="color:rgba(244,239,232,0.55)">Bridal Trio</a></li>
                    <li><a href="{{ route('size-guide') }}"
                           class="font-sans text-caption transition-colors duration-200 hover:opacity-100"
                           style="color:rgba(244,239,232,0.55)">Size guide</a></li>
                    <li><a href="{{ route('track') }}"
                           class="font-sans text-caption transition-colors duration-200 hover:opacity-100"
                           style="color:rgba(244,239,232,0.55)">Track order</a></li>
                </ul>
            </div>

            {{-- Col 3 — Studio --}}
            <div>
                <p class="font-sans text-eyebrow uppercase tracking-widest mb-6"
                   style="color:rgba(244,239,232,0.30)">Studio</p>
                <ul class="space-y-3.5">
                    <li><a href="{{ route('about') }}"
                           class="font-sans text-caption transition-colors duration-200 hover:opacity-100"
                           style="color:rgba(244,239,232,0.55)">About</a></li>
                    <li><a href="{{ route('blog') }}"
                           class="font-sans text-caption transition-colors duration-200 hover:opacity-100"
                           style="color:rgba(244,239,232,0.55)">Journal</a></li>
                    <li><a href="{{ route('contact') }}"
                           class="font-sans text-caption transition-colors duration-200 hover:opacity-100"
                           style="color:rgba(244,239,232,0.55)">Help</a></li>
                </ul>
            </div>

            {{-- Col 4 — Follow --}}
            <div>
                <p class="font-sans text-eyebrow uppercase tracking-widest mb-6"
                   style="color:rgba(244,239,232,0.30)">Follow</p>
                <div class="flex items-center gap-5">
                    <a href="https://instagram.com/{{ $settings->instagram_handle }}" aria-label="Instagram"
                       class="transition-colors duration-200" style="color:rgba(244,239,232,0.40)"
                       target="_blank" rel="noopener">
                        <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor"
                             stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="32" y="32" width="192" height="192" rx="48"/>
                            <circle cx="128" cy="128" r="40"/>
                            <circle cx="180" cy="76" r="10" fill="currentColor" stroke="none"/>
                        </svg>
                    </a>
                    <a href="https://wa.me/{{ $settings->whatsappForWaMe() }}" aria-label="WhatsApp"
                       class="transition-colors duration-200" style="color:rgba(244,239,232,0.40)"
                       target="_blank" rel="noopener">
                        <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor"
                             stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M152.61,165.49a48,48,0,0,1-62.1-62.1A8,8,0,0,1,93.8,99.46l13.6,21.84a8,8,0,0,1-1.21,9.62L98.91,138.6a40,40,0,0,0,18.49,18.49l7.68-7.28a8,8,0,0,1,9.62-1.21L156.54,162.2A8,8,0,0,1,152.61,165.49Z"/>
                            <path d="M128,32a96,96,0,0,0-83.32,143.51L32.27,224l49.71-12.49A96,96,0,1,0,128,32Z"/>
                        </svg>
                    </a>
                    <a href="https://tiktok.com/{{ '@' . $settings->tiktok_handle }}" aria-label="TikTok"
                       class="transition-colors duration-200" style="color:rgba(244,239,232,0.40)"
                       target="_blank" rel="noopener">
                        <svg class="w-5 h-5" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true">
                            <path d="M224,72a48.05,48.05,0,0,1-48-48,8,8,0,0,0-8-8H128a8,8,0,0,0-8,8V156a20,20,0,1,1-28.57-18.08A8,8,0,0,0,100,130V88a8,8,0,0,0-8.94-7.94C50.91,86.48,20,117.35,20,156a96,96,0,0,0,192,0V120a8,8,0,0,0-8-8,47.65,47.65,0,0,1-20-4.41V72Z"/>
                        </svg>
                    </a>
                </div>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div class="pt-8 border-t flex flex-col md:flex-row justify-between items-center gap-4"
             style="border-color:rgba(244,239,232,0.08)">
            <p class="font-sans text-caption" style="color:rgba(244,239,232,0.28)">
                &copy; {{ date('Y') }} Nails by Mona. All rights reserved.
            </p>
            <div class="flex gap-7">
                <a href="{{ route('privacy') }}"  class="font-sans text-caption transition-colors duration-200 hover:opacity-100"
                   style="color:rgba(244,239,232,0.28)">Privacy</a>
                <a href="{{ route('terms') }}"    class="font-sans text-caption transition-colors duration-200 hover:opacity-100"
                   style="color:rgba(244,239,232,0.28)">Terms</a>
                <a href="{{ route('shipping') }}" class="font-sans text-caption transition-colors duration-200 hover:opacity-100"
                   style="color:rgba(244,239,232,0.28)">Shipping</a>
            </div>
        </div>

    </div>
</footer>


{{-- ═══════════════════════════════════════════
     BAG DRAWER
═══════════════════════════════════════════ --}}
<aside id="bag-drawer" aria-label="Shopping bag" aria-hidden="true"
       class="fixed inset-y-0 right-0 z-[55] w-full max-w-[420px] bg-paper translate-x-full transition-transform duration-300 ease-out shadow-drawer flex flex-col">

    {{-- Drawer header --}}
    <div class="shrink-0 h-[72px] flex items-center justify-between px-6 border-b border-hairline">
        <h2 class="font-serif text-ink leading-none" style="font-size:1.5rem; font-weight:300">Your bag</h2>
        <button id="bag-close" aria-label="Close bag"
                class="p-1 text-stone hover:text-ink transition-colors duration-200">
            <svg class="w-5 h-5" viewBox="0 0 256 256" fill="none" stroke="currentColor"
                 stroke-width="14" stroke-linecap="round" aria-hidden="true">
                <line x1="200" y1="56"  x2="56"  y2="200"/>
                <line x1="200" y1="200" x2="56"  y2="56"/>
            </svg>
        </button>
    </div>

    {{-- Scrollable content --}}
    <div class="flex-1 overflow-y-auto">
        {{-- Empty state --}}
        <div id="bag-empty" class="px-6 py-16 text-center">
            <div class="w-16 h-16 rounded-full bg-shell flex items-center justify-center mx-auto mb-5">
                <svg class="w-7 h-7 text-ash" viewBox="0 0 256 256" fill="none" stroke="currentColor"
                     stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M40,72H216a8,8,0,0,1,8,8.83l-12.43,112a8,8,0,0,1-8,7.17H52.4a8,8,0,0,1-8-7.17L32,80.83A8,8,0,0,1,40,72Z"/>
                    <path d="M88,104V72a40,40,0,0,1,80,0v32"/>
                </svg>
            </div>
            <p class="font-sans text-body text-stone mb-7">Your bag is empty.</p>
            <a href="{{ route('shop') }}"
               class="inline-flex items-center justify-center gap-2 bg-lavender hover:bg-lavender-dark text-white font-sans text-caption font-medium tracking-wide rounded-full px-7 py-3 transition-colors duration-200">
                Browse designs
            </a>
        </div>

        {{-- Items list (JS-populated) --}}
        <ul id="bag-items" class="hidden divide-y divide-hairline/70"></ul>
    </div>

    {{-- Pinned footer --}}
    <div id="bag-footer" class="hidden shrink-0 p-6 bg-paper border-t border-hairline">
        <div class="flex justify-between items-center font-sans mb-1">
            <span class="text-caption text-stone">Subtotal</span>
            <span id="bag-subtotal" class="font-medium text-ink tabular-nums" style="font-size:1rem">Rs. 0</span>
        </div>
        <p class="font-sans text-caption text-ash mb-5">Shipping calculated at checkout.</p>

        {{-- Hidden form — submits localStorage bag JSON to session via initFromBag --}}
        <form id="bag-checkout-form" action="{{ route('order.bag.init') }}" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="bag" id="bag-checkout-payload">
        </form>

        <button type="button" id="bag-checkout-btn"
                class="flex items-center justify-center gap-2 w-full bg-lavender hover:bg-lavender-dark text-white font-sans text-caption font-medium tracking-wide rounded-full py-4 transition-colors duration-200">
            Checkout
            <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor"
                 stroke-width="16" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="40" y1="128" x2="216" y2="128"/>
                <polyline points="144 56 216 128 144 200"/>
            </svg>
        </button>
    </div>

</aside>

{{-- Bag backdrop --}}
<div id="bag-backdrop" class="fixed inset-0 z-[54] hidden bg-ink/25 backdrop-blur-[2px]"></div>


{{-- jQuery must load before ANY inline $(function(){}) block --}}
<script src="{{ asset('js/jquery.min.js') }}"></script>

{{-- ═══════════════════════════════════════════
     GLOBAL SCRIPTS — mobile menu + bag drawer
═══════════════════════════════════════════ --}}
<script>
$(function () {

    // ── Focus trap helpers (WCAG 2.2 — keyboard users can't tab outside an open modal) ──
    const FOCUSABLE_SELECTOR = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

    function getFocusables(container) {
        return container.find(FOCUSABLE_SELECTOR).filter(':visible').filter(function () {
            return this.offsetParent !== null; // not display:none ancestor
        });
    }

    function activateFocusTrap($container) {
        const $focusables = getFocusables($container);
        if ($focusables.length === 0) return;

        const first = $focusables.first()[0];
        const last  = $focusables.last()[0];

        $container.on('keydown.focusTrap', function (e) {
            if (e.key !== 'Tab') return;
            const focusables = getFocusables($container);
            if (focusables.length === 0) return;
            const f = focusables.first()[0];
            const l = focusables.last()[0];
            if (e.shiftKey && document.activeElement === f) {
                e.preventDefault(); l.focus();
            } else if (!e.shiftKey && document.activeElement === l) {
                e.preventDefault(); f.focus();
            }
        });
    }

    function deactivateFocusTrap($container) {
        $container.off('keydown.focusTrap');
    }

    // ── Mobile menu ─────────────────────────────────────
    const $menu = $('#mobile-menu');
    const $body = $('body');
    let menuRestoreEl = null;

    function openMenu() {
        menuRestoreEl = document.activeElement;
        $menu.removeClass('hidden').attr('aria-hidden', 'false');
        $body.css('overflow', 'hidden');
        // Move focus into the menu (close button is a reliable first stop).
        setTimeout(() => $('#mobile-menu-close').trigger('focus'), 0);
        activateFocusTrap($menu);
    }
    function closeMenu() {
        deactivateFocusTrap($menu);
        $menu.addClass('hidden').attr('aria-hidden', 'true');
        $body.css('overflow', '');
        // Restore focus to whatever the user clicked to open the menu.
        if (menuRestoreEl && typeof menuRestoreEl.focus === 'function') {
            menuRestoreEl.focus();
        }
        menuRestoreEl = null;
    }

    $('#mobile-menu-toggle').on('click', openMenu);
    $('#mobile-menu-close').on('click', closeMenu);

    // ── Bag drawer ──────────────────────────────────────
    const $drawer   = $('#bag-drawer');
    const $backdrop = $('#bag-backdrop');
    let bagRestoreEl = null;

    function openBag() {
        bagRestoreEl = document.activeElement;
        $drawer.removeClass('translate-x-full').attr('aria-hidden', 'false');
        $backdrop.removeClass('hidden');
        $body.css('overflow', 'hidden');
        renderBag();
        // Focus the close button first (or the checkout button if the bag has items).
        setTimeout(() => {
            const items = getBag();
            const $target = items.length ? $('#bag-checkout-btn') : $('#bag-close');
            if ($target.length) $target.trigger('focus');
        }, 0);
        activateFocusTrap($drawer);
    }
    function closeBag() {
        deactivateFocusTrap($drawer);
        $drawer.addClass('translate-x-full').attr('aria-hidden', 'true');
        $backdrop.addClass('hidden');
        $body.css('overflow', '');
        if (bagRestoreEl && typeof bagRestoreEl.focus === 'function') {
            bagRestoreEl.focus();
        }
        bagRestoreEl = null;
    }

    $('#bag-toggle').on('click', openBag);
    $('#bag-close').on('click', closeBag);
    $('#bag-backdrop').on('click', closeBag);

    // Checkout — write localStorage bag into the hidden form and submit
    $('#bag-checkout-btn').on('click', function () {
        const items = getBag();
        if (! items.length) return; // nothing to checkout
        $('#bag-checkout-payload').val(JSON.stringify(items));
        $('#bag-checkout-form')[0].submit();
    });

    // Escape closes both (only acts on the currently open overlay)
    $(document).on('keydown', function (e) {
        if (e.key !== 'Escape') return;
        if (!$menu.hasClass('hidden'))   closeMenu();
        if (!$drawer.hasClass('translate-x-full')) closeBag();
    });

    // ── Bag: localStorage ───────────────────────────────
    function getBag() {
        try { return JSON.parse(localStorage.getItem('nbm.bag') || '[]'); } catch { return []; }
    }
    function saveBag(items) {
        localStorage.setItem('nbm.bag', JSON.stringify(items));
        updateBadge(items.reduce((n, i) => n + i.qty, 0));
    }

    function updateBadge(count) {
        const $badge = $('#bag-count');
        if (count > 0) {
            $badge.text(count).removeClass('hidden').addClass('flex');
        } else {
            $badge.addClass('hidden').removeClass('flex');
        }
    }

    function formatPrice(pkr) {
        return 'Rs. ' + pkr.toLocaleString('en-PK');
    }

    function renderBag() {
        const items = getBag();
        if (!items.length) {
            $('#bag-empty').removeClass('hidden');
            $('#bag-items, #bag-footer').addClass('hidden');
            return;
        }
        $('#bag-empty').addClass('hidden');
        $('#bag-items, #bag-footer').removeClass('hidden');

        const subtotal = items.reduce((s, i) => s + i.price_pkr * i.qty, 0);
        $('#bag-subtotal').text(formatPrice(subtotal));

        const html = items.map((item, idx) => `
          <li class="flex gap-4 px-6 py-5 items-start">
            <div class="w-[60px] h-[60px] rounded-xl overflow-hidden bg-shell shrink-0">
              ${item.image ? `<img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover" width="60" height="60" loading="lazy">` : ''}
            </div>
            <div class="flex-1 min-w-0">
              <p class="font-serif text-ink leading-snug mb-0.5 truncate" style="font-size:1rem; font-weight:300">${item.name}</p>
              <p class="font-sans text-caption text-lavender tabular-nums">${formatPrice(item.price_pkr)}</p>
              <div class="flex items-center gap-3 mt-2.5">
                <button class="qty-dec w-6 h-6 rounded-full border border-hairline flex items-center justify-center text-stone hover:text-ink hover:border-ink transition-colors text-sm" data-idx="${idx}">−</button>
                <span class="font-sans text-caption text-graphite tabular-nums w-4 text-center">${item.qty}</span>
                <button class="qty-inc w-6 h-6 rounded-full border border-hairline flex items-center justify-center text-stone hover:text-ink hover:border-ink transition-colors text-sm" data-idx="${idx}">+</button>
              </div>
            </div>
            <button class="item-remove shrink-0 mt-0.5 p-0.5 text-stone hover:text-danger transition-colors" data-idx="${idx}" aria-label="Remove ${item.name}">
              <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="14" stroke-linecap="round" aria-hidden="true">
                <line x1="200" y1="56" x2="56" y2="200"/><line x1="200" y1="200" x2="56" y2="56"/>
              </svg>
            </button>
          </li>
        `).join('');

        $('#bag-items').html(html);
    }

    // Bag item controls (delegated)
    $('#bag-items')
        .on('click', '.qty-dec', function () {
            const items = getBag(), idx = +$(this).data('idx');
            if (items[idx].qty > 1) { items[idx].qty--; } else { items.splice(idx, 1); }
            saveBag(items); renderBag();
        })
        .on('click', '.qty-inc', function () {
            const items = getBag();
            items[+$(this).data('idx')].qty++;
            saveBag(items); renderBag();
        })
        .on('click', '.item-remove', function () {
            const items = getBag();
            items.splice(+$(this).data('idx'), 1);
            saveBag(items); renderBag();
        });

    // Init badge on load
    updateBadge(getBag().reduce((n, i) => n + i.qty, 0));

    // Expose bag API for product pages
    window.NbmBag = {
        get:  function ()     { return getBag(); },
        save: function (items){ saveBag(items); },
        open: function ()     { openBag(); },
        add:  function (item) {
            const items = getBag();
            const existing = items.find(i => i.slug === item.slug);
            if (existing) { existing.qty++; } else { items.push({ ...item, qty: 1 }); }
            saveBag(items);
            openBag();
        }
    };

});
</script>

{{-- Page-specific scripts --}}
@stack('scripts')

</body>
</html>
