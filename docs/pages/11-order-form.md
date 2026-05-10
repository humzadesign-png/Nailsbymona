# Page: Order Form `/order/start/{slug?}`

> ⚠ **2026-04-29 design revision applies.** Canonical design tokens, nav pattern, photography rules, copy guidelines, and CTA labels are in `00-global.md` and `.claude/skills/design-system.md`. In particular: bag-drawer pattern (no "Order Now"), brand-addressed WhatsApp pre-fills (no "Ask Mona"/"Hello Nails by Mona,"), hand-only photography (no faces), Fraunces + DM Sans fonts, warm-neutral palette (`bone`/`paper`/`shell`/`ink`/`graphite`/`stone`), `/gallery` removed.

---

> Design tokens, typography, and patterns: see `00-global.md` + `.claude/skills/design-system.md` — those files are authoritative.
> **Order flow:** separate URL per step (session-persisted state). Step 1 = Sizing, Step 2 = Details, Step 3 = Payment. No JS-only stepper.

---

**Purpose:** Walk the customer confidently through sizing → details → payment with minimum drop-off. Every step must feel safe, simple, and personal.
**Templates:**
- `resources/views/order/start.blade.php` (Step 1 — Sizing)
- `resources/views/order/details.blade.php` (Step 2 — Your Details)
- `resources/views/order/payment.blade.php` (Step 3 — Payment Method)
**Layout:** Custom — logo only, no main nav. Keeps customer focused.
**Schema.org:** None — `noindex` page.

---

## SEO

`noindex, nofollow` — transactional pages must not be indexed.
No meta description, no OG needed.

---

## Header Override (all 3 steps)

**Logo:** `public/logo-text.svg`, top-left, links back to `/`
**Right side:** "Questions? Get help" → WhatsApp deep-link (pre-filled: "Hello Nails by Mona, I have a question about placing an order.")
**No main navigation.** Removes all exit temptation without trapping the user.

---

## Progress Bar (persistent across all steps)

Horizontal bar just below header. Three nodes:

```
[1. Your sizing] ——————— [2. Your details] ——————— [3. Payment]
```

- Completed step: filled circle (`bg-lavender`) + label `text-lavender`
- Current step: filled circle (`bg-lavender`) + label `font-semibold text-ink`
- Future step: empty circle (`border border-subtle`) + label `text-stone`
- Connecting line: `bg-subtle` (full width) with `bg-lavender` overlay growing left-to-right as steps complete

**Each step is a separate URL.** Laravel session preserves form state. Back button is safe.

| Step | URL |
|---|---|
| Step 1 | `/order/start/{slug?}` |
| Step 2 | `/order/details` |
| Step 3 | `/order/payment` |

---

## Step 1 — Your Sizing

