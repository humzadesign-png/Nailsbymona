@extends('layouts.order')

@section('title', 'Sizing Capture — Nails by Mona')

@push('head')
<style>
  #camera-video { width: 100%; height: 100%; object-fit: cover; display: block; }

  /* SVG overlay */
  #overlay-svg { position: absolute; inset: 0; pointer-events: none; }

  /* Alignment border */
  #alignment-border {
    position: absolute; inset: 0;
    border: 3px solid transparent;
    border-radius: 1rem;
    transition: border-color 0.3s;
    pointer-events: none;
  }
  #alignment-border.green  { border-color: #22c55e; }
  #alignment-border.amber  { border-color: #f59e0b; }
  #alignment-border.red    { border-color: #ef4444; }

  /* Shutter pulse */
  @keyframes pulse-ring {
    0%   { box-shadow: 0 0 0 0 rgba(191,164,206,0.6); }
    70%  { box-shadow: 0 0 0 14px rgba(191,164,206,0); }
    100% { box-shadow: 0 0 0 0 rgba(191,164,206,0); }
  }
  #shutter-btn.pulse-ring { animation: pulse-ring 1.2s infinite; }

  /* Thumbnail retake */
  .thumb-wrap { position: relative; }
  .thumb-retake {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: rgba(28,23,39,0.75);
    color: #FBF8F2;
    font-size: 0.7rem;
    text-align: center;
    padding: 0.3rem 0;
    cursor: pointer;
  }

  /* State transitions */
  .state { display: none; }
  .state.active { display: block; }
</style>
@endpush

