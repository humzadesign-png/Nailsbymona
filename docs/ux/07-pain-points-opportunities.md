# Pain Points & Opportunities — Where the design earns its keep

> **A prioritized synthesis matrix.** Every pain surfaced across personas, journeys, and the blueprint, mapped to a specific UX fix, a phase, an effort estimate, and an expected impact. Closes with an Impact × Effort 2x2 that says clearly what to ship first.

---

## Problem framed

Across [`01-personas.md`](01-personas.md), [`04-journey-maps.md`](04-journey-maps.md), and [`06-service-blueprint.md`](06-service-blueprint.md), I've named ~30 distinct customer pains. Without a synthesis layer, those become a wishlist no team can prioritize. This document collapses them into a ranked matrix and a 2x2 plot — the kind of artifact a product manager can read in 4 minutes and use to make ship/defer decisions in a meeting.

## Methodology

For each pain I tracked:

- **Pain** — the named friction.
- **Affected persona(s)** — Sana / Hira / Ayesha / All.
- **Frequency** — how often it bites: Always · Often · Sometimes · Rare.
- **Severity (1–5)** — 1 = annoyance, 5 = customer is lost.
- **Current handling** — what we'd do today if we did nothing new.
- **Proposed UX fix** — the design response.
- **Phase to ship** — Phase 1 / 2 / 3 / 4 / 5 (per CLAUDE.md §11 build phases).
- **Effort** — Small (< ½ day) · Medium (½ – 2 days) · Large (> 2 days).
- **Expected impact** — qualitative + the metric we'd watch.

Then plotted the top 12 on an Impact × Effort 2x2.

## Design rationale

I deliberately did not write this as 30 separate pain cards. A long matrix is a feature here — when stakeholders ask "have we thought about X?", the matrix is the place to look. A short rendition would feel curated; the matrix is the whole picture.

I also did not assign owners. Ownership belongs in the build sprint planning, not the UX research layer.

---

## The matrix

