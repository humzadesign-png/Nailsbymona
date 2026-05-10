# Global — Header, Footer & Shared Conventions

> Applies to every page on the site. Read this file first before opening any individual page file.
> **Source of truth for design tokens:** `.claude/skills/design-system.md`. This file mirrors the highlights.
> **Project bible:** `CLAUDE.md` — overrides everything else if conflicts arise.
> **2026-04-29 design revision applied** — see `CLAUDE.md` §32 for what changed.

---

## Color Palette (Tailwind tokens)

The accent color (`#BFA4CE`) is sourced from the actual logo SVG. It is the **accent only** — appears in 8 specific places, never as a background tint, body text, or heading color. All palette entries live in `tailwind.config.js`.

| Token | Hex | Use |
|---|---|---|
| `bone` | `#F4EFE8` | Page background — warm off-white, atelier feel |
| `paper` | `#FBF8F2` | Cards, modals, elevated surfaces |
| `shell` | `#EAE3D9` | Alternating sections, footer |
| `white` | `#FFFFFF` | Form fields, image overlays only |
| `ink` | `#1A1614` | H1, H2, primary text — warm charcoal |
| `graphite` | `#3A332E` | H3, secondary headings |
| `stone` | `#7A6F65` | Body, captions, nav inactive |
| `ash` | `#B5A99C` | Muted, placeholders |
| `hairline` | `#E0D9CE` | Dividers, input borders |
| `lavender` | `#BFA4CE` | Accent only — CTAs, focus rings, price, active states |
| `lavenderDark` | `#9B7FB4` | CTA hover |
| `lavenderInk` | `#5B4570` | Accent text on light bg |
| `lavenderWash` | `#F2ECF8` | Selected payment tile bg only |
| `bridalBg` | `#EDE2C8` | Champagne — bridal section bg only (warmer shell, evokes gold thread) |
| `footerBg` | `#2C1F2E` | Deep aubergine — footer bg + dark hero fallback (warm-plum family, derives from lavender at low lightness) |
| `gold` | `#B8924A` | Antique gold — bridal moments only (price emphasis, premium-tier accents) |
| `goldDeep` | `#8F6F37` | Deeper gold — dark text on champagne; bridal price treatment |
| `success` / `warning` / `danger` | `#3F6E4A` / `#A6671A` / `#A33A2E` | Semantic states |

**Removed (do not use):** `cream`, `cloud`, `lavenderFaint`, `lavenderLight`, `lavenderDeep`, `body`, `mute`, `subtle`. These tokens are gone — the warm-neutral system above replaces them. **Banned:** rose, blush, any color not above.

**Lavender appears in 8 places only** — see design-system.md §2.

---

## Typography

**Display:** Fraunces (variable serif). **Body:** DM Sans (variable sans). Both Google Fonts. These are the only two typefaces. **Banned:** Cormorant Garamond, Inter, Material Symbols Outlined.

```html
<!-- In layouts/app.blade.php <head> -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,SOFT@9..144,300..600,30..100&family=DM+Sans:opsz,wght@9..40,300..600&display=swap" rel="stylesheet">
```

| Element | Tailwind classes |
|---|---|
| Hero H1 | `font-serif text-display-xl text-ink` |
| Section H2 | `font-serif text-display-lg text-ink` |
| Subsection H3 | `font-sans text-h3 font-medium text-ink` |
| Body | `font-sans text-body text-graphite` |
| Body lead | `font-sans text-body-lg text-graphite` |
| Caption | `font-sans text-caption text-stone` |
| Eyebrow (section label) | `font-sans text-eyebrow text-lavender uppercase` |
| Nav link | `font-sans text-caption font-medium text-graphite hover:text-ink` |
| Price | `font-sans text-h3 font-medium text-lavender tabular-nums` |
| Button text | `font-sans text-caption font-medium tracking-wide` |
| Blog body | `font-sans text-body-lg text-graphite leading-[1.85]` |

Use `font-light` (300) for display headings — never bold.

---

## Logo

| File | Use |
|---|---|
| `public/logo-text.svg` | **Website delivery** — nav, footer, OG, emails |
| `brand/logo-text.svg` | Design reference copy |
| `brand/logo-circle.svg` | Stickers & packaging only |
| `Nails by Mona Logo.svg` (root) | Original source file from Humza — do not delete |