**URL:** `/order/start/{slug?}`
If `{slug}` is present, the product is pre-selected (from the bag drawer's "Checkout" CTA). If absent, product selection occurs at the top of this step.

---

### 1a — Product Selection (only when no slug in URL)

**Heading (H3):** Which design are you ordering?

Dropdown or searchable select of all active products. Shows: product name + tier badge + price.
On selection → highlights product card below with image, name, price, tier.

If `{slug}` is in URL → skip this, show the product card directly.

---

### 1b — Returning Customer Lookup

**Position:** Above the sizing options. Subtle card (`bg-lavenderWash border border-hairline rounded-xl`)

**Heading (small, semibold):** Ordered from me before?

**Fields:**
- Phone or email input (single field — backend checks both)
- "Find my profile" button

**On match:** Success message inline:
> "Welcome back, [Name]! I have your sizing on file — no need to share new photos."
> ✓ Sizing: profile on file (order [NBM-YYYY-####])

Sets `sizing_capture_method = 'from_profile'` in session. Skips options A/B/C below. "Continue →" becomes active.

**On no match:** Silently disappears (don't tell them "not found" — just let them use the options below).

---

### 1c — Sizing Capture Options (new customers)

**Heading (H2):** How would you like to share your sizing?

**3 radio option cards (equal width, stacked on mobile, side-by-side on desktop):**

---

**Option A (default selected, recommended):**
Icon: `camera.svg` (large, top of card)
**Label:** Take a photo with my guide *(Recommended)*
**Sub-copy:** "I'll show you exactly where to position your hand and coin — on your screen, in real time. Takes about 2 minutes."
→ On "Continue": opens live camera module at `/order/sizing-capture` (returns back to step 2 on capture)
→ `sizing_capture_method = 'live_camera'`

---

**Option B:**
Icon: Upload icon (Heroicon, `arrow-up-tray`)
**Label:** Upload a photo from my gallery
**Sub-copy:** "Already have your 2 sizing photos (fingers + thumb, with a coin in each)? Upload them here."
→ File input appears inline on selection: `accept="image/*"` `max="8388608"` (8MB)
→ Brief reminder note below input: "Make sure: whole hand visible · coin present · shot straight down · good lighting"
→ Preview thumbnail on upload
→ `sizing_capture_method = 'upload'`

---

**Option C:**
Icon: `whatsapp.svg`
**Label:** I'll send it on WhatsApp
**Sub-copy:** "Not ready right now? Place your order and send your 2 sizing photos via WhatsApp — I'll measure from there before I start making."
→ No file input. Adds a banner reminder on the confirmation page.
→ `sizing_capture_method = 'whatsapp_pending'`

---

**Below the 3 cards (slim link):**
"Not sure how to take the photo?" → `/size-guide` (opens as modal overlay on desktop, navigates on mobile)

---

**"Continue →" button:**
- **Disabled** (grey, `bg-subtle cursor-not-allowed`) until:
  - Option A: camera capture completed (blob uploaded) OR
  - Option B: file uploaded and preview shown OR
  - Option C: selected (no upload needed)
- On enable: `bg-lavender text-white rounded-full`

---

## Step 2 — Your Details

**URL:** `/order/details`
**Pre-fill:** If returning customer matched in Step 1 → pre-fill all fields from `customers` table.

---

### Form Fields

| Field | Type | Required | Notes |
|---|---|---|---|
| Full name | text | Yes | Placeholder: "Your full name" |
| Email address | email | Yes | Placeholder: "your@email.com" |
| WhatsApp number | tel | Yes | Pre-filled: "+92", placeholder: "3XX XXXXXXX" — "This is how I'll contact you about your order" |
| Shipping address | text | Yes | Street address / house number + area |
| City | select | Yes | Major cities + "Other (enter below)" |
| City (other) | text | Conditional | Shows if "Other" selected in city dropdown |
| Postal code | text | No | Optional — "Helps with courier routing" |
| Order notes | textarea (3 rows) | No | "Anything I should know? Colour preferences, design adjustments, a wedding date." |

**City dropdown options:**
Lahore · Karachi · Islamabad · Rawalpindi · Faisalabad · Multan · Gujranwala · Sialkot · Peshawar · Quetta · Mirpur AJK · Other

---

### Price Summary (sidebar on desktop, accordion below form on mobile)

```
Product: [Name]                PKR X,XXX
Shipping:                      PKR XXX
─────────────────────────────────────────
Total:                         PKR X,XXX
```

If returning customer:
```
Reorder discount (-10%):       - PKR XXX
─────────────────────────────────────────
Total:                         PKR X,XXX
```

Shipping rate: pulled from `settings` (flat rate, city-based, or weight-based — TBD in settings admin).

---

### Reorder Discount Callout (returning customers only)

Warm banner above form (`bg-lavenderWash border border-hairline`):
> "Welcome back! A 10% reorder discount has been applied to your order."

---

**"Continue →" button:** Validates all required fields client-side first, then server-side via FormRequest. Shows inline field errors on failure.

---

## Step 3 — Payment Method

**URL:** `/order/payment`

---

### Payment Method Selection

**Heading (H2):** How would you like to pay?

**Sub-text** (`text-stone text-caption`): "All payments are processed before your order goes into production. No Cash on Delivery."

**3 radio cards** (stacked on mobile, vertical on desktop within the form column):

---

**JazzCash**
Icon: JazzCash brand SVG (`public/icons/jazzcash.svg`)
**Label:** JazzCash
**Account details (shown on selection)** — pulled from `settings`, not hardcoded:
```
Send to: 03XX-XXXXXXX
Name: [Mona's full name]
```

---

**EasyPaisa**
Icon: EasyPaisa brand SVG (`public/icons/easypaisa.svg`)
**Label:** EasyPaisa
**Account details (shown on selection):**
```
Send to: 03XX-XXXXXXX
Name: [Mona's full name]
```

---

**Bank Transfer**
Icon: Phosphor `bank` (1.5px stroke)
**Label:** Bank Transfer
**Account details (shown on selection):**
```
Account name: [Mona's full name]
IBAN: PK00XXXX0000000000000000
Bank: [Bank name]
```

---

**Selected state:** lavender outline + light wash background + checkmark icon (`border-lavender bg-lavenderWash`).

> **Card payments** (Visa, MasterCard, UnionPay) are not available at MVP — coming in Phase 6 with the SafePay gateway. The `payment_method` enum reserves `card` so no migration is needed when Phase 6 lands.

---

### What happens on "Place Order" submit

1. Server creates `Order` record with `payment_method` = jazzcash / easypaisa / bank_transfer, `payment_status = 'awaiting'`.
2. Customer is redirected to `/order/confirm/{order}` — that page renders the account details for the selected method plus a payment-proof upload field.
3. Customer transfers the amount via their preferred app/bank, returns to the confirmation page (or its email link), uploads the screenshot/receipt.
4. The order appears in Filament admin with a "Pending verification" badge. Mona reviews the proof, marks `payment_status = paid` (or `verifying` if unclear), which fires the "Order confirmed" status email.
5. Auto-reminder email at 24h if no proof uploaded; again at 48h; auto-cancel at 72h.

---

### Build notes

- **No payment gateway at MVP.** Manual proof flow only.
- Account details in `settings` table — admin-editable in Filament; never hardcoded in templates.
- Payment-proof upload is private (signed URLs in Filament; not publicly listed).
- Phase 6 SafePay integration is documented in CLAUDE.md §26 (Payment integration → Phase 6 plan) — this Step 3 will gain a Card option then, plus JazzCash/EasyPaisa will move to gateway-mediated authorization while Bank Transfer remains manual.

---

### Advance Payment Notice (conditional)

**Shown only if total ≥ PKR 5,000 (excluding Bridal Trio):**

Warning banner (`bg-lavenderWash border-l-4 border-lavender`):
> "Orders over PKR 5,000 require a 20–30% advance payment. After you place your order, I'll send the exact advance amount via WhatsApp — you'll pay the advance first, and the balance when I dispatch."

**Shown only for Bridal Trio (product tier = `bridal_trio`):**

Banner:
> "Bridal Trio orders are paid in two stages: 50% deposit on confirmation (to reserve your production slot), and 50% before dispatch. I'll send the deposit amount and payment details via WhatsApp."

---

### Order Summary (full review before submit)

Collapsible accordion (open by default): "Review your order"

Contents:
```
Product: [Name] — [Tier]               PKR X,XXX
Sizing: [live camera / uploaded / from profile / WhatsApp pending]
Deliver to: [Name], [City]
[Address]
Phone/WA: [Number]
Email: [Email]
Shipping:                               PKR XXX
[Reorder discount if applicable]:     - PKR XXX
────────────────────────────────────────────────
Total:                                  PKR X,XXX
```

---

### Submit Button

"Place my order" — full-width, `bg-lavender text-white rounded-full text-lg py-4`

**Below button (small, muted):**
> By placing this order, you confirm you've read and agree to our return and refit policy.

---

### On Submit

1. `OrderController@store` creates `Order` + `OrderItem` record.
2. Sends confirmation email to customer (from `settings.contact_email`).
3. Sends notification email to Mona.
4. Redirects to `/order/confirm/{order}` (UUID-based URL).

---

## UX Notes

- **Session state** — each step's data stored in Laravel session keyed by `order_form`. Survives back button, accidental refresh.
- **Mobile keyboard types** — `type="email"` and `type="tel"` on relevant fields trigger correct mobile keyboards.
- **WhatsApp pre-fill** — `+92` in the tel field. Customer enters the 10-digit number after the country code (e.g., 3XX XXXXXXX).
- **Pakistan cities dropdown** — only list major ones. "Other" with free-text handles remaining cities.
- **Progress bar** — reflects current step; clicking a previous step node navigates back (session state preserved).
- **No JS-only multi-step wizard** — separate URLs ensure reliable analytics, back-button support, and graceful fallback if JS fails.

---

## Assets Needed

- [ ] JazzCash and EasyPaisa logo SVGs (check brand guidelines for use permission)
- [ ] Mona's real WhatsApp number, JazzCash number, EasyPaisa number, bank IBAN — entered in Filament settings before launch
- [ ] Shipping rate matrix entered in settings (flat rate per city or zone)
- [ ] Confirm `throttle` limit on order form submit
- [ ] Test end-to-end: product → sizing → details → payment → confirm email received
