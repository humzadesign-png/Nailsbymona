# Page: Home `/`

> **2026-04-29 design revision overrides any conflicting detail below.** See `.claude/skills/design-system.md` and `docs/pages/00-global.md` for canonical tokens.
>
> **Key changes for this page:**
> - **H1:** "Custom-fit press-on nails, made for your hands." (no "by Mona" since face/founder is de-emphasized; brand line stays in nav and footer)
> - **Hero image:** full-bleed hand portrait. **No face.** Caption-style eyebrow above hero (`text-eyebrow text-lavender uppercase`).
> - **Primary CTA:** "Shop the Collection" → `/shop` (lavender filled). Secondary: WhatsApp ghost link "Get help" with brand-addressed pre-fill (see Section 1).
> - **Trust bar tiles:** Custom-fit · Wudu-friendly · Reusable 3–5× · Shipped Pakistan-wide.
> - **No "Order Now" button. (Replaced by the bag drawer pattern — see `00-global.md`.)**
> - **Sections (in order):** Hero · Trust bar · Fit Difference (2-col editorial) · Collection (bento, 1 hero + 4 small) · Bridal Trio dark callout (`bg-ink`) · **"Worn across Pakistan" UGC grid (6 hand-only tiles — replaces the old gallery)** · Studio teaser (**hand+tools image with handwritten "Mona" signature SVG; never a face portrait**) · How it works (4 numbered steps) · Pricing tier table · Journal teaser (3 cards).
> - **Section bg alternation:** `bone` (hero) → `paper` (trust) → `bone` (fit) → `shell` (collection) → `ink` (bridal callout) → `bone` (UGC) → `paper` (studio) → `shell` (how it works) → `bone` (pricing) → `paper` (journal). Never the same bg twice in a row.
> - All copy uses warm-charcoal text (`text-graphite`/`text-ink`), Fraunces for display, DM Sans for body.

**Purpose:** Turn first-time visitors into believers — prove fit, authenticity, and craft before asking for a sale.
**Template:** `resources/views/home.blade.php`
**Layout:** `layouts.app`
**Schema.org:** `Organization` + `WebSite` (with SearchAction)

---

## SEO