| # | Pain | Affected | Freq. | Sev. | Current handling | Proposed UX fix | Phase | Effort | Expected impact |
|---|---|---|---|---|---|---|---|---|---|
| 1 | "I don't know if these will fit me" | All | Always | 5 | Customer either guesses or messages on WhatsApp | **Live camera with 2-photo state machine (fingers + thumb), SVG overlays per state, brightness pill + green/red edge-contrast border, preview checklist + symmetry disclaimer + perfectionist opt-in** (Flow 2 — rewritten 2026-05-07) | 2 | Large | Differentiator. Target ≥ 60% live-camera adoption. Refit rate ≤ 6% (was ≤ 8% before the macro rev — closer-up framing reads nail bed directly off the coin reference). |
| 2 | "Is this brand legit or another Daraz disappointment?" | All | Always | 5 | Customer leaves and never returns | **Hand-only craft photography + named founder + Mirpur location + UGC carousel + free first refit on every PDP** | 1 | Medium | First-visit conversion. Target ≥ 35% cart-to-order rate. |
| 3 | Wait between order and shipment feels like silence | All | Always | 4 | Customer messages "any update?" on WhatsApp | **3-step automated status emails (Confirmed → In Production → Shipped)** | 3 | Small | -40% WhatsApp inquiry volume. Higher post-purchase NPS. |
| 4 | Wudu compatibility uncertainty | Ayesha | Always | 5 | Customer doesn't even consider press-ons; lapses to "no nails ever" | **Cornerstone Blog Post #5 + "Removable for wudu" line on every PDP + Mona's faith story on About** | 4 | Medium | Owns an uncontested keyword. Target #1 SERP for "press on nails wudu" by month 6. New customer segment unlocked. |
| 5 | Bridal lead-time panic ("Do I have time?") | Hira | Always | 5 | Customer either rushes or abandons | **"Order 4 weeks before mehendi" countdown + < 4-week branch deflects to WhatsApp + Bridal-tier confirmation copy references wedding date** | 2 | Medium | Avoids over-promised orders. Bridal Trio conversion ≥ 8% from `/bridal`. |
| 6 | Returning customers re-onboarded as if they're new | Sana | Often | 4 | Customer doesn't reorder, or reorders but feels invisible | **Phone+email lookup → saved sizing as default + -10% in bag drawer the moment of recognition + welcome-back package note** | 2 | Medium | Repeat rate ≥ 25% by month 12. Time-to-second-order < 8 weeks. |
| 7 | Photo upload fails on Pakistan 4G | All | Sometimes | 4 | Customer abandons mid-upload | **Three-tier fallback: live camera → upload → "send via WhatsApp later"** + retry-with-progress on flaky connections | 2 | Medium | < 5% sizing-step abandonment. |
| 8 | Sizing step abandonment on the metro | Sana | Often | 4 | Customer leaves, never returns | **Save-to-resume — email her a link to come back to her partial order** | 2 | Medium | 10–15% recapture of step-1 abandoners. |
| 9 | "Will they fall off in front of a client?" | Sana, Hira | Always | 4 | Customer doesn't try the category at all | **7-day wear-test UGC Reels embedded on PDP + brush-on glue sample in package + care guide written for high-stakes wear** | 1 + 2 | Medium | First-time conversion. Repeat-customer trust. |
| 10 | Customer can't compare three designs | All | Often | 3 | Opens 3 tabs, gets confused | **Bag drawer pattern + persistent localStorage** + (Phase 5) side-by-side compare on `/shop` filter | 1 / 5 | Small / Large | Multi-item AOV. Target ≥ 18% of orders contain ≥ 2 items by month 6 (with complementary products). |
| 11 | Filter UI hidden behind a tiny icon on `/shop` | All | Often | 3 | Customer scrolls more than they should | **Filter bar visible-by-default**, sticky horizontal scroll on mobile | 1 | Small | Browse → PDP rate. Time-to-first-PDP-tap. |
| 12 | Bridal customer waits 10–14 days with no proactive update | Hira | Always | 5 | Customer messages anxiously several times | **Mid-production WhatsApp photo on day 5 of bridal orders** | 3 | Small | Bridal trust, post-wedding referral rate. |
| 13 | "I paid but did they receive it?" | All | Always | 4 | Customer waits in uncertainty after JazzCash payment | **Auto-email "Payment proof received, verifying within 24 hrs" the instant proof is uploaded** | 3 | Small | Post-payment anxiety. WhatsApp inquiry deflection. |
| 14 | Customer on slow 4G bounces from slow homepage | All | Often | 5 | Lost without ever knowing they were there | **Performance budget: Lighthouse mobile ≥ 90 · LCP < 2.5s on 3G · total above-fold transfer < 200KB · WebP/AVIF/JPEG fallback** | 1 | Medium | Bounce rate < 50%. SEO ranking compounds. |
| 15 | UGC count too low; only 1–2 hands shown per product | All | Always | 3 | Doubt creeps in at the PDP | **Pre-launch task: ensure top 8 products have 4+ UGC photos** with `face_visible = false` flag enforced | 1 | Medium | PDP conversion. |
| 16 | Customer doesn't know about reorder discount until checkout | Sana | Often | 3 | Reorder feels transactional, not rewarded | **-10% surfaced in bag drawer at lookup-success moment, not at checkout confirmation** | 2 | Small | Second-order rate. |
| 17 | Care card runs out; package ships without it | All | Rare | 4 | Trust signal lost; refit risk | **Filament dashboard reminder when stock < 30 cards; Mona reorders 200 at a time** | 3 | Small | Operational reliability; refit rate stable. |
| 18 | Customer pays but forgets to upload payment proof | All | Sometimes | 4 | Order looks unpaid in Filament; gets auto-cancelled; customer may have actually paid | **Auto-reminder email at 24h with proof-upload deeplink, again at 48h, auto-cancel at 72h with notification email; cancelled orders can be restored if customer reaches out** | 3 | Small | Payment-proof completion rate ≥ 90%. |
| 19 | Customer types phone in wrong format and gets validation error mid-flow | All | Sometimes | 2 | Friction; rarely abandonment | **+92 pre-filled and read-only; inline validation; autocomplete attrs for Pakistani address** | 2 | Small | Form completion rate. |
| 20 | Bridal customer's sister/mom can't open `/bridal` on their old phone without scaling | Hira (extended) | Sometimes | 3 | Loss of family approval signal | **Touch targets ≥ 44×44px; readable at base font size; no JS-only interactions; SSR-first** | 1 | Small | (Indirect) bridal conversion. |
| 21 | Tracking link is courier-side, not branded | All | Always | 2 | Customer feels shunted to a third party | **Brand-wrap the tracking page on our side: timeline + courier link side-by-side, our brand frame** | 3 | Small | Post-purchase brand affinity. |
| 22 | "I'd like to gift this but don't know which design" | Gift buyers (Y1 secondary) | Sometimes | 3 | Customer leaves to find a salon gift voucher instead | **Phase 5: gift cards (PKR 2,000 / 5,000 / 10,000) — handles the pain without committing to a design** | 5 | Medium | New revenue stream; bridal-shower gifting. |
| 23 | Customer doesn't know how to remove press-ons cleanly | Ayesha (most acutely), All | Often | 4 | Damaged nails after first wear; bad reviews | **Removal guide on care card + dedicated tutorial in Cornerstone Post #4 + brush-on glue sample to demonstrate clean reapplication** | 3 + 4 | Small + Medium | Repeat rate; refund avoidance; Ayesha advocacy. |
| 24 | Customer searches "how do I apply press-on nails" and lands on a competitor | All | Always | 3 | We lose to whoever ranks for the long-tail | **Cornerstone Blog Post #2 + FAQ schema on every product page** | 4 | Medium | Organic traffic compounds; long-tail rankings. |
| 25 | High-value customer lapses without re-engagement | All | Sometimes | 4 | Lifetime value caps lower than it should | **Quarterly Filament report: customers with last_ordered_at > 90d AND LTV ≥ PKR 3k → Mona personally messages with a gift surprise** | 5 | Small | Retention; LTV. |
| 26 | "Free first refit" is buried in fine print | All | Always | 3 | Customers don't know it exists; anxiety is unaddressed | **Trust badge near the price on every PDP** ("Free first refit guarantee") | 1 | Small | First-order conversion. |
| 27 | Mona's WhatsApp DMs blur into personal messages | Mona-side | Always | 4 | Customer messages get lost; reply latency increases | **Operational SOP: WhatsApp Business with category-tagged Quick Replies; "Hello Nails by Mona" pre-fill enforces brand-not-person framing** | 1 | Small | Reply SLA reliability; less spam. |
| 28 | New customer doesn't know shipping is from Mirpur AJK | All | Sometimes | 2 | Slight surprise on receipt; minor concern | **"Handmade in Mirpur, shipped across Pakistan" as a footer tagline + on About page + on Help page** | 1 | Small | Trust; reduced "where is this shipping from?" inquiries. |
| 29 | Customer can't find the size guide when on a PDP | All | Sometimes | 3 | Sizing anxiety pushes them to leave | **Persistent "Size guide" link from PDP sizing tab + modal version embedded in checkout** | 1 + 2 | Small | PDP → Add-to-bag conversion. |
| 30 | Customer concerns about glue allergy or skin sensitivity | Sana, Ayesha | Rare | 4 | Customer abandons or asks via WhatsApp | **Care + Reuse FAQ on PDP includes ingredient transparency (glue is cosmetic-grade EVA-based) + patch-test recommendation** | 4 | Small | Trust for sensitive customers; reduced inquiries. |

