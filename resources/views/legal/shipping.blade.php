@extends('layouts.app')

@php
    $schema = json_encode([
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',     'item' => route('home')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Shipping', 'item' => route('shipping')],
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

@section('seo')
<x-seo
    title="Shipping & Delivery — Nails by Mona"
    description="Nationwide shipping from Mirpur AJK via TCS, Leopards, M&P and BlueEx. Flat rate, free above a threshold, 2–4 working days to major cities."
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
        <li class="text-ink font-medium">Shipping</li>
      </ol>
    </nav>

    <p class="font-sans text-eyebrow uppercase tracking-[0.2em] text-lavender-dark mb-4">Delivery</p>
    <h1 class="font-serif text-display-lg text-ink mb-3" style="font-variation-settings:'opsz' 144,'SOFT' 30">
      Shipping &amp; delivery.
    </h1>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>

    <div class="font-sans text-body text-graphite leading-relaxed space-y-8">

      <p class="text-body-lg">We ship across Pakistan from our studio in Mirpur, Azad Kashmir. Every order is packed by hand the morning it ships.</p>

      <section class="bg-paper border border-hairline rounded-2xl p-7">
        <h2 class="font-serif text-2xl text-ink mb-5">At a glance</h2>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
          <div>
            <dt class="font-sans text-caption text-stone uppercase tracking-wider mb-1">Flat rate</dt>
            <dd class="font-serif text-xl text-ink">Rs. {{ number_format($settings->shipping_flat_pkr) }}</dd>
          </div>
          @if ($settings->shipping_free_above > 0)
          <div>
            <dt class="font-sans text-caption text-stone uppercase tracking-wider mb-1">Free shipping above</dt>
            <dd class="font-serif text-xl text-ink">Rs. {{ number_format($settings->shipping_free_above) }}</dd>
          </div>
          @endif
          <div>
            <dt class="font-sans text-caption text-stone uppercase tracking-wider mb-1">Major cities</dt>
            <dd class="font-serif text-xl text-ink">2–4 working days</dd>
          </div>
          <div>
            <dt class="font-sans text-caption text-stone uppercase tracking-wider mb-1">Remote areas</dt>
            <dd class="font-serif text-xl text-ink">4–6 working days</dd>
          </div>
        </dl>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Production first, then shipping</h2>
        <p>Each set is made to your nail measurements after payment is verified. Production lead times:</p>
        <ul class="list-disc pl-6 space-y-2 mt-3">
          <li><strong>Standard sets:</strong> {{ $settings->lead_time_standard_days }} calendar days from payment verification.</li>
          <li><strong>Bridal Trio:</strong> {{ $settings->lead_time_bridal_days }} calendar days — we source materials specifically for your event.</li>
        </ul>
        <p class="mt-3">Add 2–4 working days for courier delivery on top of that.</p>
        <p class="mt-3"><strong>Bridal orders:</strong> please place your order at least 4 weeks before the Mehendi night so we have time for production, delivery, and a refit if needed.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Couriers we use</h2>
        <p>We route each order through whichever courier best serves your city. The tracking number and courier name are emailed to you when the parcel leaves the studio.</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4">
          <div class="bg-paper border border-hairline rounded-xl px-4 py-3 text-center">
            <p class="font-sans font-semibold text-ink">TCS</p>
            <p class="font-sans text-caption text-stone">Reliable nationwide</p>
          </div>
          <div class="bg-paper border border-hairline rounded-xl px-4 py-3 text-center">
            <p class="font-sans font-semibold text-ink">Leopards</p>
            <p class="font-sans text-caption text-stone">Often cheapest</p>
          </div>
          <div class="bg-paper border border-hairline rounded-xl px-4 py-3 text-center">
            <p class="font-sans font-semibold text-ink">M&amp;P</p>
            <p class="font-sans text-caption text-stone">Nationwide backup</p>
          </div>
          <div class="bg-paper border border-hairline rounded-xl px-4 py-3 text-center">
            <p class="font-sans font-semibold text-ink">BlueEx</p>
            <p class="font-sans text-caption text-stone">Nationwide backup</p>
          </div>
        </div>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Damages in transit</h2>
        <p>Our packaging is rigid and lined — damage in transit is rare. If it does happen, please record a short unboxing video and send it on WhatsApp within 24 hours of delivery. We will replace the affected nails at no charge.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">International shipping</h2>
        <p>Not yet — we ship within Pakistan only at the moment. International (UK and UAE first) is on the roadmap for next year. If you're abroad and want to be notified when we launch, message us on WhatsApp and we'll add you to the list.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Tracking your order</h2>
        <p>Once your set ships you can track it any time from <a href="{{ route('track') }}" class="text-lavender-ink underline-offset-2 hover:underline">our tracking page</a> using your order number and email or phone.</p>
      </section>

    </div>
  </div>
</div>
@endsection
