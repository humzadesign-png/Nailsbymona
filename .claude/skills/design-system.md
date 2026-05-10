# Nails by Mona — Design System

> **Authoritative design reference for all Claude sessions on this project.**
> All visual decisions — colors, typography, icons, spacing, components, layout — must follow this file.
> **CLAUDE.md is the project bible and overrides everything. This file overrides §10 of CLAUDE.md for UI implementation detail.**
> Direction: **warm neutral atelier with a single restrained lavender accent.** Aesop / Le Labo / Manucurist — never girly Pinterest template.

---

## 1. Brand Color Origin

The logo (`Nails by Mona Logo.svg`) uses one fill: **`#BFA4CE`** — soft dusty lavender-mauve.

Lavender is **the accent only.** It does **not** tint backgrounds, body text, headings, dividers, or icons. The discipline of keeping the brand color in <10% of the visual weight is what separates premium from amateur. Premium brands have neutral palettes with one well-chosen accent; amateur sites bathe everything in tinted variations of the brand color.

---

## 2. Color Palette

### Tailwind `colors` block — `tailwind.config.js`

```js
colors: {
  // Backgrounds — warm neutral system (NO purple tinting)
  bone:      '#F4EFE8',   // page background — warm off-white, atelier feel
  paper:     '#FBF8F2',   // cards, modals, elevated surfaces (warmer than #FFFFFF)
  shell:     '#EAE3D9',   // alternating section bg, footer
  white:     '#FFFFFF',   // form fields, image overlays only — sparingly

  // Text — warm charcoal scale (brown undertone, never purple)
  ink:       '#1A1614',   // H1, H2, primary body
  graphite:  '#3A332E',   // H3, secondary headings, strong body
  stone:     '#7A6F65',   // body labels, captions, nav inactive
  ash:       '#B5A99C',   // muted, placeholders
  hairline:  '#E0D9CE',   // dividers, input borders, decorative rules

  // ACCENT — lavender (logo color, used SPARINGLY)
  lavender:     '#BFA4CE',   // CTAs, focus rings, price text, active states
  lavenderDark: '#9B7FB4',   // CTA hover
  lavenderInk:  '#5B4570',   // accent text on light bg, pressed states
  lavenderWash: '#F2ECF8',   // ONLY for selected payment-tile bg, focus-input bg

  // Semantic — for status states only, never branding
  success: '#3F6E4A',   // in-stock badge, payment confirmed
  warning: '#A6671A',   // awaiting action, low stock
  danger:  '#A33A2E',   // sold out, errors, destructive admin actions
}
```

### Quick reference

| Token | Hex | Use |
|---|---|---|
| `bone` | `#F4EFE8` | Page background |
| `paper` | `#FBF8F2` | Cards, modals, elevated surfaces |
| `shell` | `#EAE3D9` | Alternating section bg, footer |
| `white` | `#FFFFFF` | Form fields, image overlays |
| `ink` | `#1A1614` | H1, H2, primary text |
| `graphite` | `#3A332E` | H3, secondary headings |
| `stone` | `#7A6F65` | Body, captions, nav |
| `ash` | `#B5A99C` | Muted, placeholders |
| `hairline` | `#E0D9CE` | Dividers, borders |
| `lavender` | `#BFA4CE` | CTA bg, price, active state |
| `lavenderDark` | `#9B7FB4` | CTA hover |
| `lavenderInk` | `#5B4570` | Accent text on light bg |
| `lavenderWash` | `#F2ECF8` | Selected/focused tiles only |

### Removed tokens (do not use)

`cream`, `cloud`, `lavenderFaint`, `lavenderLight`, `lavenderDeep`, `body`, `mute`, `subtle` — all from the pre-revision palette. They no longer exist. Don't reintroduce them.

### Where lavender appears (and nowhere else) — 8 places only

1. Primary CTA button background (`bg-lavender`)
2. Focus ring on inputs (`focus:ring-lavender`)
3. Price text on product cards (`text-lavender`)
4. Active nav link underline (`border-b-2 border-lavender`)
5. Step indicator — completed/active (`bg-lavender`)
6. Section accent rule below H2 (`h-0.5 w-10 bg-lavender`)
7. Selected payment method tile (`ring-2 ring-lavender bg-lavenderWash`)
8. Section label uppercase eyebrow (`text-lavender uppercase tracking-widest text-xs`)

That's it. Lavender does not appear on backgrounds, body text, headings, dividers, or icons.

### Section background alternation

`bg-bone` → `bg-paper` → `bg-shell`. Never the same background two sections in a row. The contrast between bone (lighter) and shell (warmer/darker) creates editorial pacing.

---

## 3. Typography

### Fonts — Fraunces (display) + DM Sans (body)

