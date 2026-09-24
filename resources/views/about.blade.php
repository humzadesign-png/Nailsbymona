@extends('layouts.app')

@push('head')
<style>
.story-body { font-family: 'Fraunces', Georgia, serif; font-variation-settings: 'opsz' 48, 'SOFT' 20; font-weight: 300; }
.mona-signature { font-family: 'Fraunces', Georgia, serif; font-variation-settings: 'opsz' 12, 'SOFT' 100; font-weight: 300; }
</style>
@endpush

@php
    $aboutSchema = json_encode([
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type'       => 'Organization',
                'name'        => 'Nails by Mona',
                'url'         => route('home'),
                'description' => 'Custom-fit, handmade press-on gel nails made in Mirpur, Azad Kashmir. One artisan, built to your measurements.',
                'address'     => [
                    '@type'           => 'PostalAddress',
                    'addressLocality' => 'Mirpur',
                    'addressRegion'   => 'Azad Kashmir',
                    'addressCountry'  => 'PK',
                ],
            ],
            [
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',  'item' => route('home')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'About', 'item' => route('about')],
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

@section('seo')
<x-seo
    title="About Nails by Mona — Custom-Fit Press-On Gel Nails, Mirpur AJK"
    description="BA in Fine Arts. Bridal mehndi artist. Practicing Muslim who started press-on nails for herself first. Made by hand in Mirpur, shipped across Pakistan."
    :schema="$aboutSchema"
/>
@endsection

@section('content')

{{-- HERO — shared page-hero component (2026-09-24). Hands and work only,
     never a face (CLAUDE.md §24). Photo supplied by Humza 2026-09-24. --}}
<x-page-hero
    image="about-hero"
    :widths="[768, 960]"
    position="center 45%"
    badge="Made by hand in Mirpur"
    alt="Hands holding a coffee cup, wearing almond press-on nails with black polka-dot tips, gold bands and hand-painted pink flowers">

    <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Our story</p>
    <h1 class="font-serif text-display-lg lg:text-display-xl text-ink">Hi, I'm Mona. I make every set myself.</h1>
    <p class="font-sans text-body md:text-body-lg text-graphite mt-4 max-w-md">
        No factory, no drop-shipping &mdash; one small studio in Mirpur, Azad Kashmir, and a lot of care in every set.
    </p>

    <ul class="mt-5 flex flex-wrap gap-x-6 gap-y-2.5">
        <li class="flex items-center gap-2 font-sans text-caption text-graphite">
            <svg class="w-5 h-5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M216,56H40a8,8,0,0,0-8,8V192a8,8,0,0,0,8,8H216a8,8,0,0,0,8-8V64A8,8,0,0,0,216,56Z"/><polyline points="32 160 96 104 152 160"/><circle cx="168" cy="100" r="12"/></svg>
            BA in Fine Arts</li>
        <li class="flex items-center gap-2 font-sans text-caption text-graphite">
            <svg class="w-5 h-5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M128,24S32,96,32,152a96,96,0,0,0,192,0C224,96,128,24,128,24Z"/></svg>
            Started for wudu</li>
        <li class="flex items-center gap-2 font-sans text-caption text-graphite">
            <svg class="w-5 h-5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="216" y1="40" x2="40" y2="216"/><polyline points="40 152 40 216 104 216"/><polyline points="152 40 216 40 216 104"/></svg>
            Every set checked by hand</li>
    </ul>

    <div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-3">
        <a href="#story" class="inline-flex items-center gap-2.5 font-sans font-medium text-ink rounded-full px-8 py-3.5 md:px-9 md:py-4 border border-ink/20 hover:border-ink/40 transition-colors duration-200">
            Read my story
            <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="128" y1="40" x2="128" y2="216"/><polyline points="56 144 128 216 200 144"/></svg>
        </a>
        <a href="{{ route('shop') }}" class="hidden sm:inline font-sans text-caption font-medium text-lavender-ink hover:underline underline-offset-4">Browse the collection &rarr;</a>
    </div>
</x-page-hero>


<!-- MY STORY -->
<section id="story" class="bg-paper border-t border-hairline/70 py-16 md:py-24 scroll-mt-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <p class="font-sans text-eyebrow text-lavender uppercase mb-3">My story</p>
    <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">How this started.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-12"></div>

    <!-- Paragraphs in Fraunces serif for editorial feel -->
    <div class="max-w-[740px] space-y-7">

      <p class="story-body text-graphite leading-[1.85]" style="font-size:1.125rem">
        From the time I was very young, I drew things. On notebooks, on walls, on my own hands. Growing up in Mirpur, I was always the girl in class who was decorating something &mdash; the one teachers asked to make the bulletin board, the one who couldn't sit through a lesson without doodling in the margins. It was never a plan. It was just how I was made.
      </p>

      <p class="story-body text-graphite leading-[1.85]" style="font-size:1.125rem">
        When it came time to choose a degree, there was never really a question. I studied Fine Arts &mdash; because nothing else made sense for someone who had spent her whole life making things. In university, I discovered I wasn't just drawn to one medium. I could do bridal mehndi, sit with a bride for three hours and design something intricate and personal just for her. I painted. I worked with resin &mdash; the kind of detailed, precise work where you pour layers and wait and correct and pour again. I made personalised name plates and gift pieces that people still message me about years later. My hands knew how to learn.
      </p>

      <p class="story-body text-graphite leading-[1.85]" style="font-size:1.125rem">
        But here's the thing nobody talks about: I am a practicing Muslim. And for years, I watched beautiful nail designs and wished &mdash; genuinely wished &mdash; I could wear them. Traditional nail polish isn't compatible with wudu. Water has to reach the nail bed for ablution to be valid, and a coat of polish blocks that. Salon acrylics have the same problem &mdash; you can't take them off five times a day. I kept hearing other women say the same thing. We wanted our nails to be beautiful. We didn't want to compromise our prayers to do it. Press-on nails changed that for me completely. You remove them before wudu. You put them back on after. They stay on for days at a time &mdash; and on your terms. I started making them because I needed them for myself. And then I realised how many other women needed them too.
      </p>

      <p class="story-body text-graphite leading-[1.85]" style="font-size:1.125rem">
        The bridal angle came from watching the women around me. Pakistani wedding season is something else &mdash; three events minimum, each with its own look, its own lehenga, its own vibe. I saw brides going to salons the morning of their mehndi to get acrylics done, then doing it again for baraat, then again for valima &mdash; each session two to three hours, each one leaving their nails thinner and more fragile than before. And all of it rushed, because appointments run late and weddings start early and nobody has time for mistakes. I knew press-ons could be the answer. One order, weeks in advance, three coordinated looks waiting in a box. No last-minute panic. No damaged nail beds the week after your wedding.
      </p>

      <p class="story-body text-graphite leading-[1.85]" style="font-size:1.125rem">
        I won't pretend the beginning was easy. There was a period &mdash; honestly, longer than I'd like to admit &mdash; where I doubted whether this was real, whether people would trust something made by one person in Mirpur rather than a bigger brand. My family believed in me before I fully believed in myself. My husband encouraged me to keep going when I wanted to step back. And slowly, order by order, it became real. Customers from Lahore, Karachi, Islamabad &mdash; women who found me on Instagram, placed one order, and came back. That trust is the thing I'm most careful about protecting. Every set that leaves my hands, I've checked. Every measurement I've taken seriously. If something isn't right, I'd rather start over than send it.
      </p>

      <p class="story-body text-lavender-ink leading-[1.85] italic" style="font-size:1.2rem">
        This is still a one-person business. That's not a limitation &mdash; it's the point.
      </p>

    </div>
  </div>
</section>


<!-- STUDIO -->
<section class="bg-shell py-14 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <p class="font-sans text-eyebrow text-lavender uppercase mb-3">The studio</p>
    <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">Where your nails are made.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-12"></div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">

      <div class="space-y-3">
        <div class="img-wrap-fallback rounded-2xl aspect-square overflow-hidden">
          <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBDM56k-cNLcm1pLfZWCiicUPXBqU7H3c1oUllyCdN-Y_2eBZW3kgijSB2C7n-_o7LCQxNz0JbQ0vAlhxE5yJlkgxrgWZ0Lp-oT2o6TNmjJz1siyflScqxA1nfRcVT6EFC8Wd7Nj0AqjpHfvz2EcYbv4pBdktBP3CTCY37bV33r6hJdlC0N5N0rNGjN12fD4CMBFn89YqKPnCGnJ9iVvlEGXgYG7RLMNlpuLt7Yrwp3BLLmXm2ixFGGKk"
               alt="Mona's worktable — gel lamp, forms, brushes" class="w-full h-full object-cover" loading="lazy" onerror="this.remove()" width="400" height="400">
        </div>
        <p class="font-sans text-caption text-stone italic">&ldquo;My worktable. Messy during a busy week, organised at the start of every new order.&rdquo;</p>
      </div>

      <div class="space-y-3">
        <div class="img-wrap-fallback rounded-2xl aspect-square overflow-hidden">
          <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCJHuKIDTuXFsAXPlXB1Bl1LKYZ9Q_5UWEHiJ7l5C5SuSXoQQxC-fC7s8KSJTgXzUvJXgMR0BbcGsLqRrflFJw4fXKlBEtdnTN9bv0uVzgJU4-N1U8EqoRAezXCRNBe3NM3zGjTON0JDnLwxl8K_wvbnFJNP-0w84MUk2PMCvTInivLMZvdq0L6hpuRXjTWpPLvCYNbhrgLvpT8WFoP-rMlpBp6aVUxfB5BWCY6fNhMoZO2zIWzCmEUns"
               alt="Nails mid-construction on forms — multiple stages visible" class="w-full h-full object-cover" loading="lazy" onerror="this.remove()" width="400" height="400">
        </div>
        <p class="font-sans text-caption text-stone italic">&ldquo;Each nail is built individually. Base, colour layers, art if there is any, topcoat. I cure each layer before the next one goes on.&rdquo;</p>
      </div>

      <div class="space-y-3">
        <div class="img-wrap-fallback rounded-2xl aspect-square overflow-hidden">
          <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJ32yAAfsHl2sbXiBXZLY3IKtRwIkwWXvKcHiU-VwXu3GcBZS-OCOQtkAzs0rwKgW4Dxp9-Z2au4aru1Nu0BtdA5S-uZ-NUYxfvCRB_EPlJ78I5QkkZiWeSOyecrlfT0sA2Kwo2P3RsYdsqE_IW2xtSSrJ7oKPp3RpON2gXOphhVK9Cf3kYPdg_HLtT-hDSpv_Dk7TIiOOMCwZD8yjf2AGWErYtoydiByeD07Oucov2MbMli1OY4mgn38QBbTbMGRf_-KaN1SZYy0"
               alt="Magnetic box being assembled with satin lining and handwritten name card" class="w-full h-full object-cover" loading="lazy" onerror="this.remove()" width="400" height="400">
        </div>
        <p class="font-sans text-caption text-stone italic">&ldquo;The last step before dispatch. I write the name card myself. Every time.&rdquo;</p>
      </div>

    </div>
  </div>
</section>


<!-- PROCESS TIMELINE -->
<section class="bg-paper py-14 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <p class="font-sans text-eyebrow text-lavender uppercase mb-3">The process</p>
    <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">From your photo to your door.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-12"></div>

    @php
      // Brand icon set (public/icons, 24×24, 1.5 stroke). Brush + magnifier
      // drawn in the same style. Markers match the home page step circles.
      $steps = [
        ['You share your sizing photos',
         'Two close-up photos — your fingers laid flat in a row with a coin above the middle nail, and your thumb extended with a coin above the thumbnail. About 90 seconds. No salon visits. I read every nail width directly off the coin in each photo.',
         '<path d="M3 8H8L10 5H14L16 8H21C21.6 8 22 8.4 22 9V18C22 18.6 21.6 19 21 19H3C2.4 19 2 18.6 2 18V9C2 8.4 2.4 8 3 8Z"/><circle cx="12" cy="13.5" r="3.5"/>'],
        ['We confirm your design',
         'Your order confirmation arrives by email. If you’ve asked for a change to a design, or something in your sizing photos needs a second look, I get in touch before I start. For bridal orders, this is where we finalise all three looks together.',
         '<path d="M12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22C13.1 22 14 21.1 14 20C14 19.4 13.8 18.9 13.4 18.5C13.1 18.1 13 17.7 13 17.2C13 16.1 13.9 15.2 15 15.2H17C19.8 15.2 22 13 22 10.2C22 5.6 17.5 2 12 2Z"/><circle cx="7" cy="11" r="1.2" fill="currentColor" stroke="none"/><circle cx="9" cy="7.5" r="1.2" fill="currentColor" stroke="none"/><circle cx="12" cy="6" r="1.2" fill="currentColor" stroke="none"/><circle cx="15.5" cy="7.5" r="1.2" fill="currentColor" stroke="none"/>'],
        ['I build your set',
         'Each nail individually on a form. Gel base, colour layers, any hand-painting or charm work, topcoat — cured between each stage. Custom orders: ' . $settings->lead_time_standard_days . ' days. Bridal Trio: ' . $settings->lead_time_bridal_days . ' days.',
         '<path d="m9.06 11.9 8.07-8.06a2.85 2.85 0 1 1 4.03 4.03l-8.06 8.08"/><path d="M7.07 14.94c-1.66 0-3 1.35-3 3.02 0 1.33-2.5 1.52-2 2.02 1.08 1.1 2.49 2.02 4 2.02 2.2 0 4-1.8 4-4.04a3.01 3.01 0 0 0-3-3.02z"/>'],
        ['Quality check',
         'Before I pack anything, I wear-test a spare nail. I check the finish, the cure, the colour payoff. If something isn’t right at this stage — I start over.',
         '<circle cx="10.5" cy="10.5" r="7"/><line x1="15.5" y1="15.5" x2="21" y2="21"/><polyline points="7.5 10.7 9.7 12.9 13.6 8.8"/>'],
        ['Packed and shipped',
         'Your nails go into the magnetic box, wrapped in tissue, with glue, a prep pad, and an application guide. You get your tracking number the same day the parcel goes to the courier.',
         '<rect x="3" y="8" width="18" height="3" rx="1"/><rect x="3.5" y="11" width="17" height="10.5" rx="0.5"/><line x1="12" y1="8" x2="12" y2="21.5"/><path d="M12 8.5C10.5 6.2 7.5 5.5 6.2 6.8C5 8 7 9.2 12 8.5"/><path d="M12 8.5C13.5 6.2 16.5 5.5 17.8 6.8C19 8 17 9.2 12 8.5"/>'],
        ['You wear them',
         'Apply in under ten minutes. Each wear lasts 5–10 days, and with careful removal you’ll get 3–5 wears from a single set. And if you send me a photo of them on — honestly, it makes my day.',
         '<path d="M12 21L4.2 13.2C2 11 2 7.5 4.2 5.3C6.4 3.1 10 3.1 12 5.3C14 3.1 17.6 3.1 19.8 5.3C22 7.5 22 11 19.8 13.2Z"/>'],
      ];
    @endphp

    <ol class="max-w-2xl">
      @foreach($steps as [$title, $text, $icon])
      <li class="flex gap-5 md:gap-6 {{ $loop->last ? '' : 'pb-10' }}">
        <div class="flex flex-col items-center">
          <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 {{ $loop->last ? 'bg-lavender text-white' : 'bg-paper border border-hairline text-lavender' }}">
            <svg class="w-[22px] h-[22px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icon !!}</svg>
          </div>
          @unless($loop->last)
          <div class="flex-1 w-px bg-hairline mt-3"></div>
          @endunless
        </div>
        <div class="pt-2.5">
          <h3 class="font-sans font-semibold text-ink mb-1.5" style="font-size:0.9375rem">{{ $title }}</h3>
          <p class="font-sans text-caption md:text-body text-stone leading-relaxed">{{ $text }}</p>
        </div>
      </li>
      @endforeach
    </ol>

  </div>
</section>


<!-- WHY NO SHORTCUTS -->
<section class="bg-bone py-14 md:py-20 border-t border-hairline/50">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="max-w-[680px]">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-3">The standard</p>
      <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">Why I don't take shortcuts.</h2>
      <div class="h-0.5 w-10 bg-lavender mb-10"></div>
      <div class="space-y-5">
        <p class="font-sans text-body-lg text-graphite leading-relaxed">I could buy pre-made nails and put my name on them. A lot of brands do. I'm not going to.</p>
        <p class="font-sans text-body-lg text-graphite leading-relaxed">The reason I started this business was because the options that existed didn't do what they said they would. The sizing was off. The finish wasn't what the photos showed. The materials didn't last. I have spent two years building the skills to do this properly &mdash; not by reading a course, but by making set after set after set and understanding what works and what doesn't.</p>
        <p class="font-sans text-body-lg text-graphite leading-relaxed">Every nail I make is built from a gel base on a nail form. Every colour is applied in layers and cured properly. Every pair of sizing photos I receive &mdash; fingers and thumb, each with a coin for scale &mdash; I actually measure from. If your coin-to-nail ratio tells me your pinky is 10mm wide, I make your pinky nail 10mm wide &mdash; not &ldquo;close enough.&rdquo;</p>
        <p class="font-sans text-body-lg text-graphite leading-relaxed">You'll never receive a set from me that I haven't personally checked. That's not a promise I made for marketing &mdash; it's a limit of capacity I've deliberately kept.</p>
      </div>
    </div>
  </div>
</section>


<!-- GUARANTEES -->
<section class="bg-paper py-14 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">What I stand behind.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>

    <div class="grid md:grid-cols-3 gap-6">

      <div class="bg-bone rounded-2xl p-8 border border-hairline/60">
        <div class="w-11 h-11 rounded-xl bg-lavender-wash flex items-center justify-center mb-5">
          <svg class="w-5 h-5 text-lavender-ink" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M128,24S32,96,32,152a96,96,0,0,0,192,0C224,96,128,24,128,24Z"/></svg>
        </div>
        <h3 class="font-sans font-medium text-ink mb-3" style="font-size:1rem">Free first refit</h3>
        <p class="font-sans text-body text-stone">If your first order doesn't fit perfectly, I resize it at no charge. I'd rather take the extra time and materials to get it right than have you wearing nails that don't feel like yours.</p>
      </div>

      <div class="bg-bone rounded-2xl p-8 border border-hairline/60">
        <div class="w-11 h-11 rounded-xl bg-lavender-wash flex items-center justify-center mb-5">
          <svg class="w-5 h-5 text-lavender-ink" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><line x1="216" y1="40" x2="40" y2="216"/><polyline points="40 152 40 216 104 216"/><polyline points="152 40 216 40 216 104"/></svg>
        </div>
        <h3 class="font-sans font-medium text-ink mb-3" style="font-size:1rem">Custom-fit, always</h3>
        <p class="font-sans text-body text-stone">Every single set I make is built to the measurements in your photo. Not a size from a pack. Not an approximation. Your actual nail widths, built nail by nail.</p>
      </div>

      <div class="bg-bone rounded-2xl p-8 border border-hairline/60">
        <div class="w-11 h-11 rounded-xl bg-lavender-wash flex items-center justify-center mb-5">
          <svg class="w-5 h-5 text-lavender-ink" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M152.61,165.49a48,48,0,0,1-62.1-62.1A8,8,0,0,1,93.8,99.46l13.6,21.84a8,8,0,0,1-1.21,9.62L98.91,138.6a40,40,0,0,0,18.49,18.49l7.68-7.28a8,8,0,0,1,9.62-1.21L156.54,162.2A8,8,0,0,1,152.61,165.49Z"/><path d="M128,32a96,96,0,0,0-83.32,143.51L32.27,224l49.71-12.49A96,96,0,1,0,128,32Z"/></svg>
        </div>
        <h3 class="font-sans font-medium text-ink mb-3" style="font-size:1rem">Real replies, from me</h3>
        <p class="font-sans text-body text-stone">When you message on WhatsApp, you're talking to me. Not an assistant, not a chatbot. I respond personally, usually within a few hours.</p>
      </div>

    </div>
  </div>
</section>


<!-- TESTIMONIALS -->
<section class="bg-shell py-14 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">What customers say.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>

    <div class="grid md:grid-cols-3 gap-6">

      <blockquote class="bg-paper rounded-2xl p-7 border border-hairline/60">
        <p class="font-sans text-body-lg text-graphite leading-relaxed mb-5 italic">&ldquo;I'd been hesitant about press-ons my whole life because nothing ever fit properly. This was the first time I felt like these were actually my nails.&rdquo;</p>
        <footer class="font-sans text-caption text-stone">&mdash; Ayesha, <span class="text-graphite">Lahore</span></footer>
      </blockquote>

      <blockquote class="bg-paper rounded-2xl p-7 border border-hairline/60">
        <p class="font-sans text-body-lg text-graphite leading-relaxed mb-5 italic">&ldquo;Ordered for my baraat and valima. Both sets were perfect. Got so many questions about them at the wedding &mdash; people genuinely couldn't tell they weren't salon gel.&rdquo;</p>
        <footer class="font-sans text-caption text-stone">&mdash; Hira, <span class="text-graphite">Karachi</span></footer>
      </blockquote>

      <blockquote class="bg-paper rounded-2xl p-7 border border-hairline/60">
        <p class="font-sans text-body-lg text-graphite leading-relaxed mb-5 italic">&ldquo;Really was not expecting the quality at this price. The packaging alone is beautiful. Will definitely reorder.&rdquo;</p>
        <footer class="font-sans text-caption text-stone">&mdash; Sara, <span class="text-graphite">Islamabad</span></footer>
      </blockquote>

    </div>
  </div>
</section>


<!-- FINAL CTA -->
<section class="bg-paper py-16 md:py-20 border-t border-hairline/50">
  <div class="max-w-7xl mx-auto px-6 lg:px-10 text-center">
    <div class="max-w-lg mx-auto">
      <h3 class="font-sans text-h3 font-medium text-ink mb-4">Curious about something?</h3>
      <p class="font-sans text-body text-graphite mb-8">I'm genuinely happy to answer questions before you order. Ask me anything &mdash; sizing, design options, timelines, whether a specific look is possible. WhatsApp is fastest.</p>
      <div class="flex flex-wrap items-center justify-center gap-3">
        <a href="https://wa.me/{{ $settings->whatsappForWaMe() }}?text=Hello%20Nails%20by%20Mona%2C%20I%20have%20a%20question%20before%20I%20order."
           class="inline-flex items-center gap-2 bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full px-8 py-4 transition-colors duration-200" style="font-size:1rem">
          Get help on WhatsApp &rarr;
        </a>
        <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 border border-ink text-ink hover:bg-ink hover:text-bone font-sans text-caption font-medium tracking-wide rounded-full px-7 py-4 transition-colors duration-200">
          Shop the collection
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
