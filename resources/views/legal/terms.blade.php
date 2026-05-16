@extends('layouts.app')

@php
    $schema = json_encode([
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',  'item' => route('home')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Terms', 'item' => route('terms')],
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

@section('seo')
<x-seo
    title="Terms of Service — Nails by Mona"
    description="Order terms, refit policy, payment terms, lead times, and delivery for custom-made press-on gel nails by Mona."
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
        <li class="text-ink font-medium">Terms</li>
      </ol>
    </nav>

    <p class="font-sans text-eyebrow uppercase tracking-[0.2em] text-lavender-dark mb-4">Legal</p>
    <h1 class="font-serif text-display-lg text-ink mb-3" style="font-variation-settings:'opsz' 144,'SOFT' 30">
      Terms of service.
    </h1>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>
    <p class="font-sans text-caption text-stone mb-14">Last updated: {{ now()->format('F Y') }}</p>

    <div class="prose-nbm font-sans text-body text-graphite leading-relaxed space-y-8">

      <p>This is the agreement between you and Nails by Mona (a one-woman studio based in Mirpur, Azad Kashmir) when you order a set of nails from us. The intent is to be fair on both sides. If anything is unclear, message us on WhatsApp.</p>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Each set is custom-made</h2>
        <p>Every set is made to fit your specific nails, by hand, after we have measured them from your sizing photos. This means we cannot accept returns on the set itself once production has begun — it would not fit anyone else. By placing the order you accept that returns of the made-to-order item are not possible.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Free first refit</h2>
        <p>If your set does not sit right when you receive it — a nail is too wide, too narrow, the wrong shape — message us on WhatsApp within 7 days of delivery. We will remake the affected nails at no charge and send them by post. Refits beyond the first one, or after 7 days, are quoted case-by-case.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Payment</h2>
        <ul class="list-disc pl-6 space-y-2">
          <li><strong>Standard orders:</strong> full payment before production begins. We accept JazzCash, EasyPaisa and bank transfer. After you place the order you upload a screenshot of the transfer; Mona verifies within 24 hours.</li>
          <li><strong>Orders ≥ Rs. 5,000:</strong> a partial advance is required (typically 25%) before production starts. The balance is collected before the set is dispatched.</li>
          <li><strong>Bridal Trio:</strong> full advance is required before production starts. Bridal work has a 10-day lead time, materials are sourced specifically for your event, and the trio cannot be re-sold.</li>
          <li>If payment proof is not uploaded within 72 hours of placing the order, the order is automatically cancelled. You can place it again any time.</li>
        </ul>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Lead times</h2>
        <p>Standard sets are dispatched 5 calendar days after the payment is verified. Bridal Trios take 10 days. Add 2–4 working days for nationwide courier delivery (more for remote areas). We always recommend ordering at least 4 weeks before a wedding event.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Delivery &amp; damages</h2>
        <p>We ship via TCS, Leopards, M&amp;P or BlueEx depending on your route. The tracking number is emailed to you when the parcel leaves the studio. If the box arrives damaged or items are missing, please send us an unboxing video on WhatsApp within 24 hours of delivery and we will replace at no charge.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Care &amp; reuse</h2>
        <p>Press-on gel nails are typically reusable 3–5 times when cared for. We include a small care card with every order. Damage from misuse (cutting, filing, glue removal with hard tools) is not covered by the refit guarantee.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Intellectual property</h2>
        <p>The designs, photographs, and copy on this site belong to Nails by Mona. Please don't reproduce them commercially without asking. You're welcome to share photos of nails you've bought from us on social media — tag us so we can reshare.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Disputes</h2>
        <p>If something goes wrong, please message us first — almost everything is resolved within a day. Formal disputes are governed by the laws of the Islamic Republic of Pakistan.</p>
      </section>

      <section>
        <h2 class="font-serif text-2xl text-ink mt-12 mb-4">Contact</h2>
        <p>WhatsApp: <a href="https://wa.me/{{ $settings->whatsappForWaMe() }}" target="_blank" rel="noopener" class="text-lavender-ink underline-offset-2 hover:underline">+{{ $settings->whatsappForWaMe() }}</a><br>
           Email: <a href="mailto:{{ $settings->contact_email }}" class="text-lavender-ink underline-offset-2 hover:underline">{{ $settings->contact_email }}</a></p>
      </section>

    </div>
  </div>
</div>
@endsection
