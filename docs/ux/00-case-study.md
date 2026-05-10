# Nails by Mona — UX Case Study

> Designing the digital service for a one-woman press-on nail studio in Mirpur, Pakistan — before a single line of code was written.

---

## At a glance

| | |
|---|---|
| **Role** | Sole UX & product designer (working with founder Mona and stakeholder Humza) |
| **Timeline** | 2 weeks of strategy + research, design ahead of an 11-day build |
| **Methods** | Desk research · Competitor benchmarking · JTBD synthesis · Persona modeling · Journey mapping · Service blueprinting · Heuristic IA validation planning |
| **Tools** | Markdown (docs-as-code) · ASCII flow diagrams · Tailwind design tokens · Filament admin reference · Pen & paper |
| **Constraints** | Solo artisan capacity (5–10 sets/week) · Pakistan-mobile context · No customer accounts (guest checkout) · No payment gateway at launch · No founder face anywhere on the site |
| **Output** | 13 self-contained UX artifacts that double as a build spec and a portfolio case study |

---

## The challenge

Mona runs a press-on gel nail studio out of her home in Mirpur, Azad Kashmir. Today her business lives entirely inside Instagram DMs: 1,000 followers, two years of word-of-mouth, all sizing arranged via voice notes and back-and-forth photos. She has a BA in Fine Arts and a real artisan craft — but she doesn't have a website, a checkout, a way to scale beyond what one person can hold in her head, or a way to be discovered outside Instagram.

The competitive landscape is busier than the founder realized. Eight other Pakistani brands are already running, four of them at 20–43k followers — 20–40× Mona's reach. Most claim "custom-fit," none demonstrate it. Most include "bridal" sets, none tell a bridal story. None have a search-engine footprint worth speaking of. The opportunity was clear: a defensible, premium, search-discoverable digital service — built on the things Mona already does that nobody else does (real custom fitting, handmade craft, a wudu-friendly product for practicing Muslim customers).

But "build a website" was the wrong frame. Mona's competitors mostly *have* websites — generic Shopify storefronts that haven't moved the needle. The real challenge was to design a digital service that earns trust on the first visit, makes the artisan craft visible, and removes the friction that today only Mona-on-WhatsApp can resolve. That meant starting with users, not pages.

---

## My UX process

```
DISCOVER → DEFINE → IDEATE → DESIGN → VALIDATE
   ↓         ↓         ↓        ↓         ↓
 Desk    Personas   Service  Flows &   Card sort
research  + JTBD   blueprint  copy &   + usability
+ comp   + empathy + IA      blueprint  testing
analysis   maps              decisions  (Phase 5)
```

**Discover** — I read everything that already existed: market notes, competitor profiles, Mona's voice notes about why she started, customer DMs Mona had screenshotted. I built a synthesis layer separating *evidence* from *hypothesis*. Anywhere I extrapolated, I labeled it. (See `01-personas.md` and `03-empathy-maps.md`.)

**Define** — Three personas emerged with distinct trigger moments and distinct jobs-to-be-done. The wudu-friendly persona — Mona's own origin story — was the surprise. No competitor articulates it, but it's load-bearing for an audience of millions of practicing Muslim women across Pakistan and the diaspora.

**Ideate** — I mapped each persona's end-to-end journey, then wrote a five-swimlane service blueprint connecting the customer-facing UI to Mona's backstage work in the Filament admin. The blueprint surfaced the two highest-risk handoffs (sizing photo quality and payment-proof verification), which drove two design decisions: the live-camera capture flow, and the auto-reminder + auto-cancel cadence for orders where customers pay but forget to upload proof.

**Design** — Six task flows, ten project-specific UX principles, an accessibility checklist scoped to Pakistan-mobile reality (3G LCP budgets, 44px touch targets, HEIC handling), and a microcopy stance ("warm, not chatty"). All in markdown so a non-designer co-founder can read it and Mona can read it.

**Validate** — Two plans queued for the polish week before launch: an open card sort to validate the locked nav (Shop · Bridal · About · Journal · Help) and a 5-user moderated test of the order flow with PKR 1,500 incentives recruited from Mona's IG audience.

---

## Five insights that shaped the design

> **1. "Custom" is meaningless until you show the measurement.** Every competitor uses the word; none demonstrate it. The differentiator isn't *being* custom — it's *looking* custom. That's why we invested in live-camera capture with SVG overlays for **2 close-up photos** (fingers row + thumb) plus a green/red alignment heuristic — 5–6 days of extra build, but the macro framing reads each nail width directly off the coin reference, which a wrist-out full-hand shot couldn't do reliably.

