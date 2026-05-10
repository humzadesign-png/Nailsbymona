# Service Blueprint — Frontstage and backstage, one whole service

> **Five swimlanes across 12 stages.** The journey maps show what a customer experiences. The service blueprint shows everything that has to happen — and everything that can fail — to make that experience real.

---

## Problem framed

A customer doesn't see the difference between a slow status email (frontstage) and a slow Filament workflow (backstage). They just see "this brand was slow." Service blueprints surface those connections — every customer-facing action depends on a chain of backstage actions, and most of the highest-impact UX investments are actually operational, not visual.

## Methodology

Five swimlanes across 12 stages of a complete order:

1. **Customer Actions** — what the customer does.
2. **Frontstage** — what they see (site UI, emails, WhatsApp messages, package).
3. **Backstage** — what Mona does (in Filament admin, in the studio, at the post office).
4. **Support Processes** — systems and external services that enable the action (Laravel, intervention/image, courier APIs, payment gateways).
5. **Failure points + recovery** — where things break and how the design recovers.

Sourced from journey maps in [`04-journey-maps.md`](04-journey-maps.md), the data model (CLAUDE.md §6), the flows (CLAUDE.md §7), and the order-form spec (`docs/pages/11-order-form.md`).

## Design rationale

Twelve stages, not twenty. I collapsed micro-stages (e.g., "open product card," "view image gallery") into single stages (Browse / PDP) because they share the same backstage. The blueprint stays useful at this granularity; finer-grained breakdowns belong in the user flows document.

I added a sixth implicit dimension — **handoffs** — which I call out at the end. Handoffs are where service quality drops most often. The two highest-risk handoffs (sizing photo quality, payment-proof verification) are the operational priorities for launch.

---

## The blueprint

### Legend

```
🟦 Customer action      🟨 Frontstage          🟩 Backstage (Mona)
🟧 Support process       🟥 Failure point        ➡ Recovery path
```

---

### Stage 1 — Discover

| Lane | What happens |
|---|---|
| 🟦 **Customer** | Sees an Instagram Reel; saves it. |
| 🟨 **Frontstage** | Reel video · IG bio link · IG Story posts · TikTok crossposts. |
| 🟩 **Backstage** | Mona schedules 3–4 Reels/week using a content calendar. Records once a week, posts daily. |
| 🟧 **Support** | Instagram + TikTok + Pinterest content workflow. Phosphor icon set used in Reel overlays for brand consistency. |
| 🟥 **Failure** | Reel reaches the customer but bio doesn't link to the website. |
| ➡ **Recovery** | Caption template requirement: every Reel ends with "nailsbymona.pk in bio." Pinned post tells visitors to "tap the link in bio." |

---

### Stage 2 — Land

| Lane | What happens |
|---|---|
| 🟦 **Customer** | Taps the bio link. Site loads. |
| 🟨 **Frontstage** | `/` (home) — hero hand photo, brand pillars trust bar, featured products, journal teasers. |
| 🟩 **Backstage** | None — site is fully cached / static at this point. Mona doesn't intervene. |
| 🟧 **Support** | Laravel SSR (no JS framework) → Lighthouse mobile ≥ 90 budget. CDN caching for static assets. spatie/laravel-sitemap. |
| 🟥 **Failure** | Site slow on Pakistan 4G. LCP > 4s = customer bounces. |
| ➡ **Recovery** | Performance budget enforced: LCP < 2.5s on emulated 3G; total above-fold transfer < 200KB. WebP + AVIF + JPEG fallback at 3 sizes. |

---

### Stage 3 — Browse

| Lane | What happens |
|---|---|
| 🟦 **Customer** | Taps Shop. Filters by tier. Taps 3 designs. |
| 🟨 **Frontstage** | `/shop` — product grid, filter bar (visible by default), sort options. |
| 🟩 **Backstage** | Mona maintains the product catalog in Filament. Adds new designs ~weekly. Marks `is_featured` for home rotation. |
| 🟧 **Support** | Filament `Products` resource. `intervention/image` v3 for thumbnails. ULID filenames for storage. |
| 🟥 **Failure** | Filter UI is collapsed behind an icon; takes the customer 8s to find. |
| ➡ **Recovery** | Filter bar visible-by-default on `/shop`. Mobile uses sticky horizontal scroll, not a hidden modal. |

---

### Stage 4 — PDP and Add to bag

