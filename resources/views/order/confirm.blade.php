@extends('layouts.order')

@section('title', 'Order Confirmed — Nails by Mona')

@push('head')
<style>
  /* Animated checkmark */
  @keyframes check-pop {
    0%   { transform: scale(0.4); opacity: 0; }
    60%  { transform: scale(1.1); opacity: 1; }
    100% { transform: scale(1.0); opacity: 1; }
  }
  #success-check { animation: check-pop 0.55s cubic-bezier(0.34,1.56,0.64,1) forwards; }

  /* Upload drag zone */
  #proof-dropzone { transition: border-color 0.15s, background-color 0.15s; }
  #proof-dropzone.dragover { border-color: #BFA4CE; background-color: #F5F0FA; }
</style>
@endpush

@section('content')
<div class="bg-bone py-10 md:py-14">
  <div class="max-w-2xl mx-auto px-6">

    {{-- ── Success header ───────────────────────────────────────────────── --}}
    <div class="text-center mb-10">
      {{-- Animated check --}}
      <div id="success-check"
           class="w-20 h-20 rounded-full bg-lavender mx-auto mb-6 flex items-center justify-center">
        <svg class="w-10 h-10 text-white" viewBox="0 0 256 256" fill="none"
             stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="40 144 96 200 224 72"/>
        </svg>
      </div>
      <h1 class="font-serif text-ink mb-2" style="font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30;line-height:1.2">
        Your order is placed, dear. Thank you.
      </h1>
      <div class="h-0.5 w-10 bg-lavender mx-auto my-4"></div>
      <p class="font-sans text-body text-graphite">
        Order <strong class="text-ink">{{ $order->order_number }}</strong>
        &nbsp;&middot;&nbsp; A confirmation email is on its way to <strong>{{ $order->customer_email }}</strong>.
      </p>
    </div>

    {{-- ── Order summary card ───────────────────────────────────────────── --}}
    <div class="bg-shell rounded-2xl border border-hairline overflow-hidden mb-6">
      <div class="px-6 py-5 border-b border-hairline">
        <p class="font-sans font-semibold text-ink text-sm">Your order</p>
      </div>
      <div class="px-6 py-5">
        {{-- Items --}}
        @foreach ($order->items as $item)
        <div class="flex justify-between mb-3">
          <div>
            <p class="font-sans text-body text-ink font-medium">{{ $item->product_name_snapshot }}</p>
            @if ($item->qty > 1)
            <p class="font-sans text-caption text-stone">Qty: {{ $item->qty }}</p>
            @endif
          </div>
          <p class="font-sans text-body text-ink shrink-0">Rs.&nbsp;{{ number_format($item->lineTotalPkr) }}</p>
        </div>
        @endforeach

        {{-- Totals --}}
        <div class="border-t border-hairline pt-3 space-y-1.5 mt-3">
          <div class="flex justify-between">
            <span class="font-sans text-caption text-stone">Shipping</span>
            <span class="font-sans text-caption text-graphite">Rs.&nbsp;{{ number_format($order->shipping_pkr) }}</span>
          </div>
          @if ($order->subtotal_pkr !== $order->total_pkr - $order->shipping_pkr)
          <div class="flex justify-between">
            <span class="font-sans text-caption text-stone">Reorder discount</span>
            <span class="font-sans text-caption text-lavender-ink">–Rs.&nbsp;{{ number_format(($order->subtotal_pkr + $order->shipping_pkr) - $order->total_pkr + $order->shipping_pkr) }}</span>
          </div>
          @endif
          <div class="flex justify-between pt-1 border-t border-hairline">
            <span class="font-sans font-semibold text-ink">Total</span>
            <span class="font-sans font-semibold text-lavender">Rs.&nbsp;{{ number_format($order->total_pkr) }}</span>
          </div>
        </div>

        {{-- Delivery + sizing --}}
        <div class="border-t border-hairline pt-4 mt-4">
          <div class="grid md:grid-cols-2 gap-4">
            <div>
              <p class="font-sans text-caption text-stone font-medium mb-0.5">Deliver to</p>
              <p class="font-sans text-caption text-graphite">{{ $order->customer_name }}</p>
              <p class="font-sans text-caption text-graphite">{{ $order->shipping_address }}</p>
              <p class="font-sans text-caption text-graphite">{{ $order->city }}</p>
            </div>
            <div>
              <p class="font-sans text-caption text-stone font-medium mb-0.5">Estimated dispatch</p>
              <p class="font-sans text-caption text-graphite">
                {{ now()->addWeekdays($leadTimeDays)->format('D, d M Y') }}
              </p>
              <p class="font-sans text-caption text-stone mt-2 font-medium">Sizing</p>
              <p class="font-sans text-caption text-graphite">
                {{ $order->sizing_capture_method?->label() ?? 'Via WhatsApp' }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── Payment instructions ─────────────────────────────────────────── --}}
    <div class="bg-paper border border-hairline rounded-2xl overflow-hidden mb-6">
      <div class="px-6 py-5 border-b border-hairline">
        <p class="font-sans font-semibold text-ink text-sm">Send your payment</p>
      </div>
      <div class="px-6 py-5">

        {{-- Advance notice --}}
        @if ($isBridalTrio)
        <div class="mb-5 bg-lavender-wash border-l-4 border-lavender rounded-r-xl px-4 py-3">
          <p class="font-sans text-caption text-lavender-ink leading-relaxed">
            <strong>Bridal Trio:</strong> 50% deposit required now to reserve your slot.
            Advance due: <strong>Rs.&nbsp;{{ number_format($order->advance_paid_pkr ?: (int)($order->total_pkr * 0.50)) }}</strong>.
            Balance paid before dispatch.
          </p>
        </div>
        @elseif ($order->requires_advance)
        <div class="mb-5 bg-lavender-wash border-l-4 border-lavender rounded-r-xl px-4 py-3">
          <p class="font-sans text-caption text-lavender-ink leading-relaxed">
            <strong>30% advance required.</strong>
            Please send <strong>Rs.&nbsp;{{ number_format((int)($order->total_pkr * 0.30)) }}</strong> now.
            The balance of Rs.&nbsp;{{ number_format($order->total_pkr - (int)($order->total_pkr * 0.30)) }} is due before dispatch.
          </p>
        </div>
        @else
        <p class="font-sans text-body text-graphite mb-4 leading-relaxed">
          Please send the full amount of <strong class="text-ink">Rs.&nbsp;{{ number_format($order->total_pkr) }}</strong> to the account below. Your order goes into production once payment is confirmed.
        </p>
        @endif

        {{-- Payment method details --}}
        @php $method = $order->payment_method; @endphp

        @if ($method === \App\Enums\PaymentMethod::JazzCash)
        <div class="bg-shell rounded-xl px-5 py-4 border border-hairline">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 bg-red-500 rounded-lg flex items-center justify-center shrink-0">
              <span class="font-sans text-white font-bold text-xs">JC</span>
            </div>
            <p class="font-sans font-semibold text-ink">JazzCash</p>
          </div>
          <p class="font-sans text-caption text-stone mb-1">Send to:</p>
          <p class="font-sans text-body text-ink font-semibold">{{ config('nbm.jazzcash_number') }}</p>
          <p class="font-sans text-caption text-stone">Account name: {{ config('nbm.jazzcash_name') }}</p>
        </div>

        @elseif ($method === \App\Enums\PaymentMethod::EasyPaisa)
        <div class="bg-shell rounded-xl px-5 py-4 border border-hairline">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 bg-green-600 rounded-lg flex items-center justify-center shrink-0">
              <span class="font-sans text-white font-bold text-xs">EP</span>
            </div>
            <p class="font-sans font-semibold text-ink">EasyPaisa</p>
          </div>
          <p class="font-sans text-caption text-stone mb-1">Send to:</p>
          <p class="font-sans text-body text-ink font-semibold">{{ config('nbm.easypaisa_number') }}</p>
          <p class="font-sans text-caption text-stone">Account name: {{ config('nbm.easypaisa_name') }}</p>
        </div>

        @else {{-- bank_transfer --}}
        <div class="bg-shell rounded-xl px-5 py-4 border border-hairline">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 bg-ink rounded-lg flex items-center justify-center shrink-0">
              <svg class="w-4 h-4 text-bone" viewBox="0 0 256 256" fill="currentColor"><path d="M243.84,76.19l-104-48a8,8,0,0,0-7.68,0l-104,48A8,8,0,0,0,24,84v8a8,8,0,0,0,8,8h8v96H32a8,8,0,0,0,0,16H224a8,8,0,0,0,0-16H216V100h8a8,8,0,0,0,8-8V84A8,8,0,0,0,243.84,76.19ZM56,196V100h24v96Zm56,0V100h24v96Zm56,0V100h24v96ZM25.78,84,128,36.29,230.22,84Z"/></svg>
            </div>
            <p class="font-sans font-semibold text-ink">Bank Transfer</p>
          </div>
          <p class="font-sans text-caption text-stone mb-1">Transfer to:</p>
          <p class="font-sans text-body text-ink font-semibold">{{ config('nbm.bank_account_name') }}</p>
          <p class="font-sans text-caption text-stone">IBAN: {{ config('nbm.bank_iban') }}</p>
          <p class="font-sans text-caption text-stone">Bank: {{ config('nbm.bank_name') }}</p>
        </div>
        @endif

        <p class="font-sans text-caption text-stone mt-4">
          Include your order number <strong>{{ $order->order_number }}</strong> in the payment reference.
          I'll confirm within 24 hours via WhatsApp.
        </p>
      </div>
    </div>

    {{-- ── Payment proof upload ─────────────────────────────────────────── --}}
    <div class="bg-paper border border-hairline rounded-2xl overflow-hidden mb-6">
      <div class="px-6 py-5 border-b border-hairline">
        <p class="font-sans font-semibold text-ink text-sm">Upload your payment screenshot</p>
      </div>
      <div class="px-6 py-5">
        <p class="font-sans text-caption text-graphite mb-4">
          After sending, upload a screenshot of your transaction here. This helps us verify faster — you won't need to wait for a WhatsApp reply.
        </p>

        {{-- Hidden file input — sits outside the dropzone so no event conflicts --}}
        <input type="file" id="proof-file-input" accept="image/*,.pdf" class="hidden" style="position:absolute;left:-9999px">

        {{-- Drop zone --}}
        <div id="proof-dropzone"
             class="border-2 border-dashed border-hairline rounded-xl px-5 py-10 text-center transition-colors duration-200"
             style="cursor:default">
          <svg class="w-8 h-8 text-stone mx-auto mb-3" viewBox="0 0 256 256" fill="currentColor"><path d="M240,136v64a16,16,0,0,1-16,16H32a16,16,0,0,1-16-16V136a16,16,0,0,1,16-16H80a8,8,0,0,1,0,16H32v64H224V136H176a8,8,0,0,1,0-16h48A16,16,0,0,1,240,136ZM85.66,77.66,120,43.31V128a8,8,0,0,0,16,0V43.31l34.34,34.35a8,8,0,0,0,11.32-11.32l-48-48a8,8,0,0,0-11.32,0l-48,48A8,8,0,0,0,85.66,77.66Z"/></svg>
          <p class="font-sans text-body text-graphite font-medium mb-1">Drop your screenshot here</p>
          <p class="font-sans text-caption text-stone mb-4">or click the button below</p>
          {{-- <label> natively opens the file picker on click — no JS needed, works in all browsers --}}
          <label for="proof-file-input"
                 class="inline-block bg-ink text-bone font-sans text-caption font-medium rounded-full px-5 py-2.5 hover:opacity-80 transition cursor-pointer select-none">
            Choose file
          </label>
          <p class="font-sans text-caption text-stone mt-3">JPEG, PNG or PDF · Max 8 MB</p>
        </div>

        {{-- Upload progress / success --}}
        <div id="proof-uploading" class="hidden mt-4 flex items-center gap-3">
          <svg class="w-5 h-5 text-lavender animate-spin" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
          </svg>
          <p class="font-sans text-caption text-graphite">Uploading your screenshot…</p>
        </div>

        <div id="proof-success" class="hidden mt-4 bg-green-50 border border-green-200 rounded-xl px-4 py-3 flex items-start gap-3">
          <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
          <div>
            <p class="font-sans text-caption text-green-800 font-semibold">Screenshot received!</p>
            <p class="font-sans text-caption text-green-700">I'll review and confirm your payment within 24 hours.</p>
          </div>
        </div>

        <div id="proof-error" class="hidden mt-4 bg-red-50 border border-red-200 rounded-xl px-4 py-3">
          <p class="font-sans text-caption text-red-700" id="proof-error-msg">Upload failed. Please try again or send via WhatsApp.</p>
        </div>

        {{-- WhatsApp fallback --}}
        <a href="https://wa.me/{{ config('nbm.whatsapp_number') }}?text={{ urlencode('Hello Nails by Mona, here is my payment proof for order ' . $order->order_number . '.') }}"
           target="_blank" rel="noopener"
           class="block text-center mt-4 font-sans text-caption text-stone hover:text-ink transition-colors duration-200">
          Or send your screenshot on WhatsApp →
        </a>
      </div>
    </div>

    {{-- ── What happens next ────────────────────────────────────────────── --}}
    <div class="mb-8">
      <h2 class="font-serif text-ink mb-2" style="font-size:1.3rem;font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30">
        What happens next
      </h2>
      <div class="h-0.5 w-10 bg-lavender mb-5"></div>

      <div class="space-y-0">
        {{-- Step 1 --}}
        <div class="flex gap-4">
          <div class="flex flex-col items-center">
            <div class="w-8 h-8 rounded-full bg-lavender flex items-center justify-center shrink-0">
              <span class="font-sans text-white font-semibold text-xs">1</span>
            </div>
            <div class="w-0.5 h-10 bg-hairline mt-1"></div>
          </div>
          <div class="pb-5">
            <p class="font-sans font-semibold text-ink text-sm mb-0.5">Payment confirmed</p>
            <p class="font-sans text-caption text-stone">I'll verify your screenshot and WhatsApp you once confirmed — usually within 24 hours.</p>
          </div>
        </div>
        {{-- Step 2 --}}
        <div class="flex gap-4">
          <div class="flex flex-col items-center">
            <div class="w-8 h-8 rounded-full border-2 border-ash flex items-center justify-center shrink-0">
              <span class="font-sans text-stone text-xs">2</span>
            </div>
            <div class="w-0.5 h-10 bg-hairline mt-1"></div>
          </div>
          <div class="pb-5">
            <p class="font-sans font-semibold text-ink text-sm mb-0.5">In production</p>
            <p class="font-sans text-caption text-stone">I'll start making your set by hand. Typical lead time is {{ $leadTimeDays }} working days.</p>
          </div>
        </div>
        {{-- Step 3 --}}
        <div class="flex gap-4">
          <div class="flex flex-col items-center">
            <div class="w-8 h-8 rounded-full border-2 border-ash flex items-center justify-center shrink-0">
              <span class="font-sans text-stone text-xs">3</span>
            </div>
          </div>
          <div>
            <p class="font-sans font-semibold text-ink text-sm mb-0.5">Shipped to you</p>
            <p class="font-sans text-caption text-stone">I'll send your tracking number via WhatsApp when your parcel is with the courier.</p>
          </div>
        </div>
      </div>
    </div>

    {{-- ── Order actions ────────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row gap-3">
      <a href="{{ route('order.track', $order) }}"
         class="flex-1 bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full py-3.5 text-center transition-colors duration-200">
        Track this order
      </a>
      <a href="{{ route('shop') }}"
         class="flex-1 border border-hairline bg-paper hover:bg-shell text-ink font-sans font-medium rounded-full py-3.5 text-center transition-colors duration-200">
        Continue shopping
      </a>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
  // Clear the shopping bag now that the order is confirmed
  if (window.NbmBag) {
    window.NbmBag.save([]);
  } else {
    localStorage.removeItem('nbm.bag');
    $('#bag-count').text('0').addClass('hidden');
  }

  const proofUploadUrl = '{{ route('order.proof', $order) }}';
  const csrfToken      = '{{ csrf_token() }}';

  function uploadFile(file) {
    if (file.size > 8 * 1024 * 1024) {
      showProofError('File too large. Please upload a file under 8 MB.');
      return;
    }

    const allowed = ['image/jpeg','image/png','image/jpg','image/webp','application/pdf'];
    if (! allowed.includes(file.type)) {
      showProofError('Please upload a JPEG, PNG, or PDF file.');
      return;
    }

    $('#proof-dropzone').addClass('opacity-50 pointer-events-none');
    $('#proof-uploading').removeClass('hidden');
    $('#proof-success, #proof-error').addClass('hidden');

    const fd = new FormData();
    fd.append('_token', csrfToken);
    fd.append('proof', file);

    $.ajax({
      url:         proofUploadUrl,
      method:      'POST',
      data:        fd,
      processData: false,
      contentType: false,
    })
    .done(function () {
      $('#proof-uploading').addClass('hidden');
      $('#proof-success').removeClass('hidden');
      $('#proof-dropzone').addClass('hidden');
    })
    .fail(function (xhr) {
      $('#proof-uploading').addClass('hidden');
      $('#proof-dropzone').removeClass('opacity-50 pointer-events-none');
      const msg = xhr.responseJSON?.message || 'Upload failed. Please try again or send via WhatsApp.';
      showProofError(msg);
    });
  }

  function showProofError(msg) {
    $('#proof-error-msg').text(msg);
    $('#proof-error').removeClass('hidden');
  }

  // File input change — fires when user picks a file via the label button
  $('#proof-file-input').on('change', function () {
    if (this.files[0]) uploadFile(this.files[0]);
  });

  // Drag and drop onto the zone
  $('#proof-dropzone')
    .on('dragover', function (e) {
      e.preventDefault();
      $(this).css({ 'border-color': '#BFA4CE', 'background-color': 'rgba(242,235,248,0.4)' });
    })
    .on('dragleave', function () {
      $(this).css({ 'border-color': '#E0D9CE', 'background-color': '' });
    })
    .on('drop', function (e) {
      e.preventDefault();
      $(this).css({ 'border-color': '#E0D9CE', 'background-color': '' });
      const file = e.originalEvent.dataTransfer.files[0];
      if (file) uploadFile(file);
    });
});
</script>
@endpush
