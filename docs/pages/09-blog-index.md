# Page: Blog Index `/blog`

> ⚠ **2026-04-29 design revision applies.** Canonical design tokens, nav pattern, photography rules, copy guidelines, and CTA labels are in `00-global.md` and `.claude/skills/design-system.md`. In particular: bag-drawer pattern (no "Order Now"), brand-addressed WhatsApp pre-fills (no "Ask Mona"/"Hi Mona!"), hand-only photography (no faces), Fraunces + DM Sans fonts, warm-neutral palette (`bone`/`paper`/`shell`/`ink`/`graphite`/`stone`), `/gallery` removed.

---

> Design tokens, typography, and patterns: see `00-global.md` + `.claude/skills/design-system.md` — those files are authoritative.

---

**Purpose:** Entry point for organic search traffic. Editorial and trustworthy — not a content farm. Routes visitors into the right post and, from there, into the shop.
**Template:** `resources/views/blog/index.blade.php`
**Layout:** `layouts.app`
**Schema.org:** `Blog` + `BreadcrumbList`

---

## SEO

| Field | Value |
|---|---|
| H1 | From Mona's desk. |
| Meta title | Nail Care, Bridal Guides & Press-On Tutorials | Nails by Mona Blog |
| Meta description | Real advice on press-on nails, bridal nail prep, and nail care from Mona — handmade nail artisan in Mirpur, Pakistan. No fluff, no filler. |
| Canonical | `https://nailsbymona.pk/blog` |
| OG image | Default brand OG (or cover of pinned featured post) |
| Breadcrumb | Home > Blog |
| RSS | `<link rel="alternate" type="application/rss+xml" href="/feed.xml">` in `<head>` |

---

## Section 1 — Hero

**Background:** `bg-shell`
**Height:** Slim — this is an index page, get to posts quickly.

**H1 (large serif, font-light):**
> From Mona's desk.

**Subheadline:**
> Everything I know about nails, bridal prep, and press-on care — written for real people, not search engines.

*No image in hero. Clean, editorial feel. Typography carries the section.*

---

## Section 2 — Category Filter Tabs

**Background:** `bg-white border-b border-subtle`
**Position:** Sticky below nav on scroll (same sticky treatment as gallery filter tabs)

**Tab pills (jQuery — filters visible posts by `.category-{name}` class, no page reload):**
All · Bridal · Tutorials · Trends · Care · Behind the Scenes

**Active tab style:** `text-lavender border-b-2 border-lavender font-semibold`
**Inactive tab style:** `text-body hover:text-lavender`

**Mobile:** All tabs visible as horizontal scroll strip (no collapse — tabs are short enough).

---

## Section 3 — Featured Post

**Background:** `bg-paper`
**Layout:** Full-width card, large. Left 55% image, right 45% text. Mobile: image top, text below.

**Which post:** Admin-pinned via `is_featured = true` in Filament. Falls back to most recently published if none pinned.

**Card content:**
- Cover image (left, full-height of card, object-cover)
- Category badge (top-left of text column): `bg-lavenderLight text-lavenderInk rounded-full text-xs uppercase tracking-wide`
- Post title (H2, serif, large)
- Excerpt (2–3 sentences, from `blog_posts.excerpt`)
- Meta row: publish date · estimated read time · "by Mona"
- CTA button: "Read article →" (`bg-lavender text-white rounded-full`)

**Note:** This is the first thing a blog visitor sees. The featured post should be the most persuasive, best-performing cornerstone post — the bridal vs. acrylics piece or the wudu/press-ons piece at launch.

---

## Section 4 — Post Grid

**Background:** `bg-paper`
**Layout:** 3 columns (desktop), 2 columns (tablet), 1 column (mobile)

**Filtered by active category tab (jQuery show/hide — no reload). All posts visible on "All" tab.**

**Each post card:**
- Cover image (16:9, WebP, lazy-load, `alt` from `blog_posts.cover_image_alt`)
- Category badge (colour-coded, same system as above)
- Title (H3 — 2 lines max, `font-serif font-light text-ink`)
- Excerpt (1–2 sentences, `text-body text-sm`)
- Meta row: `[Date] · [Read time] · by Mona` in `text-mute text-xs`
- No explicit CTA button on card — the entire card is clickable → `/blog/{slug}`

