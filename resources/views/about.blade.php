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

<!-- HERO -->
<section class="relative min-h-[60vh] md:min-h-[70vh] flex items-end overflow-hidden">
  <!-- Background hand portrait -->
  <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, #EAE3D9 0%, #FBF8F2 60%, #E0D9CE 100%)">
    <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJ32yAAfsHl2sbXiBXZLY3IKtRwIkwWXvKcHiU-VwXu3GcBZS-OCOQtkAzs0rwKgW4Dxp9-Z2au4aru1Nu0BtdA5S-uZ-NUYxfvCRB_EPlJ78I5QkkZiWeSOyecrlfT0sA2Kwo2P3RsYdsqE_IW2xtSSrJ7oKPp3RpON2gXOphhVK9Cf3kYPdg_HLtT-hDSpv_Dk7TIiOOMCwZD8yjf2AGWErYtoydiByeD07Oucov2MbMli1OY4mgn38QBbTbMGRf_-KaN1SZYy0"
         alt="Mona's hands working on a press-on nail set in her studio in Mirpur"
         class="absolute inset-0 w-full h-full object-cover" onerror="this.remove()" width="1440" height="900"
         loading="eager" fetchpriority="high">
    <div class="absolute inset-0 bg-gradient-to-t from-bone via-bone/30 to-transparent"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-bone/60 via-transparent to-transparent"></div>
  </div>

  <!-- Hero content -->
  <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 pb-16 pt-32 w-full">
    <div class="max-w-xl">
      <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2 font-sans text-caption text-graphite">
          <li><a href="{{ route('home') }}" class="hover:text-ink transition-colors duration-200">Home</a></li>
          <li aria-hidden="true"><span class="text-stone">›</span></li>
          <li class="text-ink font-medium">About</li>
        </ol>
      </nav>
      <p class="font-sans text-eyebrow uppercase mb-5 tracking-[0.2em]" style="color:var(--color-lavender-dark)">Our story</p>
      <h1 class="font-serif text-display-lg text-ink mb-5" style="font-variation-settings:'opsz' 144,'SOFT' 30">
        Hi, I'm Mona. I make every set myself.
      </h1>
      <p class="font-sans text-body-lg text-graphite max-w-md">
        No factory. No drop-shipping. Just me, my studio, and a lot of care &mdash; in Mirpur, Azad Kashmir.
      </p>
      <!-- Signature -->
      <div class="mt-6 relative inline-block">
        <span class="mona-signature text-lavender-ink" style="font-size:2.5rem; line-height:1.1; display:block">Mona</span>
        <div class="h-px w-16 bg-lavender/40 mt-1"></div>
        <p class="font-sans text-caption text-stone italic mt-1">Hi, I'm Mona &mdash; and these are my hands.</p>
      </div>
    </div>
  </div>
</section>