---

## Impact × Effort 2x2

The 12 highest-priority items from the matrix, plotted. **Quick wins** (top-left) ship first. **Big bets** (top-right) need committed phase budget. **Fill-ins** (bottom-left) ship as time permits. **Skip-or-defer** (bottom-right) get deferred to post-launch unless something changes.

```
                        EFFORT  →
                                 LOW                    MEDIUM                 LARGE
                       ┌──────────────────────┬──────────────────────┬──────────────────────┐
                       │                      │                      │                      │
                       │  QUICK WINS          │  BIG BETS            │                      │
                       │  ──────────          │  ────────            │                      │
                       │                      │                      │                      │
                  HIGH │  • #3 Status emails  │  • #2 Trust signals  │  • #1 Live camera    │
                       │  • #11 Filter visible│  • #4 Cornerstone #5 │    sizing            │
                       │  • #12 Bridal photo  │  • #5 Bridal timing  │                      │
                       │  • #13 Pay-proof ack │  • #6 Saved sizing   │                      │
                       │  • #16 -10% in drawer│  • #14 Perf budget   │                      │
                       │  • #26 Refit badge   │                      │                      │
                       │                      │                      │                      │
   IMPACT  ↑           ├──────────────────────┼──────────────────────┼──────────────────────┤
                       │                      │                      │                      │
                       │  FILL-INS            │                      │  RECONSIDER          │
                       │  ────────            │                      │  ──────────          │
                       │                      │                      │                      │
                   MID │  • #15 More UGC      │  • #7 Upload retry   │                      │
                       │  • #17 Care card SOP │  • #18 Proof-upload  │                      │
                       │                      │       reminder       │                      │
                       │  • #19 Phone format  │  • #23 Removal guide │                      │
                       │  • #21 Branded track │  • #24 Long-tail SEO │                      │
                       │  • #25 Lapsed report │                      │                      │
                       │  • #27 WhatsApp SOP  │                      │                      │
                       │  • #28 Mirpur tag    │                      │                      │
                       │  • #29 Size guide    │                      │                      │
                       │                      │                      │                      │
                       ├──────────────────────┼──────────────────────┼──────────────────────┤
                       │                      │                      │                      │
                       │  NICE-TO-HAVES       │                      │  SKIP-OR-DEFER       │
                       │  ──────────────      │                      │  ─────────────       │
                   LOW │                      │                      │                      │
                       │  • #30 Glue allergy  │  • #22 Gift cards    │  • #10 Side-by-side  │
                       │    transparency      │    (Phase 5)         │    compare           │
                       │                      │                      │    (Phase 5)         │
                       │  • #20 Touch targets │                      │                      │
                       │                      │                      │                      │
                       └──────────────────────┴──────────────────────┴──────────────────────┘
```

