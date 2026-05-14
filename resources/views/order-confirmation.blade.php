@extends('layouts.app')

@section('seo')
    <x-seo
        title="Order Confirmed — Nails by Mona"
        description="Your order has been placed. Thank you!"
        :noindex="true"
    />
@endsection

@section('content')
<section class="bg-bone section-padding">
  <div class="section-container max-w-2xl text-center">
    <div class="w-16 h-16 rounded-full bg-lavender-wash flex items-center justify-center mx-auto mb-6">
      <svg class="w-8 h-8 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
    </div>
    <h1 class="font-serif text-display text-ink mb-4" style="font-variation-settings:'opsz' 144,'SOFT' 30">Your order is placed, dear. Thank you.</h1>
    <div class="accent-rule mx-auto"></div>
    <p class="font-sans text-body text-stone mt-6">Full order confirmation with payment instructions will be available in Phase 2.</p>
    <a href="{{ route('shop') }}" class="btn-primary mt-8 inline-flex">Continue shopping</a>
  </div>
</section>
@endsection
