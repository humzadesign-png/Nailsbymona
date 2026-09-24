{{--
    Page hero — shared by Home, Bridal and About (2026-09-24 redesign,
    docs/ux/home-redesign-2026-09.md).

    Rules it enforces:
      • The photo is never covered by text or a frosted card.
      • Compact on phones (190 px photo) so the next section starts inside
        the first screen; split 50/50 from md up, photo on the right.
      • The text column (default slot) carries eyebrow → h1 → lead → actions.

    Props:
      image    base filename in public/images, e.g. 'hero-home-red-matte'
               (expects {image}-{width}.webp and .jpg for every width)
      widths   available widths, smallest first
      alt      alt text for the photo
      badge    optional small label on the photo
      position CSS object-position for the crop
      bg       background class for the section
--}}
@props([
    'image',
    'widths'   => [768, 1280],
    'alt'      => '',
    'badge'    => null,
    'position' => 'center',
    'bg'       => 'bg-bone',
])

@php
    $srcset = fn (string $ext) => collect($widths)
        ->map(fn ($w) => asset("images/{$image}-{$w}.{$ext}") . " {$w}w")
        ->implode(', ');
    $fallback = asset("images/{$image}-" . collect($widths)->last() . '.jpg');
@endphp

<section {{ $attributes->merge(['class' => "{$bg} pb-9 md:pb-20"]) }}>
  <div id="hero" class="max-w-7xl mx-auto px-6 lg:px-10 pt-5 md:pt-12 md:grid md:grid-cols-2 md:gap-14 md:items-center">

    <div class="relative rounded-2xl overflow-hidden h-[190px] sm:h-[300px] md:h-[540px] md:order-2"
         style="background:linear-gradient(135deg,#3D3530,#1A1410)">
      <picture>
        <source type="image/webp" srcset="{{ $srcset('webp') }}" sizes="(min-width: 768px) 50vw, 100vw">
        <img src="{{ $fallback }}" srcset="{{ $srcset('jpg') }}" sizes="(min-width: 768px) 50vw, 100vw"
             alt="{{ $alt }}"
             class="absolute inset-0 w-full h-full object-cover"
             style="object-position: {{ $position }}"
             loading="eager" fetchpriority="high"
             onerror="this.parentElement.remove()">
      </picture>
      @if ($badge)
        <span class="absolute bottom-3 left-3 font-sans text-eyebrow uppercase tracking-widest px-3 py-1.5 bg-paper/90 backdrop-blur-sm text-stone rounded-full">{{ $badge }}</span>
      @endif
    </div>

    <div class="pt-5 md:pt-0 md:order-1">
      {{ $slot }}
    </div>

  </div>
</section>
