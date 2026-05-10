# Page: Order Confirmation `/order/confirm/{order}`

> ⚠ **2026-04-29 design revision applies.** Canonical design tokens, nav pattern, photography rules, copy guidelines, and CTA labels are in `00-global.md` and `.claude/skills/design-system.md`. In particular: bag-drawer pattern (no "Order Now"), brand-addressed WhatsApp pre-fills (no "Ask Mona"/"Hello Nails by Mona,"), hand-only photography (no faces), Fraunces + DM Sans fonts, warm-neutral palette (`bone`/`paper`/`shell`/`ink`/`graphite`/`stone`), `/gallery` removed.

---

> Design tokens, typography, and patterns: see `00-global.md` + `.claude/skills/design-system.md` — those files are authoritative.
> **Note:** This page is `noindex`. Header shows logo only (no main nav) to keep the customer focused post-order.

---

**Purpose:** Reassure the customer immediately. Give clear, actionable payment instructions. Make proof upload frictionless. Set honest expectations for what comes next.
**Template:** `resources/views/order/confirm.blade.php`
**Layout:** Minimal custom — logo only.
**Schema.org:** None — `noindex` page.

---

## SEO

`noindex, nofollow` — order-specific transactional page.

The `{order}` segment is a UUID (not integer ID). This prevents enumeration of order counts or access to other orders' confirmation pages.

---

## Header

**Logo:** `public/logo-text.svg`, top-left, links to `/`
**Right:** WhatsApp help link (pre-filled: "Hello Nails by Mona, Here's my payment proof for order NBM-XXXX." — order number pre-filled server-side)
**No main navigation.**

---

## Section 1 — Success Confirmation

**Background:** `bg-paper`
**Layout:** Centred, max-width 600px

**Icon:** Large lavender checkmark SVG (`public/icons/package.svg` or a custom success checkmark) — animated in on page load (simple CSS scale-up, `duration-300`)

**Heading (H1, serif, font-light):**
> Your order is placed, dear. Thank you.

**Subheadline:**
> Order number: **NBM-2026-XXXX** — save this for tracking.

*The order number is displayed prominently. It's the customer's reference for everything that follows.*

**Below subheadline (small, `text-stone`):**
> A confirmation has been sent to [customer_email].

---

## Section 2 — Order Summary Card

**Background:** `bg-shell rounded-2xl`
**Layout:** Single column within max-width container

**Contents:**

```
[Product cover image — small, 80×80, rounded]

[Product name]                     PKR X,XXX
[Tier badge]
[Sizing method note]

Shipping                           PKR XXX
[Reorder discount if applied]    - PKR XXX
────────────────────────────────────────────
Total                              PKR X,XXX

──────────────────────────────────
Delivery to:
[Customer name]
[Street address]
[City], [Postal code if provided]
WhatsApp: [Number]

Estimated dispatch: [date range — today + lead_time_days working days]
```

**Sizing method note (conditional):**
- `live_camera` → "✓ Sizing photos received"
- `upload` → "✓ Sizing photo uploaded"
- `from_profile` → "✓ Using saved sizing profile"
- `whatsapp_pending` → "⏳ Sizing: please send your photo via WhatsApp (see below)"

---

## Section 3 — Payment Instructions

**Background:** `bg-paper`
**Layout:** Centred, max-width 600px

**Heading (H2):** Send your payment here.

**Sub-copy:**
> Your order is reserved for **72 hours**. Please send payment within that time to confirm your slot. After verification I'll WhatsApp you and start production.

**Conditional payment card** (rendered server-side based on `orders.payment_method` — only one card visible at a time):

---

**JazzCash card:**
```
Send PKR [total or advance_amount] to:
Mobile: 03XX-XXXXXXX
Account name: [Mona's full name]
```
*Account details pulled from `settings` — never hardcoded.*

---

**EasyPaisa card:**
```
Send PKR [total or advance_amount] to:
Mobile: 03XX-XXXXXXX
Account name: [Mona's full name]
```

---

**Bank Transfer card:**
```
Transfer PKR [total or advance_amount] to:
Account name: [Mona's full name]
IBAN: PK00XXXX0000000000000000
Bank: [Bank name]
```

---

### Advance Payment Conditions (conditional notice, shown above payment card if relevant)

Notice box `bg-paper border border-hairline rounded-xl`:

**Standard order ≥ PKR 5,000:**
> "Your order is above PKR 5,000. A **30% advance of PKR [advance_pkr]** is required to begin. The remaining PKR [balance_pkr] will be due before dispatch. I'll WhatsApp you when the balance is ready."

**Bridal Trio:**
> "Bridal Trio orders are paid in two stages. Please send **PKR [50%_deposit]** now to reserve your production slot. The remaining **PKR [50%_balance]** will be due before I dispatch your sets. I'll WhatsApp you with all the details."

