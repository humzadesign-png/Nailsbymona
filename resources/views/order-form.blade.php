@extends('layouts.app')

@section('seo')
    <x-seo
        title="Place Your Order — Nails by Mona"
        description="Order your custom-fit press-on nails from Nails by Mona."
        :noindex="true"
    />
@endsection

@section('content')
<section class="bg-bone section-padding">
  <div class="section-container max-w-2xl">
    <p class="eyebrow">Order Flow</p>
    <h1 class="font-serif text-display text-ink mb-4" style="font-variation-settings:'opsz' 144,'SOFT' 30">Place your order.</h1>
    <div class="accent-rule"></div>
    <p class="font-sans text-body text-stone">The full order form is coming in Phase 2. For now, please <a href="{{ route('contact') }}" class="text-lavender underline">get in touch</a> or reach us on WhatsApp.</p>
    <a href="https://wa.me/{{ $settings->whatsappForWaMe() }}?text={{ urlencode('Hello Nails by Mona, I\'d like to place an order.') }}" class="btn-primary mt-8 inline-flex">WhatsApp us</a>
  </div>
</section>
@endsection
