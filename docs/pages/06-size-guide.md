# Page: Size Guide `/size-guide`

> ⚠ **2026-04-29 design revision applies.** Canonical design tokens, nav pattern, photography rules, copy guidelines, and CTA labels are in `00-global.md` and `.claude/skills/design-system.md`.
>
> ⚠ **2026-05-07 sizing redesign applies.** Moved from one full-hand photo to **2 close-up photos** (fingers row + thumb) of one hand, with optional opt-in for 2 more photos for the other hand. See CLAUDE.md §8 + `docs/pages/12-sizing-capture.md` for the technical spec.

---

> Design tokens, typography, and patterns: see `00-global.md` + `.claude/skills/design-system.md` — those files are authoritative.

---

**Purpose:** Kill the "won't fit my nails" objection completely. Make sizing feel easy, visual, and trustworthy — and set expectations honestly that we measure one hand and assume the other matches (with an opt-in for perfectionists and a free first refit as a safety net).
**Template:** `resources/views/size-guide.blade.php`
**Layout:** `layouts.app`
**Schema.org:** `HowTo` (with HowToStep) + `BreadcrumbList`

---

## SEO

| Field | Value |
|---|---|
| H1 | Getting your size right — it takes about 90 seconds. |
| Meta title | Press-On Nail Size Guide — How to Measure for Custom Fit \| Nails by Mona |
| Meta description | Step-by-step guide to taking your 2 sizing photos for custom press-on nails. Use your phone camera and any coin. Includes good/bad photo examples. |
| Canonical | `https://nailsbymona.pk/size-guide` |
| OG image | Overhead photo of fingers row with a coin — example sizing photo |
| Breadcrumb | Home > Size Guide |
| Target keywords | "how to size press on nails", "custom press on nail sizing Pakistan" |

---

## Section 1 — Hero

**Background:** `bg-paper`

**H1 (large serif, font-light):**
> Getting your size right — it takes about 90 seconds.

**Subheadline:**
> No tape measures, no guessing. Just your phone camera, any coin, and 2 close-up photos.

**CTAs:**
- Primary: "Start your order" → `/order/start`
- Secondary (text link): "Get help" → WhatsApp (pre-filled: "Hello Nails by Mona, I need help with my sizing photos.")

---

## Section 2 — "Before you start"

**Background:** `bg-paper`

**Section label:** WHAT YOU'LL NEED
**H2:** Before you start.

**4 items (icon + heading + copy):**

1. **Your smartphone** (`camera.svg`)
   The back camera gives better image quality than the front. Any phone from the last 5 years works.

2. **Any coin** (`coin.svg`)
   A Rs. 5 or Rs. 10 coin is ideal, but any coin you have works — just mention which one when you submit. The coin gives me a known size to compare your nails against, so I can calculate exact widths.

3. **A dark cloth or surface** (`flower.svg` placeholder — replace with cloth icon)
   A dark fabric (sweater sleeve, cushion cover, dark towel) is best — it makes your nail edges stand out and helps the in-app camera guide read the position correctly.

4. **Natural light** (Phosphor `sun` icon)
   A window in daylight is ideal. Avoid harsh overhead lighting or direct flash — it creates shadows that obscure the nail edges.

**Note below (small, italic):**
> That's genuinely it. No printer. No ruler. No measuring tape. No trip to a salon.

---

## Section 3 — The 2 Photos

**Background:** `bg-shell`

**Section label:** THE PHOTOS
**H2:** Two close-ups, one minute.

**Intro paragraph:**
> I size your nails by reading their width directly off the coin in your photo. To do that I need to see the nails up close — not your whole hand from the wrist out. Two photos, both close up: one for your fingers, one for your thumb.

---

### Photo 1 — Your fingers

**Photo/illustration:** Overhead close-up — 4 fingers (index, middle, ring, pinky) laid flat in a row, thumb tucked behind, coin placed above the middle finger. Macro distance — fingers fill most of the frame.

> Lay your hand flat against a dark cloth. Tuck your thumb behind so just the four fingers show in a flat row. Place a coin above your middle fingernail — close enough that I can see both the coin and all four nails clearly in one frame. Hold your phone straight overhead and snap.
>
> **Tip:** the coin should sit just above your nail bed, not on top of your fingertip. Touching is fine — covering the nail isn't.

---

### Photo 2 — Your thumb

**Photo/illustration:** Overhead close-up — thumb extended flat (vertical orientation), coin placed above the thumbnail.

> Now lay your thumb flat on the same cloth, extended out. Place the coin above your thumbnail — same idea, same close distance. Snap.
>
> **Tip:** thumbs are usually the trickiest fit because they curve more than fingers. A clear photo here saves you a refit later.

---

### Photo 3 & 4 (optional) — Your other hand

**Photo/illustration:** Same 2 photos, mirror image — left vs. right.

> If you'd like the most precise fit possible, you can take the same 2 photos for your other hand. We'll then size each hand independently. Otherwise, we'll size both hands from the photos you've already given us.

---