| Lane | What happens |
|---|---|
| 🟦 **Customer** | Reads PDP, looks at UGC, taps "Add to bag." |
| 🟨 **Frontstage** | `/shop/{slug}` — product images, description, "Add to bag" CTA, UGC carousel, FAQ accordion, related products. |
| 🟩 **Backstage** | Mona adds 4–6 UGC photos per product as customers send them in. Sets `face_visible` flag in `ugc_photos` table — `true` = never published. |
| 🟧 **Support** | `ugc_photos` table with placement enum + `face_visible` flag. `localStorage` (`nbm.bag` key, JSON) for persistence. |
| 🟥 **Failure** | UGC count too low; only 1–2 hand photos per product. |
| ➡ **Recovery** | Pre-launch task: ensure top 8 products have 4+ UGC photos before launch. Refresh weekly. |

---

### Stage 5 — Sizing capture

| Lane | What happens |
|---|---|
| 🟦 **Customer** | Live camera (Flow 2) or upload (Flow 3) or WhatsApp later. **2 close-up photos (fingers + thumb)** at MVP, with optional opt-in for 2 more photos for the other hand. |
| 🟨 **Frontstage** | `/order/start` step 1 — live camera UI with **state-machine overlays** (fingers row → thumb), brightness pill, green/red alignment border, preview screen with both thumbnails + per-photo retake + symmetry disclaimer + perfectionist opt-in. |
| 🟩 **Backstage** | Mona reviews **both photos** within 24 hours of order receipt. Reads nail widths off the coin reference in each. If either photo is poor (one OK, one not), she WhatsApps the customer to re-shoot just the bad one — they don't lose the good photo. |
| 🟧 **Support** | `getUserMedia()` for camera (single permission for the whole session); `<canvas>.toBlob()` for capture; `intervention/image` for HEIC→JPEG + EXIF strip. `sizing_capture_method` enum tracks path; `photo_type` enum on `order_sizing_photos` tracks fingers/thumb/fingers_other/thumb_other. |
| 🟥 **Failure** | **Highest-risk handoff in the entire service.** One or both photos unusable (too dark, hand off-frame, no coin in shot). Edge-contrast heuristic false-positives on busy backgrounds (textured carpet, patterned fabric). |
| ➡ **Recovery** | Four layers: (1) brightness pill warns the customer in-camera. (2) Green/red alignment border guides per-frame. (3) Preview screen + per-photo retake — customer can re-shoot just the bad photo without losing the good one. (4) Mona reviews + WhatsApps a targeted re-shoot request if needed (specifies which of the 2 photos was the problem). |

---

### Stage 6 — Details + Payment

| Lane | What happens |
|---|---|
| 🟦 **Customer** | Fills name/address/WhatsApp; selects payment method; submits. |
| 🟨 **Frontstage** | `/order/start` steps 2 + 3 — form validation, advance gate for orders ≥ PKR 5,000. |
| 🟩 **Backstage** | None — fully automated. |
| 🟧 **Support** | Laravel FormRequest validation. `couriers.php` config. `+92` autocomplete for WhatsApp. Cities dropdown sourced from a fixed list. |
| 🟥 **Failure** | Customer types phone in wrong format and gets validation error mid-flow. |
| ➡ **Recovery** | `+92` is pre-filled and read-only; customer just types the rest. Validation errors are inline + above-field, not above-form. |

---

### Stage 7 — Order placed + Payment proof

| Lane | What happens |
|---|---|
| 🟦 **Customer** | Lands on confirmation page. Transfers funds via JazzCash / EasyPaisa / Bank to listed account. Uploads payment screenshot. |
| 🟨 **Frontstage** | `/order/confirm/{order}` — order number prominent; account details for selected method; payment-proof upload field (AJAX). |
| 🟩 **Backstage** | Mona receives email + Filament notification of new order. Reviews payment proof in Filament within 24 hrs. Marks `payment_status = paid` (clean) or `verifying` (needs clarification). |
| 🟧 **Support** | `order_payment_proofs` table. Signed URLs for private file access in Filament. Mailtrap (dev) / SMTP (prod) for transactional emails. |
| 🟥 **Failure** | Customer pays but forgets to upload proof. Customer uploads unclear/incomplete proof. Customer never pays at all. |
| ➡ **Recovery** | Auto-email at 24h with proof-upload link, again at 48h, auto-cancel at 72h. Unclear proof → Mona marks `verifying` + WhatsApps customer for clarification using brand-addressed pre-fill. Cancelled orders can be restored if customer reaches out. |

---

### Stage 8 — Production + Status updates

| Lane | What happens |
|---|---|
| 🟦 **Customer** | Waits 5–9 days (10–14 for Bridal Trio). Receives status emails. |
| 🟨 **Frontstage** | Email cadence: "Confirmed" → "In Production" → "Shipped." `/order/{order}/track` page with timeline. |
| 🟩 **Backstage** | Mona updates order status in Filament: new → confirmed → in_production → shipped. Each transition fires a customer email. |
| 🟧 **Support** | Filament Orders kanban (5 columns). Laravel Notifications + SMTP for status emails. Mailable templates per stage. |
| 🟥 **Failure** | **Second-highest leak point.** Customer waits 5+ days with no proactive update; assumes the order is forgotten. |
| ➡ **Recovery** | Three status emails are non-negotiable; sent automatically on Filament status change. For Bridal Trio: extra mid-production WhatsApp photo on day 5. |

