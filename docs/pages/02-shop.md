# Page: Shop `/shop`

> ⚠ **2026-04-29 design revision applies.** Canonical design tokens, nav pattern, photography rules, copy guidelines, and CTA labels are in `00-global.md` and `.claude/skills/design-system.md`. In particular: bag-drawer pattern (no "Add to bag"), brand-addressed WhatsApp pre-fills (no "Ask Mona"/"Hello Nails by Mona,"), hand-only photography (no faces), Fraunces + DM Sans fonts, warm-neutral palette (`bone`/`paper`/`shell`/`ink`/`graphite`/`stone`), `/gallery` removed.

---

> Design tokens, typography, and patterns: see `00-global.md` + `.claude/skills/design-system.md` — those files are authoritative.

---

**Purpose:** Help customers find their design quickly and move them toward "Add to bag" without overwhelm.
**Template:** `resources/views/shop/index.blade.php`
**Layout:** `layouts.app`
**Schema.org:** `ItemList` (ProductListElement per visible product)

---

## SEO

| Field | Value |
|---|---|
| H1 | Find your perfect set. |
| Meta title | Shop Press-On Nails Pakistan — Custom-Fit Gel Sets | Nails by Mona |
| Meta description | Browse handmade, custom-fit press-on gel nails — Everyday, Signature, Glam, and Bridal. Sized to your hands. Shipped across Pakistan. From PKR 1,800. |
| Canonical | `https://nailsbymona.pk/shop` |
| OG image | Collage of 4 best-selling products |
| Breadcrumb | Home > Shop |

---

## Section 1 — Minimal Hero Strip

**Background:** `bg-shell` (slim strip, not a full-height hero — get to products fast)
**Height:** ~180px desktop, ~120px mobile

**H1 (serif, font-light):**
> Find your perfect set.

**Subheadline:**
> Every design is custom-fit to your nails. Browse by occasion, style, or price.

*No CTA in the hero — the products below are the CTA.*

---

## Section 2 — Sticky Filter Bar

**Position:** Sticks to top below the nav on scroll
**Background:** `bg-white border-b border-subtle shadow-sm`
**Behaviour:** jQuery toggle — filters products by showing/hiding CSS classes. No page reload.

**Left side — Filter by tier (pill buttons):**
All · Everyday · Signature · Glam · Bridal

**Right side — Sort:**
Dropdown: Newest · Price: Low to High · Price: High to Low · Featured

**Mobile:** Filter pills hidden behind a "Filter ▾" button that opens a drawer from the bottom.

**Active filter pill style:** `bg-lavender text-white` (inactive: `border border-subtle text-body`)

---

## Section 3 — Product Grid

**Background:** `bg-paper`
**Layout:** 3-column desktop, 2-column tablet, 2-column mobile

**Each product card:**

```
┌────────────────────────┐
│   [Product image 1:1]  │  ← scale-105 on hover (desktop only)
│   (WebP, lazy-load)    │  ← hover swaps to 2nd image if available
├────────────────────────┤
│  [Tier badge pill]     │
│  Product Name          │  ← H3, font-semibold
│  From PKR X,XXX        │  ← text-lavender, font-semibold
│  [Made to Order badge] │  ← only if stock_status = made_to_order
│  [Add to bag button]    │  ← bg-lavender, full-width on mobile
└────────────────────────┘
```

**Tier badge colours:**
- Everyday: `bg-shell text-lavenderInk`
- Signature: `bg-lavenderLight text-lavenderInk`
- Glam: `bg-lavender text-white`
- Bridal: `bg-ink text-white`

**Stock badges:**
- Made to Order: `bg-shell text-lavenderInk`
- Sold Out: `bg-red-50 text-red-600` + "Add to bag" button disabled

**"Add to bag":** → `/order/start/{slug}`

---

## Section 4 — Empty State (when filter returns no results)

**Full-width, centred block:**

> Nothing in this filter yet — but Mona is always working on new designs.

> Have something specific in mind? Message her and she'll let you know what's coming.

**CTA:** "Get help" → WhatsApp deep-link (pre-filled: "Hello Nails by Mona, I'm browsing your shop and have a question.")

---

## Section 5 — Trust Strip

**Desktop:** Sticky right rail alongside the product grid (fixed column)
**Mobile:** Horizontal strip below the product grid

4 items:
- "All sets custom-fit using our sizing guide" + link → `/size-guide`
- "Free first refit on every order"
- "Ships Pakistan-wide · 5–9 working days"
- "Pay via JazzCash, EasyPaisa, or bank transfer"

---

## Section 6 — Bridal Callout Banner (below grid)

**Background:** `bg-ink` (dark, high contrast — stands out from product grid)
**Layout:** 2-column — text left, image right. Mobile: stacked.

**Text:**
> Planning a wedding?

**H3:**
> The Bridal Trio covers all three nights — Mehendi, Baraat, and Valima — as one coordinated order.

**CTA:** "See the Bridal Trio" → `/bridal` (white outlined pill button)

**Image:** One of the bridal flat-lay photos

---

## Internal Links from This Page

| Destination | Trigger |
|---|---|
| `/shop/{slug}` | Every product card click / "Add to bag" |
| `/bridal` | Bridal callout banner |
| `/size-guide` | Trust strip link |
| WhatsApp | Empty state CTA |

---

## Assets Needed

- [ ] All product images (primary + second lifestyle/on-hand image per product, 1:1 WebP)
- [ ] Bridal flat-lay image for callout banner