## Section 4 — Symmetry disclaimer (the "why 2 photos and not 4" block)

**Background:** `bg-paper`
**Layout:** Centred, max-w-2xl, in a `bg-shell rounded-2xl p-8` block

**H3:** Why 2 photos for both hands?

**Copy (warm, plain):**
> Most hands are symmetric within half a millimetre — well within the press-on fit tolerance. Asking everyone for 4 photos when 95% of customers don't need them adds friction without adding accuracy.
>
> So by default, we size both hands from your fingers + thumb on one side, and we mirror the result. If you'd like a perfect-fit set you can opt in to 2 more photos at the end of the camera flow — takes another minute. Either way, **the first refit is free**, no questions, if anything doesn't sit right.

**Below the block (small, `text-caption text-stone`):**
> Have unusually different hands (e.g. one is much larger after an injury, or you're right-side dominant in a way that's reshaped your nails)? Take all 4 photos — it'll be worth the extra minute.

---

## Section 5 — Good vs. Bad Examples

**Background:** `bg-paper`

**Section label:** QUICK REFERENCE
**H2:** What I can and can't work with.

**4-column grid (2 cols on mobile), 8 tiles (4 good, 4 bad):**

**Good examples (green ✓ badge):**
1. **Fingers, well-framed** — 4 fingers laid flat, coin clearly above middle finger, dark backdrop, even light ✓
2. **Thumb, well-framed** — thumb extended flat, coin above thumbnail, dark backdrop, in focus ✓
3. **Sharp focus** — nail edges clearly visible, coin readable ✓
4. **Even lighting** — no harsh shadow across the nail row ✓

**Bad examples (red ✗ badge):**
1. **Too far away** — fingers + coin look small, edges blurry — can't measure accurately ✗
2. **Angled shot** — fingers look shorter than they are, measurements will be wrong ✗
3. **Coin missing** — can't calculate scale ✗
4. **Dark / overexposed** — nail edges blur into the background ✗

---

## Section 6 — Live Camera Guide

**Background:** `bg-paper`
**Layout:** 2-column — text left, phone mockup right. Mobile: mockup below text.

**Section label:** EVEN EASIER
**H2:** Use our in-app camera guide.

**Copy:**
> When you place an order, there's a live camera screen built right into the process. It walks you through both photos in a single session — fingers first, then thumb — with an on-screen guide and a green/red border that shows you when each frame is well-framed. The guide does the framing for you.
>
> Works on most Android and iPhone cameras from the last few years. If your camera isn't supported, the upload option works exactly the same — just 2 file inputs.

**Image:** Phone mockup showing the live-camera screen with the fingers SVG overlay + coin circle + green alignment border (the moment everything is correctly aligned). Caption: *"The border turns green when you're in the right position — tap when you're ready."*

**CTA:** "Try the camera guide" → `/order/sizing-capture` (primary pill)

---

## Section 7 — Returning Customers

**Background:** `bg-paper`

**H3:** Already ordered from me before? Your sizing is on file.

**Copy:**
> If you've ordered from me before and provided sizing photos, I keep them saved. When you reorder, just look up your previous order with your phone and email — your sizing is already on file, no re-measuring needed. Reorders also get a 10% discount.

**CTA:** WhatsApp (pre-filled: "Hello Nails by Mona, I'd like to reorder — I have sizing on file from a previous order.")

---

## Section 8 — "Not Sure? Ask First."

**Background:** `bg-paper`

**H3:** Not sure if your photos are right? Send them to me first.

**Copy:**
> If you're unsure whether either photo will work — maybe the lighting was off or you couldn't get the coin in the right spot — just send them to me on WhatsApp before placing your order. I'll tell you immediately whether they're usable or if we need a retake. Takes 5 minutes and saves everyone time.

**CTA:** WhatsApp (pre-filled: "Hello Nails by Mona, I'd like to check my sizing photos before I place my order.")

---

## Internal Links from This Page

| Destination | Trigger |
|---|---|
| `/order/start` | "Start your order" (hero) |
| `/order/sizing-capture` | "Try the camera guide" |
| WhatsApp | Hero + Section 7 + Section 8 |

---

## Assets Needed

- [ ] **Photo 1** — Fingers laid flat (4 fingers) with coin above middle finger, dark cloth backdrop, overhead close-up
- [ ] **Photo 2** — Thumb extended flat with coin above thumbnail, dark cloth backdrop, overhead close-up
- [ ] 4 good-example tiles for Section 5 — Fingers good, Thumb good, Sharp focus, Even lighting
- [ ] 4 bad-example tiles for Section 5 — Too far, Angled, No coin, Dark/overexposed
- [ ] **Phone mockup** showing live-camera screen with the **fingers** SVG overlay + green alignment border (Section 6)
- [ ] (Optional, polish) — second phone mockup variant showing the thumb overlay, alternated via CSS animation in Section 6

*All photos should use Mona's own hands — keeps it authentic. Hand-only — no face, never. (Locked photography rule, CLAUDE.md §24.)*
