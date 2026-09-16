@extends('layouts.order')

@section('title', 'Your Custom Design — Nails by Mona')

@php
    $firstName = \Illuminate\Support\Str::of($customRequest->customer_name)->explode(' ')->first();
    $shipping  = $customRequest->shippingPkr();
    $total     = $customRequest->price_pkr + $shipping;
    $leadDays  = $customRequest->lead_time_days ?: (int) $settings->lead_time_standard_days;
    $waHelp    = 'https://wa.me/' . $settings->whatsappForWaMe() . '?text='
               . rawurlencode('Hello Nails by Mona, I have a question about my custom design "' . $customRequest->design_title . '".');
@endphp

@section('content')
<div class="bg-bone py-10 md:py-14">
  <div class="max-w-2xl mx-auto px-6">

    @if ($customRequest->status === \App\Enums\CustomOrderStatus::Completed)
      {{-- ── Already ordered ───────────────────────────────────────────── --}}
      <div class="text-center py-10">
        <div class="w-16 h-16 rounded-full bg-lavender mx-auto mb-6 flex items-center justify-center">
          <svg class="w-8 h-8 text-white" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
        </div>
        <h1 class="font-serif text-ink mb-3" style="font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30;line-height:1.2">
          This design has already been ordered.
        </h1>
        <div class="h-0.5 w-10 bg-lavender mx-auto mb-6"></div>
        <p class="font-sans text-body text-graphite mb-8 max-w-md mx-auto">
          @if ($customRequest->order)
            Your order number is <strong class="text-ink">{{ $customRequest->order->order_number }}</strong>.
          @endif
          You can check its progress any time.
        </p>
        @if ($placedOrderId)
          <a href="{{ route('order.confirm', $placedOrderId) }}"
             class="inline-flex bg-lavender hover:bg-lavender-dark text-white font-sans font-medium rounded-full px-8 py-3.5 transition-colors duration-200">
            View my order
          </a>
        @else
          <a href="{{ route('track') }}"
             class="inline-flex bg-lavender hover:bg-lavender-dark text-white font-sans font-medium rounded-full px-8 py-3.5 transition-colors duration-200">
            Track my order
          </a>
        @endif
      </div>

    @elseif (! $customRequest->isUsable())
      {{-- ── Expired or cancelled ──────────────────────────────────────── --}}
      <div class="text-center py-10">
        <h1 class="font-serif text-ink mb-3" style="font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30;line-height:1.2">
          This link is no longer active.
        </h1>
        <div class="h-0.5 w-10 bg-lavender mx-auto mb-6"></div>
        <p class="font-sans text-body text-graphite mb-8 max-w-md mx-auto">
          Custom design links are valid for a limited time. Message us on WhatsApp and we'll send you a fresh one.
        </p>
        <a href="{{ $waHelp }}" target="_blank" rel="noopener"
           class="inline-flex bg-[#25D366] hover:bg-[#1fb558] text-white font-sans font-medium rounded-full px-8 py-3.5 transition-colors duration-200">
          Message us on WhatsApp
        </a>
      </div>

    @else
      {{-- ── Active link ───────────────────────────────────────────────── --}}
      <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Your custom design</p>
      <h1 class="font-serif text-ink mb-2" style="font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30;line-height:1.2">
        {{ $firstName }}, your design is ready to order.
      </h1>
      <div class="h-0.5 w-10 bg-lavender mb-6"></div>
      <p class="font-sans text-body text-graphite mb-8 leading-relaxed">
        Here's what we agreed. Take two quick sizing photos with our camera guide, add your delivery details, and complete your payment — it takes about 3 minutes.
      </p>

      {{-- Design card --}}
      <div class="bg-paper border border-hairline rounded-2xl overflow-hidden mb-6">
        @if (count($imageUrls))
          <div class="grid {{ count($imageUrls) > 1 ? 'grid-cols-2' : 'grid-cols-1' }} gap-1 bg-shell">
            @foreach (array_slice($imageUrls, 0, 4) as $url)
              <img src="{{ $url }}" alt="Reference for {{ $customRequest->design_title }}"
                   class="w-full {{ count($imageUrls) > 1 ? 'aspect-square' : 'aspect-[4/3]' }} object-cover"
                   loading="{{ $loop->first ? 'eager' : 'lazy' }}">
            @endforeach
          </div>
        @endif

        <div class="px-6 py-5">
          <p class="font-serif text-ink text-xl mb-2" style="font-variation-settings:'opsz' 144,'SOFT' 30">{{ $customRequest->design_title }}</p>
          @if ($customRequest->design_description)
            <p class="font-sans text-body text-graphite leading-relaxed whitespace-pre-line mb-4">{{ $customRequest->design_description }}</p>
          @endif

          <div class="border-t border-hairline pt-4 space-y-1.5">
            <div class="flex justify-between">
              <span class="font-sans text-caption text-stone">Design price</span>
              <span class="font-sans text-caption text-graphite">Rs.&nbsp;{{ number_format($customRequest->price_pkr) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="font-sans text-caption text-stone">Shipping</span>
              <span class="font-sans text-caption text-graphite">{{ $shipping > 0 ? 'Rs. ' . number_format($shipping) : 'Free' }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-hairline">
              <span class="font-sans font-semibold text-ink text-sm">Total</span>
              <span class="font-sans font-semibold text-lavender">Rs.&nbsp;{{ number_format($total) }}</span>
            </div>
          </div>

          <p class="font-sans text-caption text-stone mt-4">
            Made by hand in about {{ $leadDays }} days after payment is confirmed.
            Link valid until {{ $customRequest->expires_at?->format('j M Y') }}.
          </p>
        </div>
      </div>

      {{-- Next step --}}
      @if ($hasSavedSizing)
        <div class="mb-4 bg-lavender-wash border-l-4 border-lavender rounded-r-xl px-5 py-4">
          <p class="font-sans text-body text-lavender-ink leading-relaxed">
            <strong>Welcome back!</strong> We already have your sizing photos from a previous order, so you can skip the camera.
          </p>
        </div>
        <form action="{{ route('custom-order.begin', $customRequest->token) }}" method="POST" class="mb-3">
          @csrf
          <input type="hidden" name="sizing" value="saved">
          <button type="submit" class="w-full bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full py-4 text-base transition-colors duration-200">
            Use my saved sizing &rarr;
          </button>
        </form>
        <form action="{{ route('custom-order.begin', $customRequest->token) }}" method="POST">
          @csrf
          <input type="hidden" name="sizing" value="camera">
          <button type="submit" class="w-full border border-hairline bg-paper hover:border-lavender text-graphite font-sans font-medium rounded-full py-3.5 text-base transition-colors duration-200">
            Take new sizing photos instead
          </button>
        </form>
      @else
        <div class="grid gap-3 mb-6">
          <div class="flex items-start gap-4 bg-paper border border-hairline rounded-2xl px-5 py-4">
            <span class="font-serif text-lavender shrink-0 leading-none" style="font-size:1.5rem">01</span>
            <div>
              <p class="font-sans font-semibold text-ink mb-0.5">Two sizing photos</p>
              <p class="font-sans text-caption text-stone">Your fingers, then your thumb, each next to any coin. The on-screen guide shows you exactly where to place your hand.</p>
            </div>
          </div>
          <div class="flex items-start gap-4 bg-paper border border-hairline rounded-2xl px-5 py-4">
            <span class="font-serif text-lavender shrink-0 leading-none" style="font-size:1.5rem">02</span>
            <div>
              <p class="font-sans font-semibold text-ink mb-0.5">Delivery details</p>
              <p class="font-sans text-caption text-stone">Your name and number are already filled in.</p>
            </div>
          </div>
          <div class="flex items-start gap-4 bg-paper border border-hairline rounded-2xl px-5 py-4">
            <span class="font-serif text-lavender shrink-0 leading-none" style="font-size:1.5rem">03</span>
            <div>
              <p class="font-sans font-semibold text-ink mb-0.5">Payment</p>
              <p class="font-sans text-caption text-stone">JazzCash, EasyPaisa or bank transfer, then upload your receipt.</p>
            </div>
          </div>
        </div>

        <form action="{{ route('custom-order.begin', $customRequest->token) }}" method="POST">
          @csrf
          <input type="hidden" name="sizing" value="camera">
          <button type="submit" class="w-full bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full py-4 text-base transition-colors duration-200">
            Start my sizing photos &rarr;
          </button>
        </form>
      @endif

      <p class="font-sans text-caption text-stone text-center mt-5">
        Something not right with the design or price?
        <a href="{{ $waHelp }}" target="_blank" rel="noopener" class="text-lavender-ink underline-offset-2 hover:underline">Message us on WhatsApp</a>
      </p>
    @endif

  </div>
</div>
@endsection
