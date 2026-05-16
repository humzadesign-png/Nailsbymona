@extends('layouts.order')

@section('title', 'Your Details — Step 2 of 3 — Nails by Mona')

@section('progress')
@include('order.partials.progress', ['currentStep' => 2])
@endsection

@push('head')
<style>
  /* ── Form inputs — plain CSS (no @apply; @apply only works in compiled CSS) ── */
  .form-input {
    width: 100%;
    font-family: 'DM Sans', ui-sans-serif, system-ui, sans-serif;
    font-size: 1rem;
    line-height: 1.65;
    color: #1A1410;
    background-color: #FFFFFF;
    border: 1.5px solid #E0D9CE;
    border-radius: 0.75rem;
    padding: 0.75rem 1rem;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    appearance: none;
    -webkit-appearance: none;
  }
  .form-input::placeholder { color: #B5ACA5; }
  .form-input:focus {
    outline: none;
    border-color: #BFA4CE;
    box-shadow: 0 0 0 3.5px rgba(191, 164, 206, 0.18);
    background-color: #FFFFFF;
  }
  .form-input:hover:not(:focus) { border-color: #B5ACA5; }
  .form-input.error { border-color: #C0392B; }
  .form-input.error:focus { box-shadow: 0 0 0 3.5px rgba(192, 57, 43, 0.14); }

  /* Select arrow */
  select.form-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 256 256' fill='none' stroke='%237A6E65' stroke-width='20' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='64 96 128 160 192 96'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.875rem center;
    background-size: 1.1rem;
    padding-right: 2.5rem;
    cursor: pointer;
  }
  select.form-input option[value=""][disabled] { color: #B5ACA5; }

  .field-error { color: #C0392B; font-size: 0.8rem; margin-top: 0.3rem; font-family: 'DM Sans', sans-serif; }
</style>
@endpush

@section('content')
<div class="bg-bone py-10 md:py-14">
  <div class="max-w-5xl mx-auto px-6">
    <div class="lg:grid lg:grid-cols-[1fr_340px] lg:gap-10 items-start">

      {{-- ── Main form ─────────────────────────────────────────────────── --}}
      <div>
        {{-- Heading --}}
        <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Step 2 of 3</p>
        <h1 class="font-serif text-ink mb-2" style="font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30;line-height:1.2">
          Your details.
        </h1>
        <div class="h-0.5 w-10 bg-lavender mb-8"></div>

        {{-- Reorder discount banner --}}
        @if ($isReturning)
        <div class="mb-6 bg-lavender-wash border border-hairline rounded-xl px-5 py-4">
          <p class="font-sans text-body text-lavender-ink">
            <strong>Welcome back!</strong> A 5% reorder discount has been applied to your order.
          </p>
        </div>
        @endif

        {{-- Form card --}}
        <div class="bg-paper border border-hairline rounded-2xl overflow-hidden">
          <form action="{{ route('order.details.post') }}" method="POST" id="details-form">
            @csrf

            {{-- ── Section: Contact ─────────────────────────── --}}
            <div class="px-6 py-5 border-b border-hairline">
              <p class="font-sans text-caption text-stone font-medium uppercase tracking-wide mb-5" style="font-size:0.7rem;letter-spacing:0.08em">Contact</p>

              {{-- Name + Email side by side on desktop --}}
              <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <div>
                  <label class="font-sans text-caption text-graphite font-medium mb-1.5 block" for="name">Full name</label>
                  <input type="text" id="name" name="name" required
                         value="{{ old('name', $prefill['name'] ?? '') }}"
                         placeholder="Your full name"
                         class="form-input @error('name') error @enderror">
                  @error('name')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div>
                  <label class="font-sans text-caption text-graphite font-medium mb-1.5 block" for="email">Email address</label>
                  <input type="email" id="email" name="email" required
                         value="{{ old('email', $prefill['email'] ?? '') }}"
                         placeholder="your@email.com"
                         class="form-input @error('email') error @enderror">
                  @error('email')<p class="field-error">{{ $message }}</p>@enderror
                </div>
              </div>

              {{-- WhatsApp --}}
              <div>
                <label class="font-sans text-caption text-graphite font-medium mb-1.5 block" for="phone">WhatsApp number</label>
                <div class="flex gap-0" style="border: 1.5px solid #E0D9CE; border-radius: 0.75rem; overflow: hidden; transition: border-color 0.15s, box-shadow 0.15s;" id="phone-wrapper">
                  <span class="font-sans text-stone shrink-0 flex items-center px-4 py-3 select-none"
                        style="background:#F4EFE8; border-right:1.5px solid #E0D9CE; font-size:1rem; line-height:1.65; white-space:nowrap">+92</span>
                  <input type="tel" id="phone" name="phone" required
                         value="{{ old('phone', $prefill['phone'] ?? '') }}"
                         placeholder="3XX XXXXXXX"
                         class="@error('phone') error @enderror"
                         style="flex:1; border:none; outline:none; padding:0.75rem 1rem; font-family:'DM Sans',sans-serif; font-size:1rem; color:#1A1410; background:#FFFFFF; min-width:0;">
                </div>
                <p class="font-sans text-caption text-stone mt-1.5">This is how I'll contact you about your order.</p>
                @error('phone')<p class="field-error">{{ $message }}</p>@enderror
              </div>
            </div>

            {{-- ── Section: Shipping ────────────────────────── --}}
            <div class="px-6 py-5">
              <p class="font-sans text-caption text-stone font-medium uppercase tracking-wide mb-5" style="font-size:0.7rem;letter-spacing:0.08em">Shipping address</p>

              {{-- Street address --}}
              <div class="mb-4">
                <label class="font-sans text-caption text-graphite font-medium mb-1.5 block" for="address">Street address</label>
                <input type="text" id="address" name="address" required
                       value="{{ old('address', $prefill['address'] ?? '') }}"
                       placeholder="House number, street, area"
                       class="form-input @error('address') error @enderror">
                @error('address')<p class="field-error">{{ $message }}</p>@enderror
              </div>

              {{-- City + Postal side by side --}}
              <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <div>
                  <label class="font-sans text-caption text-graphite font-medium mb-1.5 block" for="city">City</label>
                  @php
                    $cities = ['Lahore','Karachi','Islamabad','Rawalpindi','Faisalabad','Multan',
                               'Gujranwala','Sialkot','Peshawar','Quetta','Mirpur AJK','Other'];
                    $selectedCity = old('city', $prefill['city'] ?? '');
                  @endphp
                  <select id="city" name="city" required
                          class="form-input @error('city') error @enderror">
                    <option value="" disabled {{ ! $selectedCity ? 'selected' : '' }}>Select your city</option>
                    @foreach ($cities as $city)
                      <option value="{{ $city }}" {{ $selectedCity === $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                  </select>
                  @error('city')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div>
                  <label class="font-sans text-caption text-graphite font-medium mb-1.5 block" for="postal">
                    Postal code <span class="font-normal text-stone">(optional)</span>
                  </label>
                  <input type="text" id="postal" name="postal"
                         value="{{ old('postal', $prefill['postal'] ?? '') }}"
                         placeholder="e.g. 13100"
                         class="form-input @error('postal') error @enderror">
                  @error('postal')<p class="field-error">{{ $message }}</p>@enderror
                </div>
              </div>

              {{-- City other (conditional) --}}
              <div class="mb-4 @if ($selectedCity !== 'Other') hidden @endif" id="city-other-wrap">
                <label class="font-sans text-caption text-graphite font-medium mb-1.5 block" for="city_other">Enter your city</label>
                <input type="text" id="city_other" name="city_other"
                       value="{{ old('city_other', '') }}"
                       placeholder="Your city"
                       class="form-input @error('city_other') error @enderror">
                @error('city_other')<p class="field-error">{{ $message }}</p>@enderror
              </div>

              {{-- Notes --}}
              <div>
                <label class="font-sans text-caption text-graphite font-medium mb-1.5 block" for="notes">
                  Order notes <span class="font-normal text-stone">(optional)</span>
                </label>
                <textarea id="notes" name="notes" rows="3"
                          placeholder="Colour preferences, design adjustments, a special occasion date — anything I should know."
                          class="form-input resize-none @error('notes') error @enderror">{{ old('notes', $prefill['notes'] ?? '') }}</textarea>
                @error('notes')<p class="field-error">{{ $message }}</p>@enderror
              </div>
            </div>

            {{-- ── Form footer ──────────────────────────────── --}}
            <div class="px-6 pb-6">
              {{-- Mobile price summary (accordion) --}}
              <div class="lg:hidden mb-5 bg-shell border border-hairline rounded-xl overflow-hidden">
                <button type="button" id="summary-toggle"
                        class="w-full flex items-center justify-between px-5 py-3.5 font-sans font-medium text-ink bg-transparent"
                        style="font-size:0.875rem">
                  Order summary
                  <svg id="summary-chevron" class="w-4 h-4 text-stone transition-transform duration-200" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="20" stroke-linecap="round"><polyline points="64 96 128 160 192 96"/></svg>
                </button>
                <div id="summary-body" class="hidden border-t border-hairline px-5 py-4">
                  @include('order.partials.price-summary', compact('bag', 'totals', 'isReturning'))
                </div>
              </div>

              <button type="submit"
                      class="w-full bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full py-4 text-base transition-colors duration-200">
                Continue &rarr;
              </button>

              <a href="{{ route('order.start') }}" class="block text-center mt-4 font-sans text-caption text-stone hover:text-ink transition-colors duration-200">
                &larr; Back to sizing
              </a>
            </div>

          </form>
        </div>
      </div>

      {{-- ── Desktop price summary sidebar ────────────────────────────── --}}
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
  // Phone wrapper — delegate focus ring to the wrapper div
  $('#phone').on('focus', function () {
    $('#phone-wrapper').css({ 'border-color': '#BFA4CE', 'box-shadow': '0 0 0 3.5px rgba(191,164,206,0.18)' });
  }).on('blur', function () {
    $('#phone-wrapper').css({ 'border-color': '#E0D9CE', 'box-shadow': 'none' });
  });

  // City Other field toggle
  $('#city').on('change', function () {
    if ($(this).val() === 'Other') {
      $('#city-other-wrap').removeClass('hidden');
      $('#city_other').attr('required', true);
    } else {
      $('#city-other-wrap').addClass('hidden');
      $('#city_other').removeAttr('required');
    }
  });

  // Mobile summary accordion
  $('#summary-toggle').on('click', function () {
    $('#summary-body').toggleClass('hidden');
    $('#summary-chevron').toggleClass('rotate-90');
  });
});
</script>
@endpush
