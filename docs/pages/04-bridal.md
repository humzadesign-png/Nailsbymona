# Page: Bridal `/bridal`

> **2026-04-29 design revision applies. Authoritative files:** `00-global.md` + `.claude/skills/design-system.md`. Anything in this file that contradicts those is obsolete.
>
> **Key changes for this page:**
> - **Primary CTA:** **"Add Trio to bag"** → bag drawer slides in. **Never** lead with WhatsApp; it's a secondary "Get help" link.
> - **WhatsApp pre-fill** (Get help only): `Hello Nails by Mona, I'm interested in the Bridal Trio for my wedding.` **Banned:** "Ask Mona on WhatsApp", "Hi Mona!" pre-fills.
> - **Hero image:** hand on red velvet with gold thread, henna pattern visible on skin. **No face.** Aspect `3:4` or full-bleed.
> - **Three-nights editorial panels (Mehendi · Baraat · Valima):** each panel is a hand-on-fabric photograph. Mehendi = green/gold; Baraat = red velvet; Valima = ivory/pearl. **All hand-only.**
> - **Real bridal gallery section:** 6–9 photos from `ugc_photos` where `placement = 'bridal_gallery'` AND `face_visible = false`. Hand-only customer brides.
> - **CTA below gallery is NOT "See the full gallery → /gallery"** — that page is removed. Replace with: "More designs in the bridal collection" → `/shop?tier=bridal_*` or similar.
> - **Fonts:** Fraunces (display) + DM Sans (body). **Banned:** Cormorant Garamond, Inter.
> - **Palette:** `bone`/`paper`/`shell`/`ink`/`graphite`/`stone`. Lavender is accent only. **Banned tokens:** `cream`, `cloud`, `lavenderFaint`, `lavenderLight`, `body`, `mute`, `subtle`. Section bg can lean darker editorially — `shell` and `ink` are appropriate here.

**Purpose:** Make the emotional case for the Bridal Trio before revealing the price — then make the price feel like an obvious value.
**Template:** `resources/views/bridal.blade.php`
**Layout:** `layouts.bridal` (alternate layout — richer editorial feel)
**Schema.org:** `Product` (Bridal Trio) + `FAQPage` + `BreadcrumbList`

---

## SEO

| Field | Value |
|---|---|
| H1 | Your wedding nails, for all three nights. |
| Meta title | Bridal Press-On Nails Pakistan — Mehendi, Baraat & Valima Trio | Nails by Mona |
| Meta description | Custom bridal press-on nails for Pakistani weddings. The Bridal Trio covers Mehendi, Baraat, and Valima — one order, one fitting, premium packaging. From PKR 11,000. |
| Canonical | `https://nailsbymona.pk/bridal` |
| OG image | 3 coordinated bridal sets photographed together |
| Breadcrumb | Home > Bridal |
| Target keywords | "bridal press on nails Pakistan", "mehendi nails Pakistan", "baraat nail designs Pakistan" |

---

## Section 1 — Hero

**Background:** Full-width editorial image — 3 coordinated sets photographed together on velvet or marble. Warm gold (Mehendi), deep drama (Baraat), soft luminous (Valima).

**H1 (large serif, font-light):**
> Your wedding nails, for all three nights.

**Subheadline:**
> The Bridal Trio — Mehendi, Baraat, and Valima — as one coordinated order. One fitting. One shipment. Handmade by Mona.

**CTAs:**
- Primary: **"Add Trio to bag"** → bag drawer slides in (bridal tier pre-selected via query param when going to `/order/start`)
- Secondary: **"Get help"** ghost link → WhatsApp deep-link (pre-filled: "Hello Nails by Mona, I'm interested in the Bridal Trio for my wedding.")

---

## Section 2 — "Three nights. Three looks."

**Background:** `bg-paper`
**Layout:** 3 editorial panels — side-by-side on desktop, stacked on mobile. Each panel is full-bleed image with text overlay or image + text side-by-side.

---

**Panel 1 — Mehendi**

Photo: Warm, haldi-toned nails — deep yellow, orange, gold accents, earthy tones.

**Heading:** Mehendi

**Copy:**
> Mehndi night is about joy, colour, and family. Your nails should match that energy — warm, celebratory, and beautiful in dholki lighting. Think golden shimmers, terracotta accents, and designs that look as good in the candid photos as in the posed ones.

---

**Panel 2 — Baraat**

Photo: Dramatic, rich-toned nails — deep red, burgundy, intricate detail, bold statements.

**Heading:** Baraat

**Copy:**
> This is the night that matters most. Baraat nails deserve drama — rich reds, deep burgundies, or blush-and-gold combinations that hold up under professional photography and feel as important as your lehenga. These are the nails you'll see in every wedding album photo.

---

**Panel 3 — Valima**

Photo: Luminous, soft nails — pale blush, champagne, pearl accents, soft shimmer.

**Heading:** Valima

**Copy:**
> Reception-ready. Valima nails tend to go lighter and more luminous — soft blushes, champagne tones, and understated elegance. You've done the drama. Now you glow.

---

## Section 3 — "Everything you need, in one order"

**Background:** `bg-shell`
**Layout:** 2-column — checklist left, pricing right. Mobile: stacked.

**Section label:** WHAT'S INCLUDED
**H2:** Everything you need, in one order.