| Field | Value |
|---|---|
| H1 | Custom-fit press-on nails, made for your hands. |
| Meta title | Nails by Mona — Custom-Fit Press-On Gel Nails, Pakistan |
| Meta description | Handmade, custom-fit press-on gel nails made by Mona in Mirpur, AJK. Live-camera sizing, free first refit, shipped across Pakistan. Shop from PKR 1,800. |
| Canonical | `https://nailsbymona.pk/` |
| hreflang | `en-PK` |
| OG image | Hero photo (Mona's hands with a finished set) |

---

## Section 1 — Hero

**Background:** Full-width editorial photo — Mona's hands photographed from above holding a finished bridal set against a warm bone/paper surface (`#F4EFE8` / `#FBF8F2`). Hands cropped at the wrist. Real photo only — never stock, never AI-generated. Section sits on `bg-bone` so the hero photo bleeds seamlessly into the page below.

**H1 (Fraunces, font-light):**
> Custom-fit press-on nails, made for your hands.

**Subheadline (DM Sans, text-body):**
> Nails sized to your hands. Gel finish. Handmade in Mirpur, AJK. Shipped across Pakistan.

**CTAs (stacked on mobile, side-by-side on desktop):**
- Primary: `bg-lavender` pill → "Shop the Collection" → `/shop`
- Secondary: WhatsApp icon + "Get help" → WhatsApp deep-link (pre-filled: "Hello Nails by Mona, I'd like to inquire about a custom set.")

---

## Section 2 — Trust Bar

Single row of 4 icon + text pairs, `bg-paper`, directly below hero. Icons render at 24×24, `text-stone`, `stroke-width: 1.5`.

| Icon | Text |
|---|---|
| `sizing.svg` (custom ruler) | Custom-fit |
| Phosphor `drop` (water — wudu) | Wudu-friendly |
| Phosphor `arrows-clockwise` | Reusable 3–5× |
| `package.svg` | Shipped Pakistan-wide |

> **Source of truth:** trust bar contents live in `docs/pages/00-global.md` § Trust Signals. Update there if changing — this page mirrors.

---

## Section 3 — "Finally, nails that actually fit."

**Background:** `bg-bone`
**Layout:** 2-column (desktop) — image left, text right. Mobile: stacked, text below image.

**Section label (lavender uppercase):** THE FIT DIFFERENCE

**H2:**
> Finally, nails that actually fit.

**Body copy (Mona's voice, 2–3 paragraphs):**
> I know what it's like to order something beautiful and have it lift off by day two because it wasn't made for your nail shape. Most press-on brands ship 24 generic sizes in a bag and call it custom. That's not what I do.
>
> Every single set I make starts with your measurements — two close-up photos (your fingers and your thumb, each with a coin for scale) using my live-camera sizing guide. I read your nail widths directly off the coin in each photo, build each nail to those exact dimensions, and send you a set that actually fits.
>
> If your first order doesn't fit perfectly, I resize it for free. No questions, no debate. I'd rather spend the time getting it right.

**Image:** Phone showing the live-camera overlay screen (screenshot or mockup — hand outline + coin circle visible on screen)

**CTA (text link):** "See how sizing works →" → `/size-guide`

---

## Section 4 — "New & loved designs"

**Background:** `bg-shell`
**Layout:** 3-column product grid (desktop), 2-column (mobile)

**Section label:** THE COLLECTION
**H2:** New & loved designs.

**Products shown:** 6 featured products (`is_featured = true` in DB, admin-selectable via Filament)

**Each product card:**
- Image (1:1 WebP, `scale-103` on hover, `duration-700 ease-out` per `00-global.md` § Section Layout Rules)
- Tier badge (small pill, top-left of image, see tier colours below)
- Product name (H3, font-medium, `text-ink`)
- Price: "From PKR X,XXX" (`text-lavender`, tabular-nums)
- "Add to bag" button (primary pill, full-width on mobile) — **opens the bag drawer (`00-global.md` § Bag Drawer); does not redirect to `/order/start`. Updates `localStorage` key `nbm.bag` and increments the bag count badge in the nav.**

**Tier badge colours** (lavender intentionally avoided — accent reserved for the 8 cases listed in `00-global.md`. Gold reserved for bridal-only moments):
- Everyday: `bg-paper border border-hairline text-stone`
- Signature: `bg-shell/95 text-graphite`
- Glam: `bg-graphite/95 text-bone`
- Bridal Single / Bridal Trio: `bg-gold/95 text-ink` *(uses the new `gold` token — sets bridal apart from Glam at a scan-distance, signals premium)*

**CTA below grid:**
"See all designs" → `/shop` (secondary outlined pill)

---

## Section 5 — "Made for Every Big Day" (Bridal Trio callout)

**Background:** `bg-ink` — the only dark section on the home page. Used as a deliberate visual hinge separating browse-and-trust above from social-proof below.
**Layout:** 2-column — editorial flat-lay photo (left), text (right). Mobile: image above, text below.

**Section label** (`text-eyebrow text-lavender uppercase`): FOR BRIDES
**H2** (`font-serif font-light text-bone`): Bridal? We have a package for that.
**Accent rule:** `h-0.5 w-10 bg-lavender` directly under the H2.

**Copy** (`text-bone/85`, generous line-height):
> Pakistan's wedding season deserves nails for all three nights. The Bridal Trio covers Mehendi, Baraat, and Valima — one fitting, three coordinated sets, delivered together.

**Price anchor** (`text-lavender`, `font-medium`):
> Starting from PKR 11,000 for all three events.

**CTA:** "Explore the Bridal Trio" → `/bridal` (primary pill — `bg-lavender` reads correctly against `ink`).

**Photo:** Flat-lay of 3 coordinated nail sets — warm gold (Mehendi), deep drama (Baraat), soft luminous (Valima). All on red velvet, gold thread, or warm marble. **Hand-only or product-only — never a face in frame.**

**Image-area fallback gradient** (used when the photo doesn't load): `linear-gradient(150deg, #EDE2C8 0%, #D9C28A 40%, #B8924A 100%)` — moves from `bridalBg` (champagne) through a mid-gold step into `gold`, all from the bridal token family.

---

## Section 6 — "Worn and loved by customers across Pakistan"

**Background:** `bg-bone`
**Layout:** Masonry / 3-column grid. 6–9 real customer photos from Mona's Instagram (with permission).

**Section label:** REAL CUSTOMERS
**H2:** Worn and loved by customers across Pakistan.

**Photo caption style:** "Lahore · Signature set" — city + tier only. No faces required; hands are enough.

**Below grid (small text):**
> Share @nailsbymona on Instagram and you might be featured here.

**No AI-generated images. No stock photos. Real hands only.**

---

## Section 7 — "Made by one person. For your nails specifically."

**Background:** `bg-paper`
**Layout:** 2-column — text (left), studio image (right). Mobile: image above, text below.

**Section label:** THE MAKER
**H2:** Made by one person. For your nails specifically.

**Copy** (first-person, Mona's voice):
> My name is Mona, and I make every set myself in my studio in Mirpur. Each order gets my full attention — from the moment I read your sizing photo to the moment I seal the box. I'm not a brand. I'm a person who loves what she makes.

**Image — locked photography rule (CLAUDE.md §24, `00-global.md` § Photography):** **Never a face portrait.** A hand-and-tools shot at the worktable — Mona's hands holding a brush mid-application, with gel lamp, nail forms, and paint pots visible in soft frame. Warm natural light. Cropped at the wrist or forearm.

**Overlay:** A handwritten "Mona" SVG signature placed in a quiet corner of the image (lavender ink on the warm tones), serving as the personal-brand anchor — not a face, just a hand. Same signature pattern used on the About page hero.

**CTA:** "Read Mona's full story →" → `/about` (ghost text link)

---

## Section 8 — "Ordering is simpler than you think"

**Background:** `bg-shell`

**Section label:** HOW IT WORKS
**H2:** Ordering is simpler than you think.

**4-step flow (horizontal on desktop, vertical on mobile):**

| Step | Heading | Copy |
|---|---|---|
| 1 | Pick your design | Browse the collection and choose your set |
| 2 | Share your sizing | Two close-up photos using the live-camera guide — fingers, then thumb. About 90 seconds. |
| 3 | Mona makes your set | Handcrafted in 5–9 working days |
| 4 | Apply in under 10 minutes | Reusable 3–5 times with proper care |

**Step connector:** thin lavender line between steps (hidden on mobile)

**Below steps:**
> Questions? Mona answers personally.
> [WhatsApp CTA — "Message Mona"]

---

## Section 9 — Pricing Anchor

**Background:** `bg-bone` (breaks the shell-shell repeat with Section 8 above)

**Section label:** PRICING
**H2:** Sets for every occasion.

**2-column tier table (desktop) / stacked cards (mobile):**

| Tier | Price | Description |
|---|---|---|
| Everyday Plain | PKR 1,800–2,200 | Single colour, custom-fit, everyday elegance |
| Signature | PKR 2,500–3,500 | Ombre, nail art, hand-applied detail |
| Glam | PKR 3,800–4,800 | Charms, 3D elements, hand-painted designs |
| Bridal Single | PKR 5,000–6,500 | One event, premium packaging |
| Bridal Trio | PKR 11,000–13,500 | All three wedding nights |

**CTA:** "Shop all tiers" → `/shop` (primary pill)

---

## Section 10 — Blog Teaser "From Mona's desk"

**Background:** `bg-paper`

**Section label:** FROM THE BLOG
**H2:** From Mona's desk.

**Layout:** 3-column card grid (desktop), 1-column (mobile)
Shows 3 most recently published blog posts (auto-populated from DB).

**Each card:** Cover image (16:9) · Category badge · Title (serif) · 1-line excerpt · "Read more →"

**CTA below:** "All posts" → `/blog` (secondary outlined pill)

---

## Internal Links from This Page

| Destination | Trigger |
|---|---|
| `/shop` | "Shop the Collection" (hero) + "See all designs" + "Shop all tiers" |
| `/bridal` | "Explore the Bridal Trio" |
| `/size-guide` | "See how sizing works" |
| `/about` | "Read Mona's full story" |
| `/blog` | "All posts" + 3 post cards |
| WhatsApp | Hero "Get help" + "How it works" section |

---

## Assets Needed

> **Photography rule (locked):** Hand-only photography. Mona's face never appears. Customer faces never appear (cropped at the wrist). UGC with `face_visible = true` is excluded by the admin layer. See `00-global.md` § Photography for full rules.

- [ ] **Hero photo:** Mona's hands holding a finished bridal set, overhead shot, on a warm bone/paper surface. Hands cropped at the wrist. Aspect ratio: ~16:10 (full-bleed).
- [ ] **Live-camera mockup:** Phone in hand showing the camera screen with the SVG overlay (hand outline + coin circle) visible. Vertical orientation.
- [ ] **3 bridal sets flat-lay:** Mehendi (warm gold tones), Baraat (deep drama — burgundy/black), Valima (soft luminous — pearl/champagne). Backgrounds: red velvet, gold thread, or warm marble. **Never a face.**
- [ ] **6–9 customer lifestyle photos:** Hands wearing sets, with permission from IG. Hands cropped at the wrist or forearm. **`face_visible = true` photos are not used.**
- [ ] **Studio image (Section 7):** Hand-and-tools shot at the worktable — Mona's hands holding a brush mid-application, gel lamp + nail forms + paint pots in soft frame. Warm natural light. **Not a face portrait.**
- [ ] **Handwritten "Mona" signature SVG:** Lavender ink, ~120px wide, transparent background. Used in Section 7 corner overlay and About page hero.