Both are free Google variable fonts. Distinctive but not precious. Replaces the Cormorant Garamond + Inter pairing that's used by every default beauty/wellness DTC.

```js
// tailwind.config.js
fontFamily: {
  serif: ['"Fraunces"', 'Georgia', 'serif'],          // display only — H1, H2, hero headlines
  sans:  ['"DM Sans"', 'ui-sans-serif', 'system-ui'], // body, labels, nav, buttons, admin
}
```

### Google Fonts import (in `<head>` of `layouts/app.blade.php`)

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght,SOFT@9..144,300..600,30..100&family=DM+Sans:opsz,wght@9..40,300..600&display=swap" rel="stylesheet">
```

### Font size tokens — `tailwind.config.js`

```js
fontSize: {
  // Display tier (Fraunces, font-light)
  'display-xl': ['clamp(3.5rem, 7vw, 5.5rem)',  { lineHeight: '0.95', letterSpacing: '-0.02em',  fontWeight: '300' }],
  'display-lg': ['clamp(2.75rem, 5vw, 4rem)',   { lineHeight: '1.0',  letterSpacing: '-0.015em', fontWeight: '300' }],
  'display':    ['clamp(2.25rem, 4vw, 3.25rem)',{ lineHeight: '1.05', letterSpacing: '-0.01em',  fontWeight: '300' }],
  // Body tier (DM Sans)
  'h3':         ['1.25rem',  { lineHeight: '1.4',  fontWeight: '500' }],
  'body-lg':    ['1.125rem', { lineHeight: '1.7',  fontWeight: '400' }],
  'body':       ['1rem',     { lineHeight: '1.65', fontWeight: '400' }],
  'caption':    ['0.875rem', { lineHeight: '1.5',  fontWeight: '400' }],
  'eyebrow':    ['0.75rem',  { lineHeight: '1.0',  letterSpacing: '0.18em', fontWeight: '500' }],
}
```

### Type scale — Tailwind classes per element

| Element | Classes |
|---|---|
| **Hero H1** | `font-serif text-display-xl text-ink` |
| **Section H2** | `font-serif text-display-lg text-ink` |
| **Subsection H3** | `font-sans text-h3 font-medium text-ink` |
| **Body** | `font-sans text-body text-graphite` |
| **Body lead** | `font-sans text-body-lg text-graphite` |
| **Caption** | `font-sans text-caption text-stone` |
| **Eyebrow (section label)** | `font-sans text-eyebrow text-lavender uppercase` |
| **Nav link (inactive)** | `font-sans text-caption font-medium text-graphite hover:text-ink transition-colors` |
| **Nav link (active)** | `font-sans text-caption font-medium text-ink border-b-2 border-lavender pb-0.5` |
| **Button text** | `font-sans text-caption font-medium tracking-wide` |
| **Price** | `font-sans text-h3 font-medium text-lavender tabular-nums` |
| **Form label** | `font-sans text-caption font-medium text-graphite` |
| **Badge / tag** | `font-sans text-eyebrow uppercase` |
| **Blog body** | `font-sans text-body-lg text-graphite leading-[1.85]` |

### Banned fonts

Cormorant Garamond, Inter, Material Symbols Outlined, any other typeface. **Only Fraunces and DM Sans.**

### Why no bold headings

Heavy serif (`font-bold` / 700) on Fraunces reads cramped and dated. Premium editorial pairs **light display + medium sans for body** — the contrast itself is the visual rhythm. Use `font-light` (300) on H1/H2; never go above `font-medium` (500) on body or labels.

---

## 4. Logo

### Files

| File | Use |
|---|---|
| `public/logo-text.svg` | **Website delivery** — nav, footer, OG images, email headers |
| `brand/logo-text.svg` | **Design reference copy** — same SVG kept in brand assets |
| `brand/logo-circle.svg` | **Stickers & packaging only** — full circle version |
| `Nails by Mona Logo.svg` (root) | Original source file from Humza — do not delete |

### Usage rules

- On `bone` / `paper` / `shell` bg: render `public/logo-text.svg` as-is (color is `#BFA4CE`)
- On dark / lavender filled bg: apply `filter: brightness(0) invert(1)` for white version
- **Never** recolor, stretch, rotate, or add drop-shadow
- **Minimum width:** 140px in nav, 100px in footer, 240px on splash
- Logo always links to `/`

```html
<a href="/" aria-label="Nails by Mona — Home">
  <img src="/logo-text.svg" alt="Nails by Mona" class="h-9 w-auto" width="160" height="56">
</a>
```

---

## 5. Custom Thematic Icons

The 14 custom SVGs in `public/icons/` are brand assets. Authored for this project — keep them.

### Standardization rules

