# Page: Blog Post `/blog/{slug}`

> ⚠ **2026-04-29 design revision applies.** Canonical design tokens, nav pattern, photography rules, copy guidelines, and CTA labels are in `00-global.md` and `.claude/skills/design-system.md`. In particular: bag-drawer pattern (no "Order Now"), brand-addressed WhatsApp pre-fills (no "Ask Mona"/"Hi Mona!"), hand-only photography (no faces), Fraunces + DM Sans fonts, warm-neutral palette (`bone`/`paper`/`shell`/`ink`/`graphite`/`stone`), `/gallery` removed.

---

> Design tokens, typography, and patterns: see `00-global.md` + `.claude/skills/design-system.md` — those files are authoritative.

---

**Purpose:** Rank for educational and long-tail search queries. Build trust through genuine expertise. Route organic traffic into the shop via related products and natural in-body links.
**Template:** `resources/views/blog/show.blade.php`
**Layout:** `layouts.app`
**Schema.org:** `Article` + `FAQPage` + `BreadcrumbList`

---

## SEO

| Field | Value |
|---|---|
| H1 | Post title (target keyword in natural sentence form — not stuffed) |
| Meta title | {Post Title} \| Nails by Mona |
| Meta description | From `blog_posts.meta_description` — 150–160 chars, target keyword + value proposition |
| Canonical | `https://nailsbymona.pk/blog/{slug}` — no parameter variants indexed |
| OG image | `blog_posts.og_image`, fallback: `blog_posts.cover_image` |
| Breadcrumb | Home > Blog > [Category] > [Post Title] |
| Updated date | Include `dateModified` in Article schema if post has been updated |

---

## Above the Fold

**Breadcrumb (small, above H1):**
Home > Blog > [Category] > [Post Title]
Style: `text-mute text-sm`

**H1:** Post title
`font-serif text-4xl font-light text-ink leading-tight`

**Meta bar (single row, below H1):**
`[Category badge] · [Publish date] · [Updated date if applicable] · [Read time] · by Mona`
Style: `text-mute text-sm`

*Read time calculated server-side: `ceil(word_count / 200)` minutes.*

**Cover image (full-width, 16:9, lazy=false — above fold):**
- Real photo. No AI-generated images.
- `alt` from `blog_posts.cover_image_alt` — descriptive and keyword-natural
- Rendered as `<picture>` with WebP + JPEG fallback, `srcset` for 768 and 1280 sizes

---

## Article Body

**Max-width:** 740px, centred. Wide enough to read comfortably, narrow enough to feel editorial.

**Typography:**
- Body: `font-sans text-body leading-relaxed text-base`
- H2s: `font-serif text-2xl font-light text-ink mt-12 mb-4`
- H3s: `font-sans text-lg font-semibold text-ink mt-8 mb-2`
- Blockquotes: `border-l-4 border-lavender pl-6 italic text-body`

**Intro rule:** First 150 words must open with substance. No "In this article, I'll cover…" openers. Lead with the most valuable sentence. Example:

> *"Press-on nails get removed in warm water. Acrylics get dissolved in acetone. That difference — 10 minutes versus 20 minutes — is just the start of what separates them."*

**Body structure: 6–10 H2 sections, 150–300 words each.**

**In-body photos:** Real photos every 3–4 paragraphs. Each with descriptive `alt` text. WebP + JPEG fallback.

**Internal link rules (every post must have):**
- 2–3 anchor links to relevant `/shop/{slug}` pages (link text = design name, never "click here")
- 1 link to `/size-guide` in any application/sizing post
- 1 link to `/bridal` in any wedding/bridal post
- 1–2 links to related blog posts (cross-linking builds topical authority)

**External links:** `rel="nofollow"` on any link to a third-party product or source. Never link to a direct competitor.

---

## FAQ Section

**Background:** `bg-shell` (pulled out from the main column — full-width strip)
**Layout:** Max-width 740px, centred — consistent with body column

**Heading (H2):** Questions I get about this.

