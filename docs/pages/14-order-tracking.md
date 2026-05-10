# Page: Order Tracking `/order/{order}/track`

> ⚠ **2026-04-29 design revision applies.** Canonical design tokens, nav pattern, photography rules, copy guidelines, and CTA labels are in `00-global.md` and `.claude/skills/design-system.md`. In particular: bag-drawer pattern (no "Order Now"), brand-addressed WhatsApp pre-fills (no "Ask Mona"/"Hello Nails by Mona,"), hand-only photography (no faces), Fraunces + DM Sans fonts, warm-neutral palette (`bone`/`paper`/`shell`/`ink`/`graphite`/`stone`), `/gallery` removed.

---

> Design tokens, typography, and patterns: see `00-global.md` + `.claude/skills/design-system.md` — those files are authoritative.
> **Note:** This page is `noindex`. URL uses UUID (not integer ID) to prevent enumeration. Lookup requires order number + phone/email match.

---

**Purpose:** Give customers real-time visibility into their order without having to message Mona every time. Reduce support volume. Reassure customers during the production wait.
**Template:** `resources/views/order/track.blade.php`
**Layout:** `layouts.app` (standard — this is a customer-facing page they may share or bookmark)
**Schema.org:** None — `noindex` page.

---

## SEO

`noindex, nofollow` — order-specific page. UUID in URL prevents enumeration.

The `{order}` segment is a UUID. The lookup form also requires email or phone to match — double-layer protection against accidental or malicious access to other customers' orders.

---

## Header

Standard `layouts.app` header (logo + nav). This page is bookmarked and revisited, so full navigation is appropriate (unlike the order form pages).

---

## Section 1 — Lookup Form

**Shown when:** Customer arrives via direct URL without session context (bookmark, email link, first-time visit).
**Hidden when:** Customer is arriving directly from confirmation page within the same session — order data is already available.

**Background:** `bg-paper`
**Layout:** Centred card, max-width 480px

**Heading (H1):** Track your order.

**Form fields:**

| Field | Type | Placeholder |
|---|---|---|
| Order number | text | NBM-2026-XXXX |
| Email or WhatsApp | text | your@email.com or 03XX-XXXXXXX |

**Submit button:** "Find my order" (`bg-lavender text-white rounded-full`)

**Rate limit:** `throttle:10,1` — 10 attempts per minute per IP.

**On error (no match):**
`bg-red-50 border border-red-200 rounded-xl` inline message:
> "We couldn't find an order with those details. Please check your order number — it starts with NBM and was in your confirmation email. If you're still stuck, message Mona with your order number."
> [Get help →] (WhatsApp pre-filled: "Hello Nails by Mona, I'm having trouble tracking my order.")

**Note:** Never specify *which* field is wrong ("order not found" vs "email doesn't match"). This prevents probing.

---

## Section 2 — Order Found (main tracking view)

**Background:** `bg-paper`
**Layout:** Single column, max-width 680px, centred

---

### 2a — Order Header Card

**Background:** `bg-shell rounded-2xl`

```
[Product cover image — 80×80, rounded]
[Product name] · [Tier badge]
Order: NBM-2026-XXXX
Placed: [date, e.g. "29 April 2026"]
Total: PKR X,XXX
Deliver to: [City]
```

---

### 2b — Status Timeline

**Layout:** Vertical timeline, left-aligned. Mobile-first. Each node is a circle + connector line + label.

**5 status nodes:**

| Node | Status enum value | Label | Sub-label |
|---|---|---|---|
| 1 | `new` | Order Placed | [date + time] |
| 2 | `confirmed` / payment_status `paid` | Payment Received | [date + time] or "Awaiting verification" |
| 3 | `in_production` | In Production | "Mona is making your set" |
| 4 | `shipped` | Shipped | [date] + courier + tracking number |
| 5 | `delivered` | Delivered | [date] or "—" |

**Node visual states:**

| State | Circle | Line below | Label |
|---|---|---|---|
| Completed | Filled (`bg-lavender`) + white checkmark | Solid (`bg-lavender`) | `text-ink font-semibold` |
| Current (active) | Filled (`bg-lavender`) + pulsing ring animation | Dashed (`border-lavender`) | `text-ink font-semibold` |
| Future | Empty (`border-2 border-subtle`) | Dashed (`border-subtle`) | `text-stone` |

**"Awaiting verification" state (payment node only):**
If `payment_status = 'awaiting'`, node 2 shows:
- Amber circle (`bg-amber-400`) + clock icon
- Sub-label: "Awaiting payment verification"

---

### 2c — Shipped Node Detail (expanded when status = `shipped`)

When `status = 'shipped'`, the Shipped node expands to show:

```
Courier: TCS / Leopards / M&P / BlueEx
Tracking: [tracking_number] — [clickable link → courier tracking URL]
Shipped on: [shipped_at date]
```