- On `bone`/`paper`/`shell` backgrounds: render `public/logo-text.svg` as-is (color is `#BFA4CE`)
- On dark / lavender backgrounds: `filter: brightness(0) invert(1)` for white version
- **Never** recolor, stretch, rotate, or shadow
- Minimum nav width: 140px. Logo is always a link to `/`

```html
<a href="/" aria-label="Nails by Mona — Home">
  <img src="/logo-text.svg" alt="Nails by Mona" class="h-9 w-auto" width="160" height="56">
</a>
```

---

## Header — Hybrid Pattern

### Desktop ≥1024px

```
[Logo — left]   [Shop · Bridal · About · Journal · Help]   [Search] [Bag(0)]
```

- **No "Order Now" button.** The bag icon is the only commerce CTA in the header.
- **Glass nav:** `sticky top-0 z-50 bg-bone/85 backdrop-blur-md border-b border-hairline/50`.
- **Active nav link:** `text-ink border-b-2 border-lavender pb-0.5`.
- **Bag count badge:** `bg-lavender text-white text-[10px]` pill — only shown when count > 0.
- **Nav labels:** "Journal" labels `/blog`, "Help" labels `/contact`.

### Mobile / tablet <1024px

```
[Logo — left]                                    [Bag(0)] [☰]
```

- Hamburger → full-screen overlay slides in.
- Overlay: large editorial nav links (Shop · Bridal · About · Journal in `font-serif text-display`), secondary links (Help · Size Guide · Track Order · Care & Reuse in `text-eyebrow uppercase`), Instagram + WhatsApp icons in footer of overlay.
- Body scroll locks while overlay open. Escape and close button dismiss.
- **No fixed-bottom WhatsApp bar.** Removed — the bag icon and overlay handle commerce + customer-care entry points without persistent bottom clutter.

---

## Bag Drawer

- Right-side `<aside>`, slides in from `translate-x-full` on bag-toggle click.
- Width: `w-full max-w-md`. Background: `bg-paper`.
- Subtle shadow: `shadow-[-12px_0_24px_rgba(0,0,0,0.05)]`.
- Backdrop: `bg-ink/30` overlay behind drawer.
- Empty state shows "Browse designs" → `/shop`.
- Items list shows image · name · qty stepper · line subtotal · remove button.
- Footer pinned to bottom: subtotal, shipping note, "Checkout" CTA → `/order/start`.
- **Storage:** `localStorage` key `nbm.bag` as JSON array `[{slug, name, price_pkr, qty, image}]`. Persists across page loads.
- **On checkout:** array is POSTed to `/order/start`, server creates `Order` + `order_items` rows.

---

## Footer (every page)

**4-column layout (desktop) / stacked (mobile):**

**Col 1 — Brand**
- Logo
- 1-line: "Handmade in Mirpur. Shipped across Pakistan." *(no founder-personal language)*
- Instagram + WhatsApp icons

**Col 2 — Shop**
All designs · Bridal Trio · Size guide · Track order

**Col 3 — Studio**
About · Journal · Help

**Col 4 — Follow**
Instagram · WhatsApp · TikTok icons

**Bottom bar:** Copyright © 2026 Nails by Mona · Privacy · Terms · Shipping

---

## WhatsApp Deep-Link Pre-Fill — All Brand-Addressed

WhatsApp number: `+92XXXXXXXXXX` *(replace with real number before launch)*
Base URL: `https://wa.me/92XXXXXXXXXX?text=`

| Page | Pre-filled message |
|---|---|
| Home `/` | Hello Nails by Mona, I'd like to enquire about a custom set. |
| Shop `/shop` | Hello Nails by Mona, I'm browsing your shop and have a question. |
| Product `/shop/{slug}` | Hello Nails by Mona, I'm interested in [Product Name]. |
| Bridal `/bridal` | Hello Nails by Mona, I'm interested in the Bridal Trio for my wedding. |
| Size Guide `/size-guide` | Hello Nails by Mona, I need help with my sizing photo. |
| Help `/contact` | Hello Nails by Mona, I have a question. |
| Confirmation `/order/confirm/{order}` | Hello Nails by Mona, here's my payment proof for order NBM-XXXX. |
| Tracking `/order/{order}/track` | Hello Nails by Mona, I have a question about order NBM-XXXX. |

**Banned copy:** "Ask Mona", "DM Mona", "Hi Mona!", "Talk to Mona". CTA labels are **"Get help"**, **"Customer care"**, **"Contact us"**.

---

## Trust Signals — Where They Appear

