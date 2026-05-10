# Page: Live Camera Capture `/order/sizing-capture`

> ⚠ **2026-04-29 design revision applies.** Canonical design tokens, nav pattern, photography rules, copy guidelines, and CTA labels are in `00-global.md` and `.claude/skills/design-system.md`.
>
> ⚠ **2026-05-07 sizing redesign applies.** Moved from one full-hand photo to a **2-photo state machine** (fingers row + thumb). Optional opt-in for 2 more photos for the other hand. See CLAUDE.md §8 for the technical reference and §32 for the rationale.

---

> Design tokens, typography, and patterns: see `00-global.md` + `.claude/skills/design-system.md` — those files are authoritative.
> **Technical spec:** see CLAUDE.md §8 for full live-camera UX details — getUserMedia, state machine, edge-contrast heuristic, canvas capture, HTTPS requirement, iOS gotchas.

---

**Purpose:** Deliver the live-camera sizing experience — the product's signature differentiator. Mobile-first, distraction-free. Walk the customer through 2 close-up photos (fingers + thumb) in a single camera session, with optional opt-in for 2 more if they want a perfect fit. Real-time green/red guidance keeps each frame usable for Mona.
**Template:** `resources/views/order/sizing-capture.blade.php`
**Layout:** Minimal custom — logo only, no nav, no footer.
**Schema.org:** None — `noindex` page.

---

## SEO

`noindex, nofollow` — transactional utility page.

---

## Header

**Logo:** `public/logo-text.svg`, top-left only.
**Right:** "Need help?" → WhatsApp link (pre-filled: "Hello Nails by Mona, I'm having trouble with the sizing photo step.")
**No navigation.**

---

## State A — Pre-camera explainer

**Background:** `bg-paper`

**Heading (H2, centred):**
> Take 2 close-up photos.

**Subheadline (centred, `text-stone`, `text-body`):**
> One for your fingers, one for your thumb. Place a coin above the nail row in each photo so we can measure scale. About 90 seconds.

**3-up illustrated steps (concise, icon-led):**
1. Lay your hand flat on a dark surface (any cloth works).
2. Place a coin (Rs. 5, Rs. 10, or any) above your nails.
3. Hold your phone straight overhead. Tap when the guide turns green.

**"Start camera" button (primary, centred):**
`bg-lavender text-white rounded-full` — large, full-width on mobile.

*Camera never auto-starts. The user must tap this button first. Permission is requested **once for the whole session** — we do not re-prompt between fingers and thumb.*

---

## State B — Photo 1 of 2: Fingers

**Background:** Black (letterboxed on desktop, full-screen on mobile)

**Top strip** (semi-transparent over video, `bg-ink/70 backdrop-blur-sm`):
- Left: "Photo 1 of 2 — Your fingers"
- Right: thin progress bar, 50% filled, `bg-lavender`

**`<video>` element:** Full-width on mobile. On desktop: constrained to max 480px wide, centred with black bars.

### SVG Overlay — Fingers (rendered on top of video via absolute positioning)

Three elements drawn on the overlay SVG:

**1. 4-finger row outline:**
- Four dashed rectangles in a horizontal row representing index, middle, ring, pinky laid flat
- Stroke: `rgba(255,255,255,0.7)`, no fill
- Slight perspective taper (top of each finger narrower) to match natural hand shape
- Label beneath the row: "Fit fingers inside the lines"

**2. Coin circle (above the middle finger):**
- Circle ~22mm equivalent positioned above the middle-finger rectangle
- Stroke: `rgba(191,164,206,0.9)` (lavender), dashed
- Label below: "Place coin here"

**3. Alignment border:**
- A 3px border framing the entire overlay region
- **Green** (`rgba(63,110,74,0.9)` — `success` token) when alignment is OK
- **Red** (`rgba(163,58,46,0.85)` — `danger` token) when the region looks wrong
- **Amber/transitional** (`rgba(166,103,26,0.85)` — `warning` token) while the heuristic is unsure
- Heuristic = brightness OK *and* edge contrast in the expected nail-row region above threshold (see CLAUDE.md §8 technical specs)

