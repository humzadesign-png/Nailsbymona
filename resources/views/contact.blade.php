@extends('layouts.app')

@push('head')
<style>
.faq-answer { display: none; }
</style>
@endpush

@php
    $contactSchema = json_encode([
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type'         => 'LocalBusiness',
                'name'          => 'Nails by Mona',
                'url'           => route('home'),
                'email'         => 'hello@nailsbymona.pk',
                'address'       => [
                    '@type'           => 'PostalAddress',
                    'addressLocality' => 'Mirpur',
                    'addressRegion'   => 'Azad Kashmir',
                    'addressCountry'  => 'PK',
                ],
                'openingHours'  => 'Mo-Sa 10:00-21:00',
            ],
            [
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Help', 'item' => route('contact')],
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

@section('seo')
<x-seo
    title="Help & Customer Care — Nails by Mona"
    description="Get in touch with Nails by Mona for custom nail orders, bridal inquiries, or sizing help. WhatsApp is fastest. Based in Mirpur, AJK — shipping Pakistan-wide."
    :schema="$contactSchema"
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
        <li class="text-graphite font-medium">Help</li>
      </ol>
    </nav>
    <div class="max-w-xl">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-5">Customer care</p>
      <h1 class="font-serif text-display-lg text-ink mb-5" style="font-variation-settings:'opsz' 144,'SOFT' 30">Let's talk.</h1>
      <p class="font-sans text-body-lg text-graphite">I reply personally. WhatsApp is fastest &mdash; usually within a few hours.</p>
    </div>
  </div>
</section>


<!-- HOW TO REACH ME -->
<section class="bg-paper py-14 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <div class="grid md:grid-cols-2 gap-8">

      <!-- WhatsApp — primary -->
      <div class="bg-bone rounded-2xl p-8 md:p-10 flex flex-col items-start border border-hairline/60">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6" style="background:rgba(37,211,102,0.12)">
          <svg class="w-7 h-7" viewBox="0 0 256 256" fill="none" stroke="#25D366" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
            <path d="M152.61,165.49a48,48,0,0,1-62.1-62.1A8,8,0,0,1,93.8,99.46l13.6,21.84a8,8,0,0,1-1.21,9.62L98.91,138.6a40,40,0,0,0,18.49,18.49l7.68-7.28a8,8,0,0,1,9.62-1.21L156.54,162.2A8,8,0,0,1,152.61,165.49Z"/>
            <path d="M128,32a96,96,0,0,0-83.32,143.51L32.27,224l49.71-12.49A96,96,0,1,0,128,32Z"/>
          </svg>
        </div>
        <h3 class="font-sans text-h3 font-medium text-ink mb-3">Fastest: WhatsApp</h3>
        <p class="font-sans text-body text-graphite mb-8 flex-1">Send me a message and I'll get back to you within a few hours. For order questions, bridal enquiries, and sizing help &mdash; this is the fastest route.</p>
        <a href="https://wa.me/92XXXXXXXXXX?text=Hello%20Nails%20by%20Mona%2C%20I%20have%20a%20question."
           class="inline-flex items-center gap-2.5 bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full px-8 py-4 transition-colors duration-200 w-full justify-center md:w-auto" style="font-size:1rem">
          Message on WhatsApp
          <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round"><line x1="40" y1="128" x2="216" y2="128"/><polyline points="144 56 216 128 144 200"/></svg>
        </a>
        <p class="font-sans text-caption text-stone mt-5 italic">I'm usually available 10am&ndash;9pm PKT, Mon&ndash;Sat. I do read late messages &mdash; I just might reply in the morning, dear.</p>
      </div>

      <!-- Other channels -->
      <div class="flex flex-col gap-6">

        <div class="bg-bone rounded-2xl p-6 flex items-start gap-5 border border-hairline/60">
          <div class="w-11 h-11 rounded-xl bg-paper flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-graphite" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><rect x="32" y="32" width="192" height="192" rx="48"/><circle cx="128" cy="128" r="40"/><circle cx="180" cy="76" r="10" fill="currentColor" stroke="none"/></svg>
          </div>
          <div>
            <p class="font-sans font-medium text-ink mb-0.5" style="font-size:0.9375rem">Instagram</p>
            <p class="font-sans text-caption text-stone mb-2">@nailsbymona &mdash; DMs welcome, I check Instagram daily.</p>
            <a href="https://instagram.com/nailsbymona" class="font-sans text-caption text-lavender-ink hover:text-lavender underline-offset-4 hover:underline transition-colors duration-200">@nailsbymona &rarr;</a>
          </div>
        </div>

        <div class="bg-bone rounded-2xl p-6 flex items-start gap-5 border border-hairline/60">
          <div class="w-11 h-11 rounded-xl bg-paper flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-graphite" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><rect x="32" y="56" width="192" height="160" rx="8"/><path d="M128,136,32,56H224Z"/></svg>
          </div>
          <div>
            <p class="font-sans font-medium text-ink mb-0.5" style="font-size:0.9375rem">Email</p>
            <p class="font-sans text-caption text-stone mb-2">For longer questions or order concerns. I aim to reply within 24 hours.</p>
            <a href="mailto:hello@nailsbymona.pk" class="font-sans text-caption text-lavender-ink hover:text-lavender underline-offset-4 hover:underline transition-colors duration-200">hello@nailsbymona.pk &rarr;</a>
          </div>
        </div>

        <div class="bg-bone rounded-2xl p-6 flex items-start gap-5 border border-hairline/60">
          <div class="w-11 h-11 rounded-xl bg-paper flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-graphite" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"><path d="M128,16a96,96,0,1,0,96,96A96.11,96.11,0,0,0,128,16Zm0,176a80,80,0,1,1,80-80A80.09,80.09,0,0,1,128,192Z"/><path d="M164,128a36,36,0,1,1-36-36A36,36,0,0,1,164,128Z"/><line x1="128" y1="48" x2="128" y2="92"/><line x1="208" y1="128" x2="164" y2="128"/><line x1="128" y1="164" x2="128" y2="208"/><line x1="48" y1="128" x2="92" y2="128"/></svg>
          </div>
          <div>
            <p class="font-sans font-medium text-ink mb-0.5" style="font-size:0.9375rem">Location</p>
            <p class="font-sans text-caption text-stone">Mirpur, Azad Kashmir, Pakistan.</p>
            <p class="font-sans text-caption text-ash mt-1">All orders shipped nationally. No in-person visits.</p>
          </div>
        </div>

        <div class="bg-lavender/8 rounded-2xl p-6 flex items-start gap-5 border border-lavender/20">
          <div class="w-11 h-11 rounded-xl bg-lavender/15 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
              <path d="M223.9,114.3l-28.3-56.6A8,8,0,0,0,188.4,53H67.6a8,8,0,0,0-7.2,4.5L32.1,114.3A8.1,8.1,0,0,0,32,118v74a14,14,0,0,0,14,14H210a14,14,0,0,0,14-14V118A8.1,8.1,0,0,0,223.9,114.3ZM72.4,69H183.6l22,44H50.4ZM210,190H46V129H210v61Z"/>
              <line x1="100" y1="159" x2="156" y2="159"/>
              <line x1="128" y1="143" x2="128" y2="175"/>
            </svg>
          </div>
          <div>
            <p class="font-sans font-medium text-ink mb-0.5" style="font-size:0.9375rem">Track your order</p>
            <p class="font-sans text-caption text-stone mb-2">Enter your order number and the email or phone you used when ordering.</p>
            <a href="{{ route('track') }}" class="font-sans text-caption font-medium text-lavender hover:text-lavender-dark underline-offset-4 hover:underline transition-colors duration-200">Go to order tracking &rarr;</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>


<!-- CONTACT FORM -->
<section class="bg-shell py-14 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="max-w-xl mx-auto">

      <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Leave a message</p>
      <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">Or leave me a message here.</h2>
      <div class="h-0.5 w-10 bg-lavender mb-10"></div>

      @if(session('contact_success'))
      <div id="message-sent" class="mb-6 p-5 bg-success/10 border border-success/20 rounded-xl">
        <p class="font-sans text-body text-graphite italic">&ldquo;Your message is with me now. I'll get back to you soon, dear.&rdquo;</p>
      </div>
      @endif

      <!-- Success message (jQuery fallback) -->
      <div id="form-success" class="hidden mb-6 p-5 bg-success/10 border border-success/20 rounded-xl">
        <p class="font-sans text-body text-graphite italic">&ldquo;Your message is with me now. I'll get back to you soon, dear.&rdquo;</p>
      </div>

      <form id="contact-form" action="{{ route('contact.submit') }}" method="POST" class="space-y-5" novalidate>
        @csrf
        <div class="grid sm:grid-cols-2 gap-5">
          <div>
            <label for="name" class="block font-sans text-caption font-medium text-graphite mb-2">Full name <span class="text-danger">*</span></label>
            <input id="name" name="name" type="text" required placeholder="Your name" value="{{ old('name') }}"
              class="w-full bg-paper border border-hairline rounded-xl px-4 py-3 font-sans text-body text-ink placeholder-ash focus:outline-none focus:border-lavender transition-colors duration-200">
          </div>
          <div>
            <label for="email" class="block font-sans text-caption font-medium text-graphite mb-2">Email <span class="text-danger">*</span></label>
            <input id="email" name="email" type="email" required placeholder="your@email.com" value="{{ old('email') }}"
              class="w-full bg-paper border border-hairline rounded-xl px-4 py-3 font-sans text-body text-ink placeholder-ash focus:outline-none focus:border-lavender transition-colors duration-200">
          </div>
        </div>
        <div>
          <label for="phone" class="block font-sans text-caption font-medium text-graphite mb-2">Phone / WhatsApp <span class="font-normal text-stone">(recommended)</span></label>
          <input id="phone" name="phone" type="tel" placeholder="+92 300 0000000" value="{{ old('phone') }}"
            class="w-full bg-paper border border-hairline rounded-xl px-4 py-3 font-sans text-body text-ink placeholder-ash focus:outline-none focus:border-lavender transition-colors duration-200">
        </div>
        <div>
          <label for="subject" class="block font-sans text-caption font-medium text-graphite mb-2">Subject <span class="text-danger">*</span></label>
          <select id="subject" name="subject" required
            class="w-full bg-paper border border-hairline rounded-xl px-4 py-3 font-sans text-body text-graphite focus:outline-none focus:border-lavender transition-colors duration-200 appearance-none">
            <option value="" disabled selected>What's this about?</option>
            <option value="general" {{ old('subject') === 'general' ? 'selected' : '' }}>General inquiry</option>
            <option value="order" {{ old('subject') === 'order' ? 'selected' : '' }}>Order question</option>
            <option value="bridal" {{ old('subject') === 'bridal' ? 'selected' : '' }}>Bridal inquiry</option>
            <option value="sizing" {{ old('subject') === 'sizing' ? 'selected' : '' }}>Sizing help</option>
            <option value="other" {{ old('subject') === 'other' ? 'selected' : '' }}>Something else</option>
          </select>
        </div>
        <div>
          <label for="message" class="block font-sans text-caption font-medium text-graphite mb-2">Message <span class="text-danger">*</span></label>
          <textarea id="message" name="message" rows="5" required placeholder="Tell me what you need..."
            class="w-full bg-paper border border-hairline rounded-xl px-4 py-3 font-sans text-body text-ink placeholder-ash focus:outline-none focus:border-lavender transition-colors duration-200 resize-none">{{ old('message') }}</textarea>
        </div>
        <button type="submit"
          class="w-full bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full py-4 transition-colors duration-200" style="font-size:1rem">
          Send message
        </button>
        <p class="font-sans text-caption text-ash text-center">I read every message myself and reply within 24 hours. If it's urgent, WhatsApp is much faster.</p>
      </form>
    </div>
  </div>
</section>


<!-- FAQ ACCORDION -->
<section class="bg-paper py-14 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">

    <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Common questions</p>
    <h2 class="font-serif text-display text-ink mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">Most people message me about one of these.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>

    <div class="max-w-2xl space-y-0 border-t border-hairline">

      <div class="faq-item border-b border-hairline">
        <button class="faq-trigger w-full flex items-center justify-between py-5 text-left">
          <span class="font-sans font-medium text-ink pr-6" style="font-size:0.9375rem">I don&rsquo;t know how to take my sizing photos.</span>
          <svg class="faq-icon w-4 h-4 text-stone shrink-0 transition-transform duration-200" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round"><line x1="40" y1="128" x2="216" y2="128"/><line class="faq-plus-vertical" x1="128" y1="40" x2="128" y2="216"/></svg>
        </button>
        <div class="faq-answer pb-6">
          <p class="font-sans text-body text-graphite mb-3">The size guide walks you through both photos &mdash; fingers, then thumb &mdash; step by step, with good/bad examples for each.</p>
          <a href="{{ route('size-guide') }}" class="font-sans text-caption font-medium text-lavender-ink hover:text-lavender underline-offset-4 hover:underline transition-colors duration-200">View the size guide &rarr;</a>
        </div>
      </div>

      <div class="faq-item border-b border-hairline">
        <button class="faq-trigger w-full flex items-center justify-between py-5 text-left">
          <span class="font-sans font-medium text-ink pr-6" style="font-size:0.9375rem">I placed an order and want to know where it is.</span>
          <svg class="faq-icon w-4 h-4 text-stone shrink-0 transition-transform duration-200" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round"><line x1="40" y1="128" x2="216" y2="128"/><line class="faq-plus-vertical" x1="128" y1="40" x2="128" y2="216"/></svg>
        </button>
        <div class="faq-answer pb-6">
          <p class="font-sans text-body text-graphite mb-3">Use the order tracking page &mdash; enter your order number and the email or phone number you used when ordering.</p>
          <a href="{{ url('/order/track') }}" class="font-sans text-caption font-medium text-lavender-ink hover:text-lavender underline-offset-4 hover:underline transition-colors duration-200">Track your order &rarr;</a>
        </div>
      </div>

      <div class="faq-item border-b border-hairline">
        <button class="faq-trigger w-full flex items-center justify-between py-5 text-left">
          <span class="font-sans font-medium text-ink pr-6" style="font-size:0.9375rem">What payment methods do you accept?</span>
          <svg class="faq-icon w-4 h-4 text-stone shrink-0 transition-transform duration-200" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round"><line x1="40" y1="128" x2="216" y2="128"/><line class="faq-plus-vertical" x1="128" y1="40" x2="128" y2="216"/></svg>
        </button>
        <div class="faq-answer pb-6">
          <p class="font-sans text-body text-graphite">JazzCash, EasyPaisa, and bank transfer. Account details are sent automatically on the confirmation page after you place your order &mdash; you upload a screenshot of your payment and I verify it, usually within a few hours. No Cash on Delivery.</p>
        </div>
      </div>

      <div class="faq-item border-b border-hairline">
        <button class="faq-trigger w-full flex items-center justify-between py-5 text-left">
          <span class="font-sans font-medium text-ink pr-6" style="font-size:0.9375rem">I'm getting married and want nails for all three events.</span>
          <svg class="faq-icon w-4 h-4 text-stone shrink-0 transition-transform duration-200" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round"><line x1="40" y1="128" x2="216" y2="128"/><line class="faq-plus-vertical" x1="128" y1="40" x2="128" y2="216"/></svg>
        </button>
        <div class="faq-answer pb-6">
          <p class="font-sans text-body text-graphite mb-3">The Bridal Trio page has everything you need &mdash; what's included, pricing, timelines, and FAQs. Or message me directly and we'll plan it together.</p>
          <a href="{{ route('bridal') }}" class="font-sans text-caption font-medium text-lavender-ink hover:text-lavender underline-offset-4 hover:underline transition-colors duration-200">See the Bridal Trio &rarr;</a>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- BUSINESS HOURS -->
<section class="bg-bone py-12 border-t border-hairline/50">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="max-w-sm">
      <h3 class="font-sans text-h3 font-medium text-ink mb-6">When I'm around.</h3>
      <div class="space-y-3">
        <div class="flex justify-between items-center py-3 border-b border-hairline">
          <span class="font-sans text-body text-graphite">Monday &ndash; Saturday</span>
          <span class="font-sans font-medium text-ink" style="font-size:0.875rem">10am &ndash; 9pm PKT</span>
        </div>
        <div class="flex justify-between items-center py-3 border-b border-hairline">
          <span class="font-sans text-body text-graphite">Sunday</span>
          <span class="font-sans text-caption text-stone">Limited &mdash; I try to check</span>
        </div>
        <div class="flex justify-between items-center py-3">
          <span class="font-sans text-body text-graphite">Eid &amp; public holidays</span>
          <span class="font-sans text-caption text-stone">Closed &mdash; noted on IG</span>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
$(function() {
  // FAQ accordion — slideDown/Up pattern
  $('.faq-trigger').on('click', function() {
    const $item   = $(this).closest('.faq-item');
    const $answer = $item.find('.faq-answer');
    const isOpen  = $answer.is(':visible');
    $('.faq-answer').slideUp(180);
    $('.faq-icon').css('transform', '');
    if (!isOpen) {
      $answer.slideDown(180);
      $(this).find('.faq-icon').css('transform', 'rotate(45deg)');
    }
  });

  // Contact form — intercept for JS success message, still submits to server
  // The server handles the real submission; this is a progressive enhancement fallback.
  // If JS is disabled, the form submits and the server shows the session flash.
  $('#contact-form').on('submit', function() {
    // Let the form submit normally to the server; no preventDefault here.
    // The jQuery block just ensures the page scrolls to the success message
    // when the server redirects back with session('success').
    const $success = $('#form-success');
    if ($success.length && !$success.hasClass('hidden')) {
      $('html, body').animate({ scrollTop: $success.offset().top - 100 }, 400);
    }
  });
});
</script>
@endpush