<!-- MY STORY -->
<section class="bg-paper py-16 md:py-24">
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

    <div class="max-w-2xl space-y-0">

      <!-- Step 1 -->
      <div class="flex gap-6 pb-10">
        <div class="flex flex-col items-center">
          <div class="w-10 h-10 rounded-full bg-lavender-wash border-2 border-lavender flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-lavender-ink" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M208,112a80,80,0,1,1-80-80A80,80,0,0,1,208,112Z"/></svg>
          </div>
          <div class="flex-1 w-px bg-hairline mt-2"></div>
        </div>
        <div class="pt-1.5 pb-4">
          <h3 class="font-sans font-medium text-ink mb-2" style="font-size:1rem">You share your sizing photos</h3>
          <p class="font-sans text-body text-stone">Two close-up photos &mdash; your fingers laid flat in a row with a coin above the middle nail, and your thumb extended with a coin above the thumbnail. About 90 seconds. No salon visits. I read every nail width directly off the coin in each photo.</p>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="flex gap-6 pb-10">
        <div class="flex flex-col items-center">
          <div class="w-10 h-10 rounded-full bg-lavender-wash border-2 border-lavender flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-lavender-ink" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M152.61,165.49a48,48,0,0,1-62.1-62.1A8,8,0,0,1,93.8,99.46l13.6,21.84a8,8,0,0,1-1.21,9.62L98.91,138.6a40,40,0,0,0,18.49,18.49l7.68-7.28a8,8,0,0,1,9.62-1.21L156.54,162.2A8,8,0,0,1,152.61,165.49Z"/><path d="M128,32a96,96,0,0,0-83.32,143.51L32.27,224l49.71-12.49A96,96,0,1,0,128,32Z"/></svg>
          </div>
          <div class="flex-1 w-px bg-hairline mt-2"></div>
        </div>
        <div class="pt-1.5 pb-4">
          <h3 class="font-sans font-medium text-ink mb-2" style="font-size:1rem">We align on design</h3>
          <p class="font-sans text-body text-stone">I message you on WhatsApp within a day to confirm the design direction. For bridal orders, this is where we coordinate across all three looks.</p>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="flex gap-6 pb-10">
        <div class="flex flex-col items-center">
          <div class="w-10 h-10 rounded-full bg-lavender-wash border-2 border-lavender flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-lavender-ink" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M200,168v16a8,8,0,0,1-8,8H72a8,8,0,0,1-8-8V168"/><polyline points="100 120 128 148 156 120"/><line x1="128" y1="28" x2="128" y2="148"/></svg>
          </div>
          <div class="flex-1 w-px bg-hairline mt-2"></div>
        </div>
        <div class="pt-1.5 pb-4">
          <h3 class="font-sans font-medium text-ink mb-2" style="font-size:1rem">I build your set</h3>
          <p class="font-sans text-body text-stone">Each nail individually on a form. Gel base, colour layers, any hand-painting or charm work, topcoat &mdash; cured between each stage. Custom orders: 5&ndash;9 working days. Bridal Trio: 10&ndash;14.</p>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="flex gap-6 pb-10">
        <div class="flex flex-col items-center">
          <div class="w-10 h-10 rounded-full bg-lavender-wash border-2 border-lavender flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-lavender-ink" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M128,24S32,96,32,152a96,96,0,0,0,192,0C224,96,128,24,128,24Z"/></svg>
          </div>
          <div class="flex-1 w-px bg-hairline mt-2"></div>
        </div>
        <div class="pt-1.5 pb-4">
          <h3 class="font-sans font-medium text-ink mb-2" style="font-size:1rem">Quality check</h3>
          <p class="font-sans text-body text-stone">Before I pack anything, I wear-test a spare nail. I check the finish, the cure, the colour payoff. If something isn't right at this stage &mdash; I start over.</p>
        </div>
      </div>

      <!-- Step 5 -->
      <div class="flex gap-6 pb-10">
        <div class="flex flex-col items-center">
          <div class="w-10 h-10 rounded-full bg-lavender-wash border-2 border-lavender flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-lavender-ink" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><rect x="32" y="72" width="192" height="144" rx="8"/><path d="M168,72V56a40,40,0,0,0-80,0V72"/></svg>
          </div>
          <div class="flex-1 w-px bg-hairline mt-2"></div>
        </div>
        <div class="pt-1.5 pb-4">
          <h3 class="font-sans font-medium text-ink mb-2" style="font-size:1rem">Packed and shipped</h3>
          <p class="font-sans text-body text-stone">Your nails go into the magnetic box, wrapped in tissue, with glue, a prep pad, and an application guide. I send you your tracking number the same day I hand the parcel to the courier.</p>
        </div>
      </div>

      <!-- Step 6 -->
      <div class="flex gap-6">
        <div class="flex flex-col items-center">
          <div class="w-10 h-10 rounded-full bg-lavender flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M225.86,102.82c-3.77-3.94-7.67-8-9.14-11.57-1.36-3.27-1.44-8.69-1.52-13.94-.15-9.76-.31-20.82-8-28.51s-18.75-7.85-28.51-8c-5.25-.08-10.67-.16-13.94-1.52-3.56-1.47-7.63-5.37-11.57-9.14C146.28,23.51,138.44,16,128,16s-18.27,7.51-25.18,14.14c-3.94,3.77-8,7.67-11.57,9.14C88,40.64,82.56,40.72,77.31,40.8c-9.76.15-20.82.31-28.51,8S40.95,67.55,40.8,77.31c-.08,5.25-.16,10.67-1.52,13.94-1.47,3.56-5.37,7.63-9.14,11.57C23.51,109.72,16,117.56,16,128s7.51,18.27,14.14,25.18c3.77,3.94,7.67,8,9.14,11.57,1.36,3.27,1.44,8.69,1.52,13.94.15,9.76.31,20.82,8,28.51s18.75,7.85,28.51,8c5.25.08,10.67.16,13.94,1.52,3.56,1.47,7.63,5.37,11.57,9.14C109.72,232.49,117.56,240,128,240s18.27-7.51,25.18-14.14c3.94-3.77,8-7.67,11.57-9.14,3.27-1.36,8.69-1.44,13.94-1.52,9.76-.15,20.82-.31,28.51-8s7.85-18.75,8-28.51c.08-5.25.16-10.67,1.52-13.94,1.47-3.56,5.37-7.63,9.14-11.57C232.49,146.28,240,138.44,240,128S232.49,109.73,225.86,102.82Zm-52.2,6.84-56,56a8,8,0,0,1-11.32,0l-24-24a8,8,0,0,1,11.32-11.32L112,148.69l50.34-50.35a8,8,0,0,1,11.32,11.32Z" fill="currentColor" stroke="none"/></svg>
          </div>
        </div>
        <div class="pt-1.5">
          <h3 class="font-sans font-medium text-ink mb-2" style="font-size:1rem">You wear them</h3>
          <p class="font-sans text-body text-stone">Apply in under ten minutes. They last 7&ndash;10 days. With careful removal, you'll get 3&ndash;5 wears from a single set. And if you send me a photo of them on &mdash; honestly, it makes my day.</p>
        </div>
      </div>

    </div>
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