### Brightness Indicator (top-centre pill)

Canvas-based sampling at 500ms interval. Reads average pixel brightness from the centre region.

| Condition | Indicator |
|---|---|
| Good lighting | Green pill: "✓ Good lighting" |
| Too dark | Amber pill: "Too dark — move to a brighter spot" |
| Too bright | Amber pill: "Too bright — step away from direct light" |

### Shutter Button

**Design:** Large circle (`w-20 h-20`), centred at the bottom of the camera view.
- **Normal state:** White circle with lavender border.
- **Ready state:** Pulses gently (`animate-pulse`) when the alignment border is green.
- **Disabled state:** Grey — shown briefly right after tap while canvas capture processes.

**Behaviour:** User taps the shutter when they're ready. We do **not** auto-capture even when alignment is green. The heuristic guides; the human decides.

On tap → `<canvas>` captures the frame → blob stored in memory under `captures.fingers` → state advances to State C (Thumb).

---

## State C — Photo 2 of 2: Thumb

Same camera feed continues — no permission re-prompt, no UI flicker, just an overlay swap.

**Top strip:** "Photo 2 of 2 — Your thumb." Progress bar 100% filled.

### SVG Overlay — Thumb

**1. Thumb outline:**
- A single tall dashed rectangle, vertical orientation, narrower than the finger overlay
- Stroke: `rgba(255,255,255,0.7)`, no fill
- Label beneath: "Fit your thumb inside the lines"

**2. Coin circle (above the thumbnail):**
- Same circle pattern as State B, positioned above where the thumb tip sits
- Same lavender dashed style + "Place coin here" label

**3. Alignment border:**
- Same green/red/amber heuristic, this time sampling a vertical region matching the thumb overlay

Brightness indicator + shutter button: identical to State B.

On tap → blob stored under `captures.thumb` → state advances to State D (Preview).

---

## State D — Preview

**Background:** `bg-paper`

**Heading (H2, centred):**
> Do these look right?

**2 thumbnails side-by-side** (mobile: stacked vertically; tablet+: 2-up):
- Each shows the captured photo, rounded corners, `aspect-ratio:4/5`
- Tile label above each: "Fingers" / "Thumb"
- "Retake" link below each (`text-caption text-stone hover:text-lavenderInk`) → returns to the corresponding camera state (B or C) without re-prompting permission

**Mental checklist** (4 items, not interactive — small text under the thumbnails, single line):
- ☐ Coin visible and unobscured
- ☐ All nails inside the outline
- ☐ Photos in focus (not blurry)
- ☐ Even lighting

### Symmetry disclaimer

Just below the thumbnails, in a `bg-shell rounded-xl p-5` block:

> **Most hands match.** We size your other hand from these photos. Nail widths are usually symmetric within half a millimetre — well within the press-on fit tolerance.
>
> Want a perfect fit? You can optionally take 2 more photos for your other hand below. Otherwise we'll go ahead and craft your set, and if anything doesn't sit right, the **first refit is on us**.

### Two CTAs

- Primary, full-width on mobile: **"Submit my sizing"** (`bg-lavender text-white rounded-full`)
- Secondary, ghost link below: **"Add my other hand →"** (`text-caption text-graphite hover:text-lavenderInk underline-offset-4 hover:underline`)

---

## State E (optional) — Photos 3 & 4: Other hand

If the customer taps "Add my other hand →":