**Checklist (left column):**
- Three coordinated sets (10 nails each + 2 spares = 36 nails total)
- One sizing session — your measurements saved for all future reorders
- One shipment — all three sets packed together
- Premium packaging: rigid magnetic box, satin-lined tray, three labeled compartments (Mehendi / Baraat / Valima), handwritten name card
- Mini glue + prep kit included
- Free first refit guarantee
- Personal WhatsApp support from Mona through your entire wedding timeline

**Pricing block (right column):**
```
Bridal Trio
PKR 11,000 – 13,500
(depending on design complexity)

Individual Bridal Single: PKR 5,000 – 6,500

Trio saves you 10–15% compared to three singles —
and one less thing to coordinate before your wedding.
```

**CTA:** "Order your Bridal Trio" → `/order/start` (primary pill)

---

## Section 4 — "Order at least 4 weeks before your Mehendi."

**Background:** `bg-paper`
**Layout:** Timeline graphic (horizontal on desktop, vertical on mobile)

**Section label:** PLANNING YOUR TIMELINE
**H2:** Order at least 4 weeks before your Mehendi.

**Timeline steps:**

| Step | Action | Timing |
|---|---|---|
| 1 | Place your order + share your sizing photo | 4+ weeks before Mehendi |
| 2 | Mona confirms design direction via WhatsApp | 1–2 days |
| 3 | Mona makes your three sets | 10–14 working days |
| 4 | Delivery by TCS | 2–4 working days |
| 5 | Buffer for any refit adjustment | 1 week |

**Note below timeline (in Mona's voice):**
> I only take a limited number of bridal orders each month to make sure every bride gets my full attention. If your date is close, please message me on WhatsApp before placing an order and I'll tell you honestly whether I can make it work.

---

## Section 5 — "From Mona's bridal collection"

**Background:** `bg-paper`

**Section label:** BRIDAL GALLERY
**H2:** From Mona's bridal collection.

**Layout:** Masonry / 3-column grid. 6–9 real bridal photos.

**Photo types:**
- Bridal sets flat-laid on velvet or with lehenga fabric
- Nails on bride's hands (with permission — during nikah, reception, mehendi)
- Close-ups of detailed bridal work (charms, gems, hand-painted patterns)

**CTA below:** "More designs in the bridal collection" → `/shop?tier=bridal_single` (secondary outlined pill). **Do not link to `/gallery` — that route is removed.**

---

## Section 6 — "Why brides are choosing press-ons over acrylics"

**Background:** `bg-paper`

**Section label:** THE HONEST COMPARISON
**H2:** Why brides are choosing press-ons over acrylics.

**Comparison table:**

| | Bridal Trio | Salon Acrylics |
|---|---|---|
| Cost for 3 events | PKR 11,000–13,500 | PKR 7,500–15,000 |
| Nail damage | None — no drilling, no acetone soak | Thin nail bed, 4–6 week recovery |
| Custom fit | Sized to your exact nail dimensions | Standard form sizes |
| Pre-wedding stress | Order early, arrive ready | Same-day appointment, timing risk |
| Reusable after wedding | Yes, 3–5 more wears | No |
| Photography quality | Gel finish, deep colour payoff | Comparable |

**Note below table (honest, not pushy):**
> Acrylics are a fine choice and I'm not here to convince you otherwise. But if you've had lifting, breakage, or allergic reactions before a big event before — press-ons take that risk off the table entirely.

---

## Section 7 — Bridal FAQs

**Background:** `bg-shell`

**Section label:** QUESTIONS BRIDES ASK
**H2:** Questions brides ask me.

**5–6 entries, accordion.** Pulled from `faqs` table (category: `bridal`). Also feeds FAQPage schema.

**Q: Can I choose different designs for all three events?**
A: Yes — that's exactly what the Trio is for. We'll discuss your lehenga colours and the vibe for each night over WhatsApp, and I'll suggest coordinating designs that feel like a collection rather than three separate orders.

**Q: Do I need to come in person for any fittings?**
A: There are no in-person fittings at all. You share one photo using my sizing guide — that's it. I do the measuring, making, and coordinating. You just have to take the photo.

**Q: Can I adjust the design after placing my order?**
A: Minor adjustments (a colour shift, adding or removing one element) are usually possible in the first 48 hours. Major design changes after production starts may require additional time. If something comes up, message me — I'm flexible where I can be.

**Q: Can family members order bridal sets together?**
A: Yes. I can make sets for the bride, the mother of the bride, and the bridal party as a group order. Just mention it when you place your order and we'll coordinate sizes and designs for everyone.

**Q: Is full payment required upfront?**
A: Bridal Trio orders are paid in two stages: 50% deposit on order confirmation (to reserve your production slot), and 50% before dispatch. Payment is via JazzCash, EasyPaisa, or bank transfer.

---

## Internal Links from This Page

| Destination | Trigger |
|---|---|
| `/order/start` | "Order the Bridal Trio" (hero) + "Order your Bridal Trio" (checklist) |
| `/shop?tier=bridal_single` | "More designs in the bridal collection" |
| `/size-guide` | Referenced in FAQ answer #2 |
| WhatsApp | Hero secondary CTA + timeline note + any FAQ that routes to WhatsApp |

---

## Assets Needed

- [ ] Hero: 3 coordinated bridal sets photographed together (velvet/marble surface)
- [ ] Mehendi panel: warm/gold-toned nails
- [ ] Baraat panel: dramatic/deep-toned nails
- [ ] Valima panel: luminous/soft-toned nails
- [ ] 6–9 bridal gallery photos (lifestyle preferred — nails on bride's hands, events)
- [ ] Bridal FAQs entered in Filament (`faqs` table, category: `bridal`)