> **2. The wudu pain point is invisible to non-Muslim designers.** Practicing Muslim women cannot wear traditional polish or acrylics because water can't reach the nail bed during ablution. Press-ons solve this exactly — they remove cleanly. Mona started this business for that reason. Surfacing it as the 4th brand pillar and building Cornerstone Blog Post #5 around it opens a content moat with zero competition on the exact-match query.

> **3. The brand is "Nails by Mona," not Mona personally.** A face on the website would invite DM spam, conflate the brand with the person, and cap scaling. The discipline is hand-only photography and brand-addressed WhatsApp pre-fills ("Hello Nails by Mona…", never "Hi Mona!"). It's a small detail that compounds — every customer interaction reinforces a brand, not a phone number.

> **4. Bridal time-pressure is the most under-served emotional state.** A bride 4 weeks before her mehendi is an entirely different user from a working professional browsing on her commute. Same pages, completely different copy needs. The Bridal Trio gets its own page, its own photography style (hand-on-velvet, not hand-on-paper), its own countdown messaging, and its own advance-payment gate.

> **5. Pakistan-mobile is the design constraint, not the responsive afterthought.** A 3-year-old Android on patchy 4G, in landscape, in a salon waiting room. Lighthouse mobile ≥ 90 isn't a polish goal — it's a market-fit requirement. Every flow has a graceful degradation path. The live camera *itself* falls back to file upload, which falls back to "send via WhatsApp."

---

## Three high-stakes design decisions

### 1. Live camera over file upload

**The call:** Build a guided live-camera capture as a **2-photo state machine** (fingers row → thumb in a single permission session) with per-state SVG overlays, a green/red alignment heuristic, an opt-in for 2 more photos for the other hand, and a 2-input upload fallback — even though it costs 5–6 extra days of dev time and adds platform-specific edge cases (HTTPS requirement, iOS Safari version checks, permission denial handling).

**Why:** Sizing is *the* most common DM question Mona gets today. Every competitor either fakes "custom" with 24-size standard packs or asks the customer to figure out a measurement protocol on their own. A guided camera turns the most error-prone moment in the journey into the most differentiated one. The feature also doubles as marketing content ("here's our guided fitting in action" → Reels content for free).

**What I considered and rejected:**
- *Upload-only* — cheaper, but indistinguishable from competitors and fails the "show the craft" principle.
- *Mail a sizing kit* — too slow, increases first-order friction, doesn't scale.
- *AR overlay (WebXR)* — over-engineered, brittle on Pakistan mobile, too far ahead of the audience.

**How we'll know it worked:** `sizing_capture_method` field on every order. Target: ≥ 60% of first-time orders use live camera vs. upload by month 3. **Refit-request rate ≤ 6%** (vs. industry ~15%; revised down from 8% on 2026-05-07 — the macro 2-photo flow should support a tighter target). Also track `photo_type` distribution to see how many customers opt in to the perfect-fit path.

### 2. No founder face anywhere

**The call:** Mona is named publicly (About page, blog byline, packaging signature), but her face never appears on the website. UGC submissions with visible faces are flagged in Filament and never published. The About hero is a hand-portrait with a handwritten "Mona" SVG signature.

**Why:** Three reasons that all align. (a) DM spam — Pakistani micro-influencers with public faces get aggressive male DMs that drown out customer messages. (b) Brand-not-person — "Nails by Mona" must be transferable; if Mona ever wants to hire helpers or sell the business, the brand can't be a face. (c) Trust — paradoxically, hand-only photography reads more premium and more artisan than founder selfies, which read as personal IG.

**What I considered and rejected:**
- *Founder portrait on About* — standard DTC playbook, but actively harmful in this context.
- *Mona on Reels but not on the website* — inconsistent brand voice; we'd be telling a different story on different channels.
- *Stylized illustration of Mona* — twee, cheapens the craft positioning.

**How we'll know it worked:** Qualitative — Mona reports DM volume stays manageable. Quantitative — proxy: bridal trio inquiry rate vs. founder-face competitor brands.

### 3. Bag, not "Order Now"

**The call:** The product detail page CTA is **"Add to bag"** — singular. The site has no "Order Now" button anywhere. The bag icon in the nav is the only commerce CTA in the header. Bag drawer slides from the right, persists in `localStorage`, and feeds the multi-step order flow at checkout.

**Why:** "Order Now" buttons everywhere create decision paralysis and force customers into the checkout flow before they're ready. A single bag pattern lets a customer browse, accumulate, compare, and commit on their own timeline — closer to how they actually shop. It also de-clutters the visual hierarchy: every product card looks the same, every page reads the same.