- Same UI as State B but with overlay labelled "Photo 3 of 4 — Other hand fingers"
- Then State C clone labelled "Photo 4 of 4 — Other hand thumb"
- Returns to State D as a **4-thumbnail preview**: Fingers · Thumb · Other-hand fingers · Other-hand thumb (2×2 grid). Each with its own "Retake" link.
- The symmetry disclaimer is hidden in the 4-photo state (no longer needed — they did the perfect-fit path).
- Single primary CTA: **"Submit my sizing"** (no second CTA — they're already in the perfectionist branch).

---

## State F — Submit

On "Submit my sizing":

- POST blobs to `OrderSizingPhotoController@store`. One request, multipart form-data, with each blob carrying its `photo_type` field (`fingers`, `thumb`, `fingers_other`, `thumb_other`).
- Loading spinner on the button during upload. Estimated 2–6 seconds depending on connection.
- On success → brief success animation → redirect to `/order/details` (step 2 of the order form).
- On failure (network error) → inline error message + retry; blobs stay in memory client-side so the user doesn't have to re-shoot.
- Server: strips EXIF, converts HEIC → JPEG, saves with ULID filenames in `storage/app/public/sizing/{order_id}/`. Sets `orders.sizing_capture_method = 'live_camera'`.

---

## Fallback States

### Permission Denied

**Shown when:** `getUserMedia` throws `NotAllowedError`.

**Icon:** Phosphor `camera-slash`
**Heading:** Camera access was denied.
**Copy:**
> No problem — you can upload 2 photos from your gallery instead. It works just as well. The instructions below show you exactly what each photo should look like.

**2 file inputs, labelled clearly:**

```
[ Upload your fingers photo ]   .file-input-1
[ Upload your thumb photo ]     .file-input-2
```

Each `<input type="file" accept="image/*" capture="environment">`. Max 8MB per file. Shows a preview thumbnail on selection.

Below the inputs: same symmetry disclaimer as State D + an **"Add my other hand →"** link that reveals 2 more file inputs.

Below all that: 4 good-example thumbnails (small, `grid-cols-4` strip) — 2 fingers examples + 2 thumb examples — with green ✓ badges. Links to `/size-guide` for the full guide.

**Submit button** activates only when both required photos are selected (or all 4 if opt-in).

---

### Camera Unavailable

**Shown when:** `getUserMedia` not supported (old iOS, some desktop browsers) or camera device not found.

**Icon:** Phosphor `camera-slash` with question variant
**Heading:** Camera not available on this device.
**Copy:**
> That's okay — uploading 2 photos from your gallery works exactly the same. Use the guide below to make sure each photo has the right framing and lighting before you upload.

Same 2 file inputs + opt-in flow as the Permission Denied path.

**Link below:** "See the full sizing guide" → `/size-guide`

---

## Technical Implementation Notes

### JavaScript (`resources/js/camera-capture.js`)

```js
// Stream — back camera preferred, fallback to any camera. Permission asked ONCE.
const stream = await navigator.mediaDevices.getUserMedia({
  video: {
    facingMode: { ideal: 'environment' },
    width: { ideal: 1920 },
    height: { ideal: 1080 }
  }
});

// State machine
let state = 'fingers';
const captures = {}; // { fingers, thumb, fingers_other?, thumb_other? }

function capturePhoto(photoType) {
  const canvas = document.createElement('canvas');
  canvas.width  = video.videoWidth;
  canvas.height = video.videoHeight;
  canvas.getContext('2d').drawImage(video, 0, 0);
  return new Promise(resolve => {
    canvas.toBlob(blob => {
      captures[photoType] = blob;
      resolve();
    }, 'image/jpeg', 0.92);
  });
}

// On submit — POST all collected blobs
async function submitSizing() {
  const form = new FormData();
  Object.entries(captures).forEach(([type, blob]) => {
    form.append('photos[]', blob, `${type}.jpg`);
    form.append('photo_types[]', type);
  });
  form.append('_token', csrfToken);
  return fetch('/order/sizing-photos', { method: 'POST', body: form });
}
```

### Brightness Check (canvas sampling — unchanged from previous spec)

```js
function checkBrightness(video) {
  const sample = document.createElement('canvas');
  sample.width = 80; sample.height = 60;
  sample.getContext('2d').drawImage(video, 0, 0, 80, 60);
  const data = sample.getContext('2d').getImageData(0, 0, 80, 60).data;
  let sum = 0;
  for (let i = 0; i < data.length; i += 4) {
    sum += (data[i] + data[i+1] + data[i+2]) / 3;
  }
  return sum / (data.length / 4); // 0–255
}
// < 60 = too dark; > 210 = too bright; 60–210 = good
```

### Edge-contrast heuristic (NEW — drives the green/red alignment border)

```js
function checkEdgeContrast(video, regionType /* 'fingers' or 'thumb' */) {
  // Sample the region of the video that corresponds to the SVG overlay's
  // expected nail row position. For 'fingers' that's a wide horizontal strip
  // ~40% from the top; for 'thumb' it's a narrow vertical strip ~30% from the top.
  const region = regionType === 'fingers'
    ? { x: 0.10, y: 0.30, w: 0.80, h: 0.20 } // normalised
    : { x: 0.35, y: 0.20, w: 0.30, h: 0.30 };

  const c = document.createElement('canvas');
  c.width = 200; c.height = 200;
  c.getContext('2d').drawImage(
    video,
    region.x * video.videoWidth, region.y * video.videoHeight,
    region.w * video.videoWidth, region.h * video.videoHeight,
    0, 0, 200, 200
  );

  // Quick Sobel-style edge strength: compare each pixel's luminance to its right neighbour.
  const data = c.getContext('2d').getImageData(0, 0, 200, 200).data;
  let edges = 0;
  for (let i = 0; i < data.length - 4; i += 4) {
    const a = (data[i] + data[i+1] + data[i+2]) / 3;
    const b = (data[i+4] + data[i+5] + data[i+6]) / 3;
    if (Math.abs(a - b) > 30) edges++;
  }
  const ratio = edges / (200 * 200); // 0–1
  if (ratio > 0.12) return 'green';
  if (ratio < 0.06) return 'red';
  return 'amber';
}
```

The heuristic does not block capture. It just paints the overlay border. A user shooting on a busy textured background may see amber/red constantly even with a perfect hand position — the size guide recommends a dark plain cloth backdrop to make the heuristic reliable.

### Constraints & Gotchas

- **HTTPS required** in production for `getUserMedia`. Dev: localhost is exempt.
- **iOS Safari** — supported since iOS 11. Older devices → fallback automatically.
- **Camera permission asked once** for the whole session. Switching from State B (fingers) to State C (thumb) does not re-prompt.
- **HEIC files** (iPhone upload fallback) — convert server-side via `intervention/image` to JPEG before saving.
- **EXIF strip** server-side on all uploads (live-camera blobs have no EXIF, but file uploads do).
- **Filename:** ULID on save (e.g., `01HX3Y2Z...jpg`). Never original filename.
- **Storage path:** `storage/app/public/sizing/{order_id}/` — private, signed URL in Filament.
- **Per-photo retake** preserves the other captures in memory — only the retaken state's blob is replaced.

---

## UX Notes

- **Never auto-request camera permission.** Always show "Start camera" with clear explanation copy.
- **Permission asked once per session** — the state machine pattern keeps this lightweight.
- **The shutter is human-controlled.** Heuristics guide; they do not auto-capture. This is intentional — auto-capture on a heuristic would mis-fire on busy backgrounds and erode trust.
- **The symmetry disclaimer is explicit, not buried.** Customers should know we're sizing both hands from one set of photos. Hiding this would erode trust if they noticed later.
- **Module is self-contained** (`camera-capture.js`). Does not depend on the rest of `app.js`.
- **No external library** for camera access. Native `getUserMedia` + canvas is sufficient.
- **On iOS Safari:** `capture="environment"` on `<input type="file">` opens the camera directly — useful for the fallback path.
- **Accessible fallback:** the 2-input upload fallback works on every device and browser. Camera is progressive enhancement.

---

## Assets Needed

- [ ] **SVG overlay — Fingers** (`public/images/sizing-overlay-fingers.svg`) — 4 dashed rectangles in a horizontal row + coin circle above middle finger + alignment border outline
- [ ] **SVG overlay — Thumb** (`public/images/sizing-overlay-thumb.svg`) — single tall dashed rectangle + coin circle above thumbnail + alignment border outline
- [ ] 4 good-example sizing photo thumbnails (2 fingers examples + 2 thumb examples) for the fallback UI's example strip
- [ ] Test on: iPhone (Safari + Chrome), Android Chrome, desktop Chrome (permission denied flow)
- [ ] HTTPS/staging environment for full camera testing before launch
- [ ] Confirm the 4 example thumbnails on `/size-guide` match the new 2-photo flow (good vs. bad framing examples for both fingers and thumb)