- ViewBox: `0 0 24 24` always
- Single stroke weight: **1.5px** (`stroke-width="1.5"`)
- All paths use `currentColor` so Tailwind `text-*` colors them
- Inline SVG only — Blade partial pattern. Never `<img>` references.

### Brand-thematic icon list

| File | Name | Primary use |
|---|---|---|
| `nail.svg` | Press-on nail | Product cards, shop nav, tier badges |
| `sparkle.svg` | 4-point sparkle | Glam tier, hero accents, decoration |
| `bridal.svg` | Diamond ring | Bridal section nav, Bridal Trio card |
| `sizing.svg` | Ruler | Size guide page, sizing order step |
| `camera.svg` | Camera | Live sizing capture, photo button |
| `package.svg` | Gift box with bow | Order confirmation, premium packaging |
| `heart.svg` | Heart | Care, testimonials, save-to-list |
| `star.svg` | 5-point star | Customer ratings, featured badge |
| `flower.svg` | 5-petal flower | Decorative dividers, bridal page |
| `palette.svg` | Artist palette | Custom design request, color step |
| `ribbon.svg` | Bow / ribbon | Bridal Trio, gift card, premium callout |
| `coin.svg` | PKR coin | Sizing guide instructions |
| `instagram.svg` | Instagram | Footer, social CTA |
| `whatsapp.svg` | WhatsApp | Contact CTA, footer |
| `tiktok.svg` | TikTok | Footer only |

### Blade partial pattern

```blade
{{-- resources/views/components/icon/nail.blade.php --}}
<svg {{ $attributes->merge(['class' => 'w-5 h-5']) }}
     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
     fill="none" stroke="currentColor" stroke-width="1.5"
     stroke-linecap="round" stroke-linejoin="round"
     aria-hidden="true">
  <path d="M12 3 C14.6 3 16.5 5.6 16.5 9 L16.5 15.5
           C16.5 18.8 14.5 21 12 21
           C9.5 21 7.5 18.8 7.5 15.5
           L7.5 9 C7.5 5.6 9.4 3 12 3 Z"/>
  <path d="M10 8 C10 6.2 10.8 4.8 12 4.2"/>
</svg>

{{-- usage --}}
<x-icon.nail class="w-6 h-6 text-lavender" />
```

---

## 6. UI Utility Icons — Phosphor Light

For UI utilities (menu, close, bag, search, arrows, etc.) we use **Phosphor Icons (light weight)**. Reasons:

- Heroicons feel SaaS / admin-tool — used by every Tailwind-stack site, no personality
- Material Symbols look Android-y — wrong fit for a premium DTC brand
- Phosphor (light) has thinner strokes, slightly editorial character — pairs with Fraunces

Bundle approach: copy the ~25 SVG paths we need into Blade partials at `resources/views/components/icon/`. No icon-font dependency.

### Required utility icons

| UI need | Phosphor icon | Blade partial |
|---|---|---|
| Menu (hamburger) | `list` | `<x-icon.menu>` |
| Close | `x` | `<x-icon.close>` |
| Bag | `handbag` | `<x-icon.bag>` |
| Search | `magnifying-glass` | `<x-icon.search>` |
| Arrow right | `arrow-right` | `<x-icon.arrow-right>` |
| Arrow left | `arrow-left` | `<x-icon.arrow-left>` |
| Chevron down | `caret-down` | `<x-icon.chevron-down>` |
| Plus | `plus` | `<x-icon.plus>` |
| Minus | `minus` | `<x-icon.minus>` |
| Check | `check` | `<x-icon.check>` |
| Check circle | `check-circle` | `<x-icon.check-circle>` |
| Truck | `truck` | `<x-icon.truck>` |
| Clock | `clock` | `<x-icon.clock>` |
| Map pin | `map-pin` | `<x-icon.map-pin>` |
| Eye | `eye` | `<x-icon.eye>` |
| Trash | `trash` | `<x-icon.trash>` |
| Upload | `upload-simple` | `<x-icon.upload>` |
| Envelope | `envelope` | `<x-icon.envelope>` |
| Question mark | `question` | `<x-icon.question>` |
| Info | `info` | `<x-icon.info>` |
| Warning | `warning` | `<x-icon.warning>` |
| External link | `arrow-square-out` | `<x-icon.external>` |

**Banned:** `font-awesome`, `material-symbols-outlined`, `bootstrap-icons`, `heroicons` — none of these appear in any Blade view.

### Icon size tokens

| Context | Class |
|---|---|
| Inline with caption text | `w-4 h-4` |
| Inline with body text, nav | `w-5 h-5` |
| Standalone UI button | `w-6 h-6` |
| Feature / section accent | `w-8 h-8` |
| Hero decorative | `w-12 h-12` |
| Empty state large | `w-16 h-16` |

### Icon color tokens

