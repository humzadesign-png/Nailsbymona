<!DOCTYPE html>
<html lang="en-PK" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>@yield('title', 'Order — Nails by Mona')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @stack('head')
</head>
<body class="bg-bone font-sans antialiased min-h-screen flex flex-col">

  {{-- ── Order header (logo only, no nav) ──────────────────────────────── --}}
  <header class="bg-bone/90 backdrop-blur-md border-b border-hairline sticky top-0 z-40">
    <div class="max-w-5xl mx-auto px-6 h-16 flex items-center justify-between">

      {{-- Logo --}}
      <a href="{{ route('home') }}" aria-label="Nails by Mona — home">
        <img src="{{ asset('logo-text.svg') }}" alt="Nails by Mona"
             width="140" height="32" class="h-8 w-auto"
             onerror="this.style.display='none';this.nextElementSibling.style.display='inline'">
        <span class="font-serif text-ink text-lg hidden" style="font-variation-settings:'opsz' 144,'SOFT' 30">Nails by Mona</span>
      </a>

      {{-- Help link --}}
      <a href="https://wa.me/{{ $settings->whatsappForWaMe() }}?text={{ urlencode('Hello Nails by Mona, I have a question about placing an order.') }}"
         class="font-sans text-caption text-stone hover:text-lavender transition-colors duration-200 flex items-center gap-1.5"
         target="_blank" rel="noopener">
        <svg class="w-4 h-4" viewBox="0 0 256 256" fill="currentColor"><path d="M187.58,144.84l-32-16a8,8,0,0,0-8,.5l-14.69,9.8a40.55,40.55,0,0,1-16-16l9.8-14.69a8,8,0,0,0,.5-8l-16-32A8,8,0,0,0,104,64a40,40,0,0,0-40,40,88.1,88.1,0,0,0,88,88,40,40,0,0,0,40-40A8,8,0,0,0,187.58,144.84ZM152,176a72.08,72.08,0,0,1-72-72,24,24,0,0,1,19.29-23.54l11.48,22.94L101,117.64a8,8,0,0,0-.73,7.65,56.42,56.42,0,0,0,30.42,30.42,8,8,0,0,0,7.65-.73l14.3-9.55,22.94,11.48A24,24,0,0,1,152,176ZM128,24A104,104,0,0,0,36.18,176.88L24.83,210.93a16,16,0,0,0,20.24,20.24l34.05-11.35A104,104,0,1,0,128,24Zm0,192a88.11,88.11,0,0,1-44.06-11.81,8,8,0,0,0-6.54-.67L40,216l12.47-37.4a8,8,0,0,0-.66-6.54A88,88,0,1,1,128,216Z"/></svg>
        Questions? Get help
      </a>

    </div>
  </header>

  {{-- ── Progress bar (injected per-step via @section) ─────────────────── --}}
  @hasSection('progress')
    <div class="bg-paper border-b border-hairline">
      <div class="max-w-5xl mx-auto px-6 py-4">
        @yield('progress')
      </div>
    </div>
  @endif

  {{-- ── Main content ────────────────────────────────────────────────────── --}}
  <main class="flex-1">
    @yield('content')
  </main>

  {{-- ── Minimal footer ──────────────────────────────────────────────────── --}}
  <footer class="border-t border-hairline bg-paper py-5">
    <div class="max-w-5xl mx-auto px-6 flex flex-wrap items-center justify-between gap-3">
      <p class="font-sans text-caption text-stone">&copy; {{ date('Y') }} Nails by Mona. All rights reserved.</p>
      <div class="flex items-center gap-4">
        <a href="#" class="font-sans text-caption text-stone hover:text-ink transition-colors duration-200">Privacy</a>
        <a href="#" class="font-sans text-caption text-stone hover:text-ink transition-colors duration-200">Returns &amp; Refits</a>
        <a href="#" class="font-sans text-caption text-stone hover:text-ink transition-colors duration-200">Shipping</a>
      </div>
    </div>
  </footer>

  {{-- jQuery loaded as a regular (non-module) script so inline Blade scripts can use $ immediately --}}
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  @stack('scripts')
</body>
</html>