@section('content')
<div class="bg-bone min-h-[80vh] py-10 md:py-14">
  <div class="max-w-xl mx-auto px-6">

    {{-- ── State Desktop: laptop/desktop handoff ────────────────────────── --}}
    {{-- Shown automatically when a non-touch device is detected by JS       --}}
    {{-- Camera must be on a mobile phone (rear camera, overhead angle).      --}}
    <div id="state-desktop" class="state">
      <div class="py-10 text-center">

        {{-- Phone icon --}}
        <div class="w-20 h-20 mx-auto mb-8 rounded-full bg-lavender-wash flex items-center justify-center">
          <svg class="w-9 h-9 text-lavender" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="12" stroke-linecap="round" stroke-linejoin="round">
            <rect x="64" y="16" width="128" height="224" rx="16"/>
            <line x1="96" y1="196" x2="160" y2="196"/>
          </svg>
        </div>

        <h1 class="font-serif text-ink mb-4" style="font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30;line-height:1.2">
          Open this on your phone.
        </h1>
        <div class="h-0.5 w-10 bg-lavender mx-auto mb-6"></div>
        <p class="font-sans text-body text-stone mb-10 max-w-sm mx-auto leading-relaxed">
          Your laptop camera faces you — to photograph your nails close up you need your phone's rear camera. Scan the QR code below to open this page on your phone.
        </p>

        {{-- QR Code card (generated client-side — no external API) --}}
        <div class="bg-white border border-hairline rounded-2xl p-8 inline-block mb-2">
          <div id="desktop-qr-div" class="w-[200px] h-[200px] mx-auto overflow-hidden flex items-center justify-center">
            <span class="font-sans text-caption text-ash">Generating…</span>
          </div>
          <p class="font-sans text-caption text-stone mt-4 text-center">Point your phone camera at this code</p>
        </div>
        <p class="font-sans text-[11px] text-ash mb-8">Opens on your phone — no app needed</p>

        {{-- Share alternatives --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center mb-10">
          <a id="desktop-wa-link" href="#" target="_blank" rel="noopener"
             class="inline-flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#1fb558] text-white font-sans text-caption font-medium rounded-full px-6 py-3 transition-colors duration-200">
            <svg class="w-4 h-4 shrink-0" viewBox="0 0 256 256" fill="currentColor"><path d="M187.58,144.84l-32-16a8,8,0,0,0-8,.5l-14.69,9.8a40.55,40.55,0,0,1-16-16l9.8-14.69a8,8,0,0,0,.5-8l-16-32A8,8,0,0,0,104,64a40,40,0,0,0-40,40,88.1,88.1,0,0,0,88,88,40,40,0,0,0,40-40A8,8,0,0,0,187.58,144.84ZM152,176a72.08,72.08,0,0,1-72-72,24,24,0,0,1,19.29-23.54l11.48,22.94L101,117.64a8,8,0,0,0-.73,7.65,56.42,56.42,0,0,0,30.42,30.42,8,8,0,0,0,7.65-.73l14.3-9.55,22.94,11.48A24,24,0,0,1,152,176ZM128,24A104,104,0,0,0,36.18,176.88L24.83,210.93a16,16,0,0,0,20.24,20.24l34.05-11.35A104,104,0,1,0,128,24Zm0,192a88.11,88.11,0,0,1-44.06-11.81,8,8,0,0,0-6.54-.67L40,216l12.47-37.4a8,8,0,0,0-.66-6.54A88,88,0,1,1,128,216Z"/></svg>
            Send link to my WhatsApp
          </a>
          <button id="desktop-copy-link"
                  class="inline-flex items-center justify-center gap-2 border border-hairline bg-paper hover:border-lavender text-graphite font-sans text-caption font-medium rounded-full px-6 py-3 transition-colors duration-200">
            <svg class="w-4 h-4 shrink-0" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="14" stroke-linecap="round" stroke-linejoin="round"><rect x="40" y="88" width="128" height="128" rx="8"/><path d="M88,88V56a8,8,0,0,1,8-8H200a8,8,0,0,1,8,8V168a8,8,0,0,1-8,8H168"/></svg>
            <span id="copy-btn-text">Copy link</span>
          </button>
        </div>

        {{-- Upload fallback --}}
        <div class="border-t border-hairline pt-8">
          <p class="font-sans text-caption text-stone mb-3">Already have your sizing photos saved on this device?</p>
          <button id="desktop-upload-btn"
                  class="font-sans text-caption text-lavender-ink hover:text-lavender underline underline-offset-4 transition-colors duration-200">
            Upload photos from this computer instead →
          </button>
          <p class="font-sans text-[11px] text-ash mt-5">
            On a phone but seeing this screen?
            <button id="desktop-continue-anyway" class="underline underline-offset-2 hover:text-stone transition-colors">Use this device's camera →</button>
          </p>
        </div>

      </div>
    </div>{{-- /state-desktop --}}


    {{-- ── State A: Explainer ──────────────────────────────────────────── --}}
    <div id="state-explainer" class="state active">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Sizing capture</p>
      <h1 class="font-serif text-ink mb-2" style="font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30;line-height:1.2">
        Let's get your perfect fit.
      </h1>
      <div class="h-0.5 w-10 bg-lavender mb-6"></div>
      <p class="font-sans text-body text-graphite mb-8 leading-relaxed">
        We'll take two close-up photos — your <strong>fingers</strong> and your <strong>thumb</strong> — held next to a coin. This takes about 90 seconds.
      </p>

      {{-- Steps preview --}}
      <div class="grid gap-4 mb-8">
        <div class="flex items-start gap-4 bg-paper border border-hairline rounded-2xl px-5 py-4">
          <span class="font-serif text-display text-lavender shrink-0 leading-none" style="font-size:1.5rem">01</span>
          <div>
            <p class="font-sans font-semibold text-ink mb-0.5">Hold any coin above your fingers</p>
            <p class="font-sans text-caption text-stone">Lay your 4 fingers flat, place a coin above your middle finger, and shoot straight down.</p>
          </div>
        </div>
        <div class="flex items-start gap-4 bg-paper border border-hairline rounded-2xl px-5 py-4">
          <span class="font-serif text-display text-lavender shrink-0 leading-none" style="font-size:1.5rem">02</span>
          <div>
            <p class="font-sans font-semibold text-ink mb-0.5">Then your thumb</p>
            <p class="font-sans text-caption text-stone">Extend your thumb, coin above the thumbnail, same straight-down angle.</p>
          </div>
        </div>
        <div class="flex items-start gap-4 bg-paper border border-hairline rounded-2xl px-5 py-4">
          <span class="font-serif text-display text-lavender shrink-0 leading-none" style="font-size:1.5rem">03</span>
          <div>
            <p class="font-sans font-semibold text-ink mb-0.5">Review + submit</p>
            <p class="font-sans text-caption text-stone">We'll show you both photos before sending. You can retake either one.</p>
          </div>
        </div>
      </div>

      <button id="start-camera-btn"
              class="w-full bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full py-4 text-base transition-colors duration-200 flex items-center justify-center gap-2">
        <svg class="w-5 h-5" viewBox="0 0 256 256" fill="currentColor"><path d="M208,56H180.28L166.65,35.56A8,8,0,0,0,160,32H96a8,8,0,0,0-6.65,3.56L75.72,56H48A24,24,0,0,0,24,80V192a24,24,0,0,0,24,24H208a24,24,0,0,0,24-24V80A24,24,0,0,0,208,56Zm8,136a8,8,0,0,1-8,8H48a8,8,0,0,1-8-8V80a8,8,0,0,1,8-8H80a8,8,0,0,0,6.66-3.56L100.28,48h55.44l13.62,20.44A8,8,0,0,0,176,72h32a8,8,0,0,1,8,8ZM128,88a44,44,0,1,0,44,44A44.05,44.05,0,0,0,128,88Zm0,72a28,28,0,1,1,28-28A28,28,0,0,1,128,160Z"/></svg>
        Start camera
      </button>

      <p class="font-sans text-caption text-stone text-center mt-3">
        Camera not available?
        <button id="use-upload-btn" class="text-lavender-ink underline-offset-2 hover:underline ml-1">Upload photos instead</button>
      </p>
    </div>

    {{-- ── State B/C: Camera capture ───────────────────────────────────── --}}
    <div id="state-camera" class="state">

      {{-- Photo title strip --}}
      <div class="flex items-center justify-between mb-4">
        <div>
          <p id="photo-label" class="font-sans font-semibold text-ink text-sm">Photo 1 of 2 — Your fingers</p>
          <p id="photo-sublabel" class="font-sans text-caption text-stone">Lay fingers flat, coin above middle finger, shoot straight down.</p>
        </div>
        {{-- Progress dots --}}
        <div class="flex gap-2 shrink-0">
          <span id="dot-1" class="w-2 h-2 rounded-full bg-lavender"></span>
          <span id="dot-2" class="w-2 h-2 rounded-full bg-ash"></span>
          <span id="dot-opt-1" class="w-2 h-2 rounded-full bg-ash hidden"></span>
          <span id="dot-opt-2" class="w-2 h-2 rounded-full bg-ash hidden"></span>
        </div>
      </div>

      {{-- Viewfinder --}}
      <div class="relative rounded-2xl overflow-hidden bg-ink aspect-[4/3] mb-4">
        <video id="camera-video" autoplay playsinline muted></video>
        <canvas id="brightness-canvas" class="hidden" width="80" height="60"></canvas>

        {{-- SVG overlay — U-shaped finger/thumb guides matching sizing-fingers.svg and sizing-thumb.svg --}}
        <svg id="overlay-svg" viewBox="0 0 400 305" xmlns="http://www.w3.org/2000/svg" fill="none">

          {{-- Fingers overlay (shown for photo 1 and 3) --}}
          {{-- Layout: pinky (shortest/left) → ring → middle (tallest, coin above) → index --}}
          <g id="overlay-fingers">
            {{-- Coin above middle finger (cx=234 = center of middle finger at x=206, w=55) --}}
            <circle cx="234" cy="72" r="22" stroke="#BFA4CE" stroke-width="2" stroke-dasharray="6 4" opacity="0.9"/>
            <text x="234" y="78" text-anchor="middle" font-size="13" fill="#BFA4CE" opacity="0.9" font-family="DM Sans, sans-serif">₨</text>
            <text x="234" y="100" text-anchor="middle" font-size="10" fill="#BFA4CE" opacity="0.75" font-family="DM Sans, sans-serif" letter-spacing="0.5">Place coin here</text>

            {{-- Pinky — leftmost, notably shortest (~55% of middle). U-shape open at bottom. --}}
            <path d="M72,305 L72,234 Q72,206 99,206 Q127,206 127,234 L127,305"
                  stroke="#BFA4CE" stroke-width="2" stroke-dasharray="7 5" stroke-linecap="round" opacity="0.85"/>
            {{-- Ring finger (~82% of middle) --}}
            <path d="M139,305 L139,185 Q139,157 167,157 Q195,157 195,185 L195,305"
                  stroke="#BFA4CE" stroke-width="2" stroke-dasharray="7 5" stroke-linecap="round" opacity="0.85"/>
            {{-- Middle finger — tallest, coin sits above it --}}
            <path d="M206,305 L206,153 Q206,125 234,125 Q262,125 262,153 L262,305"
                  stroke="#BFA4CE" stroke-width="2" stroke-dasharray="7 5" stroke-linecap="round" opacity="0.85"/>
            {{-- Index finger (~79% of middle) --}}
            <path d="M273,305 L273,191 Q273,163 301,163 Q329,163 329,191 L329,305"
                  stroke="#BFA4CE" stroke-width="2" stroke-dasharray="7 5" stroke-linecap="round" opacity="0.85"/>
          </g>

          {{-- Thumb overlay (shown for photo 2 and 4) --}}
          {{-- Wider U-shape, coin above thumbnail, open at bottom --}}
          <g id="overlay-thumb" style="display:none">
            {{-- Coin above thumbnail --}}
            <circle cx="200" cy="52" r="22" stroke="#BFA4CE" stroke-width="2" stroke-dasharray="6 4" opacity="0.9"/>
            <text x="200" y="58" text-anchor="middle" font-size="13" fill="#BFA4CE" opacity="0.9" font-family="DM Sans, sans-serif">₨</text>
            <text x="200" y="84" text-anchor="middle" font-size="10" fill="#BFA4CE" opacity="0.75" font-family="DM Sans, sans-serif" letter-spacing="0.5">Place coin here</text>

            {{-- Thumb — wider than a finger (90px), semicircle top, open at bottom --}}
            <path d="M155,305 L155,140 Q155,95 200,95 Q245,95 245,140 L245,305"
                  stroke="#BFA4CE" stroke-width="2" stroke-dasharray="7 5" stroke-linecap="round" opacity="0.85"/>
          </g>
          {{-- Corner guides --}}
          <line x1="20" y1="20" x2="40" y2="20" stroke="#BFA4CE" stroke-width="1.5" opacity="0.4"/>
          <line x1="20" y1="20" x2="20" y2="40" stroke="#BFA4CE" stroke-width="1.5" opacity="0.4"/>
          <line x1="380" y1="20" x2="360" y2="20" stroke="#BFA4CE" stroke-width="1.5" opacity="0.4"/>
          <line x1="380" y1="20" x2="380" y2="40" stroke="#BFA4CE" stroke-width="1.5" opacity="0.4"/>
          <line x1="20" y1="280" x2="40" y2="280" stroke="#BFA4CE" stroke-width="1.5" opacity="0.4"/>
          <line x1="20" y1="280" x2="20" y2="260" stroke="#BFA4CE" stroke-width="1.5" opacity="0.4"/>
          <line x1="380" y1="280" x2="360" y2="280" stroke="#BFA4CE" stroke-width="1.5" opacity="0.4"/>
          <line x1="380" y1="280" x2="380" y2="260" stroke="#BFA4CE" stroke-width="1.5" opacity="0.4"/>
        </svg>

        {{-- Alignment border --}}
        <div id="alignment-border"></div>

        {{-- Brightness pill --}}
        <div id="brightness-pill"
             class="absolute top-3 left-1/2 -translate-x-1/2 flex items-center gap-1.5 px-3 py-1 rounded-full bg-ink/60 backdrop-blur-sm">
          <span id="brightness-dot" class="w-2 h-2 rounded-full bg-stone"></span>
          <span id="brightness-label" class="font-sans text-white text-xs">Checking…</span>
        </div>
      </div>

      {{-- Shutter --}}
      <div class="flex justify-center mb-6">
        <button id="shutter-btn"
                class="w-16 h-16 rounded-full bg-white border-4 border-lavender flex items-center justify-center transition-all duration-200 hover:bg-lavender-wash"
                aria-label="Take photo">
          <div class="w-10 h-10 rounded-full bg-lavender"></div>
        </button>
      </div>

      <p class="font-sans text-caption text-stone text-center">
        Tap when your hand fills the guide and the border turns green.
      </p>
    </div>

    {{-- ── State D: Preview ────────────────────────────────────────────── --}}
    <div id="state-preview" class="state">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Review your photos</p>
      <h2 class="font-serif text-ink mb-2" style="font-size:clamp(1.4rem,3vw,2rem);font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30">
        Looking good?
      </h2>
      <div class="h-0.5 w-10 bg-lavender mb-6"></div>

      {{-- Thumbnails grid --}}
      <div id="preview-grid" class="grid grid-cols-2 gap-4 mb-6">
        <div class="thumb-wrap rounded-xl overflow-hidden bg-shell aspect-square">
          <img id="thumb-fingers" src="" alt="Fingers photo" class="w-full h-full object-cover">
          <div class="thumb-retake" data-photo="fingers">↺ Retake</div>
        </div>
        <div class="thumb-wrap rounded-xl overflow-hidden bg-shell aspect-square">
          <img id="thumb-thumb" src="" alt="Thumb photo" class="w-full h-full object-cover">
          <div class="thumb-retake" data-photo="thumb">↺ Retake</div>
        </div>
        {{-- Optional: other hand --}}
        <div id="thumb-other-fingers-wrap" class="thumb-wrap rounded-xl overflow-hidden bg-shell aspect-square hidden">
          <img id="thumb-other-fingers" src="" alt="Other hand fingers photo" class="w-full h-full object-cover">
          <div class="thumb-retake" data-photo="fingers_other">↺ Retake</div>
        </div>
        <div id="thumb-other-thumb-wrap" class="thumb-wrap rounded-xl overflow-hidden bg-shell aspect-square hidden">
          <img id="thumb-other-thumb" src="" alt="Other hand thumb photo" class="w-full h-full object-cover">
          <div class="thumb-retake" data-photo="thumb_other">↺ Retake</div>
        </div>
      </div>

      {{-- Symmetry disclaimer --}}
      <div id="symmetry-notice" class="mb-6 bg-paper border border-hairline rounded-2xl px-5 py-4">
        <p class="font-sans text-caption text-graphite leading-relaxed">
          <strong>About your other hand:</strong> We size both hands from these photos. Most hands are symmetric to within half a millimetre — well within press-on fit tolerance. If you'd like a perfect fit, you can optionally add 2 more photos for your other hand. Or, our free first refit covers the rare outlier.
        </p>
        <button id="add-other-hand-btn"
                class="mt-3 font-sans text-caption text-lavender-ink hover:text-lavender font-medium flex items-center gap-1.5 transition-colors duration-200">
          Add photos for my other hand →
        </button>
      </div>

      {{-- Submit --}}
      <button id="submit-sizing-btn"
              class="w-full bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full py-4 text-base transition-colors duration-200">
        Submit my sizing →
      </button>
      <p class="font-sans text-caption text-stone text-center mt-3">
        Photos are only used to measure your nails. They're never shared publicly.
      </p>
    </div>

    {{-- ── Fallback: File upload ────────────────────────────────────────── --}}
    <div id="state-upload" class="state">
      <p class="font-sans text-eyebrow text-lavender uppercase mb-3">Upload your photos</p>
      <h1 class="font-serif text-ink mb-2" style="font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:300;font-variation-settings:'opsz' 144,'SOFT' 30;line-height:1.2">
        Upload your sizing photos.
      </h1>
      <div class="h-0.5 w-10 bg-lavender mb-6"></div>
      <p class="font-sans text-body text-graphite mb-6 leading-relaxed">
        Take 2 close-up photos — your fingers (4 laid flat with a coin above your middle finger) and your thumb (extended, coin above the thumbnail) — then upload them here.
      </p>

      <div class="bg-paper border border-hairline rounded-2xl p-6 mb-6">
        <div class="mb-4">
          <label class="font-sans text-caption text-graphite font-medium mb-1.5 block">Photo 1 — Fingers</label>
          <input type="file" id="upload-fingers" name="photo_fingers" accept="image/*"
                 class="block w-full font-sans text-caption text-stone file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-lavender-wash file:text-lavender-ink file:font-medium hover:file:bg-lavender/10 transition">
          <p class="font-sans text-caption text-stone mt-1">Fingers laid flat · coin above middle finger · shoot straight down · good light.</p>
        </div>
        <div>
          <label class="font-sans text-caption text-graphite font-medium mb-1.5 block">Photo 2 — Thumb</label>
          <input type="file" id="upload-thumb" name="photo_thumb" accept="image/*"
                 class="block w-full font-sans text-caption text-stone file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-lavender-wash file:text-lavender-ink file:font-medium hover:file:bg-lavender/10 transition">
          <p class="font-sans text-caption text-stone mt-1">Thumb extended · coin above thumbnail · same angle.</p>
        </div>

        {{-- Other hand (collapsible) --}}
        <div class="mt-4 pt-4 border-t border-hairline">
          <button type="button" id="upload-other-hand-toggle"
                  class="font-sans text-caption text-lavender-ink hover:text-lavender font-medium flex items-center gap-1.5 transition-colors duration-200">
            Add photos for my other hand (optional) →
          </button>
          <div id="upload-other-hand-fields" class="hidden mt-4 space-y-4">
            <div>
              <label class="font-sans text-caption text-graphite font-medium mb-1.5 block">Photo 3 — Other hand fingers</label>
              <input type="file" id="upload-fingers-other" name="photo_fingers_other" accept="image/*"
                     class="block w-full font-sans text-caption text-stone file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-lavender-wash file:text-lavender-ink file:font-medium hover:file:bg-lavender/10 transition">
            </div>
            <div>
              <label class="font-sans text-caption text-graphite font-medium mb-1.5 block">Photo 4 — Other hand thumb</label>
              <input type="file" id="upload-thumb-other" name="photo_thumb_other" accept="image/*"
                     class="block w-full font-sans text-caption text-stone file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-lavender-wash file:text-lavender-ink file:font-medium hover:file:bg-lavender/10 transition">
            </div>
          </div>
        </div>
      </div>

      {{-- Good/bad examples --}}
      <div class="mb-6">
        <p class="font-sans text-caption text-graphite font-medium mb-3">Good examples:</p>
        <div class="grid grid-cols-2 gap-2">
          <div class="bg-shell rounded-xl aspect-square flex items-center justify-center border border-green-200">
            <div class="text-center">
              <svg class="w-8 h-8 text-green-600 mx-auto mb-1" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
              <p class="font-sans text-xs text-green-700">Fingers flat · coin visible · straight down</p>
            </div>
          </div>
          <div class="bg-shell rounded-xl aspect-square flex items-center justify-center border border-green-200">
            <div class="text-center">
              <svg class="w-8 h-8 text-green-600 mx-auto mb-1" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="16" stroke-linecap="round" stroke-linejoin="round"><polyline points="40 144 96 200 224 72"/></svg>
              <p class="font-sans text-xs text-green-700">Thumb extended · coin above nail</p>
            </div>
          </div>
          <div class="bg-shell rounded-xl aspect-square flex items-center justify-center border border-red-200">
            <div class="text-center">
              <svg class="w-8 h-8 text-red-500 mx-auto mb-1" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="20" stroke-linecap="round"><line x1="60" y1="60" x2="196" y2="196"/><line x1="196" y1="60" x2="60" y2="196"/></svg>
              <p class="font-sans text-xs text-red-600">Angled · fingers curled · no coin</p>
            </div>
          </div>
          <div class="bg-shell rounded-xl aspect-square flex items-center justify-center border border-red-200">
            <div class="text-center">
              <svg class="w-8 h-8 text-red-500 mx-auto mb-1" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="20" stroke-linecap="round"><line x1="60" y1="60" x2="196" y2="196"/><line x1="196" y1="60" x2="60" y2="196"/></svg>
              <p class="font-sans text-xs text-red-600">Too dark · coin missing · blurry</p>
            </div>
          </div>
        </div>
      </div>

      <button id="upload-submit-btn"
              class="w-full bg-lavender hover:bg-lavender-dark text-white font-sans font-medium tracking-wide rounded-full py-4 text-base transition-colors duration-200">
        Submit photos →
      </button>

      <button id="try-camera-again-btn"
              class="block w-full text-center mt-3 font-sans text-caption text-stone hover:text-ink transition-colors duration-200">
        ← Try camera instead
      </button>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="{{ asset('js/camera-capture.js') }}"></script>
<script>
$(function () {

  // ── Desktop detection ─────────────────────────────────────────────────────
  function isDesktopDevice() {
    // Mobile UA is the strongest signal
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
      return false;
    }
    // screen.width gives physical screen size even in Safari's "Request Desktop Website" mode
    if (window.screen && window.screen.width < 768) return false;
    var hasTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
    if (hasTouch && window.screen && window.screen.width < 1200) return false;
    return true;
  }

  if (isDesktopDevice()) {
    $('.state').removeClass('active');
    showState('desktop');

    var pageUrl = '{{ route('order.sizing') }}';

    // Generate QR client-side (qrcode.js)
    var qrContainer = document.getElementById('desktop-qr-div');
    if (typeof QRCode !== 'undefined' && qrContainer) {
      qrContainer.innerHTML = '';
      new QRCode(qrContainer, {
        text: pageUrl, width: 200, height: 200,
        colorDark: '#1A1614', colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M
      });
    }

    // WhatsApp share
    var waMsg = 'Open this to take my nail sizing photos on my phone: ' + pageUrl;
    $('#desktop-wa-link').attr('href', 'https://wa.me/?text=' + encodeURIComponent(waMsg));

    // Copy link
    $('#desktop-copy-link').on('click', function () {
      var $label = $('#copy-btn-text');
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(pageUrl).then(function () {
          $label.text('✓ Copied!'); setTimeout(function () { $label.text('Copy link'); }, 2000);
        });
      } else {
        var $tmp = $('<input>').val(pageUrl).appendTo('body');
        $tmp[0].select(); document.execCommand('copy'); $tmp.remove();
        $label.text('✓ Copied!'); setTimeout(function () { $label.text('Copy link'); }, 2000);
      }
    });

    // Upload fallback
    $('#desktop-upload-btn').on('click', function () { showState('upload'); });

    // Escape hatch for false-positives
    $('#desktop-continue-anyway').on('click', function () { showState('explainer'); });

    return;
  }
  // ── End desktop detection ─────────────────────────────────────────────────

  // Kick off the camera capture module
  if (typeof NbmCamera !== 'undefined') {
    NbmCamera.init({
      uploadRoute: '{{ route('order.sizing.upload') }}',
      nextUrl:     '{{ route('order.details') }}',
      csrfToken:   '{{ csrf_token() }}',
    });
  }

  // Use upload fallback button (from explainer)
  $('#use-upload-btn').on('click', function () {
    showState('upload');
  });

  // Try camera again from upload state
  $('#try-camera-again-btn').on('click', function () {
    showState('explainer');
  });

  // Upload: other hand toggle
  $('#upload-other-hand-toggle').on('click', function () {
    $('#upload-other-hand-fields').toggleClass('hidden');
    $(this).text(
      $('#upload-other-hand-fields').hasClass('hidden')
        ? 'Add photos for my other hand (optional) →'
        : 'Remove other hand photos ←'
    );
  });

  // Upload: submit
  $('#upload-submit-btn').on('click', function () {
    const fingers = $('#upload-fingers')[0].files[0];
    const thumb   = $('#upload-thumb')[0].files[0];

    if (!fingers || !thumb) {
      alert('Please select both a fingers photo and a thumb photo before continuing.');
      return;
    }

    const fd = new FormData();
    fd.append('_token', '{{ csrf_token() }}');
    fd.append('photos[0]', fingers);
    fd.append('photo_types[0]', 'fingers');
    fd.append('photos[1]', thumb);
    fd.append('photo_types[1]', 'thumb');

    const otherFingers = $('#upload-fingers-other')[0].files[0];
    const otherThumb   = $('#upload-thumb-other')[0].files[0];
    if (otherFingers) {
      fd.append('photos[2]', otherFingers);
      fd.append('photo_types[2]', 'fingers_other');
    }
    if (otherThumb) {
      fd.append('photos[3]', otherThumb);
      fd.append('photo_types[3]', 'thumb_other');
    }

    $(this).text('Uploading…').prop('disabled', true).addClass('opacity-75 cursor-not-allowed');

    $.ajax({
      url:         '{{ route('order.sizing.upload') }}',
      method:      'POST',
      data:        fd,
      processData: false,
      contentType: false,
    })
    .done(function () {
      window.location.href = '{{ route('order.details') }}';
    })
    .fail(function () {
      alert('Upload failed. Please check your photos and try again.');
      $('#upload-submit-btn').text('Submit photos →').prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
    });
  });

  function showState(name) {
    $('.state').removeClass('active');
    $('#state-' + name).addClass('active');
  }
  window.NbmShowState = showState;
});
</script>
@endpush
