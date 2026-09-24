# Home page redesign — research, diagnosis, plan

*2026-09-24 · prototype: `html/home-v2.html`*

## 1. The problem in one line

Instagram ad visitors land on the home page, see no nails and no prices, and leave within ~6 seconds.

## 2. Evidence

| Source | Signal |
|---|---|
| GA4, last 28 days | 60% of sessions are Paid Social · home = 61% of views · home engagement **7 s** vs /shop **32 s**, /size-guide 36 s · 0 key events (no e-commerce tracking until today) |
| GA4 browsers | ~80% Instagram/Facebook in-app browsers (reported as "Safari" + "Android Webview") |
| Clarity, last 3 days (252 sessions) | Home: **6 s active**, 30% scroll, 22 quick-backs · Instagram-ad channel: **12% scroll**, 7 s active · 93% mobile |
| Page audit (390×700, IG-browser-sized) | Page is **18.5 screens** long · first product photo at **4.0 screens** (2,817 px) · first price at 4.7 screens · average IG visitor scrolls ~2 screens → **sees zero designs and zero prices** |
| Hero | Frosted text card covers the nail photo — the product is hidden on the only screen most people see |
| Collection section | 1 product per screen on mobile (6 products = 3,134 px) |

## 3. Who is arriving, and what they need in 5 seconds

The ad visitor is not the "Sana researching press-ons" persona from `docs/ux/`. She tapped a Reel of a design she liked, inside Instagram, on mobile data, thumb already on the back gesture.

In the first screen she needs answers to, in this order:

1. **"Is this the thing from the ad?"** → nail designs, immediately (message match)
2. **"How much?"** → a price, visibly
3. **"Will they fit me / look fake?"** → the custom-fit promise in one line
4. **"Can I trust this shop?"** → real hands, real cities, how payment works (no COD is a real hurdle in Pakistan)

The current page answers them in the order 3 → 4 → 1 → 2, and 1 and 2 only after four screens.

## 4. Principles for the redesign

1. **Product in the first screen.** Hero shrinks; the product grid starts above the fold on a phone.
2. **Price everywhere a design appears.** Plus one anchor line: "Sets from Rs. 2,000".
3. **Browse like Instagram.** 2-column grid, category chips, horizontal swipe rows — gestures they already use.
4. **Answer the objections in the order they arise**, each in one short block: fit → how it works → price vs salon → trust/payment → FAQ.
5. **Half the length.** Target ≈ 8 screens (from 18.5). Every section earns its place or goes.
6. **Always one tap from shopping.** Sticky bottom bar on mobile once the hero is scrolled past.
7. **Only real proof.** Real photos, real cities from real customers. No invented ratings, counts or testimonials. (6 orders so far — "bestseller" labels would be dishonest; use "Mona's picks" = `is_featured`.)
8. **Brand stays atelier.** Same palette, Fraunces + DM Sans, lavender only as an accent. Faster to scan does not mean cheaper.

## 5. New structure (mobile-first)

| # | Section | Job | Replaces |
|---|---|---|---|
| 0 | Slim announcement bar — delivery + free-shipping threshold | Removes the "delivery cost?" question | — |
| 1 | **Compact hero** — photo *not* covered, headline, "from Rs. 2,000", Shop CTA; product grid peeks below | Message match + price in first screen | Hero (80vh + frosted card) |
| 2 | **Category chips** (All · Everyday · Glam · Bridal · Under Rs. 3,000) | Instagram-style browsing | Trust bar |
| 3 | **Product grid**, 2-col, 8 designs with price + quick add | The page's main job | Collection (1 per screen) |
| 4 | **Fit in 3 steps** — pick · 2 photos + coin, 90 s · delivered in ~5 days, free first refit | Kills "won't fit" objection | Fit difference (1,222 px phone mockup) + How it works |
| 5 | **Why press-ons** — vs salon acrylics: cost, no damage, wudu-friendly | Newcomer education | Pricing table |
| 6 | **Bridal Trio banner** — Rs. 10,000 for three nights | Flagship, compact | Bridal section |
| 7 | **Real customers** — swipe row of UGC with city captions | Social proof | UGC grid |
| 8 | **Trust + payment** — JazzCash · EasyPaisa · Bank, tracked delivery, made in Mirpur, WhatsApp help | Trust without COD | Studio teaser |
| 9 | **Mini FAQ** (4) | Last objections | — |
| 10 | Journal links (slim) + footer | SEO internal links | Journal teaser |

Removed from the home page (still on their own pages): the phone-mockup sizing explainer (→ /size-guide), the studio story (→ /about), the tier pricing table (prices now on every card).

## 6. What changes outside the page (for the build)

- `/shop?tier=everyday` etc. so chips can deep-link (shop filter is client-side only today).
- Ads should point at the matching product page, not `/` (Meta Ads Manager — Humza).
- UTM tags on ad links to attribute orders per ad.

## 7. How we'll know it worked (2 weeks after launch, vs. the numbers above)

| Metric | Now | Target |
|---|---|---|
| Home active time (Clarity) | 6 s | ≥ 15 s |
| Home scroll depth, IG channel | 12% | ≥ 35% |
| Home → product/shop click-through | unknown (quick-backs 22) | ≥ 30% of home sessions |
| `add_to_cart` / home sessions (GA4) | not tracked before today | baseline in week 1, then +50% |

Traffic is too small for a proper A/B test; compare two weeks before vs two weeks after, same ad spend.
