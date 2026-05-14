@extends('layouts.app')

@push('head')
<style>
  .article-body { max-width: 740px; margin: 0 auto; }
  .article-body p { font-family: 'DM Sans', ui-sans-serif, system-ui; font-size: 1.0625rem; line-height: 1.8; color: #3A332E; margin-bottom: 1.5rem; }
  .article-body h2 { font-family: 'Fraunces', Georgia, serif; font-variation-settings: 'opsz' 144, 'SOFT' 30; font-size: clamp(1.35rem, 2.5vw, 1.65rem); font-weight: 300; color: #1A1614; line-height: 1.2; margin-top: 3rem; margin-bottom: 1rem; }
  .article-body h3 { font-family: 'DM Sans', ui-sans-serif, system-ui; font-size: 1.1rem; font-weight: 600; color: #1A1614; margin-top: 2rem; margin-bottom: 0.5rem; }
  .article-body blockquote { border-left: 3px solid #BFA4CE; padding-left: 1.5rem; margin: 2rem 0; font-style: italic; color: #3A332E; }
  .article-body ul { list-style: none; padding: 0; margin-bottom: 1.5rem; }
  .article-body ul li { padding-left: 1.5rem; position: relative; margin-bottom: 0.75rem; font-size: 1.0625rem; line-height: 1.7; color: #3A332E; }
  .article-body ul li::before { content: '—'; position: absolute; left: 0; color: #BFA4CE; }
  .article-body a { color: #5B4570; text-decoration: underline; text-decoration-color: #BFA4CE; text-underline-offset: 3px; }
  .article-body a:hover { color: #BFA4CE; }

  /* FAQ accordion */
  .faq-answer { display: none; }
  .faq-item.open .faq-answer { display: block; }
  .faq-item.open .faq-icon { transform: rotate(45deg); }
  .faq-icon { transition: transform 0.2s ease; }

  /* Post card hover */
  .related-card:hover .related-card-title { color: #BFA4CE; }
  .related-card:hover .related-card-img { transform: scale(1.03); }
  .related-card-img { transition: transform 0.3s ease; }
</style>
@endpush

@php
$blogPostSchema = json_encode([
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'         => 'Article',
            'headline'      => 'Can Muslim Women Wear Press-On Nails? Wudu, Nail Polish, and a Simple Solution',
            'author'        => ['@type' => 'Person', 'name' => 'Mona'],
            'publisher'     => ['@type' => 'Organization', 'name' => 'Nails by Mona'],
            'datePublished' => '2026-05-07',
            'url'           => route('blog.post', 'muslim-women-press-on-nails-wudu'),
        ],
        [
            '@type'      => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'Do press-on nails create a barrier that invalidates wudu?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'No — press-on nails sit on top of your natural nail and are removed before wudu. Unlike nail polish or acrylics, they don\'t coat the nail bed. Once you take them off, your natural nail is fully exposed to water.']],
                ['@type' => 'Question', 'name' => 'How long does it take to remove press-ons for wudu?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'About 2–3 minutes with warm water. Soak your fingertips for 30–60 seconds to soften the adhesive, then gently lift each nail from the side.']],
                ['@type' => 'Question', 'name' => 'Can I reapply press-ons right after wudu?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes — dry your hands fully first, at least a full two minutes. The full cycle — removal to reapplication — takes about five minutes once you have the routine down.']],
                ['@type' => 'Question', 'name' => 'Will removing press-ons daily damage my nails?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Not with adhesive tabs — these are designed to release gently and can be swapped between wears without damaging the nail bed.']],
                ['@type' => 'Question', 'name' => 'What about praying five times a day — is this practical?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Yes. In practice, you build a rhythm — typically removing and reapplying 2–3 times per day. The custom fit is what makes it low-effort.']],
            ],
        ],
        [
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',    'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Journal', 'item' => route('blog')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => 'Can Muslim Women Wear Press-On Nails?', 'item' => route('blog.post', 'muslim-women-press-on-nails-wudu')],
            ],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

@section('seo')
  <x-seo
    title="Can Muslim Women Wear Press-On Nails? Wudu, Nail Polish, and a Simple Solution | Nails by Mona"
    description="Press-on nails and wudu explained — remove before ablution, reapply after. The solution Mona found for herself after years of wearing her nails plain as a practicing Muslim."
    :schema="$blogPostSchema"
  />
@endsection

@section('content')

<!-- ARTICLE HEADER -->
<section class="bg-bone pt-10 pb-8 md:pt-12 md:pb-10">
  <div class="max-w-[740px] mx-auto px-6">

    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="flex items-center gap-2 mb-8 flex-wrap">
      <a href="{{ route('home') }}" class="font-sans text-caption text-stone hover:text-ink transition-colors duration-200">Home</a>
      <svg class="w-3 h-3 text-ash shrink-0" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="16" stroke-linecap="round"><polyline points="96 48 160 128 96 208"/></svg>
      <a href="{{ route('blog') }}" class="font-sans text-caption text-stone hover:text-ink transition-colors duration-200">Journal</a>
      <svg class="w-3 h-3 text-ash shrink-0" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="16" stroke-linecap="round"><polyline points="96 48 160 128 96 208"/></svg>
      <span class="font-sans text-caption text-lavender-ink">Tutorials</span>
    </nav>

    <!-- Category + H1 -->
    <span class="inline-block bg-lavender-wash text-lavender-ink font-sans text-eyebrow uppercase rounded-full px-3 py-1 mb-5">Tutorials</span>
    <h1 class="font-serif text-ink mb-6" style="font-size:clamp(1.75rem,4vw,2.6rem); font-weight:300; line-height:1.15; letter-spacing:-0.01em; font-variation-settings:'opsz' 144,'SOFT' 30">Can Muslim Women Wear Press-On Nails? Wudu, Nail Polish, and a Simple Solution</h1>

    <!-- Meta bar -->
    <div class="flex items-center gap-2 flex-wrap pb-8 border-b border-hairline">
      <span class="font-sans text-caption text-stone">7 May 2026</span>
      <span class="text-ash">&middot;</span>
      <span class="font-sans text-caption text-stone">8 min read</span>
      <span class="text-ash">&middot;</span>
      <span class="font-sans text-caption text-stone">by Mona</span>
    </div>

  </div>
</section>


<!-- COVER IMAGE -->
<section class="bg-paper py-0">
  <div class="max-w-[900px] mx-auto px-6">
    <div class="rounded-2xl overflow-hidden img-wrap-fallback aspect-[16/9] relative" style="background: linear-gradient(135deg, #D4C5E2 0%, #EAE3D9 35%, #F2ECF8 65%, #C8B4D8 100%)">
      <img src="" alt="Hands with custom-fit press-on nails resting on a prayer mat" class="w-full h-full object-cover" loading="eager" onerror="this.remove()">
      <div class="absolute inset-0 flex items-end p-6 md:p-8 pointer-events-none">
        <p class="font-sans text-caption italic" style="color:rgba(26,22,20,0.5)">The press-ons I make. Yours will be custom-fit to your measurements.</p>
      </div>
    </div>
  </div>
</section>


<!-- ARTICLE BODY -->
<section class="bg-paper pt-10 pb-8 md:pt-14 md:pb-10">
  <div class="px-6">
    <div class="article-body">

      <p>For years, I wore my nails plain. Not because I didn&rsquo;t care about how they looked &mdash; I care enormously about things like that &mdash; but because I couldn&rsquo;t find a way to have beautiful nails without compromising my prayers. This is the solution I found, and it&rsquo;s the reason I started making press-ons in the first place.</p>

      <h2>The question I kept asking myself</h2>
      <p>Growing up, I was always decorating things &mdash; drawings, mehndi designs, anything with colour and detail. I studied Fine Arts. I built a small business making bridal henna, resin name plates, paintings. All of these things were fine. And then I turned to nails.</p>
      <p>Every option I looked at created the same problem. Nail polish, gel, shellac, acrylics &mdash; they all coat the nail bed and form a physical barrier. Water cannot reach the natural nail during wudu. For me, that was not a compromise I was willing to make five times a day.</p>
      <p>So I kept asking: is there anything that gives me beautiful nails without this problem? The answer I eventually found was press-ons &mdash; and specifically, press-ons made to fit my exact nails.</p>

      <h2>Why traditional nail polish causes the problem</h2>
      <p>Wudu &mdash; the Islamic ritual of ablution before prayer &mdash; requires water to reach every part of the body it covers, including the nails, entirely. Traditional nail polish (lacquer, gel, and shellac alike) forms a waterproof film by design. No water gets through.</p>
      <p>Most scholars of Islamic jurisprudence consider wudu invalid if any part of the nails is coated with a waterproof barrier. This isn&rsquo;t a fringe view. It&rsquo;s the mainstream position across the major madhabs, and it explains why so many Muslim women &mdash; in Pakistan and across the world &mdash; wear their nails plain every day.</p>
      <p>The problem is that &ldquo;wear them plain&rdquo; is the only answer most of us are ever given. Nobody talks about alternatives.</p>

      <h2>What about &ldquo;breathable&rdquo; or &ldquo;halal&rdquo; nail polish?</h2>
      <p>This is where I want to be honest with you, because there&rsquo;s a lot of marketing that muddies the water.</p>
      <p>Some companies sell nail polishes labelled as &ldquo;breathable&rdquo; or &ldquo;halal,&rdquo; claiming water vapour can pass through the formula. The scientific evidence on this is genuinely unclear. Some tests show partial water vapour transmission. Most traditional scholars remain unconvinced that vapour permeability equals water penetrating the film in the way wudu requires.</p>
      <p>I am not a scholar, and I cannot issue a ruling. If you&rsquo;ve consulted a scholar and are comfortable with a breathable polish, that&rsquo;s between you and your practice. What I can tell you is that the debate remains live and unresolved &mdash; and for me, &ldquo;maybe&rdquo; was never enough. I needed something where the answer was clearly yes.</p>

      <blockquote>For me, &ldquo;maybe&rdquo; wasn&rsquo;t enough. I wanted a clear answer &mdash; something I could remove before wudu and put back on after, without doubt.</blockquote>

      <h2>Salon acrylics &mdash; the same issue, bigger commitment</h2>
      <p>Acrylics cover the entire nail with a hardened polymer overlay. They form a solid, opaque barrier &mdash; and they&rsquo;re bonded with a primer specifically designed to resist lifting. Removing them requires prolonged acetone soaking or mechanical grinding. You cannot take them off five times a day.</p>
      <p>This makes acrylics fundamentally incompatible with regular daily prayer. And for Pakistani brides, who often wear acrylics through a three-night wedding, the wudu question becomes even more acute. Many brides either skip prayer entirely during those days or feel deep discomfort about it. This is a real cost that nobody talks about when they sell you a set of acrylics.</p>

      <h2>How press-on nails are different</h2>
      <p>Press-on nails don&rsquo;t coat your nail. They sit on top of it, attached with nail glue or adhesive tabs. The critical difference is that they are designed to be removed and reapplied.</p>
      <p>A well-fitting press-on set &mdash; one made to your exact nail measurements &mdash; takes about 3 minutes to remove with warm water. You soak your fingertips briefly, the adhesive softens gently, and the nails lift cleanly from the sides. No acetone. No filing. No damage to the nail bed.</p>
      <p>Before wudu: you remove your press-ons and set them aside. Water reaches your natural nail completely, unobstructed. Your wudu is valid. After prayer: you dry your hands fully, then reapply. The whole process &mdash; removal to reapplication &mdash; takes about five minutes. That is the answer I was looking for.</p>
      <p>The key word is <em>fit</em>. This only works reliably with a custom-fit set. A generic one-size-fits-all press-on will pop off mid-day or won&rsquo;t lift cleanly. See the <a href="{{ route('size-guide') }}">size guide</a> for how to get your measurements right &mdash; it takes two minutes and it changes everything.</p>

      <h2>My own daily routine</h2>
      <p>I&rsquo;ve been doing this for over two years &mdash; since I started making press-ons and realised this was the answer I&rsquo;d been looking for. On a typical day, I remove before Fajr wudu in the morning and reapply after. I remove again before Dhuhr if I&rsquo;m praying at home, and so on. In practice, I&rsquo;m removing and reapplying 2&ndash;3 times per day on active prayer days.</p>
      <p>A few things I&rsquo;ve learned that make the routine easier:</p>
      <ul>
        <li>Keep your nails in a small lidded dish on your wudu shelf. Don&rsquo;t leave them on the wet bathroom counter &mdash; moisture is their enemy when not on your hands.</li>
        <li>Use adhesive tabs rather than brush-on glue if you&rsquo;re removing frequently. Tabs release gently in warm water, come off cleanly, and can be swapped between wears. Glue is for longer hold on days when you need it.</li>
        <li>After removing, dry your hands for a full two minutes before reapplying. Moisture under the nail breaks the adhesive bond faster.</li>
        <li>A custom-fit set makes all the difference. Nails that fit exactly stay put between wudu sessions and release cleanly when you want them to. This is why I take sizing seriously.</li>
      </ul>

      <h2>What to tell people who ask about your nails</h2>
      <p>At a gathering, someone will notice. Sometimes they&rsquo;ll ask if your nails are real. Sometimes, if they know you pray, they&rsquo;ll ask how that works.</p>
      <p>I tell people the truth: these come off for wudu. That&rsquo;s the short version, and in my experience it&rsquo;s always enough. People are usually surprised &mdash; first that a press-on can look this finished, and then genuinely interested in how the removal works. It often starts a conversation.</p>
      <p>You don&rsquo;t owe anyone a theological explanation. &ldquo;They&rsquo;re press-ons, so I take them off before wudu and put them back on after. Takes five minutes.&rdquo; That&rsquo;s usually enough.</p>
      <p>The deeper truth is this: so many Muslim women have been carrying this problem silently for years. Wanting beautiful nails. Feeling like they had to choose. I started making press-ons because I needed this for myself. Now I make them for everyone who needs the same thing.</p>
      <p>If you&rsquo;re curious about trying a set &mdash; whether you&rsquo;re new to press-ons or you&rsquo;ve been wearing them another way &mdash; start with the <a href="{{ route('size-guide') }}">size guide</a>. Getting the fit right is everything. Browse the <a href="{{ route('shop') }}">full collection</a> when you&rsquo;re ready.</p>

      <!-- Share strip -->
      <div class="flex flex-wrap items-center gap-5 pt-8 mt-8 border-t border-hairline">
        <span class="font-sans text-caption text-stone">Share this article:</span>
        <a href="https://wa.me/?text=Can%20Muslim%20Women%20Wear%20Press-On%20Nails%3F%20%E2%80%94%20https%3A%2F%2Fnailsbymona.pk%2Fblog%2Fmuslim-women-press-on-nails-wudu"
           class="font-sans text-caption text-stone hover:text-lavender-ink transition-colors duration-200">Share on WhatsApp</a>
        <button id="copy-link" class="font-sans text-caption text-stone hover:text-lavender-ink transition-colors duration-200 bg-transparent">
          <span id="copy-link-label">Copy link</span>
        </button>
      </div>

    </div>
  </div>
</section>


<!-- FAQ SECTION -->
<section class="bg-shell py-14 md:py-16">
  <div class="max-w-[740px] mx-auto px-6">

    <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Common questions</p>
    <h2 class="font-serif text-ink mb-3" style="font-size:clamp(1.5rem,3vw,2rem); font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30; line-height:1.2">Questions I get about this.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-8"></div>

    <div class="divide-y divide-hairline">

      <div class="faq-item py-5">
        <button class="faq-toggle w-full flex items-start justify-between gap-4 text-left bg-transparent">
          <span class="font-sans font-medium text-ink" style="font-size:1rem; line-height:1.5">Do press-on nails create a barrier that invalidates wudu?</span>
          <svg class="faq-icon w-4 h-4 text-stone shrink-0 mt-0.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round"><line x1="40" y1="128" x2="216" y2="128"/><line x1="128" y1="40" x2="128" y2="216"/></svg>
        </button>
        <div class="faq-answer pt-3 pb-1">
          <p class="font-sans text-body text-graphite leading-relaxed">No &mdash; press-on nails sit on top of your natural nail and are removed before wudu. Unlike nail polish or acrylics, they don&rsquo;t coat the nail bed. Once you take them off, your natural nail is fully exposed to water. Wudu is performed on bare nails, then press-ons are reapplied after.</p>
        </div>
      </div>

      <div class="faq-item py-5">
        <button class="faq-toggle w-full flex items-start justify-between gap-4 text-left bg-transparent">
          <span class="font-sans font-medium text-ink" style="font-size:1rem; line-height:1.5">How long does it take to remove press-ons for wudu?</span>
          <svg class="faq-icon w-4 h-4 text-stone shrink-0 mt-0.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round"><line x1="40" y1="128" x2="216" y2="128"/><line x1="128" y1="40" x2="128" y2="216"/></svg>
        </button>
        <div class="faq-answer pt-3 pb-1">
          <p class="font-sans text-body text-graphite leading-relaxed">About 2&ndash;3 minutes with warm water. Soak your fingertips for 30&ndash;60 seconds to soften the adhesive, then gently lift each nail from the side. No acetone, no tools required. Custom-fit nails release more cleanly than loose-fitting ones &mdash; another reason to get sizing right.</p>
        </div>
      </div>

      <div class="faq-item py-5">
        <button class="faq-toggle w-full flex items-start justify-between gap-4 text-left bg-transparent">
          <span class="font-sans font-medium text-ink" style="font-size:1rem; line-height:1.5">Can I reapply press-ons right after wudu?</span>
          <svg class="faq-icon w-4 h-4 text-stone shrink-0 mt-0.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round"><line x1="40" y1="128" x2="216" y2="128"/><line x1="128" y1="40" x2="128" y2="216"/></svg>
        </button>
        <div class="faq-answer pt-3 pb-1">
          <p class="font-sans text-body text-graphite leading-relaxed">Yes &mdash; dry your hands fully first, at least a full two minutes. Moisture under the adhesive will shorten the wear time noticeably. Once your hands are dry, reapply as normal. The full cycle &mdash; removal to reapplication &mdash; takes about five minutes once you have the routine down.</p>
        </div>
      </div>

      <div class="faq-item py-5">
        <button class="faq-toggle w-full flex items-start justify-between gap-4 text-left bg-transparent">
          <span class="font-sans font-medium text-ink" style="font-size:1rem; line-height:1.5">Will removing press-ons daily damage my nails?</span>
          <svg class="faq-icon w-4 h-4 text-stone shrink-0 mt-0.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round"><line x1="40" y1="128" x2="216" y2="128"/><line x1="128" y1="40" x2="128" y2="216"/></svg>
        </button>
        <div class="faq-answer pt-3 pb-1">
          <p class="font-sans text-body text-graphite leading-relaxed">Not with adhesive tabs &mdash; these are designed to release gently and can be swapped between wears without damaging the nail bed. With brush-on glue, frequent removal can cause some dehydration over time. My recommendation: use tabs on high-prayer days when you&rsquo;re removing often, and switch to glue only when you need stronger hold for a special occasion.</p>
        </div>
      </div>

      <div class="faq-item py-5">
        <button class="faq-toggle w-full flex items-start justify-between gap-4 text-left bg-transparent">
          <span class="font-sans font-medium text-ink" style="font-size:1rem; line-height:1.5">What about praying five times a day &mdash; is this practical?</span>
          <svg class="faq-icon w-4 h-4 text-stone shrink-0 mt-0.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round"><line x1="40" y1="128" x2="216" y2="128"/><line x1="128" y1="40" x2="128" y2="216"/></svg>
        </button>
        <div class="faq-answer pt-3 pb-1">
          <p class="font-sans text-body text-graphite leading-relaxed">I do it myself, so yes. In practice, you build a rhythm &mdash; I typically remove and reapply 2&ndash;3 times per day. If you pray at the mosque, some women keep a small lidded dish in their bag for their nails. It sounds like a lot until you&rsquo;re doing it, at which point it feels like any other small habit. The custom fit is what makes it low-effort &mdash; nails that fit sit securely between removals and release cleanly each time.</p>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- RELATED PRODUCTS -->
<section class="bg-paper pt-14 pb-16 md:pt-16 md:pb-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Mentioned in this article</p>
    <h2 class="font-serif text-ink mb-3" style="font-size:clamp(1.5rem,3vw,2rem); font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30; line-height:1.2">Designs mentioned in this article.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>

    <div class="grid sm:grid-cols-3 gap-6">

      <!-- Product 1 -->
      <div class="bg-paper rounded-2xl border border-hairline/80 overflow-hidden related-card">
        <div class="aspect-square img-wrap-fallback overflow-hidden relative cursor-pointer" style="background: linear-gradient(135deg, #EAE3D9 0%, #FBF8F2 60%, #D9D2C6 100%)">
          <img src="" alt="Everyday plain custom-fit press-on nails, nude tones" class="w-full h-full object-cover related-card-img" loading="lazy" onerror="this.remove()">
          <span class="absolute top-3 left-3 inline-block bg-paper/90 text-graphite font-sans text-eyebrow uppercase rounded-full px-3 py-1">Everyday</span>
        </div>
        <div class="p-5">
          <p class="font-serif text-ink mb-1 related-card-title transition-colors duration-200" style="font-size:1rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">Soft Nude Almond &mdash; Everyday</p>
          <p class="font-sans text-caption text-lavender font-medium mb-4">From Rs. 1,800</p>
          <button class="add-to-bag-btn w-full border border-ink text-ink hover:bg-ink hover:text-bone font-sans text-caption font-medium tracking-wide rounded-full py-2.5 transition-colors duration-200"
                  data-slug="soft-nude-almond-everyday" data-name="Soft Nude Almond — Everyday" data-price="1800">
            Add to bag
          </button>
        </div>
      </div>

      <!-- Product 2 -->
      <div class="bg-paper rounded-2xl border border-hairline/80 overflow-hidden related-card">
        <div class="aspect-square img-wrap-fallback overflow-hidden relative cursor-pointer" style="background: linear-gradient(135deg, #D4C5E2 0%, #FBF8F2 60%, #C8B4D8 100%)">
          <img src="" alt="Dusty rose ombre press-on nails, signature set" class="w-full h-full object-cover related-card-img" loading="lazy" onerror="this.remove()">
          <span class="absolute top-3 left-3 inline-block bg-lavender-wash/90 text-lavender-ink font-sans text-eyebrow uppercase rounded-full px-3 py-1">Signature</span>
        </div>
        <div class="p-5">
          <p class="font-serif text-ink mb-1 related-card-title transition-colors duration-200" style="font-size:1rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">Dusty Rose Ombre &mdash; Signature</p>
          <p class="font-sans text-caption text-lavender font-medium mb-4">From Rs. 2,800</p>
          <button class="add-to-bag-btn w-full border border-ink text-ink hover:bg-ink hover:text-bone font-sans text-caption font-medium tracking-wide rounded-full py-2.5 transition-colors duration-200"
                  data-slug="dusty-rose-ombre-signature" data-name="Dusty Rose Ombre — Signature" data-price="2800">
            Add to bag
          </button>
        </div>
      </div>

      <!-- Product 3 -->
      <div class="bg-paper rounded-2xl border border-hairline/80 overflow-hidden related-card">
        <div class="aspect-square img-wrap-fallback overflow-hidden relative cursor-pointer" style="background: linear-gradient(135deg, #EAE3D9 0%, #FBF8F2 50%, #E8E0D5 100%)">
          <img src="" alt="Milky white minimalist press-on nails, everyday set" class="w-full h-full object-cover related-card-img" loading="lazy" onerror="this.remove()">
          <span class="absolute top-3 left-3 inline-block bg-paper/90 text-graphite font-sans text-eyebrow uppercase rounded-full px-3 py-1">Everyday</span>
        </div>
        <div class="p-5">
          <p class="font-serif text-ink mb-1 related-card-title transition-colors duration-200" style="font-size:1rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">Milky White Minimalist &mdash; Everyday</p>
          <p class="font-sans text-caption text-lavender font-medium mb-4">From Rs. 1,800</p>
          <button class="add-to-bag-btn w-full border border-ink text-ink hover:bg-ink hover:text-bone font-sans text-caption font-medium tracking-wide rounded-full py-2.5 transition-colors duration-200"
                  data-slug="milky-white-minimalist-everyday" data-name="Milky White Minimalist — Everyday" data-price="1800">
            Add to bag
          </button>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- AUTHOR BLOCK -->
<section class="bg-shell py-10 md:py-12 border-y border-hairline/50">
  <div class="max-w-[740px] mx-auto px-6">
    <div class="flex flex-col sm:flex-row items-start gap-6">
      <!-- Avatar placeholder (no face image — text only per spec) -->
      <div class="w-14 h-14 rounded-full bg-lavender-wash border border-hairline/80 shrink-0 flex items-center justify-center">
        <span class="font-serif text-lavender-ink" style="font-size:1.25rem; font-variation-settings:'opsz' 144,'SOFT' 30; font-weight:300">M</span>
      </div>
      <div class="flex-1">
        <p class="font-sans font-semibold text-ink mb-0.5">Written by Mona</p>
        <p class="font-sans text-caption text-stone mb-3">Nails by Mona Studio, Mirpur, Azad Kashmir</p>
        <p class="font-sans text-body text-graphite leading-relaxed mb-5">I handmake every press-on gel nail set myself in my studio in Mirpur. If you have a question about anything in this post &mdash; or want a set made &mdash; get in touch on WhatsApp.</p>
        <a href="https://wa.me/92XXXXXXXXXX?text=Hello%20Nails%20by%20Mona%2C%20I%20read%20your%20article%20about%20press-on%20nails%20and%20wudu%20and%20have%20a%20question."
           class="inline-flex items-center gap-2 bg-lavender hover:bg-lavender-dark text-white font-sans text-caption font-medium tracking-wide rounded-full px-6 py-3 transition-colors duration-200">
          Get help on WhatsApp &rarr;
        </a>
      </div>
    </div>
  </div>
</section>


<!-- RELATED POSTS -->
<section class="bg-paper pt-14 pb-16 md:pt-16 md:pb-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <p class="font-sans text-eyebrow text-lavender uppercase mb-4">More from the studio</p>
    <h2 class="font-serif text-ink mb-3" style="font-size:clamp(1.5rem,3vw,2rem); font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30; line-height:1.2">Keep reading.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>

    <div class="grid sm:grid-cols-3 gap-6">

      <a href="{{ route('blog.post', 'press-on-nails-vs-acrylics-pakistani-brides') }}" class="group block rounded-2xl overflow-hidden border border-hairline/80 hover:shadow-card transition-shadow duration-300">
        <div class="aspect-[16/9] img-wrap-fallback overflow-hidden" style="background: linear-gradient(135deg, #EDE2C8 0%, #FBF8F2 60%, #D4C8BE 100%)">
          <img src="" alt="Bridal press-on nails vs acrylics comparison" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" loading="lazy" onerror="this.remove()">
        </div>
        <div class="bg-paper p-5">
          <span class="inline-block bg-lavender-wash text-lavender-ink font-sans text-eyebrow uppercase rounded-full px-3 py-1 mb-3">Bridal</span>
          <h3 class="font-serif text-ink mb-2 group-hover:text-lavender-ink transition-colors duration-200" style="font-size:1.1rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30; line-height:1.3">Press-On Nails vs Acrylics: Which Is Better for Pakistani Brides?</h3>
          <p class="font-sans text-caption text-stone">9 min read &mdash; by Mona</p>
        </div>
      </a>

      <a href="{{ route('blog.post', 'how-to-apply-press-on-nails') }}" class="group block rounded-2xl overflow-hidden border border-hairline/80 hover:shadow-card transition-shadow duration-300">
        <div class="aspect-[16/9] img-wrap-fallback overflow-hidden" style="background: linear-gradient(135deg, #F2ECF8 0%, #FBF8F2 60%, #E8E0F0 100%)">
          <img src="" alt="How to apply press-on nails step by step guide" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" loading="lazy" onerror="this.remove()">
        </div>
        <div class="bg-paper p-5">
          <span class="inline-block bg-lavender-wash text-lavender-ink font-sans text-eyebrow uppercase rounded-full px-3 py-1 mb-3">Tutorials</span>
          <h3 class="font-serif text-ink mb-2 group-hover:text-lavender-ink transition-colors duration-200" style="font-size:1.1rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30; line-height:1.3">How to Apply Press-On Nails &mdash; A Foolproof 7-Step Guide</h3>
          <p class="font-sans text-caption text-stone">7 min read &mdash; by Mona</p>
        </div>
      </a>

      <a href="{{ route('blog.post', 'how-to-remove-press-on-nails') }}" class="group block rounded-2xl overflow-hidden border border-hairline/80 hover:shadow-card transition-shadow duration-300">
        <div class="aspect-[16/9] img-wrap-fallback overflow-hidden" style="background: linear-gradient(135deg, #EAE3D9 0%, #FBF8F2 60%, #D9D2C6 100%)">
          <img src="" alt="How to remove press-on nails without damage" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" loading="lazy" onerror="this.remove()">
        </div>
        <div class="bg-paper p-5">
          <span class="inline-block bg-lavender-wash text-lavender-ink font-sans text-eyebrow uppercase rounded-full px-3 py-1 mb-3">Care</span>
          <h3 class="font-serif text-ink mb-2 group-hover:text-lavender-ink transition-colors duration-200" style="font-size:1.1rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30; line-height:1.3">How to Remove Press-On Nails Without Damaging Your Natural Nails</h3>
          <p class="font-sans text-caption text-stone">6 min read &mdash; by Mona</p>
        </div>
      </a>

    </div>

    <!-- Back to journal -->
    <div class="mt-10 text-center">
      <a href="{{ route('blog') }}" class="inline-flex items-center gap-2 font-sans text-caption font-medium text-stone hover:text-ink transition-colors duration-200">
        <svg class="w-3.5 h-3.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round"><line x1="216" y1="128" x2="40" y2="128"/><polyline points="112 56 40 128 112 200"/></svg>
        Back to the Journal
      </a>
    </div>

  </div>
</section>

@endsection

@push('scripts')
<script>
$(function(){
  // Add to bag
  $('.add-to-bag-btn').on('click', function() {
    const $btn = $(this);
    const slug = $btn.data('slug');
    const name = $btn.data('name');
    const price_pkr = parseInt($btn.data('price'));
    const items = window.NbmBag.get();
    const existing = items.find(i => i.slug === slug);
    if (existing) { existing.qty++; } else { items.push({ slug, name, price_pkr, qty: 1 }); }
    window.NbmBag.save(items);
    window.NbmBag.open();
  });

  // FAQ accordion
  $('.faq-toggle').on('click', function() {
    const $item = $(this).closest('.faq-item');
    const isOpen = $item.hasClass('open');
    $('.faq-item').removeClass('open');
    if (!isOpen) $item.addClass('open');
  });

  // Copy link
  $('#copy-link').on('click', function() {
    const url = window.location.href;
    if (navigator.clipboard) {
      navigator.clipboard.writeText(url).then(() => {
        $('#copy-link-label').text('Copied!');
        setTimeout(() => $('#copy-link-label').text('Copy link'), 2000);
      });
    }
  });
});
</script>
@endpush