| Context | Class |
|---|---|
| UI utility (menu, close, search) | `text-stone` |
| UI utility hover | `hover:text-ink` |
| Brand / accent | `text-lavender` |
| Nav link inactive | `text-stone` |
| Nav link active | `text-ink` |
| On filled lavender bg | `text-white` |
| Disabled | `text-ash` |
| Destructive (admin) | `text-danger` |

---

## 7. Buttons & CTAs

### Primary (filled — every primary CTA)

```html
<a class="inline-flex items-center justify-center gap-2 bg-lavender hover:bg-lavenderDark
          text-white font-sans text-caption font-medium tracking-wide rounded-full
          px-6 py-3 transition-colors duration-200
          focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-lavender focus-visible:ring-offset-2 focus-visible:ring-offset-bone">
  Add to bag
</a>
```

### Secondary (outlined)

```html
<a class="inline-flex items-center justify-center gap-2 border border-ink text-ink
          hover:bg-ink hover:text-bone font-sans text-caption font-medium tracking-wide
          rounded-full px-6 py-3 transition-colors duration-200
          focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-lavender focus-visible:ring-offset-2">
  View bridal collection
</a>
```

### Ghost / text link

```html
<a class="inline-flex items-center gap-1 font-sans text-caption text-graphite hover:text-lavenderInk
          underline-offset-4 hover:underline transition-colors duration-200">
  Learn more <x-icon.arrow-right class="w-4 h-4"/>
</a>
```

### Large CTA (hero / section)

```html
<a class="inline-flex items-center justify-center gap-2 bg-lavender hover:bg-lavenderDark
          text-white font-sans text-base font-medium tracking-wide rounded-full
          px-9 py-4 transition-colors duration-200">
  Browse the collection
</a>
```

### Danger (admin only)

```html
<button class="inline-flex items-center gap-2 bg-danger hover:bg-[#8B2F25] text-white
               font-sans text-caption font-medium rounded-full px-5 py-2.5 transition-colors duration-200">
  Cancel order
</button>
```

### Disabled state (any variant)

Add `opacity-40 cursor-not-allowed pointer-events-none`. Never recolor — opacity only.

### Banned button patterns

- ❌ "Order Now" pill in nav. The bag icon is the only commerce CTA in the header.
- ❌ Lavender as outline-only secondary. Secondary uses `border-ink`.
- ❌ Drop shadows on buttons. Buttons are flat — only the bag drawer has a soft shadow.
- ❌ `text-shadow` anywhere.

---

## 8. Form Inputs

```html
<input type="text"
  class="w-full font-sans text-body text-ink bg-paper border border-hairline rounded-xl
         px-4 py-3 placeholder:text-ash
         focus:outline-none focus:ring-2 focus:ring-lavender focus:border-lavender focus:bg-lavenderWash/30
         transition-colors duration-150"/>

<textarea rows="4"
  class="w-full font-sans text-body text-ink bg-paper border border-hairline rounded-xl
         px-4 py-3 placeholder:text-ash resize-none
         focus:outline-none focus:ring-2 focus:ring-lavender focus:border-lavender
         transition-colors duration-150"></textarea>

<select
  class="w-full font-sans text-body text-ink bg-paper border border-hairline rounded-xl
         px-4 py-3 appearance-none
         focus:outline-none focus:ring-2 focus:ring-lavender focus:border-lavender
         transition-colors duration-150"></select>
```

**Error state:** add `border-danger focus:ring-danger focus:border-danger`
**Error message:** `<p class="mt-1 font-sans text-eyebrow text-danger">...</p>`

---

## 9. Cards

```html
{{-- Standard product / content card --}}
<article class="bg-paper rounded-2xl overflow-hidden transition-all duration-300 ease-out group">
  <div class="aspect-[4/5] overflow-hidden bg-shell">
    <img class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700 ease-out" ...>
  </div>
  <div class="p-5">
    <p class="font-sans text-eyebrow text-stone uppercase mb-1">Glam</p>
    <h3 class="font-serif text-display text-ink mb-1">Blush Mirage</h3>
    <p class="font-sans text-h3 text-lavender tabular-nums">Rs. 3,800</p>
  </div>
</article>

{{-- Outlined card (no shadow) --}}
<div class="bg-paper rounded-2xl border border-hairline overflow-hidden">...</div>

{{-- Highlighted card --}}
<div class="bg-paper rounded-2xl ring-1 ring-lavender ring-offset-2 ring-offset-bone overflow-hidden">...</div>

{{-- Editorial dark card (bento layouts) --}}
<div class="bg-ink rounded-2xl overflow-hidden text-bone p-6 md:p-8">...</div>
```

**Cards do not have hover shadows** — the image scale-up is the hover signal. Shadows on hover read as Bootstrap-era.

---

## 10. Badges & Status Tags

