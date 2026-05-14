---
name: Artisanal Minimalist Editorial
colors:
  surface: '#fef9f2'
  surface-dim: '#ded9d3'
  surface-bright: '#fef9f2'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f8f3ec'
  surface-container: '#f2ede6'
  surface-container-high: '#ece7e1'
  surface-container-highest: '#e7e2db'
  on-surface: '#1d1b17'
  on-surface-variant: '#4b454d'
  inverse-surface: '#32302c'
  inverse-on-surface: '#f5f0e9'
  outline: '#7c757d'
  outline-variant: '#cdc4cd'
  surface-tint: '#6d567b'
  primary: '#6d567b'
  on-primary: '#ffffff'
  primary-container: '#bfa4ce'
  on-primary-container: '#4e395c'
  inverse-primary: '#d9bce8'
  secondary: '#635d5a'
  on-secondary: '#ffffff'
  secondary-container: '#e7deda'
  on-secondary-container: '#68615e'
  tertiary: '#625e55'
  on-tertiary: '#ffffff'
  tertiary-container: '#b3ada3'
  on-tertiary-container: '#454139'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#f3daff'
  primary-fixed-dim: '#d9bce8'
  on-primary-fixed: '#261334'
  on-primary-fixed-variant: '#543e62'
  secondary-fixed: '#eae1dd'
  secondary-fixed-dim: '#cec5c1'
  on-secondary-fixed: '#1f1b19'
  on-secondary-fixed-variant: '#4b4643'
  tertiary-fixed: '#e9e2d6'
  tertiary-fixed-dim: '#ccc6bb'
  on-tertiary-fixed: '#1e1b15'
  on-tertiary-fixed-variant: '#4a463e'
  background: '#fef9f2'
  on-background: '#1d1b17'
  surface-variant: '#e7e2db'
typography:
  display-lg:
    fontFamily: Noto Serif
    fontSize: 48px
    fontWeight: '300'
    lineHeight: '1.1'
    letterSpacing: -0.02em
  display-md:
    fontFamily: Noto Serif
    fontSize: 32px
    fontWeight: '300'
    lineHeight: '1.2'
  body-main:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  label-uppercase:
    fontFamily: Plus Jakarta Sans
    fontSize: 12px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.1em
  price-accent:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '500'
    lineHeight: '1'
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  unit: 4px
  container-max: 1280px
  gutter: 24px
  margin-mobile: 20px
  margin-desktop: 64px
  section-padding: 120px
---

## Brand & Style

The design system is anchored in the concept of "Quiet Luxury"—an aesthetic that prioritizes intentionality, tactility, and artisanal precision. It is designed for a discerning audience that values self-care as an art form. The UI avoids the loud, synthetic tropes of the beauty industry in favor of an editorial atmosphere reminiscent of high-end lifestyle photography and independent boutiques.

The visual style is a sophisticated blend of **Minimalism** and **Glassmorphism**. It utilizes generous whitespace (negative space as a design element), refined typography pairings, and subtle translucent layers to create a sense of depth without clutter. The focus remains entirely on the product artistry through high-quality photography, while the interface serves as a serene, high-contrast gallery.

## Colors

The palette is rooted in organic, earth-derived neutrals that provide a warm, tactile foundation. 
- **Foundational Neutrals:** Use 'Bone' as the primary canvas, 'Paper' for elevated surfaces or cards, and 'Shell' for structural sections like footers to provide subtle grounding.
- **Typography:** 'Ink' is reserved for high-level headings to maintain strong hierarchy. 'Stone' is used for all long-form body copy to reduce visual strain and maintain the soft editorial feel.
- **Accents:** 'Lavender' is the sole carrier of action. It must be used sparingly to signify interactivity (CTAs, prices, active states). This restraint ensures that when color appears, it is meaningful and elegant.

## Typography

This design system employs a classic serif/sans-serif contrast. 
- **Display Type:** Headlines use a light-weight serif to evoke a literary, sophisticated tone. Use ample line height and slight negative letter spacing for large titles to create a "tight" editorial look.
- **Body & Utility:** A clean, modern sans-serif ensures maximum legibility for product descriptions and navigation. 
- **Hierarchy Tip:** Use uppercase sans-serif labels for micro-copy (e.g., categories, eyebrow titles) to create a rhythmic break from the serif headlines.

## Layout & Spacing

The layout philosophy is based on a **Fixed Grid** with an emphasis on "Vertical Breathing Room."
- **Grid:** A 12-column grid system is used for desktop, but elements frequently span 4 or 6 columns to allow for large-scale imagery.
- **Whitespace:** Section vertical padding should be aggressive (minimum 120px on desktop) to separate different "stories" or product collections.
- **Imagery:** Photography should be treated as a structural element. Use asymmetrical placements where images bleed off the edge or overlap grid lines slightly to reinforce the magazine-style layout.

## Elevation & Depth

Depth is achieved through **Low-contrast outlines** and **Glassmorphism** rather than traditional drop shadows.
- **Header:** Employs a 'Bone' background at 85% opacity with a medium backdrop blur. This creates a "frosted" effect that allows the content to pass elegantly beneath it.
- **Dividers:** Use 'Hairline' (#E0D9CE) for all structural borders. These should be 1px thick, creating a crisp, architectural frame for the content.
- **Layers:** Use the 'Paper' color for card backgrounds to sit one step above the 'Bone' page background, providing a subtle tonal lift.

## Shapes

The shape language balances modern geometry with organic softness.
- **Cards:** Defined by a 1rem (2xl) radius, creating a soft container for product photography.
- **Interactive Elements:** Buttons are strictly pill-shaped (full radius), providing a clear contrast to the rectangular grid and drawing the eye toward actions.
- **Form Inputs:** Use a 0.75rem (xl) radius to sit comfortably between the softness of the buttons and the structure of the cards.

## Components

### Buttons
- **Primary:** Pill-shaped, Lavender (#BFA4CE) background with White or LavenderInk text. Hover state transitions smoothly to LavenderDark (#9B7FB4) with a 1.03x scale.
- **Secondary:** Pill-shaped, 'Hairline' border with 'Ink' text. 

### Cards
- Use 'Paper' background. Photography should have no radius (flush to top) or inherit the 1rem radius if contained. Ensure a subtle scale-up of the image (not the card) on hover.

### Input Fields
- 'Paper' background with a 'Hairline' border. Placeholder text uses 'Ash'. On focus, the border shifts to 'Lavender' with no glow—only a clean color change.

### Navigation Header
- Sticky position. Blurry 'Bone' background. Text links in 'Graphite' with an 'Ink' active state.

### Footer
- 4-column layout on a 'Shell' background. Use 'Stone' for secondary links and 'Ink' for column headers (using the uppercase label style).

### Product Thumbnails
- High-quality hand-only photography. Prices should always be rendered in 'Lavender' to highlight the value proposition immediately.