**Card hover:** Subtle shadow lift + title colour shifts to `text-lavender`. Image scales slightly (`scale-105`, `duration-200`).

**Pagination (server-side, Blade):** 12 posts per page. Simple prev/next links — no infinite scroll (cleaner analytics, better for SEO pagination handling).

---

## Section 5 — Subscribe Strip

**Background:** `bg-shell`
**Layout:** Two-column (desktop) — text left, form right. Mobile: stacked.

**Heading (H3):** Get new posts before they go on Instagram.

**Copy:**
> I write 2–3 times a month — bridal guides, nail care tips, and whatever I'm obsessing over in the studio. No spam, ever. Unsubscribe whenever you want.

**Form (Phase 1 MVP):**
- Email input (`type="email"`, placeholder: "your@email.com")
- Submit button: "Subscribe" (`bg-lavender text-white rounded-full`)
- POST → `SubscriberController@store` → saves to `subscribers` table (email + source: `blog_index`) → inline success: "You're in. I'll be in touch soon. — Mona"
- Rate-limited: `throttle:5,1`
- Honeypot field to block basic spam bots

**Below form (small, muted):**
> No account needed. Just your email.

---

## Section 6 — "More ways to find what you need"

**Background:** `bg-paper`
**Layout:** 3 quick-link tiles, horizontal (desktop), stacked (mobile)

*Optional section — only show if there are 6+ published posts. Helps navigation once the blog has volume.*

**Tile 1:** Bridal guide
Icon: `bridal.svg`
> "Planning a wedding? Start here."
→ filters to Bridal category (or links to `/blog?category=bridal`)

**Tile 2:** Application & care
Icon: `nail.svg`
> "How to apply, remove, and reuse."
→ filters to Tutorials + Care categories

**Tile 3:** Behind the scenes
Icon: `heart.svg`
> "See how I work."
→ filters to Behind the Scenes category

---

## Internal Links from This Page

| Destination | Trigger |
|---|---|
| `/blog/{slug}` | Post card clicks + Featured post CTA |
| `/shop` | Implicit via related posts in individual articles |
| WhatsApp | Not featured on index — lives on individual post pages |

---

## Data Requirements

| Source | Used for |
|---|---|
| `blog_posts` table | All posts: title, slug, excerpt, cover_image, cover_image_alt, category, published_at, is_published, is_featured |
| `subscribers` table | Subscribe form writes here |
| Read time | Calculated from `content` word count (200 words/min) — computed attribute on model |

**Query:** only `is_published = true` and `published_at <= now()`. Ordered by `published_at DESC`.

---

## Assets Needed

- [ ] At least 4 cornerstone posts published before launch (see Phase 4)
- [ ] Cover images for each post (real photos, 16:9, WebP)
- [ ] `is_featured` set on one post before launch (admin in Filament)
- [ ] RSS feed live at `/feed.xml` (auto-generated)

---

## Cornerstone Posts at Launch (Phase 4)

These 5 posts must be written, reviewed, and published before the site goes live. They are the entire blog's SEO foundation in Month 1.

| # | Title | Target keyword | Category | Priority |
|---|---|---|---|---|
| 1 | Press-On Nails vs Acrylics: Which Is Better for Pakistani Brides? | `press on nails vs acrylic` | Bridal | High |
| 2 | How to Apply Press-On Nails — A Foolproof 7-Step Guide | `how to apply press on nails` | Tutorials | High |
| 3 | Bridal Nail Trends in Pakistan for 2026 | `bridal nail trends Pakistan 2026` | Bridal | High |
| 4 | How to Remove Press-On Nails Without Damaging Your Natural Nails | `how to remove press on nails` | Care | High |
| 5 | Can Muslim Women Wear Press-On Nails? Wudu, Nail Polish, and a Simple Solution | `press on nails wudu` / `can Muslim women wear nail polish` | Tutorials | **Highest — most differentiated** |
