/**
 * NbmCamera — Nails by Mona sizing capture state machine.
 *
 * States:
 *   explainer  → user sees instructions, taps "Start camera"
 *   fingers    → live viewfinder, fingers overlay, shutter
 *   thumb      → live viewfinder, thumb overlay, shutter
 *   preview    → 2-thumbnail review (or 4 if other-hand opted in)
 *   fingers_other → other-hand fingers (optional)
 *   thumb_other   → other-hand thumb (optional)
 *   upload     → fallback file inputs (camera denied / unsupported)
 *
 * Config (passed via NbmCamera.init()):
 *   uploadRoute  — POST URL for sizing photo upload
 *   nextUrl      — URL to redirect after successful upload
 *   csrfToken    — Laravel CSRF token
 */
(function (window) {
  'use strict';

  // ── Module-level state ─────────────────────────────────────────────────────
  let stream        = null;   // MediaStream kept alive for the whole session
  let captures      = {};     // { fingers: Blob, thumb: Blob, fingers_other?, thumb_other? }
  let currentState  = 'explainer';
  let brightnessTimer = null;
  let config        = {};

  // ── Public API ─────────────────────────────────────────────────────────────
  const NbmCamera = {
    init(cfg) {
      config = cfg;
      bindExplainerButtons();
      bindShutter();
      bindPreviewButtons();
    },
  };

  // ── State machine ──────────────────────────────────────────────────────────
  function goTo(state) {
    currentState = state;

    // Hide all states
    document.querySelectorAll('.state').forEach(el => el.classList.remove('active'));

    if (state === 'explainer') {
      show('state-explainer');
      stopBrightnessLoop();

    } else if (state === 'fingers' || state === 'fingers_other') {
      configureOverlay('fingers');
      updatePhotoStrip(state);
      show('state-camera');
      startStream().then(startBrightnessLoop);

    } else if (state === 'thumb' || state === 'thumb_other') {
      configureOverlay('thumb');
      updatePhotoStrip(state);
      show('state-camera');
      startBrightnessLoop();        // stream already running

    } else if (state === 'preview') {
      stopBrightnessLoop();
      buildPreviewThumbnails();
      show('state-preview');

    } else if (state === 'upload') {
      stopBrightnessLoop();
      stopStream();
      show('state-upload');
    }
  }

  // ── Stream management ──────────────────────────────────────────────────────
  async function startStream() {
    if (stream) return; // already running

    try {
      stream = await navigator.mediaDevices.getUserMedia({
        video: {
          facingMode: { ideal: 'environment' },
          width:      { ideal: 1920 },
          height:     { ideal: 1080 },
        },
      });
      document.getElementById('camera-video').srcObject = stream;
    } catch (err) {
      console.warn('Camera access denied:', err);
      goTo('upload');
    }
  }

  function stopStream() {
    if (stream) {
      stream.getTracks().forEach(t => t.stop());
      stream = null;
    }
    const video = document.getElementById('camera-video');
    if (video) video.srcObject = null;
  }

  // ── Overlay and strip helpers ──────────────────────────────────────────────
  function configureOverlay(type) {
    const fingers = document.getElementById('overlay-fingers');
    const thumb   = document.getElementById('overlay-thumb');
    if (!fingers || !thumb) return;

    if (type === 'fingers') {
      fingers.style.display = '';
      thumb.style.display   = 'none';
    } else {
      fingers.style.display = 'none';
      thumb.style.display   = '';
    }
  }

  function updatePhotoStrip(state) {
    const label    = document.getElementById('photo-label');
    const sublabel = document.getElementById('photo-sublabel');
    const dot1     = document.getElementById('dot-1');
    const dot2     = document.getElementById('dot-2');
    const dotO1    = document.getElementById('dot-opt-1');
    const dotO2    = document.getElementById('dot-opt-2');
    if (!label) return;

    // Show optional dots if we're doing other-hand photos
    const doingOther = state === 'fingers_other' || state === 'thumb_other';
    if (doingOther && dotO1) {
      dotO1.classList.remove('hidden');
      dotO2.classList.remove('hidden');
    }

    const configs = {
      fingers: {
        label:    'Photo 1 of 2 — Your fingers',
        sublabel: 'Lay fingers flat, coin above middle finger, shoot straight down.',
        dots:     [true, false, false, false],
      },
      thumb: {
        label:    'Photo 2 of 2 — Your thumb',
        sublabel: 'Extend your thumb, coin above the thumbnail, same angle.',
        dots:     [true, true, false, false],
      },
      fingers_other: {
        label:    'Photo 3 of 4 — Other hand fingers',
        sublabel: 'Same as before — fingers flat, coin above middle finger.',
        dots:     [true, true, true, false],
      },
      thumb_other: {
        label:    'Photo 4 of 4 — Other hand thumb',
        sublabel: 'Extend your other thumb, coin above the thumbnail.',
        dots:     [true, true, true, true],
      },
    };

    const c = configs[state];
    if (!c) return;
    label.textContent    = c.label;
    sublabel.textContent = c.sublabel;

    const dots = [dot1, dot2, dotO1, dotO2];
    dots.forEach((dot, i) => {
      if (!dot) return;
      if (c.dots[i]) {
        dot.classList.remove('bg-ash');
        dot.classList.add('bg-lavender');
      } else {
        dot.classList.remove('bg-lavender');
        dot.classList.add('bg-ash');
      }
    });
  }

  // ── Brightness + edge-contrast heuristic ──────────────────────────────────
  function startBrightnessLoop() {
    stopBrightnessLoop();
    brightnessTimer = setInterval(checkBrightness, 500);
  }

  function stopBrightnessLoop() {
    if (brightnessTimer) {
      clearInterval(brightnessTimer);
      brightnessTimer = null;
    }
  }

  function checkBrightness() {
    const video   = document.getElementById('camera-video');
    const canvas  = document.getElementById('brightness-canvas');
    if (!video || !canvas || video.readyState < 2) return;

    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, 80, 60);
    const data = ctx.getImageData(0, 0, 80, 60).data;

    let totalBrightness = 0;
    let edgeCount       = 0;
    const w = 80, h = 60;

    // Brightness pass
    for (let i = 0; i < data.length; i += 4) {
      const r = data[i], g = data[i + 1], b = data[i + 2];
      totalBrightness += 0.299 * r + 0.587 * g + 0.114 * b;
    }
    const avgBrightness = totalBrightness / (w * h);

    // Simplified edge contrast (horizontal gradient sum over center strip)
    const rowStart = Math.floor(h * 0.25);
    const rowEnd   = Math.floor(h * 0.75);
    let totalPixels = 0;
    for (let row = rowStart; row < rowEnd; row++) {
      for (let col = 1; col < w - 1; col++) {
        const idx = (row * w + col) * 4;
        const left  = (data[idx - 4] + data[idx - 3] + data[idx - 2]) / 3;
        const right = (data[idx + 4] + data[idx + 5] + data[idx + 6]) / 3;
        if (Math.abs(right - left) > 20) edgeCount++;
        totalPixels++;
      }
    }
    const edgeRatio = edgeCount / totalPixels;

    // Determine signal quality
    let quality;
    if (avgBrightness < 40) {
      quality = 'red';   // Too dark
    } else if (avgBrightness > 240) {
      quality = 'amber'; // Overexposed
    } else if (edgeRatio > 0.12) {
      quality = 'green'; // Good contrast — likely a hand in frame
    } else if (edgeRatio > 0.06) {
      quality = 'amber'; // Borderline
    } else {
      quality = 'red';   // Flat / hand missing
    }

    updateAlignmentUI(quality, avgBrightness);
  }

  function updateAlignmentUI(quality, brightness) {
    const border  = document.getElementById('alignment-border');
    const dot     = document.getElementById('brightness-dot');
    const label   = document.getElementById('brightness-label');
    const shutter = document.getElementById('shutter-btn');
    if (!border) return;

    border.className = `absolute inset-0 border-3 border-transparent rounded-2xl transition pointer-events-none ${quality}`;

    const colorMap = {
      green: { dot: '#22c55e', label: 'Great light', text: 'text-green-400' },
      amber: { dot: '#f59e0b', label: 'Adjust light', text: 'text-amber-400' },
      red:   { dot: '#ef4444', label: 'Too dark or hand missing', text: 'text-red-400' },
    };
    const c = colorMap[quality];
    dot.style.backgroundColor = c.dot;
    label.textContent = c.label;

    if (quality === 'green') {
      shutter.classList.add('pulse-ring');
    } else {
      shutter.classList.remove('pulse-ring');
    }
  }

  // ── Capture frame ──────────────────────────────────────────────────────────
  function captureFrame(photoType) {
    const video = document.getElementById('camera-video');
    if (!video || video.readyState < 2) return;

    const canvas = document.createElement('canvas');
    canvas.width  = video.videoWidth  || 1280;
    canvas.height = video.videoHeight || 720;
    canvas.getContext('2d').drawImage(video, 0, 0);

    canvas.toBlob(blob => {
      captures[photoType] = blob;
    }, 'image/jpeg', 0.92);
  }

  // ── Preview thumbnails ─────────────────────────────────────────────────────
  function buildPreviewThumbnails() {
    setThumb('thumb-fingers',       captures.fingers);
    setThumb('thumb-thumb',         captures.thumb);
    setThumb('thumb-other-fingers', captures.fingers_other, 'thumb-other-fingers-wrap');
    setThumb('thumb-other-thumb',   captures.thumb_other,   'thumb-other-thumb-wrap');

    // Show symmetry notice only if we DON'T have other-hand photos yet
    const hasOther = captures.fingers_other || captures.thumb_other;
    const notice   = document.getElementById('symmetry-notice');
    if (notice) notice.style.display = hasOther ? 'none' : '';
  }

  function setThumb(imgId, blob, wrapId) {
    if (!blob) return;
    const url = URL.createObjectURL(blob);
    const img = document.getElementById(imgId);
    if (img) img.src = url;
    if (wrapId) {
      const wrap = document.getElementById(wrapId);
      if (wrap) wrap.classList.remove('hidden');
    }
  }

  // ── Event bindings ─────────────────────────────────────────────────────────
  function bindExplainerButtons() {
    // "Start camera" button
    const startBtn = document.getElementById('start-camera-btn');
    if (startBtn) {
      startBtn.addEventListener('click', () => goTo('fingers'));
    }

    // "Use upload instead" — handled by inline jQuery in the Blade template
    // but we expose goTo for it:
    window.NbmShowState = (name) => {
      if (name === 'upload') goTo('upload');
      else if (name === 'explainer') {
        stopStream();
        goTo('explainer');
      }
    };
  }

  function bindShutter() {
    const shutterBtn = document.getElementById('shutter-btn');
    if (!shutterBtn) return;

    shutterBtn.addEventListener('click', () => {
      if (currentState === 'fingers') {
        captureFrame('fingers');
        setTimeout(() => goTo('thumb'), 150); // brief flash delay
      } else if (currentState === 'thumb') {
        captureFrame('thumb');
        setTimeout(() => goTo('preview'), 150);
      } else if (currentState === 'fingers_other') {
        captureFrame('fingers_other');
        setTimeout(() => goTo('thumb_other'), 150);
      } else if (currentState === 'thumb_other') {
        captureFrame('thumb_other');
        setTimeout(() => goTo('preview'), 150);
      }
    });
  }

  function bindPreviewButtons() {
    // Retake buttons (delegated — thumbnails rendered dynamically)
    document.addEventListener('click', (e) => {
      const retake = e.target.closest('.thumb-retake');
      if (!retake) return;
      const photoType = retake.getAttribute('data-photo'); // fingers | thumb | fingers_other | thumb_other
      delete captures[photoType];
      goTo(photoType); // state name matches photo type
    });

    // "Add my other hand"
    const addOtherBtn = document.getElementById('add-other-hand-btn');
    if (addOtherBtn) {
      addOtherBtn.addEventListener('click', () => goTo('fingers_other'));
    }

    // "Submit my sizing"
    const submitBtn = document.getElementById('submit-sizing-btn');
    if (submitBtn) {
      submitBtn.addEventListener('click', submitSizing);
    }
  }

  // ── Upload sizing blobs ────────────────────────────────────────────────────
  async function submitSizing() {
    const btn = document.getElementById('submit-sizing-btn');
    if (!btn) return;

    btn.textContent = 'Submitting…';
    btn.disabled    = true;
    btn.classList.add('opacity-75', 'cursor-not-allowed');

    const fd = new FormData();
    fd.append('_token', config.csrfToken);

    const order = [
      { key: 'fingers',       type: 'fingers' },
      { key: 'thumb',         type: 'thumb' },
      { key: 'fingers_other', type: 'fingers_other' },
      { key: 'thumb_other',   type: 'thumb_other' },
    ];

    let idx = 0;
    order.forEach(({ key, type }) => {
      if (captures[key]) {
        fd.append(`photos[${idx}]`,      captures[key], `${type}.jpg`);
        fd.append(`photo_types[${idx}]`, type);
        idx++;
      }
    });

    if (idx === 0) {
      alert('No photos captured. Please take at least 2 photos before submitting.');
      btn.textContent = 'Submit my sizing →';
      btn.disabled    = false;
      btn.classList.remove('opacity-75', 'cursor-not-allowed');
      return;
    }

    try {
      const res = await fetch(config.uploadRoute, { method: 'POST', body: fd });

      if (res.ok) {
        stopStream();
        window.location.href = config.nextUrl;
      } else {
        const data = await res.json().catch(() => ({}));
        alert(data.message || 'Upload failed. Please try again or use the upload option instead.');
        btn.textContent = 'Submit my sizing →';
        btn.disabled    = false;
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
      }
    } catch (err) {
      alert('Network error. Please check your connection and try again.');
      btn.textContent = 'Submit my sizing →';
      btn.disabled    = false;
      btn.classList.remove('opacity-75', 'cursor-not-allowed');
    }
  }

  // ── Utility ────────────────────────────────────────────────────────────────
  function show(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('active');
  }

  // ── Export ─────────────────────────────────────────────────────────────────
  window.NbmCamera = NbmCamera;

}(window));
