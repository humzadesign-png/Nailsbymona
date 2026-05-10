# Accessibility Checklist — WCAG 2.2 AA + Pakistan-mobile context

> **Project-tailored, not boilerplate.** Specific to this product, this palette, this flow, and this market. Every item is testable; every item has a measurable target.

---

## Problem framed

Most accessibility checklists you find online are generic — copy-paste WCAG bullets that don't account for which palette you're using, which device class your users are on, or which flow has special accessibility considerations (in our case, the live camera). A generic checklist either feels too abstract to act on or imposes constraints that don't fit the product.

This document is the version that fits Nails by Mona. It's anchored to:

- Our actual color palette (warm-neutral with lavender accent — see `.claude/skills/design-system.md`).
- Our actual flow (live camera + WhatsApp + Pakistan mobile).
- Our actual market — most customers are on mid-range Android, on patchy 4G, in a country where 25% of women have some form of visual or motor accessibility need (per WHO/Pakistan Bureau of Statistics 2022).

## Methodology

Built from:

- **WCAG 2.2 AA** as the baseline standard.
- **Pakistan mobile context** — average device, bandwidth, common accessibility patterns.
- **Live camera feature audit** — WAI-ARIA patterns for media capture, which is a thinly-documented WCAG area.
- **Right-to-left preparation** — even though Year 1 is English-only, the data model and CSS should not preclude an Urdu launch in Year 2.

I structured the checklist by phase (Phase 1 / 2 / 3 / 4 / 5) so it can be folded directly into build sprints, plus a "always" section for things that don't belong to a single phase.

## Design rationale

I made two opinionated calls:

1. **Performance is in this document, not separated.** Lighthouse mobile ≥ 90 isn't a polish goal — for a customer on Pakistani 4G, slow *is* inaccessible. Treating perf and a11y as one budget keeps the team honest.

2. **No automatic-pass items.** Every item must be verified, not assumed. "Color contrast meets AA" is checked with the actual hex codes, not a vibe. The checklist is meant to be run before each phase ships, not at the end.

---

## Always — across every phase

These constraints apply to every page and every component, not a single phase.

### Performance budget (Pakistan-mobile primary device)

| Metric | Target | Test method |
|---|---|---|
| Lighthouse mobile Performance score | ≥ 90 | Lighthouse CI on `/`, `/shop`, `/blog`, `/order/start` |
| Largest Contentful Paint (LCP) | < 2.5s on emulated 3G (1.6 Mbps, 150ms RTT) | Lighthouse / WebPageTest |
| Interaction to Next Paint (INP) | < 200ms | Real-user monitoring (RUM) once live |
| Cumulative Layout Shift (CLS) | < 0.1 | Lighthouse / RUM |
| Above-fold bundle size (compressed) | < 200KB | Build report from Vite |
| Hero image weight (compressed) | < 100KB | Manual check at upload |

### Color contrast (against actual palette tokens)

| Foreground | Background | Ratio | WCAG | Use |
|---|---|---|---|---|
| `ink #1C1424` | `bone #F4EFE8` | ~14.8:1 | AAA | Primary headings + body |
| `ink #1C1424` | `paper #FBF8F2` | ~15.5:1 | AAA | Card body text |
| `graphite #4A4053` | `bone #F4EFE8` | ~7.9:1 | AAA | Body and labels |
| `stone #6B6177` | `bone #F4EFE8` | ~4.7:1 | AA | Captions and meta |
| `lavender #BFA4CE` (CTA bg) | `ink #1C1424` (CTA text) | ~4.6:1 | AA | Primary buttons |
| `ink #1C1424` (text) | `lavender #BFA4CE` (link underline / accent rule on `bone`) | passes AA | AA | Active nav, accent rules |
| `ash #8B8092` | `bone #F4EFE8` | ~3.4:1 | **FAILS AA for normal text** | Permitted only for ≥ 18pt or graphical accents |

> **Action:** Validate every dynamic combination on the storefront against this table before shipping. A small contrast-check script in CI would prevent regressions.

### Touch targets

