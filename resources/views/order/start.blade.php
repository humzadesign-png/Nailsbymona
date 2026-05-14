@extends('layouts.order')

@section('title', 'Your Sizing — Step 1 of 3 — Nails by Mona')

@section('progress')
@include('order.partials.progress', ['currentStep' => 1])
@endsection

@push('head')
<style>
  .sizing-option { transition: border-color 0.15s ease, background-color 0.15s ease; cursor: pointer; }
  .sizing-option input[type="radio"] { display: none; }
  .sizing-option.selected { border-color: #BFA4CE; background-color: #F5F0FA; }
  .sizing-option:hover { border-color: #BFA4CE; }
  .upload-preview { display: none; }
  .upload-preview.visible { display: flex; }
</style>
@endpush

@section('content')
<section class="bg-bone py-12 md:py-16 min-h-[60vh]">
  <div class="max-w-2xl mx-auto px-6">

    {{-- Returning customer lookup --}}
    <div id="returning-card" class="mb-8 bg-lavender-wash border border-hairline rounded-xl p-5">
      <p class="font-sans font-semibold text-ink text-sm mb-3">Ordered from me before?</p>
      <div class="flex gap-2">
        <input id="lookup-input" type="text"
               placeholder="Phone number or email"
               class="flex-1 font-sans text-body text-ink bg-paper border border-hairline rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-lavender/30 focus:border-lavender transition">
        <button id="lookup-btn"
                class="bg-ink text-bone font-sans text-caption font-medium rounded-full px-5 py-2.5 hover:opacity-80 transition">
          Find my profile
        </button>
      </div>
      <div id="lookup-result" class="mt-3 hidden"></div>
    </div>

    {{-- Heading --}}
    <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Step 1 of 3</p>
    <h1 class="font-serif text-ink mb-2" style="font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30;line-height:1.2">
      How would you like to share your sizing?
    </h1>
    <div class="h-0.5 w-10 bg-lavender mb-8"></div>

    {{-- Sizing options --}}
    <form id="sizing-form" action="{{ route('order.start.sizing') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="grid gap-4 mb-6">

        {{-- Option A: Live camera --}}
        <label class="sizing-option border-2 border-hairline rounded-2xl p-5 block selected" data-value="live_camera">
          <input type="radio" name="sizing_method" value="live_camera" checked>
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 shrink-0 text-lavender mt-0.5">
              <svg viewBox="0 0 256 256" fill="currentColor" class="w-10 h-10"><path d="M208,56H180.28L166.65,35.56A8,8,0,0,0,160,32H96a8,8,0,0,0-6.65,3.56L75.72,56H48A24,24,0,0,0,24,80V192a24,24,0,0,0,24,24H208a24,24,0,0,0,24-24V80A24,24,0,0,0,208,56Zm8,136a8,8,0,0,1-8,8H48a8,8,0,0,1-8-8V80a8,8,0,0,1,8-8H80a8,8,0,0,0,6.66-3.56L100.28,48h55.44l13.62,20.44A8,8,0,0,0,176,72h32a8,8,0,0,1,8,8ZM128,88a44,44,0,1,0,44,44A44.05,44.05,0,0,0,128,88Zm0,72a28,28,0,1,1,28-28A28,28,0,0,1,128,160Z"/></svg>
            </div>
            <div class="flex-1">
              <p class="font-sans font-semibold text-ink mb-1">Take a photo with my guide <span class="font-normal text-lavender text-caption">(Recommended)</span></p>
              <p class="font-sans text-body text-stone leading-relaxed">I'll show you exactly where to position your hand and coin — on your screen, in real time. Takes about 90 seconds.</p>
              {{-- Desktop-only note: shown when JS detects a non-mobile device --}}
              <div id="camera-desktop-note" class="hidden mt-2 items-start gap-1.5">
                <svg class="w-3.5 h-3.5 text-warning shrink-0 mt-0.5" viewBox="0 0 256 256" fill="currentColor"><path d="M236.8,188.09,149.35,36.22a24.76,24.76,0,0,0-42.7,0L19.2,188.09a23.51,23.51,0,0,0,0,23.72A24.35,24.35,0,0,0,40.55,224h174.9a24.35,24.35,0,0,0,21.33-12.19A23.51,23.51,0,0,0,236.8,188.09ZM120,104a8,8,0,0,1,16,0v40a8,8,0,0,1-16,0Zm8,88a12,12,0,1,1,12-12A12,12,0,0,1,128,192Z"/></svg>
                <p class="font-sans text-[11px] text-warning leading-snug">On a laptop or desktop? We'll show you a QR code — scan it to open the camera guide on your phone instead.</p>
              </div>
            </div>
            <div class="w-5 h-5 rounded-full border-2 border-lavender bg-lavender shrink-0 mt-0.5 flex items-center justify-center option-check">
              <svg class="w-3 h-3 text-white" viewBox="0 0 256 256" fill="currentColor"><polyline points="40 144 96 200 224 72" stroke="currentColor" stroke-width="24" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>
            </div>
          </div>
        </label>

        {{-- Option B: Upload --}}
        <label class="sizing-option border-2 border-hairline rounded-2xl p-5 block" data-value="upload">
          <input type="radio" name="sizing_method" value="upload">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 shrink-0 text-stone mt-0.5">
              <svg viewBox="0 0 256 256" fill="currentColor" class="w-10 h-10"><path d="M240,136v64a16,16,0,0,1-16,16H32a16,16,0,0,1-16-16V136a16,16,0,0,1,16-16H80a8,8,0,0,1,0,16H32v64H224V136H176a8,8,0,0,1,0-16h48A16,16,0,0,1,240,136ZM85.66,77.66,120,43.31V128a8,8,0,0,0,16,0V43.31l34.34,34.35a8,8,0,0,0,11.32-11.32l-48-48a8,8,0,0,0-11.32,0l-48,48A8,8,0,0,0,85.66,77.66Z"/></svg>
            </div>
            <div class="flex-1">
              <p class="font-sans font-semibold text-ink mb-1">Upload a photo from my gallery</p>
              <p class="font-sans text-body text-stone leading-relaxed">Already have your 2 sizing photos (fingers + thumb, with a coin in each)? Upload them here.</p>

              {{-- File inputs — visible when this option is selected --}}
              <div class="upload-inputs mt-4 {{ $errors->hasAny(['photo_fingers','photo_thumb']) ? '' : 'hidden' }}">
                <div class="mb-3">
                  <label class="font-sans text-caption text-graphite mb-1.5 block font-medium">Fingers photo</label>
                  <div id="fingers-wrap" class="rounded-xl p-1 transition {{ $errors->has('photo_fingers') ? 'bg-red-50 border border-red-300' : '' }}">
                    <input type="file" id="upload-fingers" name="photo_fingers" accept="image/*"
                           class="block w-full font-sans text-caption text-stone file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-lavender-wash file:text-lavender-ink file:font-medium hover:file:bg-lavender/10 transition">
                  </div>
                  <p id="fingers-error" class="font-sans text-caption text-red-600 mt-1 {{ $errors->has('photo_fingers') ? '' : 'hidden' }}">
                    {{ $errors->first('photo_fingers') ?: 'Please select your fingers photo.' }}
                  </p>
                </div>
                <div>
                  <label class="font-sans text-caption text-graphite mb-1.5 block font-medium">Thumb photo</label>
                  <div id="thumb-wrap" class="rounded-xl p-1 transition {{ $errors->has('photo_thumb') ? 'bg-red-50 border border-red-300' : '' }}">
                    <input type="file" id="upload-thumb" name="photo_thumb" accept="image/*"
                           class="block w-full font-sans text-caption text-stone file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-lavender-wash file:text-lavender-ink file:font-medium hover:file:bg-lavender/10 transition">
                  </div>
                  <p id="thumb-error" class="font-sans text-caption text-red-600 mt-1 {{ $errors->has('photo_thumb') ? '' : 'hidden' }}">
                    {{ $errors->first('photo_thumb') ?: 'Please select your thumb photo.' }}
                  </p>
                </div>
                <p class="font-sans text-caption text-stone mt-3">Make sure: whole hand visible · coin present · shot straight down · good lighting.</p>
              </div>
            </div>
            <div class="w-5 h-5 rounded-full border-2 border-hairline bg-paper shrink-0 mt-0.5 option-check"></div>
          </div>
        </label>

        {{-- Option C: WhatsApp --}}
        <label class="sizing-option border-2 border-hairline rounded-2xl p-5 block" data-value="whatsapp_pending">
          <input type="radio" name="sizing_method" value="whatsapp_pending">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 shrink-0 text-stone mt-0.5">
              <svg viewBox="0 0 256 256" fill="currentColor" class="w-10 h-10"><path d="M187.58,144.84l-32-16a8,8,0,0,0-8,.5l-14.69,9.8a40.55,40.55,0,0,1-16-16l9.8-14.69a8,8,0,0,0,.5-8l-16-32A8,8,0,0,0,104,64a40,40,0,0,0-40,40,88.1,88.1,0,0,0,88,88,40,40,0,0,0,40-40A8,8,0,0,0,187.58,144.84ZM152,176a72.08,72.08,0,0,1-72-72,24,24,0,0,1,19.29-23.54l11.48,22.94L101,117.64a8,8,0,0,0-.73,7.65,56.42,56.42,0,0,0,30.42,30.42,8,8,0,0,0,7.65-.73l14.3-9.55,22.94,11.48A24,24,0,0,1,152,176ZM128,24A104,104,0,0,0,36.18,176.88L24.83,210.93a16,16,0,0,0,20.24,20.24l34.05-11.35A104,104,0,1,0,128,24Zm0,192a88.11,88.11,0,0,1-44.06-11.81,8,8,0,0,0-6.54-.67L40,216l12.47-37.4a8,8,0,0,0-.66-6.54A88,88,0,1,1,128,216Z"/></svg>
            </div>
            <div class="flex-1">
              <p class="font-sans font-semibold text-ink mb-1">I'll send it on WhatsApp</p>
              <p class="font-sans text-body text-stone leading-relaxed">Not ready right now? Place your order and send your 2 sizing photos via WhatsApp — I'll measure from there before I start making.</p>
            </div>
            <div class="w-5 h-5 rounded-full border-2 border-hairline bg-paper shrink-0 mt-0.5 option-check"></div>
          </div>
        </label>

      </div>

      {{-- Size guide link --}}
      <p class="font-sans text-caption text-stone text-center mb-8">
        Not sure how to take the photo?
        <a href="{{ route('size-guide') }}" class="text-lavender-ink hover:text-lavender underline-offset-2 hover:underline">See the size guide</a>
      </p>

      {{-- Continue button --}}
      <button type="submit" id="continue-btn"
              class="w-full bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full py-4 text-base transition-colors duration-200">
        Continue &rarr;
      </button>

    </form>

  </div>
</section>
@endsection

@push('scripts')
<script>
$(function () {

  // ── Desktop detection: show note on camera option ────────────────────────
  function isDesktopDevice() {
    var mobileUA = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    var hasTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 1);
    var isNarrow = window.innerWidth < 1024;
    return !mobileUA && (!hasTouch || !isNarrow);
  }
  if (isDesktopDevice()) {
    $('#camera-desktop-note').removeClass('hidden').css('display', 'flex');
  }

  // ── Sizing option selection ────────────────────────────────────────────────
  // Use 'change' on the radio inputs, not 'click' on the labels.
  // Labels wrapping radios fire click twice (user click + browser's synthetic
  // click dispatched to the radio, which bubbles back up). 'change' fires once.
  function selectOption($radio) {
    const val     = $radio.val();
    const $option = $radio.closest('.sizing-option');

    // Reset all cards
    $('.sizing-option').removeClass('selected');
    $('.option-check')
      .removeClass('bg-lavender border-lavender flex items-center justify-center')
      .addClass('bg-paper border-hairline')
      .empty();

    // Activate the chosen card
    $option.addClass('selected');
    $option.find('.option-check')
      .removeClass('bg-paper border-hairline')
      .addClass('bg-lavender border-lavender flex items-center justify-center')
      .html('<svg class="w-3 h-3 text-white" viewBox="0 0 256 256" fill="currentColor"><polyline points="40 144 96 200 224 72" stroke="currentColor" stroke-width="24" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>');

    // Show file inputs only for the upload option
    $('.upload-inputs').addClass('hidden');
    if (val === 'upload') {
      $option.find('.upload-inputs').removeClass('hidden');
    }
  }

  // Bind change (fires once per actual selection change)
  $('input[name="sizing_method"]').on('change', function () {
    selectOption($(this));
  });

  // Also handle clicks directly on the label card area so tapping the card
  // text/icon (not just the hidden radio) still triggers selection.
  $('.sizing-option').on('click', function (e) {
    // Skip if the event originated from the radio itself (avoid double-fire)
    if ($(e.target).is('input[type="radio"]')) return;
    const $radio = $(this).find('input[type="radio"]');
    $radio.prop('checked', true).trigger('change');
  });

  // ── Auto-select upload card on validation error ──────────────────────────
  @if($errors->hasAny(['photo_fingers','photo_thumb']))
  var $uploadRadio = $('input[name="sizing_method"][value="upload"]');
  $uploadRadio.prop('checked', true);
  selectOption($uploadRadio);
  $('html, body').animate({ scrollTop: $uploadRadio.closest('.sizing-option').offset().top - 80 }, 200);
  @endif

  // Guard: prevent submit when "upload" is selected but files are missing — show inline errors
  $('#sizing-form').on('submit', function (e) {
    const method = $('input[name="sizing_method"]:checked').val();
    if (method !== 'upload') return;

    const noFingers = ! $('#upload-fingers')[0].files.length;
    const noThumb   = ! $('#upload-thumb')[0].files.length;

    if (noFingers || noThumb) {
      e.preventDefault();
      if (noFingers) {
        $('#fingers-wrap').addClass('bg-red-50 border border-red-300');
        $('#fingers-error').removeClass('hidden');
      }
      if (noThumb) {
        $('#thumb-wrap').addClass('bg-red-50 border border-red-300');
        $('#thumb-error').removeClass('hidden');
      }
      $('html, body').animate({ scrollTop: $('#fingers-wrap').offset().top - 100 }, 200);
      return false;
    }
  });

  // Clear inline errors when a file is chosen
  $('#upload-fingers').on('change', function () {
    if (this.files.length) {
      $('#fingers-wrap').removeClass('bg-red-50 border border-red-300');
      $('#fingers-error').addClass('hidden');
    }
  });
  $('#upload-thumb').on('change', function () {
    if (this.files.length) {
      $('#thumb-wrap').removeClass('bg-red-50 border border-red-300');
      $('#thumb-error').addClass('hidden');
    }
  });

  // Returning-customer lookup
  $('#lookup-btn').on('click', function () {
    const contact = $('#lookup-input').val().trim();
    if (! contact) return;

    $(this).text('Checking…').prop('disabled', true);

    $.post('{{ route("order.start.lookup") }}', {
      _token: '{{ csrf_token() }}',
      contact: contact,
    })
    .done(function (data) {
      if (data.found) {
        $('#lookup-result').removeClass('hidden').html(
          '<div class="bg-green-50 border border-green-200 rounded-lg px-4 py-3 font-sans text-caption text-green-800">' +
          '✓ Welcome back, ' + $('<div>').text(data.name).html() + '! ' +
          (data.last_order ? 'Last order: ' + data.last_order + '. ' : '') +
          'Your sizing is on file — you can skip straight to your details.' +
          '</div>'
        );
        // Hide sizing options — not needed for returning customers
        $('#sizing-form .grid').addClass('opacity-40 pointer-events-none');
      } else {
        $('#lookup-result').removeClass('hidden').html(
          '<p class="font-sans text-caption text-stone">No saved profile found — please choose a sizing option below.</p>'
        );
      }
    })
    .fail(function () {
      $('#lookup-result').removeClass('hidden').html(
        '<p class="font-sans text-caption text-stone">Could not check right now — please choose a sizing option below.</p>'
      );
    })
    .always(function () {
      $('#lookup-btn').text('Find my profile').prop('disabled', false);
    });
  });
});
</script>
@endpush
