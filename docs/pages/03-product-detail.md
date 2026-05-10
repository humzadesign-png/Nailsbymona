# Page: Product Detail `/shop/{slug}`

> **2026-04-29 design revision applies. Authoritative files:** `00-global.md` + `.claude/skills/design-system.md`. Anything in this file that contradicts those is obsolete — the revision wins.
>
> **Key changes for this page:**
> - **Primary CTA:** **"Add to bag"** (filled lavender, full-width on mobile). Click → bag drawer slides in from right. **Never "Order Now".**
> - **Secondary CTA:** "Get help" (ghost text link). **Banned:** "Ask Mona", "DM Mona about this design", any "Hi Mona!" pre-fill.
> - **WhatsApp pre-fill** (Get help link only): `Hello Nails by Mona, I'm interested in [Product Name].`
> - **Image gallery:** product shots and any in-wear shots are **hand-only**. Crop at the wrist. No face anywhere.
> - **Fonts:** Fraunces (display) + DM Sans (body). **Banned:** Cormorant Garamond, Inter.
> - **Palette:** `bone` page bg, `paper` cards, `shell` alt sections, `ink`/`graphite`/`stone` text. Lavender is accent only (CTA, price, focus ring, accent rule). **Banned tokens:** `cream`, `cloud`, `lavenderFaint`, `lavenderLight`, `body`, `mute`, `subtle`.
> - **New section — UGC carousel "Worn by customers":** 3–6 hand-only photos from `ugc_photos` table where `placement = 'product_carousel'` AND `face_visible = false` AND `product_id = current product`. Replaces what was previously routed to `/gallery` (page removed).
> - Internal Links: remove any link to `/gallery` — that route is gone.

**Purpose:** Remove every doubt, establish desire, and make ordering feel safe and easy.
**Template:** `resources/views/shop/show.blade.php`
**Layout:** `layouts.app`
**Schema.org:** `Product` + `FAQPage` + `BreadcrumbList`

---

## SEO

| Field | Value |
|---|---|
| H1 | Product name (e.g. "Dusty Rose Ombre — Signature Set") |
| Meta title | {Product Name} — Custom-Fit Press-On Nails Pakistan | Nails by Mona |
| Meta description | 150–160 chars — design desc + price + "handmade by Mona" + "ships Pakistan-wide" |
| Canonical | `https://nailsbymona.pk/shop/{slug}` |
| OG image | Product cover image |
| Breadcrumb | Home > Shop > [Product Name] |

---

## Above the Fold — 2-Column Layout

### Left Column (60%) — Image Gallery

**Primary image:** Full-size flat-lay (cream or marble surface). Click → lightbox.

**Thumbnail strip (below main image, horizontal scroll on mobile):**
- 3–5 thumbnails: alternate angle, on-hand lifestyle, detail (3D/charm close-up if applicable), packaging shot

**Mobile:** Full-width horizontal swipe gallery (no thumbnails — swipe between images).

---

### Right Column (40%) — Product Info

**Breadcrumb (small, above H1):**
Home > Shop > [Product Name]

**H1:** Product name
`font-serif text-3xl font-light text-ink`

**Tier badge:** Colour-coded pill (same system as shop page)

**Price:**
`PKR 2,800` — `font-sans text-xl font-semibold text-lavender`

**Stock status:**
"Made to order · Ships in 5–9 working days" — `text-mute text-sm`
or "Currently available · Ships in 5–7 working days"