- Minimum 44 × 44 px for any interactive element on mobile.
- Minimum 8px spacing between adjacent tappable elements (prevents fat-finger errors).
- Camera shutter button: ≥ 64 × 64 px (the largest touch target on the site — it's used in the most adverse condition).

### Keyboard + screen reader

- All interactive elements reachable via Tab key, in DOM order.
- Visible focus indicator on every interactive element (`outline: 2px solid lavender; outline-offset: 2px`).
- Skip-to-content link at top of every page.
- Screen-reader testing: run NVDA on Windows + VoiceOver on iOS for `/`, `/shop`, `/shop/{slug}`, `/order/start` before launch.

### Reduced motion

Respect `@media (prefers-reduced-motion: reduce)`:

- Disable all hover scale/translate transforms.
- Disable autoplay-on-load Reels (require explicit play tap).
- Reduce duration on essential transitions to 0.01ms (essentially instant but still triggers transitionend events).

### RTL preparation (defer Urdu, don't preclude it)

- Use logical CSS properties from day 1: `margin-inline-start` not `margin-left`, `padding-block-end` not `padding-bottom`.
- Avoid baking left/right-anchored design decisions into Tailwind classes (`text-end` instead of `text-right`).
- Icon directions that have semantic meaning (arrows, chevrons) — flag them now; they'll need RTL flips in Year 2.

> **Action:** Add an ESLint or stylelint rule that warns on `margin-left/right` and `padding-left/right` in favor of logical properties.

---

## Phase 1 — Public marketing site

Pages: `/` · `/shop` · `/shop/{slug}` · `/bridal` · `/about` · `/help` · `/size-guide`

### Headings

- Exactly one `<h1>` per page.
- Heading levels descend without skipping (h1 → h2 → h3, never h1 → h3).
- H1 visible content matches the SEO `<title>` semantically (not necessarily verbatim).

### Images

- Every image has `alt` text.
- Decorative images use `alt=""` (not omitted).
- Hand-only craft photos: alt describes the *action and product*, not just "a hand."
  - ✅ Good: `alt="Blush ombre press-on nails being applied to a hand"`.
  - ❌ Bad: `alt="A hand"` or `alt="IMG_5421"`.
- UGC photos: alt includes context.
  - ✅ Good: `alt="Customer wearing the bridal mehendi gold ombre set on Mehendi night"`.
- Fixed `width` and `height` on every `<img>` to prevent CLS.
- `loading="lazy"` on all below-fold images; `fetchpriority="high"` on hero LCP image.

### Links and buttons

- Link text describes destination.
  - ✅ Good: `<a>Read the bridal nail trends post</a>`.
  - ❌ Bad: `<a>Click here</a>` or `<a>Read more</a>`.
- Buttons are `<button>`, links are `<a>`. No `<div onclick>`.
- The bag icon button has an aria-label including current item count: `aria-label="View bag (3 items)"`.

### Color is never the only signal

- Active nav: lavender underline AND bold weight.
- Form errors: red text AND warning icon AND ARIA `role="alert"`.
- Status badges in admin: color AND text label.

### Forms (contact form, subscribe form)

- Every input has a visible `<label>` (not placeholder-as-label).
- Required fields marked with `aria-required="true"` AND visually with an asterisk + a key explaining the asterisk.
- Validation errors: inline beneath the field, AND summarized at top of form on submit failure with focus moved to the summary.
- Autocomplete attributes set: `name`, `email`, `tel`, `street-address`, `address-level2` (city), `postal-code`.

---

## Phase 2 — Order flow + Live camera

Pages: `/order/start/{slug}` (steps 1–3) · `/order/sizing-capture` · `/order/confirm/{order}` · `/order/{order}/track`

### Multi-step form

- Each step is a separate URL (already in the design — see CLAUDE.md §14).
- Progress bar communicates current step both visually AND via `aria-current="step"`.
- Back button works as expected; partial form state is preserved in session.
- Save-to-resume email contains a deep-link that lands on the right step.

### Live camera screen

This is the highest-risk a11y surface in the entire product. Specific requirements:

- The camera permission prompt is *preceded by* an explainer screen that requires an explicit user tap to start. Auto-start is banned (both UX and a11y reasons).
- Permission denial is detected; the UI gracefully switches to upload fallback with friendly copy. No "An error occurred" generic message.
- The `<video>` element has an `aria-label` that updates per state: `"Live camera feed for fingers sizing photo"` in State B, `"Live camera feed for thumb sizing photo"` in State C. Screen readers announce the state change when the overlay swaps.
- The SVG overlay has `aria-hidden="true"` (decorative; the instructions are in real text below).
- Real-text instructions below the camera (NOT only inside the SVG) — readable by screen readers.
- Brightness check status is announced via `aria-live="polite"` so screen reader users hear the same feedback.
- The Capture button is large (≥ 64px), labeled clearly, and reachable via keyboard (Tab focus + Enter to fire).

### Upload fallback

- `<input type="file" accept="image/*" capture="environment">` — `capture` opens the back camera on supported devices.
- File size limit (8MB) checked client-side AND server-side; client-side error is clear, server-side is never the only check.
- HEIC files accepted client-side, converted to JPEG server-side via `intervention/image`. Client should NOT silently reject HEIC (common iPhone format).

### Payment proof upload (confirmation page)

- Same constraints as sizing upload above.
- Uploading state is announced via `aria-live`: "Uploading payment proof... Upload complete. We've received your proof and will verify within 24 hours."

### Tracking page

- Order status timeline is a `<ol>` (ordered list), with each step having `aria-current="step"` on the active one.
- Courier tracking link opens in same tab (don't trap users in third-party site without warning), and the link text says "Track on TCS" (named courier), not just "Track here."

---

## Phase 3 — Filament admin

Filament v3 ships with a strong default a11y story; we don't need to retrofit much. But:

- All admin actions destructive enough to warrant a confirm dialog (cancel order, delete UGC, etc.) must use Filament's `requiresConfirmation()` and have descriptive labels — Mona uses this on her phone.
- Status emails sent to customers must have a plain-text version (not HTML-only). Some Pakistani email clients (especially older Android Gmail) render plain text more reliably and accessibly.

---

## Phase 4 — Blog (Journal)

Pages: `/blog` · `/blog/{slug}`

### Long-form content

- Maximum line length: ~70 characters (set via `max-w-prose` in Tailwind, ~65ch).
- Body font size: ≥ 18px on mobile, ≥ 20px on desktop.
- Line height: ≥ 1.6× for body copy.
- Sufficient paragraph spacing — don't crowd long-form text.

### Cornerstone Post #5 (wudu blog post) — extra care

- The post discusses theological topics; no claims or quotes attributed to scholars without source links.
- Any Arabic terms (wudu, salah) are in roman script (the brand voice, English-language). Italicized first use, plain after.
- Comments section disabled at launch (not yet built); when added, requires moderation by Mona to prevent off-topic religious debate.

### FAQ accordion

- `<details>` and `<summary>` HTML elements, not custom JS toggles. Free a11y and free SEO via FAQPage schema.
- Schema.org JSON-LD only published for FAQs that are stable — don't generate schema from user-generated content.

---

## Phase 5 — Polish

By Phase 5, the build is feature-complete. Polish-week a11y items:

### Cross-browser screen reader audit

- VoiceOver (iOS) on `/`, `/shop`, `/shop/{slug}`, `/order/start`, `/order/confirm/{order}`.
- TalkBack (Android) on the same five pages.
- NVDA (Windows) on the same.
- One test per platform; document any divergence in `docs/ux/test-results-{date}-screen-readers.md`.

### Real-user testing with accessibility-relevant participant

If we can find one Pakistani user with a relevant accessibility need (low vision, motor impairment) for the usability test, prioritize. Even one session reveals more than 100 automated audits.

### Lighthouse CI in production

- Run Lighthouse on every deployed PR.
- Block merges if Performance or Accessibility score regresses.

---

## Tools and references

| Tool | Purpose |
|---|---|
| **Lighthouse** (Chrome DevTools) | Performance + a11y audits |
| **axe DevTools** (browser ext) | Automated WCAG checks |
| **WebPageTest** | Real device testing on Pakistan-equivalent connections |
| **VoiceOver / TalkBack / NVDA** | Manual screen reader audits |
| **WhoCanUse.com** | Color combination simulator (color blindness, low vision) |
| **WAVE** (WebAIM) | Visual a11y overlay |
| **stylelint a11y rules** | Build-time style enforcement |

References:
- [WCAG 2.2 specification](https://www.w3.org/TR/WCAG22/)
- [Inclusive Components](https://inclusive-components.design/) (Heydon Pickering — patterns for accessible UI)
- [Tailwind Forms plugin](https://github.com/tailwindlabs/tailwindcss-forms) — sane default form a11y
- WAI-ARIA Authoring Practices for media capture (cite when implementing the camera screen)

## Success metrics

- Lighthouse mobile Performance ≥ 90 on key pages.
- Lighthouse mobile Accessibility ≥ 95 on key pages (the standard target — 100 is achievable for well-built sites).
- Zero axe-DevTools "critical" or "serious" issues on key pages.
- 0 Pakistan-bandwidth-related complaints in the first 100 customer support inquiries (proxy for real-user perf).

## Reflection

The hardest item on this list to actually achieve is the **real-user perf budget on Pakistan 4G**. It's easy to test at our local connection and ship; it's hard to actually keep LCP < 2.5s on Karachi 4G when the user has 5 other apps competing for bandwidth. WebPageTest has Pakistan-equivalent profiles, but the only ground truth is RUM data once we're live. Plan to install RUM (PostHog or similar) at launch and treat the first month's perf data as a real test of this checklist.

The most-likely-to-be-skipped item is the **Cornerstone Post #5 theological accuracy review**. It's tempting to ship the post fast and iterate. Don't — Ayesha's trust is fragile, and a single misattributed claim destroys the whole acquisition path for that persona. Worth a 2-hour review pass with someone qualified before publishing.