```html
{{-- Made to Order --}}
<span class="bg-lavenderWash text-lavenderInk font-sans text-eyebrow uppercase rounded-full px-3 py-1">
  Made to Order
</span>

{{-- In Stock --}}
<span class="bg-success/10 text-success font-sans text-eyebrow uppercase rounded-full px-3 py-1">
  In Stock
</span>

{{-- Sold Out --}}
<span class="bg-danger/10 text-danger font-sans text-eyebrow uppercase rounded-full px-3 py-1">
  Sold Out
</span>

{{-- Tier badge --}}
<span class="bg-ink text-bone font-sans text-eyebrow uppercase rounded-full px-3 py-1">
  Bridal Trio
</span>
```

---

## 11. Order Step Indicator

```html
<ol class="flex items-center gap-3">
  {{-- Completed --}}
  <li class="flex items-center gap-2">
    <span class="w-8 h-8 rounded-full bg-lavender flex items-center justify-center">
      <x-icon.check class="w-4 h-4 text-white"/>
    </span>
    <span class="font-sans text-eyebrow text-stone hidden sm:inline">Sizing</span>
  </li>
  <li class="h-px w-8 bg-hairline" aria-hidden="true"></li>
  {{-- Active --}}
  <li class="flex items-center gap-2">
    <span class="w-8 h-8 rounded-full bg-lavender flex items-center justify-center ring-4 ring-lavenderWash">
      <span class="font-sans text-caption font-medium text-white">2</span>
    </span>
    <span class="font-sans text-eyebrow font-semibold text-lavenderInk hidden sm:inline">Details</span>
  </li>
  <li class="h-px w-8 bg-hairline" aria-hidden="true"></li>
  {{-- Upcoming --}}
  <li class="flex items-center gap-2">
    <span class="w-8 h-8 rounded-full border border-hairline flex items-center justify-center">
      <span class="font-sans text-caption text-stone">3</span>
    </span>
    <span class="font-sans text-eyebrow text-stone hidden sm:inline">Payment</span>
  </li>
</ol>
```

---

## 12. Navigation — Hybrid Pattern

Desktop: visible nav links + bag icon. Mobile/tablet: hamburger → full-screen overlay.

### Desktop header (≥1024px)

```html
<header class="sticky top-0 z-50 bg-bone/85 backdrop-blur-md border-b border-hairline/50">
  <div class="max-w-7xl mx-auto px-6 lg:px-10 h-20 flex items-center justify-between">
    {{-- Logo --}}
    <a href="/" class="shrink-0">
      <img src="/logo-text.svg" alt="Nails by Mona" class="h-9 w-auto" width="160" height="56">
    </a>

    {{-- Center nav --}}
    <nav class="hidden lg:flex items-center gap-10" aria-label="Primary">
      <a href="/shop"    class="font-sans text-caption text-graphite hover:text-ink transition-colors {{ request()->routeIs('shop.*')   ? 'text-ink border-b-2 border-lavender pb-0.5' : '' }}">Shop</a>
      <a href="/bridal"  class="font-sans text-caption text-graphite hover:text-ink transition-colors {{ request()->routeIs('bridal.*') ? 'text-ink border-b-2 border-lavender pb-0.5' : '' }}">Bridal</a>
      <a href="/about"   class="font-sans text-caption text-graphite hover:text-ink transition-colors {{ request()->routeIs('about')    ? 'text-ink border-b-2 border-lavender pb-0.5' : '' }}">About</a>
      <a href="/blog"    class="font-sans text-caption text-graphite hover:text-ink transition-colors {{ request()->routeIs('blog.*')   ? 'text-ink border-b-2 border-lavender pb-0.5' : '' }}">Journal</a>
      <a href="/contact" class="font-sans text-caption text-graphite hover:text-ink transition-colors {{ request()->routeIs('contact') ? 'text-ink border-b-2 border-lavender pb-0.5' : '' }}">Help</a>
    </nav>

    {{-- Right utilities --}}
    <div class="flex items-center gap-5">
      <a href="/order/track" aria-label="Track order"
         class="hidden md:inline-flex text-stone hover:text-ink transition-colors">
        <x-icon.search class="w-5 h-5"/>
      </a>
      <button id="bag-toggle" aria-label="Open bag"
              class="relative text-stone hover:text-ink transition-colors">
        <x-icon.bag class="w-5 h-5"/>
        <span id="bag-count"
              class="absolute -top-1.5 -right-2 hidden min-w-[18px] h-[18px] px-1
                     rounded-full bg-lavender text-white text-[10px] font-medium
                     items-center justify-center">0</span>
      </button>
      <button id="mobile-menu-toggle" aria-label="Open menu" class="lg:hidden text-stone hover:text-ink transition-colors">
        <x-icon.menu class="w-6 h-6"/>
      </button>
    </div>
  </div>
</header>
```

