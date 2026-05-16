@extends('layouts.order')

@section('title', 'Payment — Step 3 of 3 — Nails by Mona')

@section('progress')
@include('order.partials.progress', ['currentStep' => 3])
@endsection

@push('head')
<style>
  .payment-option { transition: border-color 0.15s ease, background-color 0.15s ease; cursor: pointer; }
  .payment-option input[type="radio"] { display: none; }
  .payment-option.selected { border-color: #BFA4CE; background-color: #F5F0FA; }
  .payment-option:hover { border-color: #BFA4CE; }
  .account-details { display: none; }
  .payment-option.selected .account-details { display: block; }
</style>
@endpush

@section('content')
<div class="bg-bone py-10 md:py-14">
  <div class="max-w-5xl mx-auto px-6">
    <div class="lg:grid lg:grid-cols-[1fr_340px] lg:gap-10 items-start">

      {{-- ── Main column ─────────────────────────────────────────────── --}}
      <div>
        <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Step 3 of 3</p>
        <h1 class="font-serif text-ink mb-2" style="font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30;line-height:1.2">
          How would you like to pay?
        </h1>
        <div class="h-0.5 w-10 bg-lavender mb-2"></div>
        <p class="font-sans text-caption text-stone mb-8">All payments are processed before your order goes into production. No Cash on Delivery.</p>

        {{-- Advance payment notice --}}
        @if ($totals['isBridalTrio'])
        <div class="mb-6 bg-lavender-wash border-l-4 border-lavender rounded-r-xl px-5 py-4">
          <p class="font-sans text-body text-lavender-ink leading-relaxed">
            <strong>Bridal Trio orders</strong> are paid in two stages: 50% deposit on confirmation to reserve your production slot, and 50% before dispatch. I'll send the deposit amount and payment details via WhatsApp.
          </p>
        </div>
        @elseif ($totals['requires_advance'])
        <div class="mb-6 bg-lavender-wash border-l-4 border-lavender rounded-r-xl px-5 py-4">
          <p class="font-sans text-body text-lavender-ink leading-relaxed">
            Orders over Rs. 5,000 require a <strong>30% advance payment</strong>. After you place your order, I'll send the exact advance amount via WhatsApp — you'll pay the advance first, and the balance when I dispatch.
          </p>
        </div>
        @endif

        <form action="{{ route('order.store') }}" method="POST" id="payment-form">
          @csrf

          {{-- Payment method cards --}}
          <div class="grid gap-4 mb-8">

            {{-- JazzCash --}}
            <label class="payment-option border-2 border-hairline rounded-2xl p-5 block selected" data-value="jazzcash">
              <input type="radio" name="payment_method" value="jazzcash" checked>
              <div class="flex items-center gap-4 mb-0">
                <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center shrink-0">
                  <span class="font-sans text-white font-bold text-xs">JC</span>
                </div>
                <div class="flex-1">
                  <p class="font-sans font-semibold text-ink">JazzCash</p>
                  <p class="font-sans text-caption text-stone">Mobile wallet</p>
                </div>
                <div class="w-5 h-5 rounded-full bg-lavender border-2 border-lavender option-dot flex items-center justify-center">
                  <svg class="w-3 h-3 text-white" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
                </div>
              </div>
              <div class="account-details mt-4 bg-paper rounded-xl px-4 py-3 border border-hairline/60">
                <p class="font-sans text-caption text-stone mb-1">Send to:</p>
                <p class="font-sans text-body text-ink font-semibold">{{ $settings->jazzcash_number }}</p>
                <p class="font-sans text-caption text-stone">Account name: {{ $settings->jazzcash_name }}</p>
              </div>
            </label>

            {{-- EasyPaisa --}}
            <label class="payment-option border-2 border-hairline rounded-2xl p-5 block" data-value="easypaisa">
              <input type="radio" name="payment_method" value="easypaisa">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center shrink-0">
                  <span class="font-sans text-white font-bold text-xs">EP</span>
                </div>
                <div class="flex-1">
                  <p class="font-sans font-semibold text-ink">EasyPaisa</p>
                  <p class="font-sans text-caption text-stone">Mobile wallet</p>
                </div>
                <div class="w-5 h-5 rounded-full border-2 border-ash option-dot"></div>
              </div>
              <div class="account-details mt-4 bg-paper rounded-xl px-4 py-3 border border-hairline/60">
                <p class="font-sans text-caption text-stone mb-1">Send to:</p>
                <p class="font-sans text-body text-ink font-semibold">{{ $settings->easypaisa_number }}</p>
                <p class="font-sans text-caption text-stone">Account name: {{ $settings->easypaisa_name }}</p>
              </div>
            </label>

            {{-- Bank Transfer --}}
            <label class="payment-option border-2 border-hairline rounded-2xl p-5 block" data-value="bank_transfer">
              <input type="radio" name="payment_method" value="bank_transfer">
              <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-ink rounded-xl flex items-center justify-center shrink-0">
                  <svg class="w-5 h-5 text-bone" viewBox="0 0 256 256" fill="currentColor"><path d="M243.84,76.19l-104-48a8,8,0,0,0-7.68,0l-104,48A8,8,0,0,0,24,84v8a8,8,0,0,0,8,8h8v96H32a8,8,0,0,0,0,16H224a8,8,0,0,0,0-16H216V100h8a8,8,0,0,0,8-8V84A8,8,0,0,0,243.84,76.19ZM56,196V100h24v96Zm56,0V100h24v96Zm56,0V100h24v96ZM25.78,84,128,36.29,230.22,84Z"/></svg>
                </div>
                <div class="flex-1">
                  <p class="font-sans font-semibold text-ink">Bank Transfer</p>
                  <p class="font-sans text-caption text-stone">Direct bank deposit</p>
                </div>
                <div class="w-5 h-5 rounded-full border-2 border-ash option-dot"></div>
              </div>
              <div class="account-details mt-4 bg-paper rounded-xl px-4 py-3 border border-hairline/60">
                <p class="font-sans text-caption text-stone mb-1">Transfer to:</p>
                <p class="font-sans text-body text-ink font-semibold">{{ $settings->bank_account_name }}</p>
                <p class="font-sans text-caption text-stone">IBAN: {{ $settings->bank_iban }}</p>
                <p class="font-sans text-caption text-stone">Bank: {{ $settings->bank_name }}</p>
              </div>
            </label>

          </div>

          {{-- Order review accordion --}}
          <div class="mb-8 border border-hairline rounded-2xl overflow-hidden">
            <button type="button" id="review-toggle"
                    class="w-full flex items-center justify-between px-5 py-4 font-sans font-semibold text-ink text-sm bg-transparent">
              Review your order
              <svg id="review-chevron" class="w-4 h-4 text-stone transition-transform duration-200 rotate-90" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="20" stroke-linecap="round"><polyline points="96 48 160 128 96 208"/></svg>
            </button>
            <div id="review-body" class="border-t border-hairline px-5 py-5">
              {{-- Items --}}
              @foreach ($bag as $item)
              <div class="flex justify-between mb-2">
                <span class="font-sans text-body text-graphite">{{ $item['name'] ?? 'Custom Set' }} @if(($item['qty']??1)>1)&times;{{ $item['qty'] }}@endif</span>
                <span class="font-sans text-body text-ink">Rs.&nbsp;{{ number_format(($item['price_pkr']??0)*($item['qty']??1)) }}</span>
              </div>
              @endforeach
              {{-- Sizing method --}}
              <p class="font-sans text-caption text-stone mt-1 mb-3">
                Sizing: {{ \App\Enums\SizingCaptureMethod::tryFrom($sizingMethod)?->label() ?? 'WhatsApp' }}
              </p>
              {{-- Customer details --}}
              <div class="border-t border-hairline pt-3 mb-3">
                <p class="font-sans text-caption text-ink font-semibold mb-1">Deliver to:</p>
                <p class="font-sans text-caption text-graphite">{{ $customer['name'] }}, {{ $customer['city'] }}</p>
                <p class="font-sans text-caption text-graphite">{{ $customer['address'] }}</p>
                <p class="font-sans text-caption text-stone">WhatsApp: {{ $customer['phone'] }}</p>
              </div>
              {{-- Totals --}}
              <div class="border-t border-hairline pt-3 space-y-1.5">
                @if ($totals['discount'] > 0)
                <div class="flex justify-between"><span class="font-sans text-caption text-stone">Reorder discount</span><span class="font-sans text-caption text-lavender-ink">–Rs.&nbsp;{{ number_format($totals['discount']) }}</span></div>
                @endif
                <div class="flex justify-between"><span class="font-sans text-caption text-stone">Shipping</span><span class="font-sans text-caption text-graphite">Rs.&nbsp;{{ number_format($totals['shipping']) }}</span></div>
                <div class="flex justify-between pt-1 border-t border-hairline"><span class="font-sans font-semibold text-ink text-sm">Total</span><span class="font-sans font-semibold text-lavender">Rs.&nbsp;{{ number_format($totals['total']) }}</span></div>
              </div>
            </div>
          </div>

          {{-- Place order CTA --}}
          <button type="submit" id="place-order-btn"
                  class="w-full bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full py-4 text-lg transition-colors duration-200">
            Place my order
          </button>
          <p class="font-sans text-caption text-stone text-center mt-3">
            By placing this order, you confirm you've read and agree to our return and refit policy.
          </p>

          <a href="{{ route('order.details') }}" class="block text-center mt-4 font-sans text-caption text-stone hover:text-ink transition-colors duration-200">
            &larr; Back to details
          </a>
        </form>
      </div>

      {{-- ── Desktop summary sidebar ──────────────────────────────────── --}}
      <div class="hidden lg:block sticky top-28">
        <div class="bg-paper border border-hairline rounded-2xl overflow-hidden">
          <div class="px-6 py-5 border-b border-hairline">
            <p class="font-sans font-semibold text-ink text-sm">Order summary</p>
          </div>
          <div class="px-6 py-5">
            @include('order.partials.price-summary', compact('bag', 'totals', 'isReturning'))
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(function () {
  // Payment method selection
  $('.payment-option').on('click', function () {
    $('.payment-option').removeClass('selected')
      .find('.option-dot').removeClass('bg-lavender border-lavender').addClass('border-ash').empty();

    $(this).addClass('selected')
      .find('.option-dot').removeClass('border-ash').addClass('bg-lavender border-lavender')
      .html('<svg class="w-3 h-3 text-white" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="24" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>');

    $(this).find('input[type="radio"]').prop('checked', true);
  });

  // Order review accordion (open by default)
  $('#review-toggle').on('click', function () {
    $('#review-body').toggleClass('hidden');
    $('#review-chevron').toggleClass('rotate-90');
  });

  // Place order — show spinner
  $('#payment-form').on('submit', function () {
    $('#place-order-btn')
      .text('Placing your order…')
      .prop('disabled', true)
      .addClass('opacity-75 cursor-not-allowed');
  });
});
</script>
@endpush
