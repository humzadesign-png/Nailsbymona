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
    const counter  = document.getElementById('photo-counter');
    if (!label) return;

    const configs = {
      fingers: {
        counter:  'PHOTO 1 OF 2',
        label:    'Your fingers',
        sublabel: 'Lay fingers flat · coin above middle finger · shoot straight down',
        segs:     ['active', '', null, null],
      },
      thumb: {
        counter:  'PHOTO 2 OF 2',
        label:    'Your thumb',
        sublabel: 'Extend your thumb · coin above the thumbnail · same angle',
        segs:     ['done', 'active', null, null],
      },
      fingers_other: {
        counter:  'PHOTO 3 OF 4',
        label:    'Other hand — fingers',
        sublabel: 'Same as before · fingers flat · coin above middle finger',
        segs:     ['done', 'done', 'active', ''],
      },
      thumb_other: {
        counter:  'PHOTO 4 OF 4',
        label:    'Other hand — thumb',
        sublabel: 'Extend your other thumb · coin above the thumbnail',
        segs:     ['done', 'done', 'done', 'active'],
      },
    };

    const c = configs[state];
    if (!c) return;

    if (counter) counter.textContent = c.counter;
    label.textContent    = c.label;
    sublabel.textContent = c.sublabel;

    // Update progress segments
    ['seg-1','seg-2','seg-3','seg-4'].forEach((id, i) => {
      const seg = document.getElementById(id);
      if (!seg) return;
      const v = c.segs[i];
      if (v === null) {
        // Show optional segments when doing other-hand
        seg.classList.remove('hidden');
      }
      seg.classList.remove('done', 'active');
      if (v === 'done')   seg.classList.add('done');
      if (v === 'active') seg.classList.add('active');
      // Show optional segs
      if (state === 'fingers_other' || state === 'thumb_other') {
        document.getElementById('seg-3')?.classList.remove('hidden');
        document.getElementById('seg-4')?.classList.remove('hidden');
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

    // Thumb has one wide nail rather than four — fewer edges in frame,
    // so use a lower threshold to avoid the green state never triggering.
    const isThumb = currentState === 'thumb' || currentState === 'thumb_other';
    const greenThreshold = isThumb ? 0.06 : 0.12;
    const amberThreshold = isThumb ? 0.03 : 0.06;

    // Determine signal quality
    let quality;
    if (avgBrightness < 40) {
      quality = 'red';   // Too dark
    } else if (avgBrightness > 240) {
      quality = 'amber'; // Overexposed
    } else if (edgeRatio > greenThreshold) {
      quality = 'green'; // Good contrast — likely a hand in frame
    } else if (edgeRatio > amberThreshold) {
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
  // Long edge capped at 1600px (~15px per mm at close-up framing): plenty to
  // read nail width against the coin, and a small upload on slow connections.
  const MAX_EDGE = 1600;
  const JPEG_QUALITY = 0.85;
  // Remembered per device: if multipart uploads break here once (seen on
  // iPhone Chrome), go straight to the base64 upload next time.
  const MULTIPART_BROKEN_KEY = 'nbm.sizingMultipartBroken';

  function captureFrame(photoType) {
    const video = document.getElementById('camera-video');
    if (!video || video.readyState < 2) return;

    const srcW  = video.videoWidth  || 1280;
    const srcH  = video.videoHeight || 720;
    const scale = Math.min(1, MAX_EDGE / Math.max(srcW, srcH));

    const canvas = document.createElement('canvas');
    canvas.width  = Math.round(srcW * scale);
    canvas.height = Math.round(srcH * scale);
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

    // Keep a data URL too — used by the upload retry path and as a fallback
    // when a browser's toBlob() hands back null.
    const dataUrl = canvas.toDataURL('image/jpeg', JPEG_QUALITY);
    captures[photoType] = { blob: null, dataUrl: dataUrl };

    canvas.toBlob(blob => {
      if (captures[photoType] && captures[photoType].dataUrl === dataUrl) {
        captures[photoType].blob = blob;
      }
    }, 'image/jpeg', JPEG_QUALITY);
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

  function setThumb(imgId, capture, wrapId) {
    if (!capture) return;
    const img = document.getElementById(imgId);
    if (img) img.src = capture.dataUrl;
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

  // ── Upload sizing photos ───────────────────────────────────────────────────
  const PHOTO_ORDER = ['fingers', 'thumb', 'fingers_other', 'thumb_other'];

  async function submitSizing() {
    const btn = document.getElementById('submit-sizing-btn');
    if (!btn || btn.disabled) return;

    const types = PHOTO_ORDER.filter(t => captures[t]);
    if (!captures.fingers || !captures.thumb) {
      showUploadError('Please take both photos — your fingers and your thumb — before submitting.');
      return;
    }

    setSubmitting(btn, true);
    hideUploadError();

    // TEMP diagnostics (iPhone Chrome slow upload) — remove once resolved.
    const diag = { t0: Date.now(), skipMultipart: multipartKnownBroken(), steps: [],
      sizes: types.map(t => Math.round((captures[t].dataUrl || '').length / 1024) + 'KB'),
      blobs: types.map(t => captures[t].blob ? Math.round(captures[t].blob.size / 1024) + 'KB' : 'null') };

    // Attempt 1: multipart files. Attempt 2: the same photos as base64 text,
    // which survives phone browsers that break multipart uploads.
    let result = { ok: false, status: 0 };
    if (!multipartKnownBroken()) {
      const ts = Date.now();
      result = await send(buildMultipart(types), false, btn);
      diag.steps.push(['multipart', result.status, Date.now() - ts]);
    }
    if (!result.ok && result.status !== 419 && result.status !== 422) {
      if (result.status <= 0 || result.status === 400) rememberMultipartBroken();
      const ts = Date.now();
      result = await send(buildBase64(types), true, btn);
      diag.steps.push(['base64', result.status, Date.now() - ts]);
    }
    reportDiag(diag);

    if (result.ok) {
      stopStream();
      window.location.href = config.nextUrl;
      return;
    }

    setSubmitting(btn, false);
    if (result.status === 419) {
      showUploadError('Your session expired. Please reload this page and take your photos again.');
    } else if (result.status <= 0) {
      showUploadError('We couldn\'t reach the server — please check your internet connection and tap Submit again.');
    } else {
      showUploadError((result.message || 'Upload failed. Please tap Submit to try again, or upload your photos instead.')
        + ' (Error ' + result.status + ')');
    }
  }

  function reportDiag(diag) {
    try {
      diag.total = Date.now() - diag.t0;
      const body = new URLSearchParams({ _token: config.csrfToken, diag: JSON.stringify(diag) });
      if (navigator.sendBeacon) navigator.sendBeacon('/order/sizing-diag', body);
    } catch (e) { /* diagnostics must never break the upload */ }
  }

  function buildMultipart(types) {
    const fd = new FormData();
    fd.append('_token', config.csrfToken);
    types.forEach((type, i) => {
      const cap  = captures[type];
      const blob = cap.blob || dataUrlToBlob(cap.dataUrl);
      fd.append('photos[' + i + ']', new File([blob], type + '.jpg', { type: 'image/jpeg' }));
      fd.append('photo_types[' + i + ']', type);
    });
    return fd;
  }

  function buildBase64(types) {
    return JSON.stringify({
      _token:        config.csrfToken,
      photos_base64: types.map(t => captures[t].dataUrl),
      photo_types:   types,
    });
  }

  function send(body, isJson, btn) {
    return new Promise(resolve => {
      const xhr = new XMLHttpRequest();
      if (btn && xhr.upload) {
        xhr.upload.onprogress = (e) => {
          if (!e.lengthComputable) return;
          const pct = Math.min(99, Math.round((e.loaded / e.total) * 100));
          btn.textContent = pct < 99 ? 'Uploading your photos… ' + pct + '%' : 'Almost done…';
        };
      }
      xhr.open('POST', config.uploadRoute, true);
      xhr.timeout = 90000;
      xhr.setRequestHeader('Accept', 'application/json');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.setRequestHeader('X-CSRF-TOKEN', config.csrfToken);
      if (isJson) xhr.setRequestHeader('Content-Type', 'application/json');

      xhr.onload = () => {
        let data = {};
        try { data = JSON.parse(xhr.responseText); } catch (e) { /* non-JSON error page */ }
        resolve({
          ok:      xhr.status >= 200 && xhr.status < 300 && data.success !== false,
          status:  xhr.status,
          message: data.message,
        });
      };
      xhr.onerror   = () => resolve({ ok: false, status: 0 });
      xhr.onabort   = () => resolve({ ok: false, status: -1 });
      xhr.ontimeout = () => resolve({ ok: false, status: -2 });
      xhr.send(body);
    });
  }

  function multipartKnownBroken() {
    try { return localStorage.getItem(MULTIPART_BROKEN_KEY) === '1'; } catch (e) { return false; }
  }

  function rememberMultipartBroken() {
    try { localStorage.setItem(MULTIPART_BROKEN_KEY, '1'); } catch (e) { /* private mode */ }
  }

  function dataUrlToBlob(dataUrl) {
    const [head, b64] = dataUrl.split(',');
    const mime  = (head.match(/data:([^;]+)/) || [])[1] || 'image/jpeg';
    const bin   = atob(b64);
    const bytes = new Uint8Array(bin.length);
    for (let i = 0; i < bin.length; i++) bytes[i] = bin.charCodeAt(i);
    return new Blob([bytes], { type: mime });
  }

  function setSubmitting(btn, busy) {
    btn.textContent = busy ? 'Submitting…' : 'Submit my sizing →';
    btn.disabled    = busy;
    btn.classList.toggle('opacity-75', busy);
    btn.classList.toggle('cursor-not-allowed', busy);
  }

  function showUploadError(message) {
    const box = document.getElementById('sizing-upload-error');
    if (!box) { alert(message); return; }
    box.querySelector('[data-message]').textContent = message;
    box.classList.remove('hidden');
    box.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }

  function hideUploadError() {
    const box = document.getElementById('sizing-upload-error');
    if (box) box.classList.add('hidden');
  }

  // ── Utility ────────────────────────────────────────────────────────────────
  function show(id) {
    const el = document.getElementById(id);
    if (el) el.classList.add('active');
  }

  // ── Export ─────────────────────────────────────────────────────────────────
  window.NbmCamera = NbmCamera;

}(window));