---

### Stage 9 — Shipping

| Lane | What happens |
|---|---|
| 🟦 **Customer** | Receives Shipped email with tracking link. Watches it on courier site. |
| 🟨 **Frontstage** | `/order/{order}/track` shows courier-specific tracking link from `config/couriers.php`. |
| 🟩 **Backstage** | Mona drops package at TCS / Leopards / M&P / BlueEx. Records tracking number in Filament. |
| 🟧 **Support** | `couriers.php` URL templates. `tracking_number` and `courier` fields on orders. |
| 🟥 **Failure** | Tracking link is courier-side, not branded; customer feels shunted to a third party. |
| ➡ **Recovery** | Brand-wrap the tracking page on our side: show the timeline + courier link side-by-side, with our brand frame around it. |

---

### Stage 10 — Receive + Unboxing

| Lane | What happens |
|---|---|
| 🟦 **Customer** | Receives package. Opens it. |
| 🟨 **Frontstage** | Physical: rigid box (Bridal) or padded mailer (Everyday/Signature). Tissue paper. Care card. Brush-on glue sample. Handwritten thank-you. QR code to application Reel. |
| 🟩 **Backstage** | Mona's packaging SOP: same materials every time, handwritten signature in lavender pen, batch-prepped care cards. |
| 🟧 **Support** | Packaging supply chain (one rotating supplier in Karachi). Care card design template. |
| 🟥 **Failure** | Care card runs out of stock; package ships without it; trust signal lost. |
| ➡ **Recovery** | Mona's restock SOP: orders 200 cards at a time when stock < 30; reordered automatically by a Filament dashboard reminder. |

---

### Stage 11 — Application + First wear

| Lane | What happens |
|---|---|
| 🟦 **Customer** | Watches application Reel via QR code. Applies. Wears for the event. |
| 🟨 **Frontstage** | QR-linked Reel + care card written instructions. |
| 🟩 **Backstage** | None — autonomous. |
| 🟧 **Support** | Application Reel hosted on IG; QR code on the printed care card. |
| 🟥 **Failure** | Sizing was wrong; nail doesn't fit. |
| ➡ **Recovery** | **Free first refit guarantee.** Customer messages on WhatsApp; Mona ships a re-fit set free; uses learnings to improve sizing photo guidance. |

---

### Stage 12 — Reorder OR Lapse

| Lane | What happens |
|---|---|
| 🟦 **Customer** | Either reorders within 6–8 weeks (Sana pattern) or lapses (need re-engagement). |
| 🟨 **Frontstage** | Returning-customer lookup (Flow 4); -10% reorder discount visible in bag drawer. |
| 🟩 **Backstage** | Mona watches retention numbers in Filament dashboard. Sends a hand-written "miss you" note to lapsed customers (>90 days, value ≥ PKR 3k) once a quarter. |
| 🟧 **Support** | `customers.last_ordered_at` field. `total_orders` and `lifetime_value_pkr` for segmentation. |
| 🟥 **Failure** | High-value customer lapses without re-engagement attempt. |
| ➡ **Recovery** | Quarterly Filament report: customers with `last_ordered_at` > 90 days AND `lifetime_value_pkr` ≥ PKR 3,000. Mona personally messages them. |

---

## The two highest-risk handoffs

When the blueprint is read end-to-end, two handoffs jump out as disproportionately likely to break the service. Both deserve operational priority.

### Handoff 1 — Sizing photos quality (Customer → Mona)

**The handoff:** Customer captures **2 close-up photos** (fingers + thumb), optionally 4 if they opt in to the perfect-fit path. Mona reads each nail's width off the coin reference in those photos to make a custom-fit set.

**Why it's risky:**
- The customer is the only person who can take the photos, and they're often non-experts in lighting and framing.
- Mona has no way to ask for a re-shoot in real time — there's a delay of hours between order and Mona's review.
- A poor photo + a custom-made set = a refit, costing material + Mona's time + customer trust.
- **The 2-photo flow has a partial-failure mode unique to itself:** one photo fine, the other unusable. This is more recoverable than the old single-photo flow (re-shoot just the bad one) but it's a real operational case Mona must triage.

