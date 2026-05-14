@extends('layouts.app')

@section('title', 'Track Your Order — Nails by Mona')

@section('content')
<div class="bg-bone py-12 md:py-16">
  <div class="max-w-2xl mx-auto px-6">

    @if (!$fromSession)
    {{-- ── Lookup form (shown unless arriving from session) ──────────────── --}}
    <div id="lookup-section">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Order tracking</p>
      <h1 class="font-serif text-ink mb-2" style="font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30;line-height:1.2">
        Track your order.
      </h1>
      <div class="h-0.5 w-10 bg-lavender mb-8"></div>

      {{-- Error from server-side lookup (non-JS fallback) --}}
      @error('lookup')
      <div class="mb-6 bg-red-50 border border-red-200 rounded-xl px-5 py-4">
        <p class="font-sans text-caption text-red-700">{{ $message }}</p>
      </div>
      @enderror

      <div class="bg-paper border border-hairline rounded-2xl p-6">
        <div class="mb-5">
          <label class="font-sans text-caption text-graphite font-medium mb-1.5 block" for="lookup-order-number">
            Order number
          </label>
          <input type="text" id="lookup-order-number"
                 placeholder="e.g. NBM-2026-0001"
                 class="w-full font-sans text-body text-ink bg-bone border border-hairline rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-lavender/30 focus:border-lavender transition">
        </div>
        <div class="mb-6">
          <label class="font-sans text-caption text-graphite font-medium mb-1.5 block" for="lookup-contact">
            Email or WhatsApp number
          </label>
          <input type="text" id="lookup-contact"
                 placeholder="The details you used when ordering"
                 class="w-full font-sans text-body text-ink bg-bone border border-hairline rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-lavender/30 focus:border-lavender transition">
        </div>

        <div id="lookup-error" class="hidden mb-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
          <p class="font-sans text-caption text-red-700" id="lookup-error-msg"></p>
        </div>

        <button id="lookup-btn"
                class="w-full bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full py-4 text-base transition-colors duration-200">
          Find my order
        </button>
      </div>

      <p class="font-sans text-caption text-stone text-center mt-4">
        Your order number is in the confirmation email we sent after you placed your order.
      </p>
    </div>
    @endif

    {{-- ── Tracking view ─────────────────────────────────────────────────── --}}
    <div id="tracking-section" class="{{ $fromSession ? '' : 'hidden' }}">

    @if($order)
      {{-- Order header card --}}
      <div class="bg-paper border border-hairline rounded-2xl overflow-hidden mb-6">
        <div class="px-6 py-5 border-b border-hairline flex items-center justify-between gap-3 flex-wrap">
          <div>
            <p class="font-sans text-caption text-stone">Order</p>
            <p class="font-sans font-semibold text-ink">{{ $order->order_number }}</p>
          </div>
          <div>
            <p class="font-sans text-caption text-stone">Placed</p>
            <p class="font-sans font-semibold text-ink">{{ $order->created_at->format('d M Y') }}</p>
          </div>
          <div>
            <p class="font-sans text-caption text-stone">Total</p>
            <p class="font-sans font-semibold text-lavender">Rs.&nbsp;{{ number_format($order->total_pkr) }}</p>
          </div>
          <a href="{{ route('home') }}"
             class="font-sans text-caption text-stone hover:text-ink transition-colors duration-200 shrink-0">
            ← Back to shop
          </a>
        </div>
        <div class="px-6 py-4">
          <p class="font-sans text-caption text-stone mb-0.5">Delivering to</p>
          <p class="font-sans text-body text-ink">{{ $order->customer_name }}</p>
          <p class="font-sans text-caption text-graphite">{{ $order->shipping_address }}, {{ $order->city }}</p>
        </div>
      </div>

      {{-- ── Awaiting payment banner ────────────────────────────────────── --}}
      @if ($order->payment_status === \App\Enums\PaymentStatus::Awaiting)
      <div class="mb-6 bg-lavender-wash border-l-4 border-lavender rounded-r-xl px-5 py-4">
        <p class="font-sans text-caption text-lavender-ink leading-relaxed">
          <strong>Payment pending.</strong> We haven't received your payment screenshot yet.
          Once uploaded, Mona will verify within 24 hours.
        </p>
        <a href="{{ route('order.confirm', $order) }}"
           class="mt-2 inline-block font-sans text-caption text-lavender-ink font-semibold hover:text-lavender underline-offset-2 hover:underline transition-colors duration-200">
          Upload payment proof →
        </a>
      </div>
      @endif

      {{-- ── Timeline ─────────────────────────────────────────────────────── --}}
      <div class="bg-paper border border-hairline rounded-2xl px-6 py-6 mb-6">
        <p class="font-sans font-semibold text-ink text-sm mb-6">Order status</p>

        <div class="space-y-0">
          @foreach ($timeline as $index => $node)
          @php
            $isLast = $index === count($timeline) - 1;
            $state  = $node['state']; // completed | current | awaiting_payment | future
          @endphp

          <div class="flex gap-4">
            {{-- Node column --}}
            <div class="flex flex-col items-center">
              {{-- Circle --}}
              @if ($state === 'completed')
              <div class="w-8 h-8 rounded-full bg-lavender flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="22" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
              </div>
              @elseif ($state === 'current')
              <div class="w-8 h-8 rounded-full bg-lavender shrink-0 flex items-center justify-center"
                   style="box-shadow:0 0 0 6px rgba(191,164,206,0.25), 0 0 0 10px rgba(191,164,206,0.10)">
                <div class="w-3 h-3 rounded-full bg-white"></div>
              </div>
              @elseif ($state === 'awaiting_payment')
              <div class="w-8 h-8 rounded-full border-2 border-lavender flex items-center justify-center shrink-0">
                <div class="w-2.5 h-2.5 rounded-full bg-lavender"></div>
              </div>
              @else
              <div class="w-8 h-8 rounded-full border-2 border-ash flex items-center justify-center shrink-0 opacity-50">
                <div class="w-2 h-2 rounded-full bg-ash"></div>
              </div>
              @endif

              {{-- Connector --}}
              @if (!$isLast)
              <div class="w-0.5 mt-1 mb-1 flex-1 {{ in_array($state, ['completed']) ? 'bg-lavender' : 'bg-ash/50' }}"
                   style="min-height:2.5rem"></div>
              @endif
            </div>

            {{-- Content --}}
            <div class="{{ $isLast ? '' : 'pb-6' }} flex-1 pt-0.5">
              <p class="font-sans font-semibold text-sm {{ in_array($state, ['completed','current']) ? 'text-ink' : 'text-stone' }}">
                {{ $node['label'] }}
              </p>
              <p class="font-sans text-caption {{ $state === 'current' ? 'text-lavender-ink' : 'text-stone' }}">
                {{ $node['sublabel'] }}
              </p>

              {{-- Courier link (shipped node) --}}
              @if (!empty($node['tracking_url']))
              <a href="{{ $node['tracking_url'] }}" target="_blank" rel="noopener"
                 class="mt-1 inline-block font-sans text-caption text-lavender-ink hover:text-lavender underline-offset-2 hover:underline transition-colors duration-200">
                Track with {{ $node['courier_label'] }}: {{ $node['tracking_number'] }} →
              </a>
              @endif
            </div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- ── Refit reminder (delivered within 7 days) ───────────────────── --}}
      @if ($order->isWithinRefitWindow())
      <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl px-5 py-4">
        <p class="font-sans text-caption text-green-800 leading-relaxed">
          <strong>Your first refit is free.</strong> If any nail doesn't sit right, reply on WhatsApp within 7 days of delivery and I'll sort it out.
        </p>
      </div>
      @endif

      {{-- ── Cancelled notice ────────────────────────────────────────────── --}}
      @if ($order->status === \App\Enums\OrderStatus::Cancelled)
      <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl px-5 py-4">
        <p class="font-sans text-caption text-red-700 leading-relaxed">
          This order was cancelled. If you believe this is an error, please get in touch on WhatsApp.
        </p>
      </div>
      @endif

    @endif {{-- $order --}}
    </div>

    {{-- ── Help strip (always visible) ─────────────────────────────────────── --}}
    <div class="mt-6 bg-paper border border-hairline rounded-2xl px-6 py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <p class="font-sans font-semibold text-ink text-sm mb-0.5">Need help with your order?</p>
        <p class="font-sans text-caption text-stone">I'm on WhatsApp — usually respond within a few hours.</p>
      </div>
      <a href="https://wa.me/{{ config('nbm.whatsapp_number') }}?text={{ $whatsappMsg }}"
         target="_blank" rel="noopener"
         class="shrink-0 bg-ink hover:opacity-80 text-bone font-sans text-caption font-medium rounded-full px-5 py-2.5 transition-opacity duration-200 flex items-center gap-2">
        <svg class="w-4 h-4" viewBox="0 0 256 256" fill="currentColor"><path d="M187.58,144.84l-32-16a8,8,0,0,0-8,.5l-14.69,9.8a40.55,40.55,0,0,1-16-16l9.8-14.69a8,8,0,0,0,.5-8l-16-32A8,8,0,0,0,104,64a40,40,0,0,0-40,40,88.1,88.1,0,0,0,88,88,40,40,0,0,0,40-40A8,8,0,0,0,187.58,144.84ZM152,176a72.08,72.08,0,0,1-72-72,24,24,0,0,1,19.29-23.54l11.48,22.94L101,117.64a8,8,0,0,0-.73,7.65,56.42,56.42,0,0,0,30.42,30.42,8,8,0,0,0,7.65-.73l14.3-9.55,22.94,11.48A24,24,0,0,1,152,176ZM128,24A104,104,0,0,0,36.18,176.88L24.83,210.93a16,16,0,0,0,20.24,20.24l34.05-11.35A104,104,0,1,0,128,24Zm0,192a88.11,88.11,0,0,1-44.06-11.81,8,8,0,0,0-6.54-.67L40,216l12.47-37.4a8,8,0,0,0-.66-6.54A88,88,0,1,1,128,216Z"/></svg>
        Get help
      </a>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
  @if (!$fromSession)
  // AJAX lookup
  $('#lookup-btn').on('click', function () {
    const orderNumber = $('#lookup-order-number').val().trim();
    const contact     = $('#lookup-contact').val().trim();

    if (!orderNumber || !contact) {
      showLookupError('Please enter your order number and the email or phone number used when ordering.');
      return;
    }

    $(this).text('Looking…').prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
    $('#lookup-error').addClass('hidden');

    $.post('{{ route('order.track.lookup') }}', {
      _token:       '{{ csrf_token() }}',
      order_number: orderNumber,
      contact:      contact,
    })
    .done(function (data) {
      if (data.success && data.redirect) {
        window.location.href = data.redirect;
      }
    })
    .fail(function (xhr) {
      const msg = xhr.responseJSON?.message
        || "We couldn't find an order with those details. Please check your order number — it starts with NBM.";
      showLookupError(msg);
      $('#lookup-btn').text('Find my order').prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
    });
  });

  // Enter key triggers lookup
  $('#lookup-order-number, #lookup-contact').on('keydown', function (e) {
    if (e.key === 'Enter') $('#lookup-btn').trigger('click');
  });

  function showLookupError(msg) {
    $('#lookup-error-msg').text(msg);
    $('#lookup-error').removeClass('hidden');
  }
  @endif
});
</script>
@endpush
