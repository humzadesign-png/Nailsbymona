@extends('layouts.app')

@section('seo')
<x-seo
    title="Page not found — Nails by Mona"
    description="The page you were looking for has moved or never existed. Browse the collection, read the journal, or get in touch."
    :noindex="true"
/>
@endsection

@section('content')
<div class="bg-bone py-24 md:py-32">
  <div class="max-w-3xl mx-auto px-6 lg:px-10 text-center">

    <p class="font-sans text-eyebrow uppercase tracking-[0.2em] text-lavender-dark mb-6">404</p>

    <h1 class="font-serif text-display-lg text-ink mb-5" style="font-variation-settings:'opsz' 144,'SOFT' 30">
      We couldn't find that page.
    </h1>
    <div class="h-0.5 w-10 bg-lavender mx-auto mb-8"></div>

    <p class="font-sans text-body-lg text-graphite leading-relaxed max-w-xl mx-auto mb-12">
      The link may have moved, or the page never existed. Here are a few places you might be looking for instead.
    </p>

    <div class="grid sm:grid-cols-2 gap-4 max-w-xl mx-auto mb-12 text-left">
      <a href="{{ route('shop') }}"
         class="bg-paper border border-hairline rounded-2xl px-5 py-5 hover:border-lavender transition-colors duration-200 group">
        <p class="font-serif text-xl text-ink mb-1">Shop the collection</p>
        <p class="font-sans text-caption text-stone">Custom-fit press-on sets.</p>
      </a>
      <a href="{{ route('bridal') }}"
         class="bg-paper border border-hairline rounded-2xl px-5 py-5 hover:border-lavender transition-colors duration-200 group">
        <p class="font-serif text-xl text-ink mb-1">Bridal Trio</p>
        <p class="font-sans text-caption text-stone">Mehendi · Baraat · Valima.</p>
      </a>
      <a href="{{ route('blog') }}"
         class="bg-paper border border-hairline rounded-2xl px-5 py-5 hover:border-lavender transition-colors duration-200 group">
        <p class="font-serif text-xl text-ink mb-1">The journal</p>
        <p class="font-sans text-caption text-stone">Care, application, bridal guides.</p>
      </a>
      <a href="{{ route('contact') }}"
         class="bg-paper border border-hairline rounded-2xl px-5 py-5 hover:border-lavender transition-colors duration-200 group">
        <p class="font-serif text-xl text-ink mb-1">Get in touch</p>
        <p class="font-sans text-caption text-stone">Anything else, just ask.</p>
      </a>
    </div>

    <a href="{{ route('home') }}"
       class="inline-flex items-center justify-center gap-2 bg-lavender hover:bg-lavender-dark text-white font-sans text-caption font-medium tracking-wide rounded-full px-7 py-3 transition-colors duration-200">
      ← Back to home
    </a>

  </div>
</div>
@endsection