**Standard order < PKR 5,000:**
No advance notice — show full amount in the payment card.

---

## Section 4 — Payment Proof Upload

**Background:** `bg-shell`
**Layout:** Centred, max-width 600px

**Heading (H3):** Share your payment screenshot.

**Copy (small, muted):**
> Upload your JazzCash / EasyPaisa / bank-transfer receipt here and I'll verify it within 24 hours during business hours.

**Upload zone:**

Desktop: Drag-and-drop zone with dashed border (`border-2 border-dashed border-hairline rounded-xl`).
Mobile: Native file picker button (large, full-width).

```
Drag and drop your screenshot here
— or —
[Browse files]
```

`accept="image/*, application/pdf"` · Max 8MB

**On selection:** Preview thumbnail (for images) or filename (for PDF) with a "Remove" ×.

**Upload button:** "Upload receipt" (`bg-lavender rounded-full`) — triggers AJAX POST to `OrderPaymentProofController@store`.

**On success (inline — no page reload):**
Green success strip above the upload zone:
> "✓ Got your receipt — I'll verify it within a few hours and WhatsApp you when it's confirmed."

**On failure:** Inline error + retry.

---

**If not uploading now (below the zone):**
> "Not ready to upload? You can also send your screenshot directly on WhatsApp."
→ WhatsApp link (pre-filled: "Hello Nails by Mona, Here's my payment proof for order NBM-XXXX.")

---

### Note for `whatsapp_pending` sizing method

If `sizing_capture_method = 'whatsapp_pending'`, show an additional notice box above the payment section:

`bg-amber-50 border border-amber-200 rounded-xl`

> **Don't forget to send your 2 sizing photos.**
> To complete your order, please send 2 close-up photos via WhatsApp — one of your fingers (laid flat with a coin above the middle finger) and one of your thumb (extended with a coin above the thumbnail). I can't start making your nails until I have them.
> [Get help on WhatsApp →] (pre-filled: "Hello Nails by Mona, Here are my sizing photos for order NBM-XXXX.")

---

## Section 5 — "What happens next"

**Background:** `bg-paper`
**Layout:** 3-step numbered list, centred, max-width 560px

**Heading (H3):** What happens next.

**Step 1:**
Icon: Phosphor `check` (1.5px stroke)
**Heading:** I verify your payment
> Once you've sent the payment and uploaded your receipt, I'll verify within 24 hours during business hours (10am–9pm, Mon–Sat). Most verifications are done within 1–3 hours.

**Step 2:**
Icon: `whatsapp.svg`
**Heading:** I WhatsApp you to confirm
> I'll send a message to confirm your payment and discuss any final details — especially for custom designs or bridal sets.

**Step 3:**
Icon: `package.svg`
**Heading:** I make and ship your order
> Custom sets ship in [lead_time_days] working days. I'll send your tracking number the same day I hand the parcel to the courier.

**Note at the bottom (small, muted):**
> If you haven't heard from me within 24 hours, please send a follow-up WhatsApp with your order number. Sometimes messages slip through.

---

## Section 6 — Order Actions

**Background:** `bg-paper`
**Layout:** Two buttons, centred, stacked on mobile

**Button 1 (primary):** "Track this order" → `/order/{order_uuid}/track`

**Button 2 (secondary, outlined):** "Continue shopping" → `/shop`

---

## Technical Notes

- **URL uses UUID** (`orders.id` as UUID), not the sequential order number. The order number (`NBM-YYYY-####`) is only for human reference — URLs use UUID to prevent guessing other customers' confirmation pages.
- **Payment account details** are stored in `settings` and rendered server-side. Never hardcoded in the template. Never exposed as plaintext in page HTML comments or data attributes.
- **Progressive enhancement** — the payment proof upload works without JS (standard form POST fallback). AJAX upload is enhancement layer.
- **Confirmation email** is sent on order creation (in `OrderController@store`), before this page renders — not triggered from this page.

---

## Assets Needed

- [ ] Mona's real JazzCash, EasyPaisa, and bank details — entered in Filament settings
- [ ] Confirmation email template (`resources/views/emails/order-confirmation.blade.php`) — references the selected payment method's account details
- [ ] Mona notification email template (`resources/views/emails/order-notification.blade.php`)
- [ ] Auto-reminder email at 24h/48h if no proof uploaded; auto-cancel at 72h
- [ ] Test end-to-end: order placed → confirmation page renders correct payment card → proof upload works → Filament shows new order with `payment_status = awaiting` → Mona marks paid
- [ ] Test advance logic: PKR 5,000+ shows 30% notice; Bridal Trio shows 50% deposit notice
- [ ] Test `whatsapp_pending` banner renders correctly above payment section