| Signal | Pages |
|---|---|
| Trust bar (Custom-fit · Wudu-friendly · Reusable 3–5× · Shipped Pakistan-wide) | Home below hero, Product detail above fold |
| Free first refit guarantee | Home, Product, Size Guide |
| "Handmade in Mirpur. Shipped across Pakistan." | Footer (all pages) |
| Process transparency copy | About, Size Guide mid-page |
| Payment-methods note (JazzCash · EasyPaisa · Bank Transfer; manual proof upload) | Shop sidebar, Product detail, Confirmation page |
| Wudu-compatible messaging | About, Journal (wudu post), Product where relevant |

**Removed:** "3-Day Stick Guarantee" (we don't offer it). "Real Person Replies" card (replaced with brand-neutral language).

---

## Section Layout Rules

- **Page max-width:** `max-w-7xl mx-auto px-6 lg:px-10`
- **Section vertical padding:** `py-20 md:py-28`
- **Section bg alternation:** `bone` → `paper` → `shell`. Never the same bg twice in a row.
- **Section label pattern:** eyebrow microcopy (`text-eyebrow text-lavender uppercase`) above H2, then H2, then accent rule `h-0.5 w-10 bg-lavender` underneath.
- **Card radius:** `rounded-2xl`. Image inside card: `rounded-none` (editorial, fills the card corners).
- **Button radius:** `rounded-full`. Input radius: `rounded-xl`.
- **Hover transitions:** color `duration-200`, image scale `duration-700 ease-out`, scale `1.03` (not 1.05).

---

## Button Variants

**Primary (filled):** `bg-lavender hover:bg-lavenderDark text-white text-caption font-medium tracking-wide rounded-full px-6 py-3`

**Secondary (outlined):** `border border-ink text-ink hover:bg-ink hover:text-bone text-caption font-medium tracking-wide rounded-full px-6 py-3`

**Ghost / text link:** `text-caption text-graphite hover:text-lavenderInk underline-offset-4 hover:underline`

**Large CTA (hero):** `bg-lavender hover:bg-lavenderDark text-white text-base font-medium tracking-wide rounded-full px-9 py-4`

**Banned:** "Order Now" button in nav. Drop shadows on buttons. Lavender outlines (use `border-ink` for secondary).

---

## Icons

### 14 custom thematic SVGs (in `public/icons/`)

Press-on nail · sparkle · diamond ring · ruler · camera · gift box · heart · star · flower · palette · ribbon · coin · Instagram · WhatsApp · TikTok.

ViewBox `0 0 24 24`, stroke-width `1.5`, `currentColor` for color via Tailwind classes. Inline SVG via Blade partials at `resources/views/components/icon/*.blade.php`.

### UI utility icons — Phosphor Light

For menu, close, bag, search, arrows, check, plus/minus, truck, clock, map-pin, eye, trash, upload, envelope, info, warning. Copied SVG paths into Blade partials. **Banned:** Heroicons, Material Symbols, FontAwesome, Bootstrap Icons.

### Color tokens

| Context | Class |
|---|---|
| UI utility | `text-stone hover:text-ink` |
| Brand / accent | `text-lavender` |
| Active nav | `text-ink` |
| On filled lavender bg | `text-white` |
| Disabled | `text-ash` |
| Destructive (admin) | `text-danger` |

---

## Photography — Locked Rules

1. **Never** show Mona's face. Anywhere.
2. **Never** show a customer's face. Crop at the wrist, forearm, or below shoulders.
3. UGC submissions with a face → admin sets `face_visible = true` → **never** displayed publicly.
4. Process photos: hands working, tools, paint pots, packaging — no face in frame.
5. Bridal photography: hands on red velvet / gold thread / mehndi henna — face cropped.
6. About page: hand portrait + handwritten "Mona" signature SVG (**not** a face portrait).
7. All images have `width` + `height` attributes (CLS prevention).
8. `alt` describes the design and placement, never "photo of Mona".

---

## What's Removed (2026-04-29 revision)

- ❌ `/gallery` page and "Gallery" nav link
- ❌ "Order Now" button (anywhere, but especially in nav)
- ❌ "Ask Mona" / "DM Mona" / "Hi Mona!" copy
- ❌ Mobile fixed-bottom WhatsApp bar (the hamburger overlay handles it)
- ❌ Mona's face in any imagery
- ❌ Cormorant Garamond + Inter fonts
- ❌ `cream`, `cloud`, `lavenderFaint`, `lavenderLight` color tokens
- ❌ Material Symbols + Heroicons
