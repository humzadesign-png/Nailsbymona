@extends('layouts.app')

@php
    $schema = json_encode([
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',    'item' => route('home')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Privacy', 'item' => route('privacy')],
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

@section('seo')
<x-seo
    title="Privacy Policy — Nails by Mona"
    description="What data Nails by Mona collects when you order, how it's stored, who it's shared with, and how to contact us about your data."
    :schema="$schema"
/>
@endsection

@section('content')
<div class="bg-bone py-16 md:py-24">
  <div class="max-w-3xl mx-auto px-6 lg:px-10">

    <nav class="mb-6" aria-label="Breadcrumb">
      <ol class="flex items-center gap-2 font-sans text-caption text-graphite">
        <li><a href="{{ route('home') }}" class="hover:text-ink transition-colors duration-200">Home</a></li>
        <li aria-hidden="true"><span class="text-stone">›</span></li>
        <li class="text-ink font-medium">Privacy</li>
      </ol>
    </nav>

    <p class="font-sans text-eyebrow uppercase tracking-[0.2em] text-lavender-dark mb-4">Legal</p>
    <h1 class="font-serif text-display-lg text-ink mb-3" style="font-variation-settings:'opsz' 144,'SOFT' 30">
      Privacy policy.
    </h1>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>
    <p class="font-sans text-caption text-stone mb-14">Last updated: {{ now()->format('F Y') }}</p>

    <div class="prose-nbm font-sans text-body text-graphite leading-relaxed space-y-8">

      <p>This is a short, plain-English summary of how Nails by Mona — a one-woman studio based in Mirpur, Azad Kashmir — handles your personal data when you order from us. If anything here is unclear, get in touch on WhatsApp and we'll explain.</p>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">What we collect</h2>
        <p>When you place an order, we collect: your name, email address, phone (and WhatsApp number if different), shipping address, your nail sizing photos (close-ups of your hands), and the payment proof you upload after sending the transfer. We also collect the city you ordered from so couriers know where to ship.</p>
        <p>We collect basic anonymous analytics (page views, country, device type) via Google Analytics and Microsoft Clarity to understand which designs people respond to. This data does not identify you personally.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Why we collect it</h2>
        <ul class="list-disc pl-6 space-y-2">
          <li><strong>Name, address, phone:</strong> so your courier can find you.</li>
          <li><strong>Email:</strong> so we can send your order confirmation, production updates, and shipping notification.</li>
          <li><strong>Sizing photos:</strong> so Mona can make your nails to fit. They are looked at by Mona only, and used only for your order.</li>
          <li><strong>Payment proof:</strong> so we can verify the payment matched the order, and resolve any disputes.</li>
        </ul>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Where it lives</h2>
        <p>All of the above sits in a private database on our hosting provider, secured by TLS and password authentication. Your sizing photos and payment proofs are kept on private storage — there is no public link to them. They can only be viewed by Mona after she logs into the admin panel.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Who we share it with</h2>
        <ul class="list-disc pl-6 space-y-2">
          <li><strong>Couriers</strong> (TCS, Leopards, M&amp;P, BlueEx — whichever serves your route): name, phone, full address, and the package weight. That is all they get.</li>
          <li><strong>Our email provider</strong> (Brevo): your email address only, so we can send order updates.</li>
          <li>Nobody else. We do not sell your data. We do not run ads. We do not share your sizing photos with anyone, ever.</li>
        </ul>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">How long we keep it</h2>
        <p>Order records, including sizing photos, are kept indefinitely so that returning customers don't have to re-send measurements. If you want your data deleted, message us on WhatsApp and we'll remove everything within 7 days, except the minimum we need to keep for tax records (typically the order total + date, no personal details).</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Cookies</h2>
        <p>We use a single session cookie to keep your bag and order flow working as you move between pages. It does not track you between visits. Analytics tools may set their own cookies for visit counting.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Your rights</h2>
        <p>You can ask us at any time for a copy of the data we hold on you, ask us to correct it, or ask us to delete it. WhatsApp us at <a href="https://wa.me/{{ $settings->whatsappForWaMe() }}" target="_blank" rel="noopener" class="text-lavender-ink underline-offset-2 hover:underline">+{{ $settings->whatsappForWaMe() }}</a> or email <a href="mailto:{{ $settings->contact_email }}" class="text-lavender-ink underline-offset-2 hover:underline">{{ $settings->contact_email }}</a> and we'll respond within 7 days.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Children</h2>
        <p>We do not knowingly collect data from anyone under 16. If you're a parent and believe your child has placed an order without your permission, contact us and we'll cancel and refund it.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Updates</h2>
        <p>If we change anything material here, we'll update the "last updated" date at the top of this page. For substantial changes we'll email anyone with an active order.</p>
      </section>

    </div>
  </div>
</div>
@endsection