**What I considered and rejected:**
- *"Order Now" + "Add to bag"* — both buttons on every PDP. Dilutes the primary action and forces the user to choose between two near-identical paths.
- *No bag at all, single-item checkout* — fast for a one-product purchase but breaks the Bridal Trio multi-item flow and any future complementary-product cross-sell.
- *Sticky cart sidebar* — eats horizontal space on mobile, looks dated.

**How we'll know it worked:** Add-to-bag rate · cart abandonment rate · multi-item order share. Target: ≥ 18% of orders contain ≥ 2 items by month 6 (with complementary products launched in Phase 5).

---

## What I'd do next

If I had two more weeks and a small budget, in order:

1. **Run the card sort** before the build hits the IA. 8 Pakistani women, PKR 1,000 each, 30 minutes.
2. **Voice-record Mona** reading 20 product descriptions and the entire FAQ — transcribe and rewrite to her natural cadence. Today the copy is good; with her voice it could be unmistakable.
3. **Shoot the 35-frame DIY photography pack.** A half-day with smartphone + ring light + window light + three backdrops. The quality of hand photography is the single biggest visual quality lever and is currently the biggest known unknown.
4. **Tree-test the order flow** before anyone codes step 2 of the form. Cheaper to discover users can't find "saved sizing" in a tree-test than in a usability test on a built product.
5. **Diary study with 3 brides** through one full wedding cycle — what we learn about the 4-week lead-time period is content gold for Cornerstone Post #3 (bridal trends).

---

## Outcomes to measure (12 months post-launch)

| Metric | Baseline | Target Y1 |
|---|---|---|
| Organic search visitors / mo | 0 | 5,000–15,000 |
| Live-camera adoption (first-time orders) | n/a | ≥ 60% |
| Refit-request rate | n/a (DM era) | ≤ 6% (was ≤ 8% before the 2026-05-07 macro 2-photo rev) |
| Repeat-customer rate | qualitative ~10% | ≥ 25% |
| Bridal Trio share of revenue | ~0% | 25–35% |
| Payment-proof upload completion rate | n/a (DM era) | ≥ 90% |
| Verification SLA (proof-to-paid time) | n/a (DM era) | < 24h for 90% of orders |
| Average order value | PKR ~2,200 | PKR 2,800–3,500 |

These are the numbers that tell us whether the design choices landed — not Lighthouse scores, not Dribbble likes, not how the screenshots feel.

---

## Reflection

The thing I'm proudest of in this work isn't a screen — it's the discipline of *what we deliberately did not build*. No "Order Now" buttons, no AI chatbot, no display ads, no founder face, no Urdu translation in Year 1, no payment gateway in Phase 1. Each "no" was a decision, not an oversight. Premature features are the enemy of a one-woman studio that needs to ship and survive.

The thing I'm least sure about is the live-camera adoption rate. The whole sizing differentiation rests on it. If only 20% of customers grant camera permission and the rest fall back to upload, we still ship a working product — but the wedge gets smaller and the build cost gets harder to justify. That's why instrumentation is in the schema from day 1 (`sizing_capture_method` enum) and why the first usability test is timed for the polish week, before launch is irreversible.

The thing I'd most like to have done differently is to have spoken to a real Pakistani bride before mapping the bridal journey. The journey is built from competitor reviews, Mona's secondhand reports, and one diaspora bride's blog post. It's the most assumption-heavy artifact in the folder. The Phase 5 usability test recruits one bride for that reason.

---

## Folder index

This case study is the entry point. The 12 supporting artifacts:

| File | Purpose |
|---|---|
| [`README.md`](README.md) | Folder map and reading order |
| [`01-personas.md`](01-personas.md) | Three primary personas (Sana, Hira, Ayesha) |
| [`02-jobs-to-be-done.md`](02-jobs-to-be-done.md) | JTBD + switching forces analysis |
| [`03-empathy-maps.md`](03-empathy-maps.md) | Says/Thinks/Does/Feels per persona |
| [`04-journey-maps.md`](04-journey-maps.md) | Four end-to-end journeys with emotion curves |
| [`05-user-flows.md`](05-user-flows.md) | Six task flows in ASCII |
| [`06-service-blueprint.md`](06-service-blueprint.md) | Five-swimlane service blueprint |
| [`07-pain-points-opportunities.md`](07-pain-points-opportunities.md) | Prioritized pain/fix matrix + 2x2 |
| [`08-card-sort-ia-validation.md`](08-card-sort-ia-validation.md) | Card sort plan for IA validation |
| [`09-usability-testing-plan.md`](09-usability-testing-plan.md) | 5-user moderated test plan |
| [`10-accessibility-checklist.md`](10-accessibility-checklist.md) | WCAG 2.2 AA + Pakistan-mobile checklist |
| [`11-ux-principles.md`](11-ux-principles.md) | 10 project-specific UX principles |