### Reading the 2x2

- **Quick Wins are the operational priorities for Phases 1–3.** Six of them. None take more than half a day. Together they cover trust signals, status emails, UI fixes, and the recognition + reward moment for returning customers.
- **The single Large-effort item (live camera, #1) is the one we already committed to.** The 2x2 validates that decision: it's the only item with the impact to justify a 5–6 day investment (bumped from 3–4 days on 2026-05-07 when we moved to the 2-photo state machine + green/red heuristic).
- **Three Medium-effort items in the High-impact band are the design's central bets.** Trust signals (#2), Cornerstone Post #5 (#4), and the perf budget (#14). These are the moves that define what kind of brand this is.
- **The Skip-or-Defer cell is small.** Side-by-side product compare (#10) is the only deliberate "no" for now — it's a Phase 5+ idea that depends on having more SKUs than we'll launch with.

## Phase rollup

Reorganized by build phase from CLAUDE.md §11 — same items, different cut:

| Phase | Items | Notes |
|---|---|---|
| **Phase 1** (public site) | #2, #11, #14, #15, #20, #26, #27, #28, #29 | The trust + IA + perf foundations. |
| **Phase 2** (order + camera) | #1, #5, #6, #7, #8, #19, #29 | The flow differentiator + retention path. |
| **Phase 3** (Filament admin) | #3, #12, #13, #17, #18, #21 | Operational scaffolding for status emails + manual payment-proof verification + tracking. |
| **Phase 4** (blog + SEO) | #4, #23, #24, #30 | Content moat + long-tail. |
| **Phase 5** (polish) | #10, #22, #25 | Refinements + new revenue lever. |

## Success metrics rollup

The seven numbers I'd watch monthly to know whether the matrix is paying off:

| Metric | Source | Target |
|---|---|---|
| First-visit cart-to-order rate | PostHog | ≥ 35% |
| Live-camera adoption (first-time orders) | `sizing_capture_method` | ≥ 60% |
| Refit-request rate | Filament | ≤ 6% (revised down from ≤ 8% on 2026-05-07 with the 2-photo macro flow) |
| Repeat-customer rate (12-month) | DB query | ≥ 25% |
| Payment-proof upload completion rate | DB | ≥ 90% |
| Verification SLA (proof-to-paid time) | DB | < 24h for 90% of orders |
| Status-email open rate | SMTP / SendGrid logs | ≥ 60% |
| Cornerstone Post #5 organic rank for "press on nails wudu" | Google Search Console | #1 by month 6 |

## Reflection

Building this matrix made me notice three patterns I hadn't named explicitly until I had everything in one table:

1. **Most quick wins are operational, not visual.** Status emails, payment-proof acknowledgments, the WhatsApp SOP, the care card restock reminder — none of these are screens. The screens get the attention; the operational layer is where the lift actually compounds.

2. **Pakistan-mobile performance (#14) is upstream of every other Phase-1 metric.** If LCP is 5 seconds, none of the trust signals, UGC carousels, or bag drawer goodness matters because the customer is gone. I keep flagging it as Phase 1, never as polish.

3. **The Ayesha persona drives 4 of the top 30 items** (#4, #23, #28, #30) but unlocks an entirely new customer segment that no competitor is serving. The unit economics on that segment are extraordinary because the acquisition cost is one well-engineered blog post.

The biggest open question in this matrix is **#10 (side-by-side product compare)**. I parked it as skip-or-defer because we won't have enough SKUs to make compare meaningful at launch. But by month 9 with 30+ products, it might move to "ship fast." Worth a re-look at the Phase 5 polish week.