**5–7 Q/A entries, jQuery accordion.**
- Questions loaded from `faqs` table filtered by relevant `category`
- If no matching FAQs exist, this section is hidden (Blade conditional)
- Feeds `FAQPage` JSON-LD schema (rendered via `<x-seo>` component with the post's FAQ data passed in)

**Format per entry:**
```
Q: [Question text]
A: [Answer — 1–3 sentences, Mona's voice, conversational]
```

---

## Related Products Block

**Background:** `bg-paper`
**Layout:** 3 product cards side-by-side (desktop), horizontal scroll (mobile)

**Heading (H2):** Designs mentioned in this article.

*This section only renders if `blog_post_products` pivot has entries for this post. Admin sets this in Filament.*

**Each card:**
- Product image (1:1, WebP)
- Tier badge (colour-coded)
- Product name
- Price: "From PKR X,XXX"
- "Add to bag" button (opens bag drawer) → `/order/start/{slug}`

**This is the commerce bridge.** Every educational post should route readers into at least one relevant product.

**Example pairings:**
- Bridal post → Bridal Trio product card + 1–2 bridal single products
- Application tutorial → 2–3 Everyday or Signature products
- Wudu post → cross-section of tiers (show variety)
- Removal/care post → any currently featured product

---

## Author Block

**Background:** `bg-paper`
**Layout:** 2-column — Mona's circular avatar left (64px), text right. Mobile: avatar top.

**Avatar:** `public/images/mona-avatar.jpg` — circular crop, 64×64px

**Text:**
**Written by Mona**
> *I'm Mona, and I handmake every press-on gel nail set myself in my studio in Mirpur, Azad Kashmir. If you have a question about anything in this post — or want a set made — WhatsApp me directly.*

**CTA:** "Get help on WhatsApp →" (pre-filled: "Hello Nails by Mona, I read your article about [post title] and have a question.")

---

## Related Posts

**Background:** `bg-paper`
**Layout:** 3 cards (same category, or manually set in Filament via `blog_posts.related_post_ids`)

**Heading (H2):** Keep reading.

*If no related posts are set manually, auto-populate with 3 most recent posts in the same category.*

**Each card:** Same structure as blog index post cards (cover image, category badge, title, excerpt, date).

---

## Social Sharing

**Background:** None — inline at bottom of article body, before FAQ section.
**Style:** Text-only links (no external JS widgets, no tracking pixels)

**Links:**
- "Share on WhatsApp" → `https://wa.me/?text={encoded_title}%20{encoded_url}`
- "Copy link" → clipboard API (`navigator.clipboard.writeText(url)`) → inline "Copied!" confirmation

`text-mute text-sm` — subtle, not prominent. Sharing is helpful but secondary to conversion.

---

## Internal Links from This Page

| Destination | Trigger |
|---|---|
| `/shop/{slug}` | Related products block + in-body anchor links |
| `/size-guide` | In-body for sizing/application posts |
| `/bridal` | In-body for bridal posts |
| `/blog/{slug}` | Related posts section + in-body cross-links |
| WhatsApp | Author block CTA |

---

## Schema.org — `Article`

```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "{post title}",
  "image": "{og_image_url}",
  "author": {
    "@type": "Person",
    "name": "Mona",
    "url": "https://nailsbymona.pk/about"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Nails by Mona",
    "logo": {
      "@type": "ImageObject",
      "url": "https://nailsbymona.pk/logo-text.svg"
    }
  },
  "datePublished": "{published_at ISO8601}",
  "dateModified": "{updated_at ISO8601}",
  "description": "{meta_description}"
}
```

`FAQPage` schema rendered separately if FAQ section has entries — see `<x-seo>` component.

---

## Data Requirements

| Source | Used for |
|---|---|
| `blog_posts` | All post fields |
| `faqs` table | FAQ accordion (filtered by `category`) |
| `blog_post_products` pivot | Related products (filtered by `blog_post_id`) |
| `products` table | Product cards in related block |

---

## Assets Needed (per post)

- [ ] Cover image (real photo, 16:9, WebP, descriptive alt text)
- [ ] 4–6 in-body images (process photos, product shots, examples)
- [ ] 5–7 FAQ entries entered in Filament (`faqs` table, correct category)
- [ ] Related products linked in Filament (`blog_post_products` pivot)
- [ ] Mona reviews + voices each post before publish

---

## Cornerstone Post Briefs (to be written in Phase 4)

### Post 1: "Press-On Nails vs Acrylics: Which Is Better for Pakistani Brides?"
**Target keyword:** `press on nails vs acrylic`
**Category:** Bridal
**Target length:** 2,000–2,500 words
**Key H2 sections:**
1. The acrylic trap Pakistani brides fall into
2. What press-on nails actually are (and aren't)
3. Cost comparison across a full wedding season
4. Nail damage: what the evidence shows
5. Pre-wedding timeline stress — acrylic vs press-on
6. Can you remove press-ons for wudu? (Yes — and it matters)
7. Our honest verdict
**Related products:** Bridal Trio + 2 bridal singles
**Internal links:** `/bridal`, `/size-guide`

---

### Post 2: "How to Apply Press-On Nails — A Foolproof 7-Step Guide"
**Target keyword:** `how to apply press on nails`
**Category:** Tutorials
**Target length:** 1,800–2,200 words
**Key H2 sections:**
1. What you'll need before you start
2. Why prep is 80% of the result
3. Step 1: Clean and shape your nails
4. Step 2: Push back cuticles
5. Step 3: Buff the surface lightly
6. Step 4: Apply prep pad / dehydrator
7. Step 5: Apply glue — how much is too much
8. Step 6: Press and hold correctly
9. Step 7: Final check and cleanup
10. How long should they last?
**Related products:** 2–3 Everyday or Signature sets
**Internal links:** `/size-guide`, `/shop`

---

### Post 3: "Bridal Nail Trends in Pakistan for 2026"
**Target keyword:** `bridal nail trends Pakistan 2026`
**Category:** Bridal
**Target length:** 1,500–2,000 words
**Key H2 sections:**
1. Mehendi nails — what's working this season
2. Baraat nails — the drama trend
3. Valima nails — why brides are going softer
4. Colour palettes Pakistani brides are choosing
5. 3D and charm elements — when it works, when it doesn't
6. The Bridal Trio advantage
**Related products:** Bridal Trio + bridal gallery cross-link
**Internal links:** `/bridal`, `/gallery`

---

### Post 4: "How to Remove Press-On Nails Without Damaging Your Natural Nails"
**Target keyword:** `how to remove press on nails`
**Category:** Care
**Target length:** 1,500–2,000 words
**Key H2 sections:**
1. Why you should never force or peel press-ons
2. The warm water soak method (step by step)
3. How long to soak — and how to tell when they're ready
4. Gentle lifting technique
5. What to do with your natural nails after
6. Storing your press-ons for reuse
7. Signs it's time to retire a set
**Related products:** 2–3 Everyday or Signature sets (reorder angle)
**Internal links:** `/size-guide` (for reorder sizing note), `/shop`

---

### Post 5 (Priority): "Can Muslim Women Wear Press-On Nails? Wudu, Nail Polish, and a Simple Solution"
**Target keyword:** `press on nails wudu` / `can Muslim women wear nail polish`
**Category:** Tutorials
**Target length:** 1,500–2,000 words
**Tone:** Personal, warm, directly from Mona's own experience. Not a religious ruling — a practical guide from a Muslim woman who found her own answer.
**Key H2 sections:**
1. The question I kept asking myself
2. Why traditional nail polish causes the problem (water barrier + wudu)
3. What about "breathable" or "halal" nail polish? (honest answer)
4. Salon acrylics — the same issue, bigger commitment
5. How press-on nails are different
6. My own routine — removing before wudu, reapplying after
7. What to tell people who ask about your nails
**Opening paragraph (draft):**
> "For years, I wore my nails plain. Not because I didn't care about how they looked — I care enormously about things like that — but because I couldn't find a way to have beautiful nails without compromising my prayers. This is the solution I found, and it's the reason I started making press-ons in the first place."
**Related products:** Everyday + Signature tiers (entry-level, everyday wear)
**Internal links:** `/size-guide`, `/shop`
**Important:** This post must be in Mona's authentic voice and include her personal experience. Do not reduce it to a dry FAQ. The authenticity is the SEO and conversion advantage.