### Mobile / tablet overlay (<1024px)

```html
<div id="mobile-menu" aria-hidden="true"
     class="fixed inset-0 z-[60] hidden bg-bone overflow-y-auto">
  <div class="h-20 flex items-center justify-between px-6 border-b border-hairline">
    <img src="/logo-text.svg" alt="Nails by Mona" class="h-8 w-auto">
    <button aria-label="Close menu" id="mobile-menu-close" class="text-stone hover:text-ink">
      <x-icon.close class="w-6 h-6"/>
    </button>
  </div>

  <nav class="px-8 py-12 flex flex-col gap-7" aria-label="Primary">
    <a href="/shop"    class="font-serif text-display text-ink">Shop</a>
    <a href="/bridal"  class="font-serif text-display text-ink">Bridal</a>
    <a href="/about"   class="font-serif text-display text-ink">About</a>
    <a href="/blog"    class="font-serif text-display text-ink">Journal</a>
  </nav>

  <div class="px-8 mt-2 pt-8 border-t border-hairline flex flex-col gap-3">
    <a href="/contact"     class="font-sans text-eyebrow uppercase text-stone hover:text-ink">Help</a>
    <a href="/size-guide"  class="font-sans text-eyebrow uppercase text-stone hover:text-ink">Size Guide</a>
    <a href="/order/track" class="font-sans text-eyebrow uppercase text-stone hover:text-ink">Track Order</a>
    <a href="/blog/care"   class="font-sans text-eyebrow uppercase text-stone hover:text-ink">Care &amp; Reuse</a>
  </div>

  <div class="px-8 mt-12 mb-12 flex items-center gap-5">
    <a href="https://instagram.com/nailsbymona" aria-label="Instagram" class="text-stone hover:text-ink">
      <x-icon.instagram class="w-5 h-5"/>
    </a>
    <a href="https://wa.me/+92XXXXXXXXX?text=Hello%20Nails%20by%20Mona" aria-label="WhatsApp" class="text-stone hover:text-ink">
      <x-icon.whatsapp class="w-5 h-5"/>
    </a>
  </div>
</div>
```

### Behavior (jQuery in `resources/js/app.js`)

```js
$(function () {
  const $menu  = $('#mobile-menu');
  const $body  = $('body');
  const open  = () => { $menu.removeClass('hidden').attr('aria-hidden', 'false'); $body.addClass('overflow-hidden'); };
  const close = () => { $menu.addClass('hidden').attr('aria-hidden', 'true');  $body.removeClass('overflow-hidden'); };
  $('#mobile-menu-toggle').on('click', open);
  $('#mobile-menu-close').on('click', close);
  $(document).on('keydown', e => { if (e.key === 'Escape') close(); });
});
```

### Banned nav patterns

