@extends('layouts.app')

@section('seo')
    <x-seo
        title="Sizing Capture — Nails by Mona"
        description="Take your sizing photos for your custom press-on nail order."
        :noindex="true"
    />
@endsection

@section('content')
<section class="bg-bone section-padding">
  <div class="section-container max-w-2xl text-center">
    <p class="eyebrow">Sizing</p>
    <h1 class="font-serif text-display text-ink mb-4" style="font-variation-settings:'opsz' 144,'SOFT' 30">Your sizing photos.</h1>
    <div class="accent-rule mx-auto"></div>
    <p class="font-sans text-body text-stone mt-6">The live camera sizing experience is coming in Phase 2. For now, please send your sizing photos on WhatsApp and we'll get started.</p>
    <a href="https://wa.me/{{ $settings->whatsappForWaMe() }}?text={{ urlencode('Hello Nails by Mona, I\'d like to send my sizing photos.') }}" class="btn-primary mt-8 inline-flex">Send photos on WhatsApp</a>
  </div>
</section>
@endsection