**Courier tracking links** (from `config/couriers.php`):
- TCS: `https://www.tcs.com.pk/track-shipment/?waybill={tracking_number}`
- Leopards: `https://leopardscourier.com/leopards-courier-online-tracking/?tracking_number={tracking_number}`
- M&P: `https://mp.pk/tracking?trackno={tracking_number}`
- BlueEx: `https://blueex.pk/track.php?trackno={tracking_number}`

The tracking number is rendered as a clickable link that opens the courier's own tracking page in a new tab.

---

### 2d — Cancelled Order State

If `status = 'cancelled'`, hide the 5-node timeline and show:

**Single node (red circle, ✕ icon):** Order Cancelled

Below:
`bg-red-50 border border-red-200 rounded-xl`
> "This order was cancelled. If you have questions about why, or would like to place a new order, please message Mona."
> [Get help →] (WhatsApp pre-filled: "Hello Nails by Mona, I have a question about my cancelled order NBM-XXXX.")

---

### 2e — Awaiting Payment Banner

If `payment_status = 'awaiting'` (regardless of order status), show a prominent banner above the timeline:

`bg-amber-50 border-l-4 border-amber-400 rounded-r-xl`

> "If you've already sent payment, please upload your receipt or send your screenshot directly on WhatsApp — I'll verify it within a few hours."
> [Upload receipt] → links to `/order/{uuid}/confirm` (or opens upload modal)
> [Send on WhatsApp →] → WhatsApp pre-filled with order number

---

### 2f — Free Refit Reminder Banner (conditional)

**Shown only when:** `status = 'delivered'` AND `delivered_at >= now() - 7 days`

`bg-paper border border-hairline rounded-xl`

> "Your free first refit is still available if anything doesn't fit perfectly. Get help with your order number — she'll sort it out."
> [Get help about a refit →] (WhatsApp pre-filled: "Hello Nails by Mona, I'd like to use my free first refit for order NBM-XXXX.")

After 7 days from delivery, this banner disappears.

---

## Section 3 — Always-Visible Footer Strip

**Background:** `bg-paper`
**Layout:** Centred text, full-width

**Copy:**
> Questions about your order? Get help with your order number and she'll get back to you personally.

**CTA:** "Get help on WhatsApp →" (pre-filled: "Hello Nails by Mona, I have a question about order NBM-XXXX." — order number populated server-side)

---

## Status-to-Copy Mapping

The sub-label text for the active (current) status node:

| Active status | Sub-label copy |
|---|---|
| `new` | "Your order has been placed — waiting for payment confirmation." |
| `confirmed` | "Payment confirmed. Mona will start making your set soon." |
| `in_production` | "Mona is making your set. This usually takes [lead_time_days] working days." |
| `shipped` | "Your order is on its way! See tracking details below." |
| `delivered` | "Delivered. We hope you love them — enjoy wearing them!" |

---

## Technical Notes

- **URL uses UUID** for the `{order}` segment. The lookup form also requires matching email or WhatsApp to prevent sharing a UUID and accessing another customer's order.
- **`throttle:10,1`** on the lookup form POST.
- **Session context** — if the customer is coming directly from the confirmation page, skip the lookup form and go straight to the tracking view (order UUID is in session).
- **Courier URL config** lives in `config/couriers.php` — keyed by the `courier` enum value. Template reads the URL template and substitutes `{tracking_number}`.
- **No integer order IDs in URLs** anywhere. UUID only. The human-readable `NBM-YYYY-####` order number is for display and customer reference, never for URL routing.
- **`delivered_at`** timestamp — set by Mona in Filament when she marks an order delivered. Used for the 7-day refit window.

---

## Assets Needed

- [ ] `config/couriers.php` created with URL templates for TCS, Leopards, M&P, BlueEx
- [ ] Test with seeded orders at each status — confirm timeline renders correct completed/active/future states
- [ ] Test throttle on lookup form (10 attempts, then blocked for 1 minute)
- [ ] Test refit banner — appears within 7 days of delivery, disappears after
- [ ] Test cancelled state renders correctly (no 5-node timeline)
- [ ] Test `whatsapp_pending` payment banner renders on awaiting status
- [ ] Confirm courier tracking links open correctly for each courier

---

## Filament Integration Notes

The tracking page reads the `orders` table. Mona controls the data that feeds into this page entirely from the Filament Orders resource:

- Moving an order through the kanban (New → Confirmed → In Production → Shipped → Delivered) updates `orders.status`.
- Marking payment verified updates `orders.payment_status` to `paid`.
- Adding the tracking number and selecting the courier in Filament populates the shipped node detail.
- Setting `delivered_at` triggers the 7-day refit window.

No customer action required to update the tracking page — Mona's Filament actions drive everything.