- ❌ "Order Now" button in nav (or anywhere in the header)
- ❌ "Ask Mona" / "DM Mona" links
- ❌ `/gallery` link
- ❌ Phone number in nav (it's in the help page only)
- ❌ Mobile fixed-bottom WhatsApp bar (the hamburger overlay handles it)

---

## 13. Bag Drawer

```html
<aside id="bag-drawer" aria-label="Shopping bag" aria-hidden="true"
       class="fixed inset-y-0 right-0 z-[55] w-full max-w-md bg-paper translate-x-full
              transition-transform duration-300 ease-out shadow-[-12px_0_24px_rgba(0,0,0,0.05)]">
  <div class="h-20 flex items-center justify-between px-6 border-b border-hairline">
    <h2 class="font-serif text-display text-ink">Your bag</h2>
    <button aria-label="Close bag" id="bag-close" class="text-stone hover:text-ink">
      <x-icon.close class="w-6 h-6"/>
    </button>
  </div>

  {{-- Empty state --}}
  <div id="bag-empty" class="px-6 py-16 text-center">
    <p class="font-sans text-body text-stone">Your bag is empty.</p>
    <a href="/shop" class="mt-6 inline-flex bg-lavender hover:bg-lavenderDark text-white
                            font-sans text-caption font-medium tracking-wide rounded-full px-6 py-2.5
                            transition-colors">Browse designs</a>
  </div>

  {{-- Items list --}}
  <ul id="bag-items" class="hidden divide-y divide-hairline overflow-y-auto"></ul>

  {{-- Footer --}}
  <div id="bag-footer" class="hidden absolute bottom-0 inset-x-0 p-6 bg-paper border-t border-hairline">
    <div class="flex justify-between font-sans text-body text-graphite mb-4">
      <span>Subtotal</span>
      <span class="font-medium text-ink tabular-nums" id="bag-subtotal">Rs. 0</span>
    </div>
    <p class="font-sans text-eyebrow text-stone mb-4 normal-case tracking-normal">
      Shipping calculated at checkout. Custom-fit orders ship in 5–9 working days.
    </p>
    <a href="/order/start" class="block w-full text-center bg-lavender hover:bg-lavenderDark
                                   text-white font-sans text-caption font-medium tracking-wide
                                   rounded-full py-3.5 transition-colors">Checkout</a>
  </div>
</aside>
<div id="bag-backdrop" class="fixed inset-0 z-[54] hidden bg-ink/30"></div>
```

### Storage

Bag persists in `localStorage` under key `nbm.bag` as JSON: `[{slug, name, price_pkr, qty, image}]`. jQuery in `resources/js/bag.js` handles add/remove/update + DOM render. On checkout, jQuery POSTs the array to `/order/start` which creates an `Order` + `order_items`.

---

## 14. Section Layout

```html
<section class="py-20 md:py-28 bg-bone">  {{-- alternate bone → paper → shell --}}
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Collection</p>
    <h2 class="font-serif text-display-lg text-ink max-w-3xl">Made by hand, made to fit.</h2>
    <div class="mt-5 mb-12 h-0.5 w-10 bg-lavender"></div>
    {{-- content --}}
  </div>
</section>
```

### Grid patterns

```html
{{-- Product grid --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8"></div>

{{-- Bento (homepage feature) --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[220px] md:auto-rows-[260px]">
  <div class="col-span-2 row-span-2 ..."></div>
  <div class="col-span-1 row-span-1 ..."></div>
</div>

{{-- Two-column editorial --}}
<div class="grid md:grid-cols-2 gap-12 md:gap-20 items-center">
  <div>{{-- text --}}</div>
  <div class="aspect-[4/5] overflow-hidden bg-shell">{{-- hand image --}}</div>
</div>
```

---

## 15. Layout Tokens

| Property | Value |
|---|---|
| Page max-width | `max-w-7xl mx-auto px-6 lg:px-10` |
| Section vertical padding | `py-20 md:py-28` |
| Card radius | `rounded-2xl` |
| Image inside card | `rounded-none` (editorial; image fills the card corners) |
| Button radius | `rounded-full` |
| Input radius | `rounded-xl` |
| Section divider | `border-t border-hairline` |
| Accent rule | `h-0.5 w-10 bg-lavender` |
| Image hover scale | `group-hover:scale-[1.03] transition-transform duration-700 ease-out` |
| Default transition | `transition-colors duration-200` |
| Long transition | `transition-all duration-300 ease-out` |

---

## 16. Photography Rules — Hand-only, No Faces

### Locked rules (non-negotiable)

1. **Never** show Mona's face anywhere on the website. **No founder portrait. Not on the About page. Not anywhere.**
2. **Never** show a customer's face. Crop at the wrist, forearm, or below shoulders.
3. UGC submissions with a face → admin sets `face_visible = true` → cannot be selected for site display.
4. Process photos: hands working, tools, paint pots, packaging — face out of frame.
5. Bridal photography: hands on traditional fabrics (red velvet, gold embroidery, henna patterns) — always face-cropped.
6. About page: replace any founder portrait with a hands-and-tools studio shot + handwritten "Mona" signature SVG.
7. Image aspect ratio: products `aspect-[4/5]` (portrait); editorial / hero `aspect-[3/4]` or full-bleed `aspect-[16/9]`.
8. Always include `width` + `height` attributes (CLS prevention).
9. Always provide `alt` — describes the design, the placement on the hand, and any context. Never "photo of Mona".

### What to capture (DIY shoot brief)

- 12× product shots: nail set on white card, on shell paper, in packaging, in hand cropped at second knuckle
- 8× "in wear": hand against denim, silk, henna patterns
- 6× process: tools laid flat, paint pots, brushes mid-motion
- 4× bridal: hand on red velvet with gold thread, with mehndi
- 3× lifestyle: hand holding tea cup / flowers / phone — closeup, anonymous
- 2× signature/branding: handwritten "Mona" SVG over packaging detail

---

## 17. Modern Design Principles (2026)

These apply to every page and component.

### 1. Whitespace IS the layout
- Every section: `py-20 md:py-28`. Never stack sections without breathing room.
- Card padding `p-5` or `p-6`. Never `p-3`.

### 2. Floating glass nav
- Sticky header `bg-bone/85 backdrop-blur-md`. The blur signals modern quality.

### 3. Editorial typography
- Hero H1 is big and light: `font-serif text-display-xl font-light`. Not bold. Not cramped.
- Eyebrow microcopy `text-eyebrow text-lavender uppercase` above H2.

### 4. Bento product showcases
- Homepage uses asymmetric bento (one hero `col-span-2 row-span-2`).
- Feels editorial, not catalog.

### 5. Restrained micro-interactions
- All hovers `duration-200`–`duration-300`.
- Image scale on hover: `scale-[1.03]` over 700ms — slow, expensive-feeling.
- Buttons: color shift only, no shadow lift.

### 6. Authentic imagery only
- Real Pakistani hands. No stock. No AI-generated lifestyle.
- Background-removal AI is fine; AI-generated photos are banned.
- **No faces.** Ever.

### 7. Monochromatic accent
- One accent: `lavender`. Eight permitted uses (see §2). No exceptions.

### 8. Full-bleed hero
- Homepage hero `min-h-[90vh]` with full-bleed image. Overlay `bg-ink/30` for legibility — never pure black.

### 9. Minimal decoration
- Borders only when structurally necessary.
- No gradients except very subtle `from-bone to-shell` section washes.
- Decorative elements: `flower.svg` icon + accent rule only.

### 10. Section bg alternation
- `bone` → `paper` → `shell`. Never same bg twice in a row.

---

## 18. Accent Usage Rules — Non-negotiable

1. Every primary CTA → `bg-lavender`.
2. Active nav link → `text-ink border-b-2 border-lavender pb-0.5`.
3. Input focus → `focus:ring-lavender focus:border-lavender`.
4. Price → `text-lavender font-medium tabular-nums`.
5. Section accent rule below H2 → `h-0.5 w-10 bg-lavender`.
6. Step indicator active/completed → `bg-lavender text-white`.
7. Selected payment method → `ring-2 ring-lavender bg-lavenderWash`.
8. Eyebrow above H2 → `text-lavender uppercase tracking-widest text-eyebrow`.
9. Errors / warnings use `danger` / `warning`. Never lavender for errors.
10. Never invent new colors. Don't add `lavenderFaint`, `lavenderLight`, `cream`, `cloud`. Only the tokens in §2 exist.

---

## 19. Do Nots

- ❌ React, Vue, Livewire on public pages.
- ❌ Blue, teal, orange, yellow, pink (other than the lavender accent) anywhere.
- ❌ `cream`, `cloud`, `lavenderFaint`, `lavenderLight`, `lavenderDeep`, `body`, `mute`, `subtle` — these tokens are removed.
- ❌ Cormorant Garamond, Inter, Material Symbols, Heroicons.
- ❌ "Order Now" button in nav.
- ❌ Mona's face. Or any human face. Anywhere.
- ❌ "Ask Mona" / "DM Mona" / "Hi Mona!" copy.
- ❌ `/gallery` page or "Gallery" nav link.
- ❌ Hard shadows (`shadow-lg`+) on public pages. Only `bag-drawer` has a shadow.
- ❌ Gradient stronger than `from-bone to-transparent`.
- ❌ Body font weight above `font-medium` (500).
- ❌ Card radius below `rounded-2xl`.
- ❌ Images without `width` + `height`.
- ❌ `cursor-pointer` on non-interactive elements.
- ❌ Bootstrap-era patterns: full-width colored button bars, bordered table-heavy public pages, modal popups beyond the bag drawer + cookie banner.
- ❌ Mobile fixed-bottom WhatsApp bar (the hamburger overlay handles it).

---

## 20. Customer Service Copy

WhatsApp deep-links exist for genuine customer service, never as a primary commerce CTA. All pre-fills are brand-addressed, never personal.

| Page | WhatsApp pre-fill |
|---|---|
| Home | `Hello Nails by Mona, I'd like to enquire about a custom set.` |
| Shop | `Hello Nails by Mona, I'm browsing your shop and have a question.` |
| Product detail | `Hello Nails by Mona, I'm interested in [Product Name].` |
| Bridal | `Hello Nails by Mona, I'm interested in the Bridal Trio for my wedding.` |
| Size guide | `Hello Nails by Mona, I need help with my sizing photo.` |
| Help | `Hello Nails by Mona, I have a question.` |
| Order confirmation | `Hello Nails by Mona, here's my payment proof for order NBM-XXXX.` |
| Order tracking | `Hello Nails by Mona, I have a question about order NBM-XXXX.` |

CTA labels: **"Get help"**, **"Customer care"**, **"Contact us"**. Never "Ask Mona", "DM Mona", "Talk to Mona".

---

## 21. Social Icons

| Platform | Icon | Use |
|---|---|---|
| Instagram | `public/icons/instagram.svg` | Footer, mobile menu, contact page |
| WhatsApp | `public/icons/whatsapp.svg` | Contact CTA, order confirm, mobile menu, footer |
| TikTok | `public/icons/tiktok.svg` | Footer only |
| Pinterest | `public/icons/pinterest.svg` (add if needed) | Footer only |

Footer social: `w-5 h-5 text-stone hover:text-ink transition-colors`.