**Short description (2–3 sentences, Mona's voice):**
What makes this set special. The colour story, the technique, what occasions it suits.
Example: *"This soft dusty rose ombre transitions from a sheer base to a full blush tip — perfect for everyday wear that still feels elevated. I hand-blend each gradient so no two sets are identical."*

**"Add to bag" button (primary, full-width on mobile):**
`bg-lavender hover:bg-lavenderDark text-white rounded-full` → triggers bag-drawer open with this product added to localStorage `nbm.bag`.

**"Get help" link (secondary, ghost):**
→ WhatsApp deep-link with product name pre-filled: "Hello Nails by Mona, I'm interested in [Product Name]."

**Quick trust signals (3 items, icons + small text, horizontal row):**
- Custom-fit to your measurements
- Free first refit
- JazzCash · EasyPaisa · Bank Transfer

---

## Section 2 — Product Detail Tabs (jQuery accordion)

### Tab 1 — "About this set"

200+ words in Mona's voice. Cover:
- The colour story and visual description
- The technique (gel layers, hand-painting, any 3D/charm elements)
- What occasions it works for
- Brief making note ("I build each nail on a form, cure the gel in layers, then hand-paint...")
- What's in the box: 10 nails + 2 spare nails + brush-on glue + prep pad + application guide card
- Lead time: 5–9 working days from sizing confirmation
- Packaging: wrapped in tissue, boxed

### Tab 2 — "Sizing & Fit"

Short copy:
> This set is made to fit your specific nails — not a generic size. Before I begin making your set, you'll share a quick photo of your hand using my sizing guide.

Link: "How sizing works" → `/size-guide` (opens in modal on desktop, navigates on mobile)

Note for returning customers:
> Already ordered from me before? I keep your measurements on file. Just mention it when you order and I'll use your saved profile.

### Tab 3 — "Care & Reuse"

5 bullet points:
- Apply on clean, dry nails (use the prep pad included)
- Press firmly for 30 seconds from base to tip, working out air bubbles
- Avoid soaking in water for the first hour after application
- To remove: soak in warm water for 10–15 minutes, gently lift from the side. Never force or peel.
- Store in the original box or a nail case — they'll last 3–5 applications with proper care

Link (when available): "Full application guide →" → `/blog/how-to-apply-press-on-nails`

---

## Section 3 — FAQ Block

**Heading:** Questions about this set

**4–6 entries, accordion (jQuery).** Pulled from `faqs` table (category-matched). Also feeds FAQPage schema.

**Default FAQs for every product page:**

**Q: Will these nails fall off?**
A: I use a brush-on nail glue that bonds firmly with proper preparation. Most customers get 7–10 days of wear. Every set comes with a 3-day stick guarantee — if they lift within 3 days of correct application, I'll replace them, no questions.

**Q: What if the sizing is wrong?**
A: That's exactly what the free first-refit guarantee is for. If your first order doesn't fit perfectly, I resize it at no charge. I'd rather take the extra time to get it right.

**Q: How long does it take?**
A: Custom sets take 5–9 working days from the day I confirm your sizing. Bridal sets take 10–14 days. I'll confirm your exact timeline over WhatsApp before I start.

**Q: Can I reuse these?**
A: Yes — with careful removal (soak off, never force), most customers get 3–5 wears from a set. I'll include care and storage instructions with every order.

**Q: How can I pay?**
A: I accept JazzCash, EasyPaisa, and bank transfer. Account details are sent automatically on the confirmation page after you place your order, and you upload a screenshot of your payment for me to verify (usually within a few hours). **No Cash on Delivery.** Card payments will be available later in the year — I'm in the process of setting up a payment gateway. Orders ≥ PKR 5,000 require a 20–30% advance up front; Bridal Trio orders are paid in two stages (50% to reserve, 50% before dispatch).

---

## Section 4 — "You might also like" (Related Products)

**Layout:** 3 product cards (same tier or complementary — admin-configured in Filament)

**Heading:** You might also like

---

## Section 5 — Related Reading (Blog Posts)

**Appears only when related posts are linked via `blog_post_products` pivot in Filament.**

**Layout:** 2 blog post cards side-by-side (desktop), stacked (mobile)

**Heading:** Further reading

Example pairings:
- Bridal product → "Bridal Nail Trends Pakistan 2026" + "Press-On vs Acrylics for Pakistani Brides"
- Glam product → "How to Apply Press-On Nails"

---

## Internal Links from This Page

| Destination | Trigger |
|---|---|
| Bag drawer (then `/order/start`) | "Add to bag" button |
| `/size-guide` | "How sizing works" in Tab 2 |
| `/shop/{other-slug}` | Related products section |
| `/blog/{slug}` | Related reading section + Tab 3 care guide link |
| WhatsApp | "Get help" link (brand-addressed pre-fill) |

---

## Assets Needed (per product)

- [ ] Primary flat-lay image (cream or marble surface)
- [ ] On-hand lifestyle photo (nails being worn)
- [ ] Detail close-up (if 3D elements, charms, or nail art)
- [ ] Packaging shot (optional — shows the box)
- [ ] 5–6 FAQ entries entered in Filament (`faqs` table)