**Mitigations layered into the design:**
1. **State-machine live camera (Flow 2)** — separate SVG overlays per photo type (4-finger row vs. single thumb), each with brightness pill + green/red edge-contrast border. Sets the customer up to succeed on each frame.
2. **Single permission session** — switching from fingers to thumb doesn't re-prompt, so abandonment between photos is minimal.
3. **Per-photo retake** on the preview screen — the customer doesn't have to redo the whole flow if just one photo is bad.
4. **Symmetry disclaimer + perfectionist opt-in** at preview time — the 5% who genuinely need 4 photos self-select; the rest stay on the fast 2-photo path.
5. **Mona reviews within 24 hours** and WhatsApps a *targeted* re-shoot request — "your fingers photo is great; please send me a clearer thumb photo." Specificity beats "please redo your sizing photo."
6. **Free first refit guarantee** covers the residual failure rate.

**Operational metric:** % of orders requiring a re-shoot request. **Target: < 6% by month 3** (revised down from < 8% on 2026-05-07 — the macro framing should let Mona accept more borderline-quality photos than the old wrist-out angle did).

### Handoff 2 — Payment-proof verification (Customer → Mona's Filament admin)

**The handoff:** Customer pays via JazzCash / EasyPaisa / Bank Transfer outside our system, then uploads a screenshot of the transaction. Mona reviews the screenshot in Filament admin, confirms the amount and recipient match, and marks `payment_status = paid`. Production begins.

**Why it's risky:**
- Mona is the only verifier — there's no automated reconciliation. If she's overwhelmed or delayed, orders pile up in `awaiting` and customers get nervous.
- Customers can pay but forget to upload proof — the order looks unpaid in our system even though Mona's account received the funds.
- Screenshot quality varies — blurry, cropped, or wrong-amount proofs slow the verification cycle.
- The only signal that an order is paid is what's in the screenshot — there's no server-side verification.

**Mitigations layered into the design:**
1. **24-hour SLA** — Mona checks Filament once or twice daily; the kanban surfaces oldest pending proofs first.
2. **Auto-reminder cadence** — customer who forgets to upload gets an automated email at 24h, again at 48h. Auto-cancel at 72h with a notification email; can be restored if they reach out.
3. **`verifying` status** — when a proof is unclear, Mona marks `verifying` (not `paid`), WhatsApps the customer using a brand-addressed pre-fill, and resolves quickly without rejecting the order outright.
4. **Filament dashboard widget** — counts pending proofs by age. Anything > 24h becomes a visual cue Mona can't miss when she opens the admin.
5. **No PCI scope** — payment happens entirely in customer's banking app; we never see card or wallet credentials. Cheapest compliance posture for an MVP.

**Operational metric:** Verification SLA — < 24 hours from proof upload to `payment_status = paid` for ≥ 90% of orders. `verifying` rate (proof needed clarification): ≤ 10% — anything higher signals confirmation-page copy or upload UI needs work.

> **Phase 6 (SafePay) replaces this handoff** for JazzCash, EasyPaisa, and (new) Card payments — webhook does the verification automatically. Bank Transfer remains manual. See CLAUDE.md §26 for the Phase 6 architecture.

---

## Cross-blueprint patterns

Three patterns appear across all 12 stages:

1. **Most "frontstage failures" are actually backstage failures.** Slow status emails feel like the email is broken; really, the Filament transition wasn't triggered. Care card missing from package feels like a packaging failure; really, the restock SOP didn't fire. The frontstage is downstream of the backstage; investing in backstage reliability is investing in customer experience.

2. **Mona is the single point of failure in 8 of 12 stages.** This is by design at this scale (solo + helpers), but a deliberate operational risk. If Mona is sick or away, the service degrades fast. The Filament dashboard + Quick Replies are partial mitigations; a longer-term mitigation (a part-time helper trained on the order workflow) is in the Y1 growth plan.

3. **Recovery paths are designed at every stage.** Every 🟥 has a ➡. There's no stage where the design says "this can't be recovered." That's intentional — a small online brand earns trust by handling failures well, not by promising they won't happen.

## Success metrics

- Re-shoot request rate (target: < 8%).
- Payment-proof upload completion rate (target: ≥ 90% of placed orders).
- Status-email open rate (target: ≥ 60% — high because it's transactional and expected).
- Mona's daily Filament time investment (target: < 90 min/day at 30 orders/month, ≤ 3 hrs/day at 100 orders/month).

## Reflection

The blueprint exposes the operational fragility of a one-woman studio. That's not a design flaw to fix — it's the reality the design is built around. Premature automation (chatbot, auto-sizing AI, full-stack payment gateway) would actually *hurt* the service in Year 1 because Mona's personal touch is the brand. The blueprint validates the deferral choices in CLAUDE.md (§32 sessions): keep things manual, build the digital surface around the human core.

The piece I'm least confident about is **Stage 10 (Unboxing)**. I described it from limited information — Mona's packaging is real but I haven't seen photos of every variant. The recommendations (handwritten note, brush-on glue sample, care card with QR) are best-practice patterns from comparable artisan brands; Mona may already do better. Verify with her before locking the SOP.
