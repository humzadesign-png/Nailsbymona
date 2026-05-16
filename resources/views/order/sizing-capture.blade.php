@extends('layouts.order')

@section('title', 'Sizing Capture — Nails by Mona')

@push('head')
<style>
  /* ── State transitions ─────────────────────────────────────── */
  .state { display: none; }
  .state.active { display: block; }

  /* ── Full-screen camera mode ───────────────────────────────── */
  #state-camera.active {
    position: fixed;
    inset: 0;
    z-index: 50;
    background: #000;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  /* Video fills the screen */
  #camera-video {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  /* SVG overlay stretches over video */
  #overlay-svg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
  }

  /* Alignment border — full viewport edge */
  #alignment-border {
    position: absolute;
    inset: 0;
    border: 2.5px solid transparent;
    transition: border-color 0.35s ease, box-shadow 0.35s ease;
    pointer-events: none;
    z-index: 10;
  }
  #alignment-border.green {
    border-color: #4ade80;
    box-shadow: inset 0 0 24px rgba(74,222,128,0.12);
  }
  #alignment-border.amber {
    border-color: #fbbf24;
    box-shadow: inset 0 0 24px rgba(251,191,36,0.08);
  }
  #alignment-border.red {
    border-color: #f87171;
    box-shadow: inset 0 0 24px rgba(248,113,113,0.08);
  }

  /* ── Top HUD bar ───────────────────────────────────────────── */
  #camera-hud-top {
    position: absolute;
    top: 0; left: 0; right: 0;
    z-index: 20;
    padding: env(safe-area-inset-top, 16px) 20px 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0) 100%);
  }

  /* Progress bar track */
  #progress-track {
    display: flex;
    gap: 6px;
    align-items: center;
  }
  .progress-segment {
    height: 2px;
    flex: 1;
    border-radius: 999px;
    background: rgba(255,255,255,0.25);
    transition: background 0.3s;
  }
  .progress-segment.done   { background: #BFA4CE; }
  .progress-segment.active { background: rgba(255,255,255,0.85); }

  /* ── Bottom controls bar ───────────────────────────────────── */
  #camera-controls {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    z-index: 20;
    padding: 20px 24px calc(env(safe-area-inset-bottom, 20px) + 20px);
    background: linear-gradient(to top, rgba(0,0,0,0.70) 0%, rgba(0,0,0,0) 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
  }

  /* Brightness pill */
  #brightness-pill {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 5px 14px;
    border-radius: 999px;
    background: rgba(0,0,0,0.45);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.12);
    transition: opacity 0.3s;
  }
  #brightness-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #6b7280;
    transition: background 0.3s;
    flex-shrink: 0;
  }
  #brightness-dot.green  { background: #4ade80; }
  #brightness-dot.amber  { background: #fbbf24; }
  #brightness-dot.red    { background: #f87171; }
  #brightness-label {
    font-family: 'DM Sans', sans-serif;
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.9);
  }

  /* Shutter button */
  #shutter-btn {
    width: 72px; height: 72px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.85);
    background: transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.15s ease;
    -webkit-tap-highlight-color: transparent;
    flex-shrink: 0;
  }
  #shutter-btn:active { transform: scale(0.93); }
  #shutter-inner {
    width: 54px; height: 54px;
    border-radius: 50%;
    background: rgba(255,255,255,0.92);
    transition: background 0.2s;
  }
  #shutter-btn:active #shutter-inner { background: #BFA4CE; }

  @keyframes pulse-ring {
    0%   { box-shadow: 0 0 0 0   rgba(191,164,206,0.7); }
    60%  { box-shadow: 0 0 0 16px rgba(191,164,206,0);   }
    100% { box-shadow: 0 0 0 0   rgba(191,164,206,0);   }
  }
  #shutter-btn.pulse-ring { animation: pulse-ring 1.4s infinite; }

  /* Instruction hint */
  #camera-hint {
    font-family: 'DM Sans', sans-serif;
    font-size: 12px;
    font-weight: 400;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.6);
    text-align: center;
  }

  /* ── Thumbnail retake ──────────────────────────────────────── */
  .thumb-wrap { position: relative; }
  .thumb-retake {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: rgba(28,23,39,0.78);
    color: #FBF8F2;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.7rem;
    font-weight: 500;
    letter-spacing: 0.05em;
    text-align: center;
    padding: 0.35rem 0;
    cursor: pointer;
    transition: background 0.2s;
  }
  .thumb-retake:hover { background: rgba(191,164,206,0.85); }

  /* Hide brightness canvas */
  #brightness-canvas { display: none; }
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

    {{-- ── State B/C: Camera capture — full-screen ───────────────────── --}}
    <div id="state-camera" class="state">

      {{-- Hidden canvas for brightness sampling --}}
      <canvas id="brightness-canvas" width="80" height="60"></canvas>

      {{-- Live video feed --}}
      <video id="camera-video" autoplay playsinline muted></video>

      {{--
        SVG overlay — viewBox 400×870 matches modern iPhone aspect ratio (~9:19.5).
        preserveAspectRatio="xMidYMax meet" — scales to fit, anchors to bottom of screen.
        Finger centers at x=68(pinky) 140(ring) 220(middle) 300(index) — confirmed by user.
        Guides are 56-64px wide to match real finger nail-bed widths.
        Everything shifted down so coin sits comfortably above the hand.
        Coin r=38 — sized to approximate a PKR 5 coin relative to finger widths.
      --}}
      <svg id="overlay-svg" viewBox="0 0 400 870" xmlns="http://www.w3.org/2000/svg" fill="none" preserveAspectRatio="xMidYMax meet">

        {{-- Fingers overlay (photo 1 and 3) --}}
        <g id="overlay-fingers">
          {{-- Coin above middle finger --}}
          <circle cx="220" cy="255" r="38" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" stroke-dasharray="5 4"/>
          <text x="220" y="263" text-anchor="middle" font-size="17" fill="rgba(255,255,255,0.9)" font-family="DM Sans, sans-serif">₨</text>
          <text x="220" y="308" text-anchor="middle" font-size="11" fill="rgba(255,255,255,0.5)" font-family="DM Sans, sans-serif" letter-spacing="1.5">COIN ABOVE NAILS</text>

          {{-- Pinky — 70px wide, center x=53, x=18-88. ~75% of middle height. --}}
          <path d="M18,870 L18,486 Q18,451 53,451 Q88,451 88,486 L88,870"
                stroke="rgba(255,255,255,0.75)" stroke-width="1.5" stroke-dasharray="7 5" stroke-linecap="round"/>
          {{-- Ring — 75px wide, center x=132, x=95-170. ~90% of middle height. --}}
          <path d="M95,870 L95,406 Q95,368 132,368 Q170,368 170,406 L170,870"
                stroke="rgba(255,255,255,0.75)" stroke-width="1.5" stroke-dasharray="7 5" stroke-linecap="round"/>
          {{-- Middle — 75px wide, center x=220, x=183-258. Tallest. --}}
          <path d="M183,870 L183,350 Q183,312 220,312 Q258,312 258,350 L258,870"
                stroke="rgba(255,255,255,0.75)" stroke-width="1.5" stroke-dasharray="7 5" stroke-linecap="round"/>
          {{-- Index — 75px wide, center x=308, x=271-346. ~88% of middle height. --}}
          <path d="M271,870 L271,417 Q271,379 308,379 Q346,379 346,417 L346,870"
                stroke="rgba(255,255,255,0.75)" stroke-width="1.5" stroke-dasharray="7 5" stroke-linecap="round"/>
        </g>

        {{-- Thumb overlay (photo 2 and 4) --}}
        {{-- 90px wide, center x=200 (x=155-245). Coin r=38. --}}
        <g id="overlay-thumb" style="display:none">
          <circle cx="200" cy="310" r="38" stroke="rgba(255,255,255,0.8)" stroke-width="1.5" stroke-dasharray="5 4"/>
          <text x="200" y="318" text-anchor="middle" font-size="17" fill="rgba(255,255,255,0.9)" font-family="DM Sans, sans-serif">₨</text>
          <text x="200" y="360" text-anchor="middle" font-size="11" fill="rgba(255,255,255,0.5)" font-family="DM Sans, sans-serif" letter-spacing="1.5">COIN ABOVE NAIL</text>
          <path d="M155,870 L155,480 Q155,438 200,438 Q245,438 245,480 L245,870"
                stroke="rgba(255,255,255,0.75)" stroke-width="1.5" stroke-dasharray="7 5" stroke-linecap="round"/>
        </g>

        {{-- Corner brackets --}}
        <line x1="20" y1="20" x2="52" y2="20" stroke="rgba(255,255,255,0.25)" stroke-width="1.5" stroke-linecap="round"/>
        <line x1="20" y1="20" x2="20" y2="52" stroke="rgba(255,255,255,0.25)" stroke-width="1.5" stroke-linecap="round"/>
        <line x1="380" y1="20" x2="348" y2="20" stroke="rgba(255,255,255,0.25)" stroke-width="1.5" stroke-linecap="round"/>
        <line x1="380" y1="20" x2="380" y2="52" stroke="rgba(255,255,255,0.25)" stroke-width="1.5" stroke-linecap="round"/>
        <line x1="20" y1="850" x2="52" y2="850" stroke="rgba(255,255,255,0.25)" stroke-width="1.5" stroke-linecap="round"/>
        <line x1="20" y1="850" x2="20" y2="818" stroke="rgba(255,255,255,0.25)" stroke-width="1.5" stroke-linecap="round"/>
        <line x1="380" y1="850" x2="348" y2="850" stroke="rgba(255,255,255,0.25)" stroke-width="1.5" stroke-linecap="round"/>
        <line x1="380" y1="850" x2="380" y2="818" stroke="rgba(255,255,255,0.25)" stroke-width="1.5" stroke-linecap="round"/>
      </svg>

      {{-- Alignment border --}}
      <div id="alignment-border"></div>

      {{-- ── Top HUD ───────────────────────────────────────── --}}
      <div id="camera-hud-top">
        <div class="flex items-center gap-3 mb-3 pt-3">
          <button id="camera-back-btn" aria-label="Cancel" class="shrink-0 w-8 h-8 flex items-center justify-center rounded-full bg-black/30 backdrop-blur-sm border border-white/10 text-white/80 hover:text-white transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"><polyline points="160 208 80 128 160 48"/></svg>
          </button>
          <div id="progress-track" class="flex-1">
            <span class="progress-segment active" id="seg-1"></span>
            <span class="progress-segment" id="seg-2"></span>
            <span class="progress-segment hidden" id="seg-3"></span>
            <span class="progress-segment hidden" id="seg-4"></span>
          </div>
          <span id="photo-counter" class="shrink-0 font-sans text-white/70 text-xs font-medium tracking-widest uppercase">PHOTO 1 OF 2</span>
        </div>
        <p id="photo-label" class="font-sans text-white font-semibold text-sm mb-0.5 tracking-wide">Your fingers</p>
        <p id="photo-sublabel" class="font-sans text-white/60 text-xs leading-snug mb-3">Lay fingers flat · coin above middle finger · shoot straight down</p>
      </div>

      {{-- ── Bottom controls ──────────────────────────────── --}}
      <div id="camera-controls">
        {{-- Brightness/alignment indicator --}}
        <div id="brightness-pill">
          <span id="brightness-dot"></span>
          <span id="brightness-label">Checking…</span>
        </div>

        {{-- Shutter --}}
        <button id="shutter-btn" aria-label="Take photo">
          <div id="shutter-inner"></div>
        </button>

        <p id="camera-hint">Tap when guide is green</p>
      </div>

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

  // Camera back button → return to explainer
  $('#camera-back-btn').on('click', function () {
    showState('explainer');
  });

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
