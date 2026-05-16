@extends('layouts.app')

@section('seo')
    <x-seo
        title="Track Your Order — Nails by Mona"
        description="Track the status of your Nails by Mona order."
        :noindex="true"
    />
@endsection

@section('content')
<section class="bg-bone section-padding">
  <div class="section-container max-w-lg mx-auto">
    <p class="eyebrow text-center">Order Tracking</p>
    <h1 class="font-serif text-display text-ink mb-4 text-center" style="font-variation-settings:'opsz' 144,'SOFT' 30">Track your order.</h1>
    <div class="accent-rule mx-auto"></div>
    <p class="font-sans text-body text-stone mt-6 text-center">Full order tracking is coming in Phase 2. For now, please reach us on WhatsApp for a status update.</p>
    <a href="https://wa.me/{{ $settings->whatsappForWaMe() }}?text={{ urlencode('Hello Nails by Mona, I\'d like to check on my order.') }}" class="btn-primary mt-8 mx-auto flex w-fit">WhatsApp us</a>
  </div>
</section>
@endsection
