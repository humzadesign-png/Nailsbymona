# Nails by Mona — Project Bible

> Press-on gel nails e-commerce website + brand strategy for Mona's business, currently run via Instagram DMs. Surprise gift from Humza (in Germany) for his wife (in Mirpur, Azad Kashmir). Mona does not know we're building this.
>
> **Status:** Phases 0–4 complete and live at nailsbymona.pk; Phase 5 polish largely done. Real orders now arrive through both the shop and custom design links. Most recent work: custom (DM) order links, full-payment-up-front checkout, and camera-upload fixes for iPhone Chrome — see the 2026-09-16 → 18 entry in §32.
>
> This file is the source of truth for future Claude sessions. If anything here conflicts with memory or training data, trust this file.

---

## Table of Contents

**Build (technical):**
1. Project Overview
2. Fixed Decisions
3. Prerequisites
4. Tech Stack
5. Information Architecture
6. Data Model
7. The Order Flow
8. Sizing Capture UX (live camera)
9. Size Guide
10. Design System
11. Build Phases
12. Folder Structure
13. Run Locally
14. Conventions
15. Security Notes

**Strategy (business):**
16. Market Positioning
17. Competitor Landscape (2026)
18. Customer Pain Points & Messaging
19. Pricing Ladder
20. Marketing Playbook
21. SEO Playbook
22. Blog Strategy
23. Monetization Strategy
24. Photography Strategy
25. Customer Service & Chatbot Plan
26. Logistics & Payment Risk
27. Bridal Market Specifics
28. Diaspora Opportunity (Phase 3)
29. Brand Name & Expansion
30. 12-Month Growth Plan

**Meta:**
31. Open Questions
32. Session History
33. Pointers for Future Claude Sessions
34. Content Architecture (page-by-page reference)

---

# PART 1 — BUILD

## 1. Project Overview

### What we're building

1. **Public storefront** — customers browse press-on gel nail designs, place orders with custom nail measurements (live-camera photo-with-coin), and pay via Pakistan-local methods.
2. **Admin / CRM panel** — Mona manages products, gallery, orders, blog, customer messages, and settings from one polished dashboard.
3. **SEO content engine** — blog targeting Pakistani long-tail beauty queries to grow organic traffic alongside paid + Instagram.

### Who it's for

- **Customers:** Pakistani women, primarily 22–40, working professionals + brides, urban tier-1/2 cities; later Pakistani diaspora (UK/UAE/Germany/Canada/US). Prices in PKR. Payment: bank transfer, JazzCash, EasyPaisa; Phase 5 SafePay.
- **Admin:** Mona, non-technical. Admin must be polished and obvious day 1.

### Out of scope (MVP)

- Subscriptions / recurring orders.
- Customer accounts (returning-customer lookup is by phone+email, not full auth).
- International shipping (Phase 6 / Year 2).
- Native mobile app.
- AI chatbot at launch (deferred to Phase 7+ — see section 25).
- Display ads / AdSense — explicitly never on the storefront (see section 23).

---

## 2. Fixed Decisions

These were locked in by Humza. Do not revisit without asking.

| Decision               | Choice                                                                | Reason                                              |
| ---------------------- | --------------------------------------------------------------------- | --------------------------------------------------- |
| Backend framework      | **Laravel 11.x** (PHP)                                                | User preference                                     |
| Frontend               | **Blade + Tailwind CSS + jQuery** — no React/Vue/Svelte               | User explicitly ruled out JS frameworks             |
| Admin / CRM            | **Filament v3**                                                       | Polished out of the box                             |
| Product scope (MVP)    | **Press-on gel nails only**                                           | Mona's actual business                              |
| Visual direction       | **Elegant & minimal** — lavender `#bfa4ce` (from logo), cream page bg, serif headings | Palette derived from actual logo SVG — confirmed 2026-04-28 |
| Payments (MVP)         | **Manual** — JazzCash, EasyPaisa, Bank Transfer; payment-proof upload; Mona verifies in Filament within 24h. **No COD. No card option at MVP** (deferred to Phase 5 with SafePay). | No gateway signup, no KYC chain, no extra build days; ships fast |
| Payment timing         | **Full payment up front on every order** — no partial advance, no balance-before-dispatch step *(locked 2026-09-16, Mona's call)* | Sets are made to measure and can't be re-sold; chasing the balance was the biggest operational drag, and many customers don't read email |
| Custom (DM) orders     | **Private custom order links** — `/custom/{token}` created from Filament; customer sizes + pays through the normal checkout, producing a regular Order flagged `is_custom` *(added 2026-09-16)* | Instagram/WhatsApp design requests were invisible to the system; this counts them in revenue, tracking and saved sizing without a second workflow |
| Payments (Phase 5)     | **SafePay** — automates Card (Visa/MC/UnionPay) + JazzCash + EasyPaisa via Pakistan-local gateway. Bank Transfer remains as manual fallback. | Pakistan-local, single integration; architectural plan in §26 |
| Hosting                | **Decide later** — develop locally first                              | Ship MVP before committing                          |
| Currency               | **PKR**, integer rupees (no decimals)                                 | Avoid float math                                    |
| Capacity model         | **Solo + 1–2 helpers + home studio**                                  | User-confirmed                                      |
| Geography (Y1)         | **Pakistan-wide online**, origin Mirpur AJK                           | User-confirmed                                      |
| Geography (Y2+)        | **Diaspora pilot — UK / UAE first**                                   | Humza is in Germany; leverage diaspora              |
| Photography (MVP)      | **DIY half-day shoot** — smartphone + ring light                      | Authentic, cheap                                    |
| AI for images          | **Background removal / cleanup only**, never generation               | Generated images kill trust for handmade brand      |
| **Sizing capture UX**  | **Live camera + SVG overlay guide — 2 close-up photos (fingers + thumb)** with upload fallback. Optional opt-in for 2 more (other hand) at preview time. | Macro nail-bed measurement is dramatically more accurate than wrist-out full-hand; symmetry assumption + free first refit cover the rare outlier. Differentiator vs. competitors. |
| **Chatbot**            | **Phase 7+** — collect Mona's real DM data first                      | Authenticity > automation for an artisan brand      |
| **Monetization**       | **Complementary products + services + selective blog affiliates** — never storefront AdSense | Premium positioning; AdSense math is terrible vs lost conversions |
| **Language**           | **English-only Y1**, Urdu deferred to Y2 if data justifies            | Stronger SEO authority, lower content cost          |
| **Goal posture**       | **Realistic over ambitious** — quarterly metrics, no hype             | User-mandated                                       |

---

## 3. Prerequisites

The dev machine currently has:
- ✅ Node 24.14.1
- ✅ npm 11.11.0
- ❌ **PHP — not installed**
- ❌ **Composer — not installed**

Before any Laravel work:

```bash
brew install php composer
php --version       # need 8.2+
composer --version  # need 2.x
sqlite3 --version   # usually preinstalled
```

---

## 4. Tech Stack

| Layer              | Choice                                                                           |
| ------------------ | -------------------------------------------------------------------------------- |
| Framework          | Laravel 11.x                                                                     |
| Templating         | Blade                                                                            |
| Styling            | Tailwind CSS v3 + custom palette tokens                                          |
| Interactivity      | jQuery 3.x — gallery lightbox, cart drawer, form niceties                        |
| Camera capture     | Native browser `getUserMedia` + `<canvas>` capture (no library)                  |
| Admin / CRM        | Filament v3                                                                      |
| Auth               | Laravel Breeze (Blade) — admin only; customers checkout as guests                |
| Database (dev)     | SQLite                                                                           |
| Database (prod)    | MySQL 8                                                                          |
| Image storage      | `storage/app/public/` + `php artisan storage:link` (S3/R2 later)                 |
| Image processing   | `intervention/image` v3 — thumbnails, HEIC→JPEG, EXIF strip, orientation fix     |
| Forms              | Laravel FormRequests                                                             |
| Settings           | `spatie/laravel-settings`                                                        |
| Email              | Mailtrap dev / SMTP prod                                                         |
| Sitemap            | `spatie/laravel-sitemap`                                                         |
| SEO meta           | Custom Blade `<x-seo>` component                                                 |
| Schema.org         | Hand-rolled JSON-LD via Blade partials per page type                             |
| Asset bundling     | Vite                                                                             |

**No frontend framework.** No React. No Vue. No Livewire on public pages. Filament's internal Livewire is fine.

---

## 5. Information Architecture

### Public routes

| Route                       | Page                | Purpose                                                                                  |
| --------------------------- | ------------------- | ---------------------------------------------------------------------------------------- |
| `/`                         | Home                | Logo hero, featured designs, brand story, CTA → Shop                                     |
| `/shop`                     | Shop                | Grid, filter by tier/style/color                                                         |
| `/shop/{slug}`              | Product detail      | Images, description, price, FAQ block, **"Add to bag"** (no "Order Now")                 |
| `/bridal`                   | Bridal              | Bridal Trio package + bridal storytelling (hand-only photography)                        |
| `/size-guide`               | Size guide          | Illustrated photo-with-coin instructions                                                 |
| `/contact`                  | Help                | Form, WhatsApp/Instagram links, hours (label is **"Help"**, not "Contact")               |
| `/about`                    | About / Mona's story | Founder story (a moat competitors can't copy)                                           |
| **`/blog`**                 | Blog index          | Category filters (Bridal, Tutorials, Trends, Care)                                       |
| **`/blog/{slug}`**          | Blog post           | Long-form content with FAQ schema, related products                                      |
| **`/custom/{token}`**       | Custom design link  | Private, unguessable link for a DM-agreed design — shows the quote, then hands off to the normal checkout (`POST /custom/{token}/begin`). `noindex`, `Disallow: /custom/` in robots.txt |
| `/order/start/{slug?}`      | Order form          | Multi-step: sizing capture → details → payment method                                    |
| `/order/sizing-capture`     | Live camera capture | Standalone camera screen (deeplinkable, mobile-first)                                    |
| `/order/confirm/{order}`    | Order confirmation  | Order number, payment instructions, payment-proof upload                                 |
| `/order/{order}/track`      | Order tracking      | Lookup via order number + email/phone                                                    |

### SEO-first routes

- `/sitemap.xml` — generated.
- `/robots.txt` — explicit crawl rules.
- `/feed.xml` — RSS for the blog (low cost, helps PR + content distribution).

### Admin routes (Filament, `/admin`)

| Route               | Resource                                                                                       |
| ------------------- | ---------------------------------------------------------------------------------------------- |
| `/admin`            | Dashboard — today's orders, revenue, awaiting verification, top blog posts                     |
| `/admin/orders`     | Orders list + kanban (New → Confirmed → In Production → Shipped → Delivered → Cancelled)       |
| `/admin/products`   | Product CRUD                                                                                   |
| `/admin/ugc`        | UGC photos CRUD with `face_visible` flag — never publish if face shown                         |
| `/admin/customers`  | Customer profiles with **saved sizing on file**                                                |
| `/admin/blog`       | Blog post CRUD with rich-text editor, SEO meta fields, scheduled publish                       |
| `/admin/messages`   | Contact form submissions                                                                       |
| `/admin/settings`   | Payment account #s, hours, contact info, shipping rates, FAQ entries                           |

---

## 6. Data Model

All models in `app/Models/`. Enums in `app/Enums/`.

### `users`
Admin accounts only.

### `products`
- `id`, `slug`, `name`, `description`
- `tier` (enum: `everyday`, `signature`, `glam`, `bridal_single`, `bridal_trio`)
- `price_pkr` (integer)
- `cover_image`, `is_active`, `is_featured`
- `stock_status` (enum: `in_stock`, `made_to_order`, `sold_out`)
- `lead_time_days` (typical 5–9)
- `meta_title`, `meta_description` *(SEO overrides)*
- `sort_order`, timestamps

### `product_images`
- `id`, `product_id`, `path`, `alt`, `sort_order`

### `ugc_photos` *(replaces `gallery_items` — consolidates UGC across home, product, bridal)*
- `id`, `image_path`, `alt`
- `placement` (enum: `home_grid`, `product_carousel`, `bridal_gallery`, `about_inline`)
- `product_id` (nullable — links UGC to a specific product for the product carousel)
- `face_visible` (bool — admin sets true if photo shows a face; `face_visible=true` is **never** displayed publicly)
- `is_published`, `sort_order`, `created_at`

### `customers`
- `id`, `name`, `email`, `phone`, `whatsapp`, `instagram` *(handle, added 2026-09-17 — DM customers often have no phone number)*
- `default_shipping_address`, `city`, `postal_code`
- `has_sizing_on_file` (bool)
- `notes` (Mona's private notes)
- `created_at`, `last_ordered_at`, `total_orders`, `lifetime_value_pkr`

### `customer_sizing_profiles`
- `id`, `customer_id`, `notes`, `verified_by_admin_at`
- Per-nail sizes: `size_r_thumb` … `size_r_pinky`, `size_l_thumb` … `size_l_pinky` (varchar 20 — any notation Mona likes)
- `source_order_id` — **must be `CHAR(36)`**. It was created with `foreignUlid()` (char 26) while `orders.id` is a 36-char UUID, so *every* "Record nail sizes" save failed with `Data too long for column 'source_order_id'` from May until the fix on 2026-09-18 (migration `2026_09_18_090000_fix_sizing_profile_source_order_id_type`). One profile row per customer (`updateOrCreate` on `customer_id`) — saving again updates it.
- *(2026-05-07: dropped `photo_path` — replaced by 1:N relation to `customer_sizing_photos` below, since the new sizing UX produces 2 photos at MVP, optionally 4)*

### `customer_sizing_photos` *(NEW — 2026-05-07)*
- `id`, `customer_sizing_profile_id` (FK)
- `path` (storage path, ULID filename)
- `photo_type` (enum: `fingers`, `thumb`, `fingers_other`, `thumb_other`)
- `uploaded_at`
- A profile has **2 rows** by default (fingers + thumb of one hand) or **4 rows** if the customer used the perfectionist opt-in to add the other hand. Same enum used on `order_sizing_photos` so the two tables can be reasoned about together.

### `orders`
- `id`, `order_number` (`NBM-YYYY-####`)
- `customer_id` (nullable for first-time guest, backfilled after)
- Snapshot: `customer_name`, `customer_email`, `customer_phone`
- `shipping_address`, `city`, `postal_code`, `notes`
- `subtotal_pkr`, `shipping_pkr`, `total_pkr` (integers)
- `payment_method` (enum: `jazzcash`, `easypaisa`, `bank_transfer`, `card`) *(`cod` removed 2026-04-30; `card` reserved for Phase 5 SafePay launch — not selectable at MVP)*
- `payment_status` (enum: `awaiting`, `verifying`, `paid`, `partial_advance`, `refunded`) *(`partial_advance` is legacy-only since 2026-09-16 — new orders are paid in full; the "Confirm: advance only" / "Balance received" actions only appear on orders placed under the old rule, i.e. `Order::isLegacyAdvanceOrder()`)*
- `advance_paid_pkr` *(populated by Filament confirmation actions: full payment → `total_pkr`; advance only → `advanceAmountPkr()`; balance received → `total_pkr`)*
- `status` (enum: `new`, `confirmed`, `in_production`, `shipped`, `delivered`, `cancelled`)
- `tracking_number`, `courier` (enum: `tcs`, `leopards`, `mp`, `blueex`)
- `requires_advance` (bool — **always false on new orders** since 2026-09-16; still true on pre-change orders, which keep their quoted advance)
- `is_returning_customer` (bool — since 2026-09-16 means "has a previous **paid** order", i.e. qualifies for the reorder discount; cancelled/unpaid earlier orders don't count)
- `is_custom` (bool — order came from a `/custom/{token}` link; shown as `✦ Custom` in the admin list and widgets, with a "Custom designs (from DM links)" filter)
- `sizing_capture_method` (enum: `live_camera`, `upload`, `from_profile`, `whatsapp_pending`) *(track which UX customers actually use; `whatsapp_pending` when customer skips capture and will send via WhatsApp)*
- `refit_requested_at`, `refit_shipped_at`, `refit_notes` *(added Phase 5 — free-first-refit tracking, set via Filament admin actions on Delivered orders)*
- timestamps

### `order_items`
- `id`, `order_id`, ~~`product_id`~~ *(column exists but is `unsignedBigInteger` while `products.id` is ULID — incompatible; never written. Dead column candidate for cleanup migration.)*
- `product_name_snapshot`, `product_tier_snapshot`, `product_slug_snapshot` *(slug is the de-facto FK to products)*
- `unit_price_pkr`, `qty` *(prices re-fetched server-side from `products` at order-creation time — never trust client bag pricing)*
- `sizing_notes`

### `order_sizing_photos`
- `id`, `order_id`, `path`, `uploaded_at`
- `photo_type` (enum: `fingers`, `thumb`, `fingers_other`, `thumb_other`) *(added 2026-05-07 — was a single full-hand photo; now 2 close-ups by default, 4 if perfectionist opt-in)*
- An order has **2 rows** in this table by default (fingers + thumb), or **4** if the customer opts in to size the other hand at preview time.

### `order_payment_proofs`
- `id`, `order_id`, `path`, `uploaded_at`, `verified_at`, `is_advance` (bool)

### `contact_messages`
- `id`, `name`, `email`, `phone`, `message`, `is_read`, `created_at`

### `blog_posts` *(NEW)*
- `id`, `slug` (unique), `title`, `excerpt`
- `content` (long-form HTML or Markdown)
- `cover_image`, `cover_image_alt`
- `category` (enum: `bridal`, `tutorials`, `trends`, `care`, `behind_scenes`)
- `tags` (JSON array)
- `meta_title`, `meta_description`, `og_image` *(SEO)*
- `target_keyword` *(primary keyword for the post — used for tracking)*
- `is_published`, `published_at`, `author_id` (FK to users)
- `view_count` (counter — used to find top posts)
- timestamps

### `blog_post_products` *(pivot — related products to a post for cross-linking)*
- `blog_post_id`, `product_id`

### `faqs` *(NEW — for FAQPage schema + on-page customer service)*
- `id`, `category` (enum: `sizing`, `payment`, `shipping`, `returns`, `application`, `bridal`, `general`)
- `question`, `answer`, `sort_order`, `is_active`

### `settings`
Single-row key/value via `spatie/laravel-settings` (class: `App\Settings\StoreSettings`). **As of Phase 5, this is the only source of truth for store-config values** — `config/nbm.php` was deleted; every controller and view reads from `app(StoreSettings::class)` (exposed in views as `$settings` via `AppServiceProvider::boot()`).

Stored keys:
- **Contact / social:** `whatsapp_number`, `instagram_handle`, `tiktok_handle`, `contact_email`, `business_hours`
- **Payments (manual):** `jazzcash_number`, `jazzcash_name`, `easypaisa_number`, `easypaisa_name`, `bank_name`, `bank_account_name`, `bank_account_no`, `bank_iban`
- **Payment method visibility** *(added 2026-09-11)*: `jazzcash_enabled` (bool, default true), `easypaisa_enabled` (bool, default true), `bank_transfer_enabled` (bool, default true) — admin-controlled toggles that hide the corresponding radio on `/order/payment` while preserving the credentials above. `OrderController::store()` re-validates against the enabled set at POST time.
- **Shipping:** `shipping_flat_pkr` (default 350), `shipping_free_above` (default 5000; 0 disables)
- **Advance & deposits:** `advance_threshold_pkr` (default 5000), `advance_percent` (default 25), `bridal_deposit_percent` (default 100 — full advance per §7), `reorder_discount_percent` (default 5)
- **Lead times (calendar days):** `lead_time_standard_days` (default 5), `lead_time_bridal_days` (default 10)

Helper: `StoreSettings::whatsappForWaMe()` returns the digits-only WhatsApp number (no `+`, no spaces) for `wa.me/{n}` URLs. Save-side normalizes the stored value to canonical `+<digits>`.

### `custom_order_requests` *(NEW — 2026-09-16, custom design links)*
- `id` (ULID), `token` (40 random chars, unique — the URL secret)
- `customer_id` (FK, nullable) — matched or created on save by `syncCustomer()` (phone first, then email), so DM customers appear under Customers **before** they order and the checkout reuses the same row instead of creating a duplicate
- `customer_name` (required), `customer_phone` (nullable), `customer_instagram` (nullable, normalized to a bare handle), `customer_email` (nullable)
- `design_title`, `design_description`, `reference_images` (JSON array of public-disk paths, max 4)
- `price_pkr`, `shipping_pkr` (nullable → standard shipping rules), `lead_time_days` (nullable → standard lead time)
- `status` (enum `pending` | `completed` | `cancelled`), `expires_at` (default +7 days, end of day), `opened_at`, `order_id` (FK, set when the order is placed), `admin_notes`
- Model helpers: `isUsable()`, `shareMessage()` (one text for WhatsApp/Instagram/SMS), `whatsappUrl()`, `instagramUrl()` (`ig.me/m/<handle>`), `shippingPkr()`, `toBagItem()` (tier `custom`, slug `custom-<id suffix>`)
- One link places **one** order: the row is locked inside the order transaction and flipped to `completed`

### `subscribers` *(NEW — blog email capture)*
- `id`, `email`, `source` (e.g. `blog_index`), `subscribed_at`, `unsubscribed_at`
- Simple MVP: collects emails with no automation at launch. Mailgun/Resend integration in Phase 5+.

---

## 7. The Order Flow

1. **Pick design** — `/shop/{slug}` → **Order Now** → `/order/start/{slug}`.
2. **Returning-customer lookup** — phone/email check. If `has_sizing_on_file` → skip to step 4.
3. **Sizing capture** (step 1, first-time customers) — see section 8 for the live-camera UX.
4. **Details** (step 2) — name, email, phone (WhatsApp), shipping address, city, postal code, notes.
5. **Payment method** (step 3) — radio: JazzCash · EasyPaisa · Bank Transfer (each hideable from admin Settings). All manual; account details rendered server-side from `settings`. **Every order is paid in full before production** (2026-09-16) — no advance, no balance step. **No COD. No Card at MVP** (Phase 5).
6. **Submit** → `Order` created (status `new`, `payment_status = awaiting`) → confirmation email sent → customer lands on `/order/confirm/{order}`.
7. **Confirmation** — order number, total to pay in full, account details for selected method, payment-proof upload field. *(Orders placed before 2026-09-16 still show their quoted advance + balance.)*
8. **Admin verifies** in Filament — Mona reviews payment proof within 24h, marks `payment_status = paid`, kanban progresses, status emails fire to customer.

### Custom (DM) order flow — `/custom/{token}`

1. **Mona agrees a design in Instagram/WhatsApp DMs**, then Filament → Orders → **Custom order links** → "New custom order link": customer name (only required field), WhatsApp number and/or Instagram handle, design title + description, up to 4 reference photos, quoted price, optional shipping + lead-time overrides, expiry date.
2. **She sends the link** — "Send on WhatsApp" (prefilled), "Copy message + link" (for Instagram/Facebook/SMS), or "Open Instagram chat" (`ig.me`).
3. **Customer opens the link** — sees the design, price, shipping and total, plus lead time and expiry. Returning customers with sizing on file get "Use my saved sizing"; everyone else goes to the camera guide.
4. **Normal checkout from there** — camera sizing → details (name/phone/email prefilled) → payment. `OrderController` prices the bag from the request row, never from the client.
5. **Result:** a regular Order flagged `is_custom`, attached to the linked customer, counted in the normal dashboard widgets, with the usual emails, tracking page, sizing photos and "Record nail sizes" action.

**Phase 5:** SafePay automates Card + JazzCash + EasyPaisa via gateway (architecture in §26); Bank Transfer remains as manual fallback.

---

## 8. Sizing Capture UX (live camera)

The signature feature. Most competitors say "custom" but ship 24-size standard packs. Live-camera capture with on-screen alignment guide is the differentiator.

**2026-05-07 revision:** moved from one full-hand-with-coin photo to **two close-up photos** — fingers row + thumb — of one hand. Macro distance with the coin in the same focal plane reads the per-nail width directly off the coin reference. Far more accurate than a wrist-out angle. The customer can opt in to add 2 more photos for the other hand if they want a perfect fit; otherwise we size both hands from the one we measured (symmetry assumption) and the free-first-refit policy catches the rare outlier.

### User journey

1. Customer reaches sizing step.
2. **Primary:** "Take photos with guide" button. Pre-camera explainer copy: *"We'll need 2 close-up photos — fingers and thumb. About 90 seconds."*
3. Browser requests camera permission once. On grant, a **state machine** runs in a single camera session:

   **State A — Photo 1 of 2: Fingers**
   - `<video>` shows live camera feed (`facingMode: 'environment'`).
   - SVG overlay: 4-finger row outline (horizontal layout, fingers laid flat) + coin circle above the middle finger.
   - Title strip: "Photo 1 of 2 — Your fingers" with a thin progress bar.
   - Brightness pill (top-centre) — green/amber based on canvas sampling.
   - **Alignment border** (around the SVG overlay) — turns **green** when brightness is OK *and* edge contrast in the expected nail-row region is high; **red** when something's off (too dark, hand off-frame, coin missing, low contrast). Heuristic only — does not block capture; just guides.
   - Shutter button. User taps when the border is green. We do not auto-capture.

   **State B — Photo 2 of 2: Thumb**
   - Same camera feed continues; overlay swaps to a single-thumb outline (vertical, thumb extended) + coin circle above the thumbnail.
   - Title strip updates to "Photo 2 of 2 — Your thumb."
   - Same brightness + alignment heuristics.

   **State C — Preview**
   - Two thumbnails side-by-side (fingers + thumb), each with a "Retake" link that pops back to the corresponding state.
   - **Symmetry disclaimer** (one paragraph, calm tone): "We size your other hand from these photos. Most hands are symmetric to within half a millimetre, well within the press-on fit tolerance. If you'd like a perfect fit, you can optionally take 2 more photos for your other hand."
   - Two CTAs: primary **"Submit my sizing"** · secondary **"Add my other hand →"** which reveals States A+B again (with overlay labels "Photo 3 of 4 — Other hand fingers" / "Photo 4 of 4 — Other hand thumb"), then returns to a 4-thumbnail preview.

   **State D — Submit**
   - POST 2 (or 4) blobs to `OrderSizingPhotoController@store` along with `photo_type` for each (`fingers` / `thumb` / `fingers_other` / `thumb_other`).
   - Server strips EXIF + converts HEIC, saves with ULID filenames in `storage/app/public/sizing/{order_id}/`.
   - Sets `orders.sizing_capture_method = 'live_camera'`.

4. **Fallback (camera blocked / unsupported):** 2 labelled file inputs ("Fingers photo" + "Thumb photo"), each `accept="image/*" capture="environment"`. Symmetry disclaimer + "Add my other hand" reveal still applies; reveals 2 more file inputs.
5. **Tertiary:** carousel of 4–6 good/bad example thumbnails below both options.

### Technical specifics

```js
// Permission + stream — opened once for the whole session
const stream = await navigator.mediaDevices.getUserMedia({
  video: { facingMode: 'environment', width: { ideal: 1920 }, height: { ideal: 1080 } }
});
videoEl.srcObject = stream;

// State machine (simplified)
let captures = {}; // { fingers, thumb, fingers_other?, thumb_other? }

function captureFrame(photoType) {
  const canvas = document.createElement('canvas');
  canvas.width = videoEl.videoWidth;
  canvas.height = videoEl.videoHeight;
  canvas.getContext('2d').drawImage(videoEl, 0, 0);
  canvas.toBlob(blob => { captures[photoType] = blob; }, 'image/jpeg', 0.92);
}

// Edge-contrast heuristic — runs at 500ms tick alongside brightness check
function checkEdgeContrast(video, regionType /* 'fingers' or 'thumb' */) {
  // Sample a 200x200 region around the expected nail row position for the current overlay,
  // run a Sobel filter, return % of pixels above an edge-strength threshold.
  // > 12% → likely a real hand in roughly the right place → green.
  // < 6% → likely empty / overexposed / missing → red.
  // Between → amber/transitional.
}
```

### Constraints + gotchas

- **HTTPS required** for `getUserMedia` in production. Dev workaround: localhost is allowed without HTTPS; staging on a non-HTTPS domain falls back to upload mode.
- **iOS Safari** supports `getUserMedia` since iOS 11; older iPhones still in use in Pakistan may not. Detect + fall back gracefully.
- **Permission denied** → friendly message + automatic switch to 2-input upload mode.
- **EXIF strip** server-side regardless of source.
- **Camera permission asked once.** The state machine does not re-prompt between fingers and thumb.
- **Edge-contrast false positives** on busy backgrounds — recommend a dark cloth/fabric backdrop in the size guide. The heuristic should not block capture even when red; only guide.
- **Track usage** in `orders.sizing_capture_method` — measure adoption. Track `photo_type` distribution: how many customers add the optional other-hand photos vs. stick with 2.

### Build effort

- ~5–6 days extra dev time on top of base order flow (was 3–4 before the 2026-05-07 rev). State machine + edge-contrast heuristic + 2-photo upload + schema migration.
- Worth it: macro accuracy drops the refit rate. Better data for Mona. Better Reel content ("here's our guided sizing in action — fingers, then thumb, all in one minute").

### Phase

- Built in **Phase 2** alongside the order flow. Don't defer — it's part of the core differentiator. Phase 5 can upgrade the green/red heuristic to TensorFlow.js hand-landmarks if conversion data shows mis-shoots above 8%.

---

## 9. Size Guide

Standalone illustrated page, embedded as modal from order form and product pages.

Sections: what you'll need · step 1 hand flat · step 2 place coin · step 3 shoot straight down · good vs bad examples · tips · CTA.

Ship with placeholder SVG illustrations; replace with real photos from Mona's first DIY shoot.

---

## 10. Design System

### Palette (`tailwind.config.js`)

> **Source of truth:** `.claude/skills/design-system.md` — extracted from the actual `Nails by Mona Logo.svg`. The logo fill is `#bfa4ce` (dusty lavender). All UI accents trace back to this color.

```js
colors: {
  // Brand — derived from logo fill #bfa4ce
  lavender:      '#bfa4ce',  // primary: CTA buttons, active links, icon fills
  lavenderDark:  '#9b7fb4',  // hover state for buttons and links
  lavenderDeep:  '#7a5e99',  // pressed/active, strong emphasis
  lavenderLight: '#d9c8e8',  // subtle borders, tag backgrounds, disabled fills
  lavenderFaint: '#f2ecf8',  // section wash backgrounds, input focus bg

  // Neutrals
  cream:  '#FDFBFE',         // page background (near-white, whisper of lavender)
  white:  '#FFFFFF',         // cards, modals, form fields
  cloud:  '#F7F3FB',         // alternate section stripes

  // Text scale
  ink:    '#1C1727',         // primary headings & body (deep purple-toned black)
  body:   '#4A4158',         // body copy, labels
  mute:   '#8B7E9A',         // captions, placeholder text, secondary labels
  subtle: '#C4B8D2',         // dividers, disabled state, decorative rule lines
}
```

### Typography

```js
fontFamily: {
  serif: ['"Cormorant Garamond"', 'serif'], // headings
  sans:  ['Inter', 'ui-sans-serif', 'system-ui'], // body
}
```

### Logo files

| File | Use |
|---|---|
| `public/logo-text.svg` | **Website logo** — nav, footer, OG images, emails. Circle removed, text-only wordmark. |
| `brand/logo-text.svg` | **Design reference copy** — same wordmark kept in brand assets |
| `brand/logo-circle.svg` | **Stickers & packaging only** — the original circular version |
| `Nails by Mona Logo.svg` (root) | **Original source file** from Humza — do not delete |

- On `cream`/`white`/`cloud` backgrounds: render as-is (color is already `#bfa4ce`)
- On `lavender` filled backgrounds: use `filter: brightness(0) invert(1)` for a white version
- Never recolor, stretch, or shadow the logo. Min width 120px, preferred 160px in nav.

### Icon system

- **15 custom thematic SVGs** in `public/icons/`: nail, sparkle, bridal, sizing, camera, package, heart, star, flower, palette, ribbon, coin, instagram, whatsapp, tiktok.
- **2 sizing overlay SVGs** (added 2026-05-13): `sizing-fingers.svg` (4 U-shaped dashed finger outlines + coin, viewBox 0 0 400 480) and `sizing-thumb.svg` (1 wide U-shaped thumb + coin, viewBox 0 0 300 480). Lavender stroke `#BFA4CE`, `stroke-dasharray="10 6"`. Used in size-guide page illustrations + camera overlay mockups.
- **UI utility icons:** Heroicons v2 outline — copy SVG paths into Blade partials. Use for search, close, menu, check, truck, envelope, etc.
- Full icon map in `.claude/skills/design-system.md` §5.

### Design direction (2025/2026 modern minimal)

- **Floating nav** with `bg-white/80 backdrop-blur-md` — glass effect signals premium.
- **Editorial typography**: H1 is `font-serif text-6xl font-light` — large, airy, not bold.
- **Bento grid** for homepage product feature — asymmetric cells, one hero `col-span-2 row-span-2`.
- **Micro-interactions**: all hovers `duration-200`–`duration-300`, card images `scale-105` on hover.
- **Section color alternation**: `cream` → `white` → `cloud` — never same bg twice in a row.
- **Accent rules**: `h-0.5 w-10 bg-lavender` below H2s. Section labels in `text-lavender uppercase tracking-widest text-sm` above H2s.
- Full implementation reference in `.claude/skills/design-system.md`. That file overrides this section.

### Reusable Blade components (`resources/views/components/`)

- `layouts.app`, `layouts.bridal`.
- `ui.button`, `ui.input`, `ui.textarea`, `ui.card`.
- `icons.*` — one file per icon, inline SVG pattern.
- `product.card`, `gallery.tile`, `order.step-header`, `order.camera-capture`.
- `bridal.trio-card`.
- `seo` (renders title, meta, og, twitter, canonical, json-ld).
- `blog.post-card`, `blog.related-products`.

---

## 11. Build Phases

### Phase 0 — Scaffold (day 1) ✅ COMPLETE
Laravel + Tailwind + jQuery + Filament + `intervention/image` + `spatie/laravel-settings` + `spatie/laravel-sitemap`. SQLite. Initial commit. Tailwind v4 CSS-based config with `@theme` block. All custom palette + font tokens confirmed in compiled CSS.

### Phase 1 — Public marketing site ✅ COMPLETE (2026-05-10)
All 9 public marketing Blade views created and returning HTTP 200. `<x-seo>` component with full meta + OG + JSON-LD. `layouts/app.blade.php` with sticky nav, mobile full-screen overlay, bag drawer (localStorage `nbm.bag`), `window.NbmBag` global API, dark footer. SEO JSON-LD using `@graph` pattern on all multi-schema pages.

**Views delivered:** `home` · `shop` · `product` · `bridal` · `size-guide` · `about` · `contact` · `blog` · `blog-post` · `order/start` · `order/sizing-capture` · `order/confirm` · `order/track`

### Phase 2 — Order flow + live camera ✅ COMPLETE (2026-05-16)
Multi-step order form (sizing → details → payment, each a separate URL), **live-camera sizing capture with 2-photo state machine (fingers + thumb)**, optional opt-in for 2 more photos (other hand), upload fallback, desktop QR-code handoff, returning-customer lookup (phone/email → skip sizing if profile on file), advance-payment logic (30% for orders ≥ Rs. 5,000; full advance for Bridal Trio), confirmation page with payment-proof drag-and-drop upload, order tracking page with 5-node timeline, 6 transactional email templates (OrderPlaced, PaymentVerified, InProduction, Shipped, PaymentReminder ×2, AutoCancel), background jobs for 24h/48h payment reminders and 72h auto-cancel. **No payment gateway** — SafePay deferred to Phase 6.

### Phase 3 — Filament admin (days 7–8) ✅ COMPLETE (2026-05-13)
All 7 resources (Orders, Products, UgcPhotos, Customers, BlogPosts, FAQs, ContactMessages), Settings page, 2 dashboard widgets (OrderStats, RecentOrders), admin seeder, 9 demo products seeded. Filament v4 API patterns documented in §32 session history (2026-05-13 entry).

### Phase 4 — Blog + SEO infrastructure ✅ COMPLETE (2026-05-16)
Blog index + post template with FAQ schema. Sitemap generator (`/sitemap.xml`). RSS feed (`/feed.xml`). Schema.org Article + FAQPage + BreadcrumbList on all post pages. All 5 cornerstone posts seeded and live: wudu/Muslim women (priority) · press-on vs acrylics · bridal trends 2026 · how to apply · how to remove. BlogPostSeeder + FaqSeeder run on production. Category filter (client-side jQuery), email subscribe strip, reading-time calculation, related-posts, and view-count tracking all working.

### Phase 5 — Polish & handoff ⚠️ MOSTLY COMPLETE (2026-05-16, 5-block sweep)

**Security + workflow + SEO sweep done** (see §32 "Phase 5 polish pass" entry for full detail). 37 audit findings closed across five deploys:

- **Security:** server-side bag price verification, session allowlist on order pages, private-disk file storage with auth-gated admin route, EXIF strip + HEIC normalization on every upload, DB-transaction + row-locked order numbering, throttle on tracking lookup.
- **Settings unification:** `config/nbm.php` deleted; every value comes from `StoreSettings`. Bridal deposit + reorder discount + lead times all admin-editable.
- **Order workflow:** advance/balance/refit actions in Filament, payment-age SLA badge, bulk confirm, one-tap WhatsApp, editable customer/address post-placement, refit-request tracking.
- **SEO + share:** `/privacy` `/terms` `/shipping` legal pages live (clears footer 404s), custom 404, generated OG image, `og:type=article` for blog posts, deduplicated Org schema, `lang="en-PK"`, RSS autodiscovery, `robots.txt` with Sitemap line, populated favicon.
- **A11y + hygiene:** focus traps + restore on bag/menu overlays, queued admin notifications (`ShouldQueue`), lazy-loading audit, 5 dead Blade views deleted.

**Still to do (real-world polish, not blocked):**
- Real photography swap-in (replace Google AI placeholder images with Mona's actual photo shoot — DIY half-day, hand-only per §24 brand rules).
- Real designer-made OG image (current is auto-generated DejaVu fallback; Mona could commission a 1200×630 hand-only brand card).
- 8–12 demo products seeded (currently 9 from Phase 3 seeder).
- `docs/mona-admin-guide.md` walkthrough — non-technical-friendly admin tutorial.
- Analytics consent banner (finding #36) — defer until Phase 7 diaspora UK opens.

### Phase 6 — Payment gateway (post-launch)
**SafePay integration.** Adds automated card (Visa/MC/UnionPay) + JazzCash + EasyPaisa via Pakistan-local gateway. Manual proof-upload flow stays as fallback for Bank Transfer. Architectural plan (HMAC webhook, idempotent handler, polling fallback, decline-retry path) lives in §26 — no need to redo design work when this phase opens.

**Pre-conditions for Phase 6 to start** (Mona-side, ~7–14 days lead time):
1. Sole-proprietorship registration
2. Business bank account
3. NTN registration
4. SafePay merchant onboarding + KYC

These are deferred until after launch — they're not on the critical path to MVP.

### Phase 7 — Diaspora pilot (Year 2)
UK + UAE shipping. Flat international rate. Advance-only. See section 28.

### Phase 7.5 — WhatsApp Business API (post Phase 7, when volume justifies)
Direct Meta Cloud API integration — no third-party provider. **Pricing note:** the old "free 1,000 conversations/month" tier no longer applies — Meta now charges per template message (order updates are "utility" templates; replies inside a customer's 24h window are free). Check Meta's current Pakistan rates when this phase opens. Removes the customer call button entirely (API accounts are messaging-only). Requires Meta business verification (website is already live so approval path is clear), a dedicated number, and a Laravel webhook + Filament inbox. Deferred until order volume makes the number migration worthwhile — **reconfirmed 2026-09-17**: Mona does not want to send a message per order stage by hand, so automatic WhatsApp updates wait for this phase rather than being faked with manual prompts. Prefer a **new dedicated number** for API traffic so her current chat number keeps working in the WhatsApp Business app. For now: emails are the automatic channel, plus one optional WhatsApp button per order + Silence Unknown Callers setting.

### Phase 8 — AI chatbot (Year 2, see section 25)
Only after 3–6 months of Mona's real DM data is collected. Tone-mimicking system prompt + few-shot examples + strict guardrails (always route pricing/sizing to WhatsApp).

---

## 12. Folder Structure

```
Nails Website/
├── CLAUDE.md                       # Project bible (this file)
│
├── brand/                          # Brand identity source files (design originals)
│   ├── logo-circle.svg             # Full circle logo — stickers & packaging only
│   └── logo-text.svg               # Text-only wordmark — reference copy
│
├── docs/                           # Project documentation
│   ├── about-page-copy.md          # Mona's story — drafted, needs her approval
│   ├── content-calendar.md         # Blog editorial calendar (section 22)
│   ├── mona-admin-guide.md         # Admin walkthrough for Mona (Phase 5)
│   ├── seo-audit-checklist.md      # Quarterly SEO audit (Phase 5)
│   └── pages/                      # Page-by-page content blueprints (all 14 public routes)
│       ├── 00-global.md            # Header, footer, shared tokens — read first
│       ├── 01-home.md              # /
│       ├── 02-shop.md              # /shop
│       ├── 03-product-detail.md    # /shop/{slug}
│       ├── 04-bridal.md            # /bridal
│       ├── 05-gallery.md           # /gallery
│       ├── 06-size-guide.md        # /size-guide
│       ├── 07-contact.md           # /contact
│       ├── 08-about.md             # /about
│       ├── 09-blog-index.md        # /blog
│       ├── 10-blog-post.md         # /blog/{slug}
│       ├── 11-order-form.md        # /order/start/{slug?} (multi-step)
│       ├── 12-sizing-capture.md    # /order/sizing-capture
│       ├── 13-order-confirmation.md # /order/confirm/{order}
│       └── 14-order-tracking.md    # /order/{order}/track
│
├── public/                         # Laravel web root — assets delivered to browsers
│   ├── logo-text.svg               # Website wordmark (circle removed, lavender text)
│   ├── robots.txt
│   ├── sitemap.xml                 # auto-generated
│   ├── feed.xml                    # RSS blog feed
│   └── icons/                      # Custom SVG icon set — 15 files
│       ├── nail.svg                # Almond press-on nail silhouette
│       ├── sparkle.svg             # 4-point sparkle (Glam tier, hero accents)
│       ├── bridal.svg              # Diamond ring (Bridal section)
│       ├── sizing.svg              # Ruler with ticks (size guide)
│       ├── camera.svg              # Camera + sparkle (sizing capture)
│       ├── package.svg             # Gift box + bow (order confirmation)
│       ├── heart.svg               # Heart (nail care, wishlist)
│       ├── star.svg                # 5-point star (ratings, featured badge)
│       ├── flower.svg              # 5-petal flower (decorative accents)
│       ├── palette.svg             # Artist palette (design choice)
│       ├── ribbon.svg              # Bow/ribbon (premium packaging, bridal)
│       ├── coin.svg                # PKR coin with ₨ symbol (sizing guide)
│       ├── instagram.svg           # Instagram brand icon
│       ├── whatsapp.svg            # WhatsApp brand icon
│       └── tiktok.svg              # TikTok brand icon
│
│   ── ── ── Laravel scaffold (added in Phase 0) ── ── ──
│
├── app/
│   ├── Enums/                      # OrderStatus, PaymentMethod, ProductTier, etc.
│   ├── Filament/                   # Admin resources, widgets, pages
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Requests/               # FormRequest validation classes
│   └── Models/                     # Eloquent models
│
├── config/
│   └── couriers.php                # Tracking URL templates: TCS, Leopards, M&P, BlueEx
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── database.sqlite             # Dev database
│
├── resources/
│   ├── css/app.css
│   ├── js/
│   │   ├── app.js                  # jQuery imports + small modules
│   │   └── camera-capture.js       # Live-camera sizing UX (section 8)
│   └── views/                      # Blade templates
│       └── components/
│           └── icons/              # One .blade.php per icon (inline SVG pattern)
│
├── routes/web.php
│
├── storage/app/public/             # User-uploaded files (storage:link → public/storage/)
│   ├── products/
│   ├── gallery/
│   ├── sizing/
│   ├── sizing-profiles/
│   ├── payment-proofs/
│   └── blog/
│
├── tailwind.config.js
└── vite.config.js
```

---

## 13. Run Locally

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
npm run dev
php artisan serve  # http://localhost:8000
```

**Admin (seeded):** `admin@nailsbymona.test` / `password`

---

## 14. Conventions

- **Currency:** PKR integer in `*_pkr` columns. Format via `format_pkr()` helper.
- **Images — public assets** (products, UGC, blog covers): `storage/app/public/{products,ugc,blog}/`. Filenames are ULIDs. Served via the `storage:link` symlink at `/storage/...`.
- **Images — private uploads** (sizing photos, payment proofs): `storage/app/private/{sizing,payment-proofs}/{order_id}/`. **Never web-accessible.** Read only via the auth-gated admin route `route('admin.private-file', ['category', 'order', 'filename'])`. Model accessors `OrderSizingPhoto::viewer_url` / `OrderPaymentProof::viewer_url` produce that URL.
- **Image variants:** auto-generate WebP + AVIF + JPEG fallback at 3 sizes (320, 768, 1280) via `intervention/image` on upload. *(Aspirational — not yet implemented.)*
- **Enums:** `app/Enums/`. Cast in models.
- **Settings — single source of truth:** `App\Settings\StoreSettings` (Spatie). Globally available in views as `$settings` via `AppServiceProvider::boot()`. **Do not reintroduce `config/nbm.php`** — it was deleted Phase 5 because it diverged from admin Settings edits. Every payment / contact / shipping / lead-time / advance value comes from `$settings`.
- **WhatsApp URLs:** always `wa.me/{{ $settings->whatsappForWaMe() }}` (digits-only). Save-side normalizes to `+<digits>`.
- **Reorder discount:** `$settings->reorder_discount_percent` (default 5). Stored as integer percent, applied as `subtotal * (percent / 100)`.
- **Bridal deposit:** `$settings->bridal_deposit_percent` (default 100 — full advance per §7). Used by `Order::advanceAmountPkr()`.
- **Design:** no new colors without updating Tailwind v4 `@theme` in `resources/css/app.css`. No new fonts without `<head>` updates.
- **Validation:** FormRequests, never inline. *(Aspirational — current code uses inline `$request->validate()` widely; finding #12 deferred.)*
- **Uploads:** HEIC/HEIF → JPEG via Intervention. EXIF strip. Max 8 MB. Applies to BOTH sizing photos (`OrderSizingPhotoController`) AND payment proofs (`OrderPaymentProofController`).
- **Order numbers:** `NBM-YYYY-####`, zero-padded, resets yearly. Generated by `Order::generateOrderNumber()` which wraps a `SELECT … FOR UPDATE` in a `DB::transaction` and retries up to 3× on unique-constraint violations (race-safe).
- **Order creation:** wrapped in `DB::transaction` — items + customer stats + Order row commit atomically.
- **SEO:** every public route has a `<x-seo>` component with `title`, `description`, `ogType` (default `website`, blog posts pass `"article"`), `og:image`, `canonical`, and route-appropriate JSON-LD. When `:schema` contains an `@graph`, Organization is merged into it (no duplicate `<script>` block).
- **URLs:** lowercase-hyphenated slugs only. Permanent 301 for any URL changes (use `redirects` table or Laravel's `App\Http\Middleware\RedirectLegacyUrls`).
- **Order form steps:** each step is a separate URL + server-rendered page (session preserves state). No JS-only single-page stepper — separate URLs give reliable back-button support and accurate step-level analytics on Pakistan mobile networks.
- **Order tracking URLs:** use UUID, not integer ID, to prevent enumeration. Lookup requires order number + phone/email match. Lookup is throttled `5,1` and stores the order ID in `session('order_form.authorized_orders')` on success.
- **Order page authorization (confirm + track + proof upload):** session-allowlist pattern. `OrderController::sessionMayViewOrder($id)` / `OrderController::authorizeOrderForSession($id)`. Visitors without a session entry are redirected to `/track` lookup. Never assume UUID knowledge = authorization.
- **Bag pricing:** never trust client-submitted prices. `OrderController::verifyBag()` re-fetches every item by slug from `products` before order creation; items with no/invalid slug are dropped.
- **Admin notifications:** all `Notification` classes must `implements ShouldQueue` so push notifications go to the queue worker, not the request thread. Iterate users with `chunkById(50)`, never `User::all()`.

---

## 15. Security Notes

- Admin gated behind Filament auth. `User` model implements `Filament\Models\Contracts\FilamentUser` with `canAccessPanel(): true` (without this, Filament v4 blocks all admin access in production — see §32 2026-05-15).
- All customer uploads (sizing photos AND payment proofs) pass through Intervention: EXIF strip + HEIC/HEIF/webp → JPEG. PDFs in payment proofs pass through unchanged.
- **Payment proofs + sizing photos live on the private `local` disk** (`storage/app/private/`) — never web-accessible. The only read path is the auth-gated route `GET /admin/files/{category}/{order}/{filename}` (controller: `App\Http\Controllers\Admin\PrivateFileController`). Path-traversal guarded (no `..`, `/`, `\`), category whitelisted to `sizing|payment-proofs`, filename regex restricted to `[A-Za-z0-9._-]+`. Auth check happens inside the controller via `Auth::check()` because Laravel's default `auth` middleware redirects to a non-existent `login` route.
- **Bag price tampering closed.** `OrderController::verifyBag()` re-fetches every cart item by slug from the DB before pricing. Customer-submitted prices are ignored.
- **Order creation atomicity.** `OrderController@store` wraps the Order + OrderItems + customer-stats writes in `DB::transaction`. `Order::generateOrderNumber()` uses `SELECT … FOR UPDATE` + 3-retry on unique-violation, race-safe.
- **Order-page authorization.** `/order/confirm/{uuid}`, `/order/{uuid}/track`, and the proof upload endpoint all check `OrderController::sessionMayViewOrder($uuid)` first. The session allowlist `order_form.authorized_orders` is populated on order placement + successful tracking lookup. Unauthorized visitors get redirected to `/track` lookup form. UUID knowledge alone is no longer sufficient.
- CSRF default; rate-limit:
  - Contact form `POST /contact` → `throttle:5,1`
  - Customer lookup `POST /order/start/lookup` → `throttle:10,1`
  - Order placement `POST /order/payment` → `throttle:5,1`
  - Tracking lookup `POST /order/track/lookup` → `throttle:5,1` (was 10/min before Phase 5)
  - Subscribe `POST /subscribe` → `throttle:5,1`
- Settings page admin-only (Filament panel auth).
- Camera permission requested only on the sizing step — explanation copy preceding the prompt to maximize grant rate.
- `robots.txt` Disallows `/admin`, `/admin/`, `/order/`, `/track`.
- All public notifications (`NewOrderNotification`, `NewMessageNotification`) `implements ShouldQueue` — they run on the queue worker, never on the request thread.

**Phase 6 (SafePay) security additions** *(deferred — listed here for future reference)*: HMAC signature verification on the webhook endpoint, idempotent handler keyed on `safepay_transaction_id`, IP allow-listing if SafePay publishes their range. PCI-DSS scope stays near-zero because card data lives entirely on SafePay's hosted checkout — our servers never see it.

---

# PART 2 — STRATEGY

## 16. Market Positioning

**Tagline:**
> **"Custom-fit, handmade press-on nails — made for your hands, made for the moment that matters."**

**Four pillars:**

1. **Fit you can feel.** Live-camera sizing, saved profile for reorders, free first-time refit guarantee.
2. **Handmade in Mirpur.** A real artisan studio, BA in Fine Arts, 2-year track record. Show the making — hands working, tools, materials. Never the maker's face.
3. **Built for big days.** Bridal Trio (Mehendi + Baraat + Valima) + everyday luxe sets.
4. **Wudu-compatible.** Press-ons can be removed before ablution and reapplied after. This is the founder's own personal reason for starting the business — and an under-served need shared by millions of Muslim women across Pakistan. No competitor has articulated this.

**Anti-positioning (what we're not):**
- Not a Daraz commodity brand.
- Not a 24-standard-size ready-pack shop.
- Not a personal-Instagram-storefront — the brand is "Nails by Mona," not "Mona personally." Customer service is "Customer care," not "DM Mona."
- Not cluttered — no banner ads, no popups beyond a single tasteful subscribe modal.

**Founder visibility — locked rule:** Mona's name and story are public assets (About page, blog byline, packaging signature). **Mona's face is never shown** anywhere on the website. No photo, no portrait, no on-camera Reel embed. The brand is built around the craft and the hands, not the face. Customer-side WhatsApp pre-fills always address "Nails by Mona," never "Mona" personally — protects from spam and reinforces brand-not-person framing.

---

## 17. Competitor Landscape (2026)

8 active Pakistani press-on brands. Leaders 23k–43k IG followers — 20–40× Mona's current ~1k. Closeable in 12–18 months.

| Brand                  | Handle              | IG (~) | Price PKR    | Positioning                  | Key strength                          | Weakness                            |
| ---------------------- | ------------------- | ------ | ------------ | ---------------------------- | ------------------------------------- | ----------------------------------- |
| **Aura Nails**         | @auranails.pk       | 43k    | 1,500–3,500  | Everyday Gen-Z               | Largest IG audience                   | Less bridal depth                   |
| **Nailzy Pakistan**    | @nailzy.pakistan    | 33k    | 1,720–2,800  | Made-to-order                | "Shaadi Glam" line, kit included      | No premium tier above ~PKR 2,800    |
| **Luxe Press On**      | @luxe_press_on      | 29k    | 1,800–3,500  | Ready + custom               | Polished feed, dual catalog           | Sizing methodology not transparent  |
| **Nail Queen**         | @nailqueen.pk       | 23k    | 1,200–2,499  | Mass / accessible            | Largest design library (73+)          | No bridal narrative                 |
| **Alaaya.pk**          | @alaaya.pk          | 10–20k | 1,700–4,000  | Customized handmade          | Bridal category, 7–9d MTO             | Sizing process unclear              |
| **Nail Barbie**        | @nailbarbieee       | 15–25k | 770–3,850    | Trendy, value-led            | "Customize" tool                       | No bridal, no shipping policy       |
| **Nail Aura PK**       | @nail.aura.pk       | 5–15k  | ~1,500–3,000 | Handmade niche               | Craft positioning                     | Smaller catalog                     |
| Mass retailers         | various             | mixed  | 1,000–3,500  | Mass / Daraz                 | Distribution                          | Commodity feel                      |

**Cross-cutting:** nobody dominant. Words like "custom" and "handmade" have lost meaning. **Process transparency, fit guarantee, and bridal storytelling are the wedge.**

---

## 18. Customer Pain Points & Messaging

**Why Pakistani women currently choose acrylics:**
- Salon-finished look, lasts 2–3 weeks.
- Single decision (one salon visit).

**What they hate:**
- 2–3h per session, every 3 weeks.
- PKR 2,500–5,000/session → PKR 30–60k/year.
- Acrylic damage: thinning, peeling, brittleness — widely complained about.
- 20-min acetone soaks; sometimes forced scraping.

**How press-ons solve this:** 5-min application, reusable 3–5×, no nail-bed damage, ~1/5 annual cost.

**The religious pain point (NEW — Mona's own story):**
Traditional nail polish and acrylics are not compatible with wudu (Islamic ablution). Press-ons are removed before wudu, reapplied after. This solves a genuine daily pain point for practicing Muslim women. Mona started this business partly because *she* faced this problem. This is authentic, no competitor owns it, and it opens a distinct content/search angle.

**Common objections + counter-messaging:**

| Objection                  | Counter                                                                            |
| -------------------------- | ---------------------------------------------------------------------------------- |
| "Will they fall off?"      | 7-day wear-test Reels, brush-on glue rec, care guide included with every order.    |
| "Do they look fake?"       | Lead with handmade gel finish on real Pakistani hands. Never stock images.         |
| "Glue mess / damage?"      | Educational Reel on safe application + removal.                                    |
| "Won't fit my nails."      | **Live-camera sizing + free first refit.** This is where we win.                   |
| "Shipping risk / scam?"    | Mirpur location named, real customer videos, free first refit, secure payment options (bank/JazzCash/EasyPaisa/card link). |
| "Can I wear them as a Muslim?" | **Yes — remove before wudu, reapply after. No fatwa issues, no compromises.** Mona is Muslim, wears them herself. |

---

## 19. Pricing Ladder

| Tier                                          | Price (PKR)     | Notes                                              |
| --------------------------------------------- | --------------- | -------------------------------------------------- |
| **Everyday Plain**                            | 1,800–2,200     | Single color, custom-fit                           |
| **Signature** (volume)                        | 2,500–3,500     | Ombre, art, custom-fit                             |
| **Glam**                                      | 3,800–4,800     | Charms, 3D, hand-painted                           |
| **Bridal Single**                             | 5,000–6,500     | One event, premium packaging                       |
| **Bridal Trio** (Mehendi + Baraat + Valima)   | **10,000 flat** | 3 sets, one fitting; ~33–49% off vs 3 singles (2026-09-11)  |
| **Refill / Reorder**                          | -5%             | Saved sizing on file, faster lead time             |

**Margin model:** ~35–45% net at handmade scale. Track quarterly.

---

## 20. Marketing Playbook

### Channel mix (monthly time)

- **Instagram Reels & Stories — 50%.** Primary discovery + conversion.
- **TikTok Pakistan — 15%.** Repurpose Reels.
- **WhatsApp Business — 15%.** Closing channel + Quick Replies.
- **SEO (blog + technical) — 15%.** Compounds over time. See sections 21–22.
- **Pinterest — 5%.** Cheap, evergreen, wedding/nail searches drive traffic.

### Content cadence (monthly)

- 12–16 Reels (3–4/week).
- Daily Story posts.
- 1 hero education piece/week (acrylic vs press-on, sizing how-to, removal).
- **2–3 blog posts/month** (see section 22).

### Influencer strategy

- Pakistani micro (10–50k): PKR 5,000–20,000/post.
- Mid (50–250k): PKR 20,000–75,000.
- **Better:** gifted bridal sets to 5–10 wedding/bridal creators per quarter (~PKR 25–50k in product + small thank-you fee).

### Suggested monthly marketing budget

| Phase           | Total PKR/mo  | Allocation                                                         |
| --------------- | ------------- | ------------------------------------------------------------------ |
| Months 1–3      | 15,000–25,000 | Mostly Meta ads on best-performing organic Reels                   |
| Months 4–9      | 50,000–100,000 | Ads 30–60k, influencer 15–30k, content production 5–10k            |
| Months 10–12    | 70,000–120,000 | Wedding-season push, more bridal collabs                          |

### Sales channels

- IG + WhatsApp: 60–70% of orders Y1.
- Own site: 20–30%. Required for credibility, search, bridal.
- **Daraz: defer** to Year 2+, only for low-end SKU.

---

## 21. SEO Playbook

The website itself is a marketing asset — most competitors have generic Shopify sites. A properly optimized Laravel site outranks them on long-tail queries within 6–9 months.

### Technical SEO (built into Phase 1 + 4)

- **Server-side rendering** — Laravel's default. Massive advantage over JS-heavy SPA competitors.
- **Lighthouse mobile ≥ 90** for Performance + Accessibility on `/`, `/shop`, `/blog`. Core Web Vitals: LCP < 2.5s, INP < 200ms, CLS < 0.1.
- **Image optimization:** WebP + AVIF + JPEG fallback, `srcset` for responsive sizes, `loading="lazy"` on below-fold images, proper `width`/`height` to prevent CLS.
- **Schema.org JSON-LD** on every page type:
  - Home + every page → `Organization`
  - Product pages → `Product` (price, availability, aggregateRating, brand, image)
  - Breadcrumbs everywhere → `BreadcrumbList`
  - Blog posts → `Article` + `FAQPage` (when post has FAQ section)
  - Contact → `LocalBusiness`
- **Open Graph + Twitter Card** on every page (rendered via `<x-seo>` component).
- **XML sitemap** auto-regenerated on product/post publish (`spatie/laravel-sitemap`).
- **Canonical URLs** on every page; no parameter duplication.
- **HTTPS** end-to-end via Let's Encrypt (in production).
- **`hreflang="en-PK"`** as a soft signal for Pakistan-targeting.
- **404 page** with site search + popular products (drops bounce rate).

### Keyword strategy (English, Pakistan-focused)

**Money keywords (transactional, target on product/category pages):**
- `press on nails Pakistan`
- `press on nails Lahore` / `Karachi` / `Islamabad`
- `bridal press on nails Pakistan`
- `custom press on nails Pakistan`
- `buy press on nails online Pakistan`
- `reusable nails Pakistan`

**Long-tail / educational (target on blog):**
- `how to apply press on nails`
- `press on nails vs acrylic which is better`
- `are press on nails reusable`
- `how to remove press on nails without damage`
- `bridal nail trends Pakistan 2026`
- `best nail designs for mehendi`
- `how long do press on nails last`
- `nail care after press on removal`

**Branded (defensive, easy wins):**
- `Nails by Mona` / `Nails by Mona Pakistan`
- `Mona nails Mirpur`
- `Mona Nail Atelier` (once trademark filed)

### On-page SEO (per product page)

- H1 = product name (one per page).
- Title tag: `{Product Name} — Custom-Fit Press-On Nails | Nails by Mona`
- Meta description: 150–160 chars, includes price + USP + CTA.
- Description: 200+ words, natural keyword inclusion, no stuffing.
- FAQ block with 4–6 entries (auto-renders FAQPage schema).
- Internal links: 2–3 to related blog posts + 2–3 to similar products.
- Image alt text: descriptive, keyword-natural ("blush ombre press-on nails with rose-gold charm — Bridal Single tier").

### Off-page SEO

- **Pakistani wedding blogs** (Shaadi Mubarak, etc.) — guest post pitches Q2.
- **Beauty blog backlinks** — pitch tutorials.
- **"Best of" listicles** — proactively reach out to be included in "Best Pakistani press-on brands 2026" listicles.
- **Press releases** for milestones (1000th order, trademark filed) — distribute via Pakistani PR services.
- **Pinterest** — every product pinned with link back; nail content drives genuine traffic.
- **YouTube tutorials** — Mona's voice on application/removal videos with website link.

### Local SEO

- **Google Business Profile** (Mirpur AJK) — even though sales are nationwide, the GBP boosts brand searches. Photos, hours, posts, Q&A.
- **NAP consistency** (Name, Address, Phone) across web, IG bio, GBP, business directories.
- **Pakistani business directories** — hamariweb.com, businesslist.pk, etc. (free listings).

### Realistic SEO timeline + targets

| Phase            | Organic visitors/mo | Notes                                       |
| ---------------- | ------------------- | ------------------------------------------- |
| Months 1–3       | 50–300              | Indexing + first long-tail rankings         |
| Months 4–6       | 500–1,500           | First page rankings on long-tail queries    |
| Months 7–9       | 2,000–5,000         | Authority building, branded search rises    |
| Months 10–12     | 5,000–15,000        | Realistic with consistent blog cadence      |
| Year 2           | 15,000–40,000       | Compounding — blog hits maturity            |

**Don't promise more.** SEO is patient capital.

### SEO admin tooling

- Filament dashboard widget: top 10 blog posts by view count.
- Google Search Console + Bing Webmaster Tools verified at launch.
- Quarterly SEO audit checklist in `docs/seo-audit.md` (write at Phase 5).

---

## 22. Blog Strategy

The blog is the engine that compounds organic traffic into orders without paying for every click.

### Cadence

- **2–3 posts/month** sustained. Less is fine; consistency beats volume.
- **First 5 cornerstone posts at launch** (Phase 4):
  1. *"Press-on Nails vs Acrylics: Which Is Better for Pakistani Brides?"* (target: `press on nails vs acrylic`)
  2. *"How to Apply Press-On Nails — A Foolproof 7-Step Guide"* (target: `how to apply press on nails`)
  3. *"Bridal Nail Trends in Pakistan for 2026"* (target: `bridal nail trends Pakistan 2026`)
  4. *"How to Remove Press-On Nails Without Damaging Your Natural Nails"* (target: `how to remove press on nails`)
  5. *"Can Muslim Women Wear Press-On Nails? Wudu, Nail Polish, and a Simple Solution"* (target: `press on nails wudu`, `can Muslim women wear nail polish`) — **Priority post.** Mona's personal reason for starting the business. Authentic, zero competition on this exact angle, high-intent audience of practicing Muslim women across Pakistan + diaspora. Estimated traffic potential is large; no Pakistani nail brand has written this.

### Categories (`blog_posts.category`)

- **Bridal** — wedding-season content, bridal lookbooks, real-bride stories.
- **Tutorials** — application, removal, care.
- **Trends** — seasonal designs, color trends, charm ideas.
- **Care** — nail health, post-removal recovery.
- **Behind the scenes** — Mona's process, studio updates, customer features.

### Post structure (template)

- 1500–2500 words, 6–10 H2 sections.
- Cover image (real photo, never AI-generated).
- 4–6 in-body images (process photos, examples).
- FAQ section at the end (5+ Q/A) — auto-renders FAQPage schema.
- "Related products" block (3 items) at the bottom — feeds traffic to commerce.
- Author byline: Mona (with face photo) — even if Humza/Claude drafts, Mona reviews + voices.

### Content calendar (`docs/content-calendar.md`)

- Plan 3 months ahead.
- Weight 60% educational/long-tail, 30% bridal, 10% behind-the-scenes.
- October–March (wedding peak): shift to 50% bridal.
- Track: target keyword, publish date, current rank, referrer traffic.

### Realistic blog targets

| Phase            | Posts published | Total organic visits/mo |
| ---------------- | --------------- | ------------------------ |
| Phase 4 launch   | 4 cornerstone   | minimal                  |
| Month 6          | 12–16           | 500–1,500                |
| Month 12         | 28–36           | 5,000–15,000             |
| Year 2           | 60+             | 15,000–40,000            |

### Authoring workflow

- Mona is the credited author, but content is co-produced. Workflow:
  1. Humza/Claude drafts based on keyword research + competitor SERP analysis.
  2. Mona voices (record her reading the draft on WhatsApp; transcribe; rewrite drafts in her cadence).
  3. Mona approves + supplies any process photos.
  4. Publish via Filament with full SEO meta filled in.

---

## 23. Monetization Strategy

### Hard rule: **never display ads on the storefront.**

The math:
- Google AdSense earns ~PKR 50–200 per 1,000 visitors in Pakistan.
- One lost Bridal Trio (PKR 12,000) = 60,000–240,000 page views of ad earnings to break even.
- Ad clutter drops conversion 20–40% — guaranteed loss > speculative gain.
- Premium positioning is incompatible with banner ads. Period.

### Recommended monetization stack

**Primary (storefront — direct revenue):**
1. **Press-on nails** (the core business). PKR 1,800–13,500 per order.
2. **Complementary products** *(launch in Phase 5 or Q2):*
   - Brush-on glue + prep kit (PKR 500–800)
   - Replacement adhesive tabs (PKR 300)
   - Cuticle oil (PKR 600)
   - Charm packs / DIY kits (PKR 1,000–1,500)
   - Gift cards (PKR 2,000 / 5,000 / 10,000 denominations — bridal-shower friendly)
   These are 50–70% margin items and bundle naturally with main orders.

**Secondary (services — Y2):**
3. **Online "Apply Like a Pro" course** — pre-recorded video + PDF guide (PKR 2,500–5,000 one-time). Mona records once, sells forever.
4. **30-min Mona consultation calls** — bridal design consults via Zoom/WhatsApp (PKR 1,500/30min). Limited slots create scarcity.
5. **B2B salon wholesale** — sell ready-made signature sets to salons at trade discount (Year 2+, cautious about brand dilution).

**Tertiary (blog only — modest, tasteful):**
6. **Affiliate links** in blog posts — link to nail-care products on Daraz/Amazon when relevant. Disclosure required. No banners. Expected: 1–3% of blog traffic clicks affiliates → ~PKR 5–20k/mo at 10k blog visitors. Real money but not primary.
7. **Sponsored blog posts** — at most 1/month, clearly labeled, only from on-brand partners (nail tools, beauty courses, bridal photographers). Pricing: PKR 15–40k/post once blog hits 10k monthly visitors.

**Off-site:**
8. **Run paid ads TO the site** (Meta, Google Search, Pinterest). This is a marketing cost in section 20, not revenue — but it's how the website grows.

### What we will NOT do

- ❌ AdSense / display banners on storefront.
- ❌ Affiliate links on product pages (would imply lower-quality alternatives).
- ❌ Pop-up subscribe modals beyond a single tasteful one (10s delay, dismissible).
- ❌ Sell email list / data.

### Revenue mix target — Year 2

- 80% press-on sales (core)
- 12% complementary products
- 5% courses + consultations
- 3% blog affiliates + sponsored content

---

## 24. Photography Strategy

**Core principle:** authenticity beats polish for handmade brands. **Hands and craft only — never any face.**

### Locked rules

1. **Never** show Mona's face. Anywhere.
2. **Never** show a customer's face. Crop at the wrist, forearm, or below shoulders.
3. UGC submissions with a face → admin sets `face_visible = true` → cannot be displayed publicly.
4. Process photos: hands working, tools, paint pots, packaging — no face in frame.
5. Bridal photography: hands on traditional fabrics (red velvet, gold embroidery, henna patterns) — face cropped.
6. About page: replace any founder portrait with a hand-and-tools studio shot + handwritten "Mona" signature SVG.

### How to use existing IG photos

- **Lifestyle / social proof** on home + product pages — only photos that show hands without a face. Anything with a face is excluded.
- **Reels** — embed only Reels that show hands or product close-ups, never on-camera face shots.
- **About / story page** — Mona's first-person story stays. Hero image is a hand portrait with the signature SVG.

### What to shoot fresh (DIY half-day)

~35 canonical shots in one afternoon. Smartphone + ring light + window light. Backdrops: white card, shell-color paper, red velvet remnant. Models own hands only — cropped at the second knuckle or wrist.

### AI image guidelines

| Use AI for                           | Do NOT use AI for                                |
| ------------------------------------ | ------------------------------------------------ |
| Background removal / replacement     | Generating product designs that don't exist      |
| White-balance / exposure cleanup     | Faking customer hands wearing nails              |
| Light shadow consistency             | Generating "lifestyle" photos                    |
| Image upscaling on low-res IG saves  | Creating fictional testimonials                  |

### Phasing

- **Phase 1 launch:** existing IG photos + 1 DIY shoot for product cards.
- **Phase 5 polish:** swap homepage hero + bridal page to professionally-shot images (PKR 15–40k for a Lahore/Karachi photographer; consider Mona traveling once/year).
- **Ongoing:** every new design photographed in DIY style for catalog consistency.

---

## 25. Customer Service & Chatbot Plan

### Phase A — Launch (Months 1–6): zero AI

**WhatsApp Business Quick Replies** handle 80% of repetitive questions. Setup is in WhatsApp Business app (free), no dev work:

- `/sizing` → returns the photo-with-coin instructions + link to size guide.
- `/payment` → returns JazzCash + EasyPaisa + bank details + payment-proof instructions.
- `/leadtime` → "Custom orders ship in 5–9 working days. Bridal Trio: 10–14 days. Order 4 weeks before mehendi."
- `/track` → returns the order tracking link template.
- `/return` → returns the refit policy.
- `/care` → returns care + reuse instructions.

**On the website:**
- FAQ section on every product page (driven by `faqs` table).
- Help page (`/contact`, label "Help") → WhatsApp deep-link with pre-filled brand-addressed message ("Hello Nails by Mona, ...") — never "Hi Mona".
- WhatsApp deep-link is a secondary helper, **not** a primary commerce CTA. Primary commerce path is Bag → Checkout.
- CTAs labeled **"Get help"**, **"Customer care"**, **"Contact us"**. Banned labels: "Ask Mona", "DM Mona", "Talk to Mona".
- No chatbot widget at launch.

### Phase B — Months 6–12: collect data

Mona's WhatsApp DMs become the corpus. **Tag each conversation** by question type for the eventual training set:
- Sizing
- Payment / proof-upload issues
- Lead time
- Stock/availability
- Custom design requests
- Bridal-specific
- Complaints / refit requests
- Compliments / repeat customers

This is just folder labels in WhatsApp, ~30 seconds per conversation. Builds a real corpus.

### Phase C — Phase 8 (Year 2): tone-matched AI chatbot

Once we have ~500–1000 tagged real conversations:

**Tech:**
- Backend: Anthropic Claude API or OpenAI GPT-4 via Laravel HTTP client.
- Cost estimate: PKR 3,000–8,000/mo at 500–2000 conversations/mo.
- Frontend: floating widget (bottom-right), Tailwind + jQuery, opens to a chat panel.
- Conversation persistence in `chat_conversations` + `chat_messages` tables.
- Admin can view all conversations in Filament.

**Tone training (no fine-tune needed):**
- System prompt: ~500 words capturing Mona's tone (warm, Urdu-flavored English, uses "dear" and "ji", patient, friendly, never pushy).
- 8–12 few-shot example conversations from real DMs.

**Hard guardrails:**
- Pricing → always pulls from current `products` table; if uncertain, routes to WhatsApp.
- Sizing advice → routes to WhatsApp confirmation.
- Custom design requests → routes to WhatsApp + creates a `contact_messages` row.
- Refunds / complaints → never auto-handled; routes to WhatsApp + alerts admin.
- Order status questions → looks up by order number; if not found, routes to WhatsApp.

**Customer-facing disclosure:** "Hi! I'm a helpful assistant trained on Mona's voice. For prices, sizing, and orders I'll connect you to Mona on WhatsApp." Honesty preserves trust.

**Why not earlier?** AI hallucinates prices and sizes. For a handmade artisan brand where founder authenticity is the USP, an AI mimicking the founder before we have her real voice data is brand risk > convenience gain.

---

## 26. Logistics & Payment Risk

### Couriers (origin Mirpur AJK)

| Courier      | Use case                                  | Notes                                              |
| ------------ | ----------------------------------------- | -------------------------------------------------- |
| **TCS**      | Bridal / orders > PKR 4,000                | Most reliable for fragile/high-value               |
| **Leopards** | Everyday tier, mass volume                 | Often cheapest; LeoPart Economy ~PKR 400+          |
| **M&P / BlueEx** | Backup nationwide                      | Used by many Shopify stores                        |

Estimated nationwide delivery from Mirpur: 2–4 working days to major cities, 4–6 to remote.

### Payment integration — MVP

**No Cash on Delivery.** Removed 2026-04-30 — industry COD return rates of 12–35% impose too much operational drag for a one-woman studio. Every order is paid before it ships.

**No card option at MVP.** Card payments require a payment gateway (SafePay), and SafePay onboarding adds a 7–14 day Mona-side dependency chain (sole-proprietorship registration → bank account → NTN → KYC → integration). Decided 2026-05-01 to defer the entire gateway to Phase 6 to keep MVP simple and ship-ready. Card is reserved in the `payment_method` enum so Phase 6 needs no migration.

**Payment options at MVP** (all manual, all paid before production starts):

| Method | Flow | Confirmation |
|---|---|---|
| **JazzCash** | Customer sends PKR to listed mobile wallet number, uploads screenshot | Mona verifies in Filament within 24h; sets `payment_status = paid` |
| **EasyPaisa** | Same as JazzCash, different number | Same |
| **Bank Transfer** | Customer transfers to listed bank account, uploads receipt | Same |

Account details for all three methods are stored in `settings` (not hardcoded in templates), rendered server-side on the confirmation page based on the customer's selection.

**Operational risk + mitigation (manual flow):**

| Risk | Mitigation |
|---|---|
| Customer pays but forgets to upload proof | Auto-email at 24h with proof-upload link, again at 48h, auto-cancel at 72h |
| Payment proof unclear / wrong amount | Filament admin marks `payment_status = verifying`; Mona WhatsApps for clarification |
| Customer never pays after placing order | Auto-cancel at 72h with notification email; can be restored if customer reaches out |
| Order not paid in full before production | **Since 2026-09-16 every order is paid in full up front** — there is no partial-advance path, so there's no balance to chase after the set is made. Orders placed before that date keep their quoted advance and the admin's "Balance received" action |
| Mona's verification time creates a bottleneck | Filament Orders kanban surfaces all `payment_status = awaiting` orders sorted by oldest; target SLA: < 24h verification |

---

### Phase 6 — SafePay integration plan *(deferred from MVP, kept here so design work isn't redone)*

When Phase 6 opens, this is the architecture. Stays in CLAUDE.md so future Claude sessions and Mona herself can reference it without rebuilding the design.

**What SafePay delivers:** one Pakistan-local integration covering Card (Visa/MC/UnionPay) + JazzCash + EasyPaisa via either a hosted checkout or an embedded drop-in form. Real-time payment confirmation via webhook removes the manual proof-upload step for those three methods. Bank Transfer remains as a manual fallback for customers who prefer cash-equivalent transfers.

**Pre-conditions** (~7–14 days, Mona-side, can run in parallel with code build):
1. Sole-proprietorship registration
2. Business bank account
3. NTN (National Tax Number) — Pakistani tax registry
4. SafePay merchant onboarding — KYC, business documents, bank verification

**Build scope** (~4–6 days of dev work after pre-conditions clear):
- Laravel package: `safepay/safepay-laravel` or community equivalent — verify at build time.
- Server-side checkout-init endpoint that creates a SafePay order token and returns checkout URL.
- Client-side redirect to SafePay-hosted checkout (or embedded drop-in form) after order details submitted.
- Webhook endpoint `POST /api/safepay/webhook` with HMAC signature verification → updates `payment_status`, transaction reference, completion timestamp.
- Polling fallback: queued job retries SafePay status API every 60s for 30 min if webhook hasn't arrived.
- Decline handling: returns user to step 3 with form state preserved; "Try another method" path.
- Idempotent webhook handler keyed on transaction ID.
- Sandbox testing before production credentials swap.

**Schema additions when Phase 6 lands** (one migration):
- `payment_provider` enum (`manual`, `safepay`)
- `safepay_token` (string, nullable)
- `safepay_transaction_id` (string, nullable, unique)
- `payment_completed_at` (timestamp, nullable)
- Add `failed` to `payment_status` enum

**SafePay transaction fee:** ~2.5–3.5% per transaction (verify at onboarding). Factored into pricing — reduces net margin by 2.5–3.5 percentage points (35–45% margin target in §19 still holds).

**Operational risk + mitigation (SafePay-driven, for the eventual Phase 6 build):**

| Risk | Mitigation |
|---|---|
| SafePay webhook delayed / dropped | Polling fallback as above; manual admin reconciliation in Filament for stuck payments |
| Card declined by issuer | Decline code shown; user swaps method without restarting form |
| Customer abandons mid-payment | Auto-email at 1h with reusable SafePay token (valid 24h); auto-cancel at 72h |
| SafePay outage during checkout | Show clear error + WhatsApp deflection; manual bank-transfer fallback always available |
| Chargebacks / disputes | Industry rate ~0.3–0.7%; SafePay handles dispute UX; track in a `chargebacks` table |

### Returns / damages

- "No returns on custom-made products" — industry standard, accepted.
- **Free first refit** — the trust-builder.
- Damages in transit: replace at cost; require unboxing video.

---

## 27. Bridal Market Specifics

- **Wedding season:** October–March peak; secondary April–May.
- **Off-season** (June–September): build content, run promotions.
- **Mid-range Pakistani wedding** (3 events, 300–400 guests): PKR 3.5–5.5M total. Bridal beauty ~PKR 100–300k. **PKR 12k nail trio is 4–10% of beauty spend — comfortable buy.**
- **Today's options:** plain salon mani, salon acrylics (damaging), or skip nails. **Press-ons are still under-considered as a bridal option.**
- **Word-of-mouth:** sister/cousin/friend referrals dominate. Build a post-wedding referral program.
- **Lead-time messaging:** "Order 4 weeks before mehendi."

### Bridal Trio package

- One fitting (saved profile), one shipment, three coordinated looks.
- **PKR 10,000 flat** (updated 2026-09-11 from the previous 11,000–13,500 range) — a meaningful saving vs three singles (15,000–19,500).
- Premium packaging (rigid magnetic box, satin lining, mini glue + prep kit, handwritten name card).

---

## 28. Diaspora Opportunity (Phase 7 — Year 2+)

**Audience:**
- UK: ~1.59M Pakistani-origin.
- UAE: ~1.7M Pakistanis.
- Germany: ~70–100k (Humza here).
- US/Canada: ~700k combined.

**Why not Phase 1 or 2:** shipping friction (DHL Pakistan→UK 4–7d, ~PKR 4–8k/parcel), nail glue customs scrutiny, FX complexity, card-issuer compatibility outside Pakistan.

**When:** Year 2, after 10k+ IG followers and stable domestic ops.

**Priority:** UK first (largest premium-paying community), UAE second, Germany/US/Canada opportunistic.

**Operating model:**
- Batch ship 1× per month per region.
- Flat rate (~PKR 4,000 UK / 3,500 UAE).
- Advance only.
- Glue shipped separately or sourced locally.

---

## 29. Brand Name & Expansion

### "Nails by Mona" analysis

- **SEO Pakistan:** low competition. ✓
- **Trademark globally:** medium-high collision risk (US salons, IG handles).
- **Pakistan trademark:** no obvious blocker.

### Recommendation

- Keep **"Nails by Mona"** as consumer-facing brand.
- Register a defensible Pakistan trademark on a unique formal mark (legal entity + packaging):
  - **Mona Nail Atelier** (recommended)
  - Mona by Hand
  - Studio Mona PK
- Cost via Pakistan IPO with agent: PKR 10–25k.
- **Buy domains now:** `nailsbymona.pk`, `monanail.pk`, `studiomona.pk`.

### Category extension (Year 2–3)

**Natural extensions:** nail care kits · charms · eyelash strips.
**Avoid:** full cosmetics, skincare (DRAP regulation, brand dilution).

---

## 30. 12-Month Growth Plan

### Q1 (Months 1–3) — Foundation

- Lock positioning, price ladder, photography style guide.
- Launch website with **live-camera sizing** + 4 cornerstone blog posts.
- Codify photo-with-coin SOP.
- Set up WhatsApp Business catalog + Quick Replies.
- Hire 1 part-time helper.
- **Targets:** 2,500 IG followers · 30–40 orders/mo · PKR 90–120k revenue/mo · 200–500 organic site visitors/mo.

### Q2 (Months 4–6) — Bridal launch + content scale

- Launch **Bridal Trio**.
- Begin gifted-set program (5–10 micro-influencers).
- Meta ads PKR 25–40k/mo.
- 8–10 more blog posts published.
- **Targets:** 6,000 IG followers · 60–80 orders/mo · PKR 180–300k/mo · 5+ bridal trios · 1,000–2,500 organic visitors/mo.

### Q3 (Months 7–9) — Operations + wedding prep

- Hire 2nd helper. Capacity → 100+ sets/mo.
- Saved-sizing-profile reorder live.
- Reorder discount + referral program.
- Pre-wedding-season push (October peak).
- **Targets:** 12,000 IG followers · 120–180 orders/mo · PKR 400–650k/mo · 3,000–6,000 organic visitors/mo.

### Q4 (Months 10–12) — Wedding harvest + diaspora pilot

- Capacity-stretch period.
- Soft-launch UK + UAE shipping. 5–15 international orders/mo.
- File trademark application.
- Launch first complementary product (glue + prep kit).
- **Targets:** 18–25k IG followers · 200–300 orders/mo · PKR 700k–1.1M/mo · 15+ bridal trios · 5,000–15,000 organic visitors/mo.

### Year-end metrics to grade against

- Repeat-customer rate **> 25%**
- Bridal trio share of revenue **25–35%**
- Payment-proof upload completion rate **≥ 90%** (orders placed → proof received within 72h before auto-cancel)
- Verification SLA **< 24h** for ≥ 90% of payment proofs (Mona's Filament workload metric)
- Average order value **PKR 2,800–3,500**
- Net margin **35–45%**
- Organic share of traffic **≥ 25%** by month 12

---

# PART 3 — META

## 31. Open Questions

- Production hosting — Forge / Hetzner / DigitalOcean / shared PHP? *(decide before Phase 5)*
- Domain — `nailsbymona.pk` available? Buy now.
- **Phase 6 SafePay onboarding** *(post-launch, not blocking MVP)* — sole-proprietorship registration, business bank account, NTN, SafePay merchant KYC. ~7–14 days lead time. Schedule when MVP is stable and order volume justifies the gateway fees. Architecture in §26.
- Logo — ✅ `Nails by Mona Logo.svg` already in project root. Lavender fill (#bfa4ce).
- Final formal trademark name — Mona Nail Atelier? Mona by Hand? Studio Mona PK? *(decide before Q4 trademark filing)*
- Returns/T&Cs draft — needed before public launch.
- Bridal photoshoot — 2–3 friendly local brides Q2 — Mona to identify.
- Tax / business registration — sole proprietor vs. LLC in Pakistan.
- Email service for transactional + newsletters — Mailgun / Resend / SES *(decide before Phase 1 email setup)*.

---

## 32. Session History

### 2026-04-24 — Planning session

- Confirmed business context, locked tech stack (Laravel + Blade + Tailwind + jQuery + Filament).
- Locked feature scope, visual direction, payments MVP, deferred hosting.
- Plan saved to `/Users/humzasdesign/.claude/plans/claude-create-a-new-scalable-kahan.md`.

### 2026-04-28 — Market research session

- Profiled 8 Pakistani competitors, identified 6 differentiation gaps.
- Defined positioning + pricing ladder including **Bridal Trio**.
- Drafted 12-month growth plan.
- Added `customers` + `customer_sizing_profiles` to data model.
- Added `/bridal`, `/about` to public IA.

### 2026-04-28 (later) — UX & marketing strategy session

- Locked **live-camera + SVG overlay** as the sizing capture UX (with upload fallback). Added section 8 with technical details. Added `sizing_capture_method` to `orders` for analytics.
- Deferred **AI chatbot to Phase 8 (Year 2)**. Added detailed plan in section 25 (3-phase: Quick Replies → data collection → tone-matched AI with strict guardrails).
- Established **monetization principle: never AdSense on storefront**. Stack: complementary products + courses + consultations + selective blog affiliates. Section 23.
- Added full **SEO playbook** (section 21) — technical, keyword strategy, on-page, off-page, local, realistic targets.
- Added **blog strategy** (section 22) — 2–3 posts/month, 4 cornerstone posts at launch, content calendar in `docs/content-calendar.md`.
- Added `blog_posts`, `blog_post_products`, `faqs` to data model.
- Added `/blog`, `/blog/{slug}` to IA. Added `/sitemap.xml`, `/robots.txt`, `/feed.xml`.
- Added Phase 4 (Blog + SEO infrastructure) and reorganized phases.
- Locked English-only content for Y1.
- Coding still not started.

### 2026-04-28 (later) — Design system, logo & icon session

- **Logo split into two variants:** `public/logo-text.svg` (circle removed, text-only wordmark for website) and original `Nails by Mona Logo.svg` (circle version, for stickers/packaging only). Color stays `#bfa4ce`.
- **Created 14 custom thematic SVG icons** in `public/icons/` — nail, sparkle, bridal, sizing, camera, package, heart, star, flower, palette, ribbon, coin, instagram, whatsapp, tiktok.
- **Design direction updated to modern minimal (2025/2026):** floating glass nav, editorial oversized typography, bento grid product layout, card hover scale transitions, section color alternation, section label + H2 + accent rule pattern.
- **`.claude/skills/design-system.md` fully rewritten** — now covers logo, full icon system (custom + Heroicons map), button variants, form inputs, cards, badges, step indicator, nav, section layout patterns, layout tokens, 10 modern design principles, 11 non-negotiable accent rules, and 13 do-nots.
- **CLAUDE.md §10** updated with logo file split, icon system table, and modern design direction summary.
- **CLAUDE.md §12** folder structure updated with `public/icons/` listing.
- Coding still not started.

### 2026-04-28 (later) — Content architecture session

- Built **full page-by-page content architecture** for all 14 public routes. Added section 34.
- **Confirmed color palette: lavender** (`#bfa4ce`) from the actual logo SVG — not rose/blush. Updated section 10, section 2, design-system.md was already correct.
- **Confirmed order form approach: separate URL per step** (reliable on Pakistan mobile, back-button safe, accurate analytics).
- **Confirmed blog cornerstone posts drafted during Phase 4** — real content at launch for immediate SEO.
- **Added `subscribers` table** to data model — simple email capture on blog index, no automation at launch.
- **Added `whatsapp_pending`** to `sizing_capture_method` enum for customers who skip camera/upload.
- **Added `config/couriers.php`** for tracking URL templates (TCS, Leopards, M&P, BlueEx).
- **WhatsApp deep-link strategy** documented — each page gets a contextually pre-filled message.
- **About page content** — Humza to supply Mona's real personal story before Phase 2 build. No placeholder at launch.
- Full architecture plan at `/Users/humzasdesign/.claude/plans/claude-fetch-the-file-witty-creek.md`.

### 2026-04-28 (later) — Mona's story + Islamic angle session

- **Humza supplied Mona's real personal backstory** — childhood art passion, BA in Fine Arts, multi-artisan background (bridal mehndi, paintings, resin/acrylic name plates), 2 years of business building from hobby.
- **Discovered critical new pillar:** Mona started this business because she is a practicing Muslim who wanted beautiful nails but couldn't wear nail polish or acrylics (wudu-incompatible). Press-ons solve this exactly — remove before ablution, reapply after. No competitor owns this angle.
- Added **4th brand pillar: Wudu-compatible** to section 16.
- Added **new objection + counter** in section 18: "Can I wear them as a Muslim?"
- Added **5th cornerstone blog post** to section 22: "Can Muslim Women Wear Press-On Nails? Wudu, Nail Polish, and a Simple Solution" — marked Priority.
- **About page copy drafted** in Mona's authentic first-person voice (see docs/about-page-copy.md — to be created during Phase 1 build). Mona must review and approve before publish.
- Updated section 33 pointer: About page copy is now ready in draft form.

### 2026-04-29 — HTML build begins; guarantee section removed

- Started building standalone HTML pages from the Stitch UI mockup. Files live in `html/` directory.
- **Removed the Guarantee section from the home page.** The 3-day stick guarantee (free replacement nails/kit if they fall off within 72h) is not offered. "Free first refit" for sizing issues remains as a trust bar item only — not a dedicated section.
- Updated §18 objection counter: "Will they fall off?" now points to care guide, not stick guarantee.
- Updated §34 home page sections list accordingly.
- `html/index.html` created: 10 sections (Hero → Trust Bar → Fit Difference → Collection → Bridal Trio → Customer Wall → Maker → How it Works → Pricing → Blog), mobile fixed bottom WhatsApp bar, hamburger menu, `public/icons/logo-text.svg` in nav + footer.

### 2026-04-29 (later) — Design system revision (the "feels cheap" fix)

The first design pass and the Stitch HTML mockup felt amateur. Diagnosed three concrete causes and locked sweeping revisions before any production code is written.

**What was wrong:**
1. Lavender-tinted neutrals (`cream` `#FDFBFE`, `cloud` `#F7F3FB`, `ink` `#1C1727` all carried purple) — bathed everything in the brand color so the accent stopped being an accent.
2. Cormorant Garamond + Inter — the default beauty/wellness DTC pairing; no distinctive character.
3. The Stitch HTML mockup mixed Material Design 3 tokens with the custom lavender tokens — visual incoherence read as cheap.

**Locked revisions:**
- **New palette** — warm neutral system (`bone` `#F4EFE8` page, `paper` `#FBF8F2` cards, `shell` `#EAE3D9` alt sections, warm charcoal text scale `ink/graphite/stone/ash/hairline`) with lavender (`#BFA4CE`) restricted to 8 specific accent uses (CTAs, focus rings, prices, active nav underline, step indicator, accent rule under H2, selected payment tile, eyebrow above H2). Removed: `cream`, `cloud`, `lavenderFaint`, `lavenderLight`, `lavenderDeep`, `body`, `mute`, `subtle`.
- **New typography** — **Fraunces** (variable serif, Stripe / Vercel-tier) for display + **DM Sans** for body. Replaces Cormorant + Inter.
- **Hybrid nav** — desktop ≥1024px shows 5 visible links centered (Shop · Bridal · About · Journal · Help) + bag icon top-right; mobile/tablet <1024px = hamburger → full-screen overlay with editorial-sized links + secondary links + Instagram/WhatsApp footer icons.
- **No "Order Now" button anywhere.** Bag icon in nav is the only commerce CTA in the header.
- **Bag drawer pattern** — right-side `<aside>`, persists in `localStorage` (`nbm.bag` key), feeds the existing multi-step order flow at checkout.
- **`/gallery` page removed.** UGC consolidated into Home (Worn across Pakistan section), Product detail (UGC carousel), Bridal (real-bride hands gallery). New `ugc_photos` table replaces `gallery_items`, with `face_visible` flag and `placement` enum.
- **Hand-only photography rule (locked).** Mona's face never appears anywhere on the website. Customer faces never appear (cropped at wrist). `face_visible = true` UGC cannot be displayed publicly. About page hero swapped from face portrait to hand-portrait + handwritten "Mona" signature SVG.
- **No "Ask Mona" copy.** All WhatsApp pre-fills brand-addressed ("Hello Nails by Mona, ..."). CTA labels are "Get help", "Customer care", "Contact us" — never personal.
- **Nav label changes:** "Blog" → "Journal", "Contact" → "Help" (routes unchanged).
- **Icon consolidation:** Phosphor (light weight) for utility icons, replaces Heroicons + Material Symbols. The 14 custom thematic SVGs in `public/icons/` are kept and standardized to 1.5px stroke, 24×24 viewBox, `currentColor`.
- **Updated files:** `.claude/skills/design-system.md` rewritten in full. CLAUDE.md §5, §6 (gallery_items → ugc_photos), §7, §10, §16, §24, §25, §34. The Stitch HTML mockup needs corresponding rewrite.
- Plan file: `/Users/humzasdesign/.claude/plans/claude-create-a-new-scalable-kahan.md` was updated with the full revision spec and approved by Humza.

### 2026-04-30 — UX research deliverables + payment-method change

Two changes locked in this session:

**1. UX research layer added.** New folder `docs/ux/` with 13 portfolio-grade artifacts (case study, personas, JTBD, empathy maps, journey maps, user flows, service blueprint, pain-points-opportunities, card-sort plan, usability test plan, accessibility checklist, UX principles, README). Each file structured as a self-contained case-study deliverable with problem framing, methodology, design rationale, success metrics, and reflection — usable as portfolio material and as a build spec. Three personas: **Sana** (Lahore working pro), **Hira** (Karachi bride), **Ayesha** (Islamabad practicing Muslim — wudu-friendly persona, Mona's own origin story user). Diaspora persona deferred to Y2.

**2. COD dropped; SafePay promoted from Phase 5 to MVP.** Cash on Delivery removed entirely — industry return rates of 12–35% impose too much operational drag for a one-woman studio. Replaced with a real Pakistan-local payment gateway: **SafePay integrated at MVP** (was Phase 5). Customer enters card / JazzCash / EasyPaisa details directly in the SafePay-hosted checkout; webhook fires back to mark the order paid in real time. Bank Transfer remains as a manual proof-upload alternative for customers who prefer to send PKR directly. Build cost: **+4–6 days on Phase 2** (was 3 days, now 7–9). Pushes launch by ~1 week.

**Why Path B over the manual card-link option** (considered earlier in this session): manual card-link sends scale poorly (Mona has to be on her phone within 1h of every order), creates a bad UX for the customer (waiting after order placement is anxiety-inducing — see UX journey maps), and would have to be replaced with SafePay in Phase 5 anyway. Doing the integration once, at MVP, is cleaner and removes a known operational bottleneck before launch.

**Files updated by the COD→SafePay change:** CLAUDE.md (§2, §6 data model, §7, §11 phases, §18, §25, §26, §28, §30, §34), `docs/pages/00-global.md`, `docs/pages/02-shop.md`, `docs/pages/03-product-detail.md`, `docs/pages/07-contact.md`, `docs/pages/11-order-form.md`, `docs/pages/13-order-confirmation.md`, `docs/ux/00-case-study.md`, `docs/ux/05-user-flows.md` (Flow 6 rewritten as SafePay flow), `docs/ux/06-service-blueprint.md` (Handoff 2 rewritten), `docs/ux/07-pain-points-opportunities.md` (#18 reshaped), `docs/ux/08-card-sort-ia-validation.md`, `docs/ux/09-usability-testing-plan.md`.

**Pre-launch blockers added by SafePay decision** (track in §31 Open Questions): sole-proprietorship registration, business bank account, NTN registration, SafePay merchant onboarding (~7–14 days for KYC). These are Mona-side tasks; without them, SafePay can't go live, and without SafePay, MVP can't launch.

### 2026-05-01 — SafePay deferred back to Phase 6; MVP stays manual

Reversed the previous day's decision. **SafePay is no longer in MVP scope** — it's back at Phase 6 (post-launch). The reasoning, captured so future sessions don't relitigate:

- The 7–14 day Mona-side dependency chain (sole proprietorship → bank → NTN → KYC) added pre-launch friction that has nothing to do with design or build velocity. Wrong place to spend complexity for a project still pre-build and pre-photography.
- Manual JazzCash + EasyPaisa + Bank Transfer with proof upload is the actual Pakistani SMB norm for shops at this scale. Customers know the pattern; Mona already runs it via DM today. No customer-side learning required.
- The 4–6 days of build saved goes to higher-leverage MVP work: photography, the live-camera flow, blog cornerstones, status emails.
- SafePay architecture (HMAC webhook, idempotent handler, polling fallback, decline retry, schema additions) is preserved in §26 as the Phase 6 plan — no design work is wasted.

**What changed:**
- Card option removed from MVP UI. `payment_method` enum keeps `card` reserved for Phase 6.
- SafePay-specific schema fields (`payment_provider`, `safepay_token`, `safepay_transaction_id`, `payment_completed_at`, `failed` status) **dropped from §6 data model**. Phase 6 will add them via a single migration when it lands.
- Phase 2 build budget back to 3 days (was 7–9). Phase 6 restored as "Payment gateway (post-launch)."
- Pre-launch blockers (sole proprietorship, KYC, etc.) moved out of §31 critical-path and into a "Phase 6 prep" entry.
- §15 SafePay webhook security note moved into a "deferred for Phase 6" parenthetical.

**What stays:**
- **No COD** (the 2026-04-30 decision holds — has nothing to do with SafePay).
- Three-radio MVP payment UI: JazzCash · EasyPaisa · Bank Transfer.
- Manual payment-proof upload flow on `/order/confirm/{order}`. Mona verifies in Filament within 24h.

**Files reverted in this session:** CLAUDE.md (§2, §6, §7, §11, §15, §26 restructured, §30, §31, §34); `docs/pages/00-global.md`, `02-shop.md`, `03-product-detail.md`, `07-contact.md`, `11-order-form.md`, `13-order-confirmation.md`; `docs/ux/00-case-study.md`, `05-user-flows.md` (Flow 6 → Manual payment verification), `06-service-blueprint.md` (Stage 7 + Handoff 2 manual), `07-pain-points-opportunities.md` (#18 reverted), `08-card-sort-ia-validation.md`, `09-usability-testing-plan.md`.

### 2026-05-07 — Champagne Wedding palette + home-page polish pass

Two design moves locked in this session:

**1. Bridal section + footer palette refit (Champagne Wedding direction).** Previous Sonnet pass had set bridal bg to `#F8EBD0` butterscotch and footer to `#1C3225` forest green. Both colors were fine in isolation but introduced new hue families with no relationship to the lavender brand accent — that's why the page felt off. Replaced with:
- `bridalBg` `#EDE2C8` (champagne — saturated warm shell, evokes gold thread)
- `footerBg` `#2C1F2E` (deep aubergine — derives from lavender at low lightness)
- Plus two new bridal-only accent tokens: `gold` `#B8924A` and `goldDeep` `#8F6F37`
- Hero dark-fallback gradient also moved into the plum family for consistency

The principle: every "feature" color must derive from either the lavender or the warm-neutral system. No new hue families allowed.

**2. Senior-level home-page polish pass on `html/index.html`** (Tier 1 + Tier 2 of an 8-item refinement audit):
- **T1.1** — Hero `<h1>` simplified from 4 hard-coded `<br>` tags to one `<br>` + `max-w-[14ch]` so it reflows gracefully.
- **T1.2** — Studio quotes converted to smart curly quotes (`&ldquo;` / `&rdquo;`) with thin-space + em-dash (`&thinsp;&mdash;&thinsp;`). Atelier-grade typography signal.
- **T1.3** — Pricing section gained the eyebrow → H2 → accent-rule pattern (was missing); table headers swapped from `text-ash` to `text-stone` to fix WCAG AA contrast failure on `bg-paper`. H2 changed from "Investment in your style." to "Sets for every occasion." for brand-voice consistency.
- **T1.4** — Tier badges differentiated per tier: Signature `bg-shell/95 text-graphite`, Glam `bg-graphite/95 text-bone`, Bridal `bg-gold/95 text-ink` (premiering the new gold token). Customer can now scan-distinguish tiers.
- **T1.5** — Hero overlay gradient softened from `from-ink/55` to `from-ink/35`; second overlay reduced from `from-ink/40` to `from-ink/15`. Hand photo (the brand asset) reads through clearly now.
- **T2.6** — Hero `min-h-[92vh]` reduced to `min-h-[80vh] md:min-h-[88vh]`. Saves a screen-height of scroll, surfaces trust signals faster on laptops.
- **T2.7** — Phone mockup screen swapped hardcoded `from-[#2A2420] to-[#1A1614]` for `from-graphite to-ink` tokens; bezel `border-graphite` (invisible) → `border-stone/40` (visible).
- **T2.10** — Bridal image fallback gradient now uses the gold token family (`bridalBg → mid-gold → gold`) instead of generic hardcoded gold values. Prevents drift as palette evolves.

**Deferred to build phase** (also called out in the audit): focus-trap + focus-restore for bag drawer and mobile menu (WCAG 2.2 issue), inline footer hover handlers replaced with Tailwind opacity classes, `loading="lazy"` on below-fold images, hover-only UGC city labels accessibility.

**Files updated:** `html/index.html` (8 surgical edits), `docs/pages/00-global.md` (added bridalBg/footerBg/gold/goldDeep tokens to palette table), `docs/pages/01-home.md` (tier-badge palette + bridal image gradient documented with the new gold tokens).

### 2026-05-07 (later) — Sizing capture redesign: 1 photo → 2 close-ups

Humza shared two reference images (10₹ coin held above 4 fingers laid flat; coin above an extended thumb) showing the macro geometry that actually lets Mona read per-nail width off the coin. The previous wrist-out full-hand-with-coin photo gave perspective but not nail-bed accuracy. **Switched the sizing flow from one full-hand photo to two close-up photos — fingers row + thumb — of one hand.** Optional opt-in at preview time to add 2 more photos for the other hand if the customer wants a perfect fit.

**Decisions locked in this session:**
- **Alignment logic:** heuristics (brightness + edge contrast) + manual confirm. SVG overlay border turns green when the region looks correct, red otherwise. User taps shutter when ready — no auto-capture. Phase 5 can upgrade to TensorFlow.js hand-landmarks if mis-shoot rate exceeds 8%.
- **Capture sequence:** single camera session, one URL `/order/sizing-capture`, internal state machine (Fingers → Thumb → Preview → optional Other-hand fingers → Other-hand thumb → 4-thumbnail Preview → Submit). Camera permission requested **once** for the whole session.
- **Asymmetry safety net:** 2 photos required at MVP. Persistent disclaimer on the size-guide page + inline disclaimer on the preview screen. Opt-in "Add my other hand →" at preview reveals 2 more capture slots for the perfectionist customer. Free first refit covers the rest.

**Schema changes (§6):**
- `order_sizing_photos`: added `photo_type` enum (`fingers`, `thumb`, `fingers_other`, `thumb_other`). Order now has 2 rows by default, 4 if opt-in.
- `customer_sizing_profiles`: dropped `photo_path` column.
- New `customer_sizing_photos` table — 1:N relationship to profiles with the same `photo_type` enum. Cleaner than nullable `_other` columns.

**Build cost:** Phase 2 budget bumped from 4–6 days to 4–8 days. Worth it — the macro accuracy should drop refit rate enough to repay the build week within the first 30 orders.

**Files updated:** CLAUDE.md (§2, §6, §8, §11, §32, §34); `docs/pages/12-sizing-capture.md` (full rewrite), `docs/pages/06-size-guide.md` (full rewrite), `docs/pages/11-order-form.md`, `docs/pages/13-order-confirmation.md`, `docs/pages/01-home.md`; `docs/ux/05-user-flows.md` (Flow 2 + Flow 3 + Flow 4 patches), `docs/ux/04-journey-maps.md`, `docs/ux/06-service-blueprint.md`, `docs/ux/07-pain-points-opportunities.md`, `docs/ux/00-case-study.md`, `docs/ux/02-jobs-to-be-done.md`; `html/index.html` Section 3 (phone mockup SVG redrawn to fingers-row overlay + copy updated).

### 2026-05-09 — Secondary HTML mockups brought in line with the 2-photo sizing flow

Two days after the sizing redesign locked in the canonical specs, swept through the remaining static HTML mockups in `html/` to clear stale 1-photo references. Now every customer-facing surface in the project tells the same story: 2 close-ups (fingers + thumb), optional opt-in for 2 more, free first refit if anything doesn't sit right.

**Files updated:**
- **`html/size-guide.html`** — substantive rewrite. Hero H1 ("90 seconds" not "two minutes"), meta description, hero subheadline, "Before you start" coin section (now any coin, not Rs. 5 specific), entire 4-step section restructured around the 2-photo flow (Get ready → Photo 1 fingers → Photo 2 thumb → Optional other hand + checklist), 6 good/bad example captions, in-app camera guide copy, returning-customer copy, "not sure" copy, all WhatsApp pre-fills.
- **`html/shop.html`** — meta description + sidebar trust line.
- **`html/contact.html`** — sizing-help FAQ entry + answer.
- **`html/bridal.html`** — bridal FAQ rewrite (now mentions both photos + bridal-tier opt-in for perfect fit).
- **`html/about.html`** — process step "You share your sizing photos" + Mona's "every pair of sizing photos I receive" line.

**What was deferred from the 2026-05-07 session:** the call-out at the end of that entry flagged the secondary HTML mockups as "snapshots not yet rebuilt against the new specs." This session closes that gap. All canonical specs (CLAUDE.md, `docs/pages/*.md`, `docs/ux/*.md`) and all static HTML mockups now agree.

**Build implication:** when Phase 2 starts the real Laravel + Blade build, the SVG overlay assets it needs are documented in CLAUDE.md §8 (state machine) and `docs/pages/12-sizing-capture.md` (per-state overlay specs). The HTML mockups now serve as visual reference for the 2-photo flow rather than the legacy 1-photo flow they previously documented.

### 2026-05-10 — Phase 0 + Phase 1 Laravel Blade build complete

**Phase 0 confirmed complete.** Laravel 11 scaffold with Tailwind v4 CSS-based config (`@theme` block — not `tailwind.config.js`), jQuery, Filament, `intervention/image`, `spatie/laravel-settings`, `spatie/laravel-sitemap`. All custom palette tokens (`bone`, `paper`, `shell`, `ink`, `graphite`, `stone`, `ash`, `hairline`, `lavender`, `lavender-dark`, `lavender-wash`, `lavender-ink`, `bridal-bg`, `gold`, `gold-deep`, `footer-bg`) and font tokens confirmed in Vite-compiled CSS.

**Phase 1 complete.** All 9 public marketing Blade views plus 4 order-flow stub views created, tested, and returning HTTP 200. Key infrastructure built:

- **`resources/views/layouts/app.blade.php`** — sticky glass nav, 5-link centered desktop nav, mobile full-screen overlay (hamburger), bag drawer (right-side `<aside>`, localStorage `nbm.bag` key), `window.NbmBag` global API (`add`, `get`, `save`, `open`), active nav state via route matching, dark aubergine footer.
- **`resources/views/components/seo.blade.php`** — accepts `:schema` (JSON string), outputs title, meta, OG, Twitter Card, hreflang, canonical, page-specific JSON-LD, plus always-present Organization JSON-LD.
- **All schemas** use `@graph` array pattern — merges multiple schema types (e.g. Article + FAQPage + BreadcrumbList) into a single `:schema` string.

**Blade bugs found and fixed during this build — critical pattern for Phase 2:**

| Bug | Fix |
|---|---|
| Agent put `@php` blocks inside `@push('head')`, then closed with `@endsection` instead of `@endpush` → "Cannot end a section without first starting one" | `@push` contains CSS `<style>` only; `@php` blocks live outside any push/section |
| `:schemas="$schemas"` (plural) → unknown prop, schemas not output | `:schema="$schema"` (singular, JSON string via `json_encode`) |
| `route('blog.show', ...)` → "Route not defined" | `route('blog.post', ...)` — correct name from `web.php` |
| `route('order.sizing-capture')` → "Route not defined" | `route('order.sizing')` — correct name from `web.php` |
| `route('order.track.lookup')` → "Route not defined" | `url('/order/track')` — tracking requires UUID, no generic lookup route |
| `session('success')` in contact view | `session('contact_success')` — matches the flash key set in the route handler |
| `window.NbmBag.get()` / `.save()` / `.open()` not defined on product page | Added all three methods to the `NbmBag` global in `layouts/app.blade.php` |

**Route names confirmed (from `routes/web.php`) — use these in Phase 2:**
`home` · `shop` · `shop.show` (takes `{slug}`) · `bridal` · `size-guide` · `about` · `contact` · `contact.submit` (POST) · `blog` · `blog.post` (takes `{slug}`) · `order.start` · `order.sizing` · `order.confirm` (takes `{order}`) · `order.track` (takes `{order}`)

**All 13 routes verified HTTP 200.** Phase 2 stubs are in place. HTML mockups in `html/` are the visual reference for Phase 2 implementation.

---

### 2026-05-09 (later) — All 13 HTML mockup pages complete; homepage renamed; product.html confirmed

**All static HTML mockups are now complete.** This session finished the remaining pages:

**`html/sizing-capture.html` (created)** — real working `getUserMedia` camera prototype with a full jQuery + vanilla JS state machine:
- State A (Explainer): pre-camera copy + "Start camera" CTA. Permission requested once when the user taps, not on page load (reduces decline rate).
- States B+C (Camera view): shared `<video>` element with stream kept alive across photo transitions. SVG overlays swap per photo type — fingers overlay (4 dashed `<rect>` elements in a horizontal row + lavender `<circle>` above the middle finger) and thumb overlay (1 tall dashed `<rect>` + `<circle>` above the thumbnail). 500ms canvas brightness sampling (80×60 downsampled) shows a green/amber pill. Sobel-style edge-contrast heuristic (ratio >0.12 = green, <0.06 = red, else amber) animates an alignment border around the SVG overlay. Shutter shows a `.pulse-ring` CSS animation when alignment is green. User taps when ready — no auto-capture.
- State D (Preview): two side-by-side thumbnails with per-photo "Retake" links. Symmetry disclaimer. "Add my other hand →" opt-in reveals a second pass through States B+C (overlay labels update to "Photo 3 of 4" / "Photo 4 of 4"), then a 2×2 four-thumbnail preview.
- State E (Fallback): triggered on `getUserMedia` permission denied or API unavailability. Shows 2 labelled file inputs + 4 good-example thumbnail strip. Same "Add my other hand" opt-in reveals 2 more file inputs.
- Submit: 1.5s spinner → `window.location.href = 'order-form.html#step-2'`. `order-form.html` checks `window.location.hash` on load and jumps to step 2 if present.
- No main nav, no bag drawer, logo-only header + WhatsApp "Need help?" link.

**`html/order-confirmation.html` (created)** — transactional confirmation page:
- Logo-only header with WhatsApp "Send my payment proof" pre-filled link.
- CSS keyframe animated lavender checkmark (`scale(0.5) opacity:0` → `scale(1.08)` → `scale(1.0)`). H1: "Your order is placed, dear. Thank you."
- Order summary card (`bg-shell rounded-2xl`): product row, price breakdown, delivery address, estimated dispatch.
- JazzCash payment card with account details in an inner `bg-paper` block.
- Drag-and-drop upload zone (jQuery `dragover`/`dragleave`/`drop`/`change`): 8MB validation, image preview via `FileReader`, simulated 1.6s upload → success strip with file thumbnail.
- "What happens next" 3-step timeline with connector lines.
- "Track this order" → `order-tracking.html` · "Continue shopping" → `shop.html`.
- Minimal copyright-only footer (no full dark footer on this `noindex` page).

**`html/order-tracking.html` (created)** — the one order-flow page with a full standard nav + bag drawer (tracking is accessed from email/bookmarks, not mid-checkout):
- Centred 480px lookup card: order number + contact fields. "Find my order" button. jQuery lookup: 1.2s simulated delay, accepts any input containing "NBM", shows `#lookup-error` card otherwise.
- On valid lookup: lookup section hides, tracking section reveals. Order header card (`bg-shell rounded-2xl`).
- 5-node vertical timeline in "In Production" demo state: nodes 1+2 filled lavender with white checkmarks, node 3 has pulsing `tlPulse` CSS animation (box-shadow rings), nodes 4+5 grey empty circles with `opacity-50` content. Connector divs between nodes are lavender for done connections, `#E0D9CE` for future.
- Always-visible WhatsApp help strip.
- Full dark aubergine footer (`#2C1F2E`) matching all marketing pages.

**`html/product.html` (already existed — confirmed complete)** — discovered this page was fully built in a prior session (642 lines). The user was unaware. Contains: 2-column layout (3-thumbnail gallery left with jQuery swap, product info right), tier badge, lavender price, "Add to bag" → localStorage + bag drawer, "Get help" WhatsApp ghost link, tab accordion (About/Sizing & Fit/Care & Reuse), FAQ accordion (5 questions), related products (3 cards), related blog posts (2 cards). Fixed absolute path hrefs (`href="/"` → `href="home.html"`, `/order/track` → `order-tracking.html`, `/order/start` → `order-form.html`, `/privacy`/`/terms`/`/shipping` → `#`).

**Homepage renamed `html/index.html` → `html/home.html`.** All internal `href="index.html"` references across all 10 affected HTML files updated via bulk sed/Python replacement.

**Complete HTML mockup inventory (all 13 pages done):**
`home.html` · `shop.html` · `product.html` · `bridal.html` · `size-guide.html` · `about.html` · `contact.html` · `blog.html` · `blog-post.html` · `order-form.html` · `sizing-capture.html` · `order-confirmation.html` · `order-tracking.html`

The entire static prototype layer is now finished. Phase 1 Laravel scaffold can begin whenever Humza is ready.

---

### 2026-05-13 — Phase 3 Filament admin complete + sizing SVGs + payment radio fix

**Phase 3 Filament admin complete.** All 7 Filament resources, 2 dashboard widgets, Settings page, and seeder built and working.

**Filament v4 API patterns (discovered during Phase 3 — critical for Phase 2 build):**

| Issue | Correct Pattern |
|---|---|
| `form(Form $form)` signature | `form(Schema $schema): Schema` using `use Filament\Schemas\Schema` |
| `infolist(Infolist $infolist)` signature | `infolist(Schema $schema): Schema` — same Schema class |
| `?string $navigationIcon` | `string | \BackedEnum | null $navigationIcon` |
| `?string $navigationGroup` | `string | \UnitEnum | null $navigationGroup` |
| `static string $view` | `string $view` (non-static — parent is non-static in Filament v4) |
| Inside form/infolist | `$schema->components([...])` OR `$schema->schema([...])` — both work as aliases |

**Settings migration order (Spatie):** Publish the package's own migration first (`php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations" && php artisan migrate`) before running custom settings migrations, or the `settings` table won't exist.

**Resources built:**
- `OrderResource` — kanban-style status, row actions: confirm → in_production → ship (with tracking input) → deliver, navigation badge (New count)
- `ProductResource` — full CRUD, image upload, slug auto-fill, SEO section, `->reorderable('sort_order')`
- `CustomerResource` — table + infolist + edit form, saved-sizing indicator
- `UgcPhotoResource` — `face_visible` boolean shown as danger/success badge; face_visible=true = never publicly shown
- `BlogPostResource` — RichEditor, related products via `->relationship('products', 'name')->multiple()`, auto-fill slug + meta_title
- `FaqResource` — `->reorderable('sort_order')`, categories
- `ContactMessageResource` — no create page, navigation badge (unread count), auto-marks read on ViewPage open

**Widgets:** `OrderStatsWidget` (4 Stat cards: orders today, week revenue, awaiting payment, in production) · `RecentOrdersWidget` (last 10 orders, full-width table)

**Seeder:** `admin@nailsbymona.test` / `password` · 9 demo products (Everyday through BridalTrio), all idempotent via `firstOrCreate`.

---

**Sizing SVGs created** — two new canonical SVG assets for the nail sizing camera overlay:

- **`public/icons/sizing-fingers.svg`** — 4 dashed U-shaped finger outlines (pinky leftmost/shortest → ring → middle tallest → index), coin circle (₨) above the middle finger. ViewBox `0 0 400 480`. Lavender stroke `#BFA4CE`, `stroke-dasharray="10 6"`. Matches the visual design of `Assets/nails_sizing.svg` (the reference file from Humza) in a clean hand-coded form.
- **`public/icons/sizing-thumb.svg`** — single wider dashed U-shaped thumb outline, coin circle (₨) above the thumbnail. ViewBox `0 0 300 480`. Same stroke style.

**Files updated with sizing SVGs:**
- `html/home.html` — phone mockup inline SVG (fit-difference section, "Photo 1 of 2" camera screen): replaced the old `<rect>` finger shapes with proper U-shaped `<path>` elements matching `sizing-fingers.svg` geometry exactly. ViewBox updated to `0 0 140 156`.
- `html/size-guide.html` — Step 2 (Photo 1: Fingers): Google AI placeholder photo replaced with `<img src="../public/icons/sizing-fingers.svg">` centered on a `bg-shell` panel. Step 3 (Photo 2: Thumb): same replacement with `sizing-thumb.svg`. Live-camera phone mockup (bottom "Use our in-app camera guide" section): old verbose path SVG replaced with the same U-shape overlay design + green alignment border + progress strip + brightness pill + shutter button, matching the home.html phone mockup exactly.

**Payment radio indicator fix (`html/order-form.html`):**
- Problem: JazzCash had a hardcoded selected dot, but EasyPaisa and Bank Transfer used ad-hoc classes (`ep-indicator`, `bank-indicator`) with empty jQuery that never injected the dot on selection. Result: selecting EasyPaisa or Bank Transfer showed no indicator dot.
- Fix: Unified all three indicator divs to class `payment-radio-indicator`. Updated the jQuery payment handler to: (1) reset all `payment-radio-indicator` elements to `border-hairline` + `.empty()`, then (2) on the selected card inject `border-lavender` + `<div class="w-2.5 h-2.5 rounded-full bg-lavender">` — the same filled-dot used as the JazzCash default.

---

### 2026-05-14 — Desktop camera handoff (QR code / phone redirect) + SVG overlay fixes

**Session split into two parts:**

---

**Part 1 — Desktop camera handoff**

**Problem:** A laptop webcam faces the user — it cannot photograph nails close up overhead. Any desktop/laptop visitor who clicked "Take a photo with my guide" on the sizing capture page would be shown a live camera feed of their own face, which is both useless for sizing and a bad UX.

**Solution locked:** When a desktop/laptop device is detected, the sizing-capture page shows a **"Open this on your phone" handoff state** instead of the camera explainer. The camera explainer is still shown normally for mobile devices.

**What was built:**

**`html/sizing-capture.html` + `resources/views/order/sizing-capture.blade.php`:**
- New `#state-desktop` div added before the explainer state. Contains: phone icon, heading "Open this on your phone.", body copy explaining the webcam limitation, QR code (generated client-side by `qrcode.js` CDN), WhatsApp share link, copy-link button, "Upload photos from this computer instead →" fallback, and an **"On a phone but seeing this screen? Use this device's camera →"** escape hatch for false-positives.
- `isDesktopDevice()` JS function — see detection logic below.
- On desktop: `#state-explainer` loses `active`; `#state-desktop` gains `active`. Camera init (`NbmCamera.init`) is skipped entirely via `return` — no camera permission request, no stream opened.
- QR URL resolution: `file://` / `localhost` URLs fall back to `https://nailsbymona.com/order/sizing-capture`; live URLs use `window.location.href` directly.
- Upload-from-desktop path activates the existing fallback/upload state.

**`html/order-form.html` + `resources/views/order/start.blade.php`:**
- Camera option card (Option A) contains a `#camera-desktop-note` div (hidden by default). On desktop detection it reveals as a flex row with a warning icon: "On a laptop or desktop? We'll show you a QR code — scan it to open the camera guide on your phone instead."

---

**Part 2 — QR blank + detection false-positive fix (tested live via Cloudflare tunnel)**

During live testing on iPhone via `https://*.trycloudflare.com`, two bugs appeared:

**Bug 1: QR code blank.** The original implementation used `api.qrserver.com` (external image API). On some networks the image failed to load silently, leaving a blank white box.
- **Fix:** Replaced with `qrcode.js` (CDN: `cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js`). QR is now generated entirely client-side via `new QRCode(container, { text, width, height, colorDark, colorLight, correctLevel })`. No external image request, no network failure possible.

**Bug 2: iPhone showing desktop state.** iPhones with Safari's "Request Desktop Website" setting enabled change their UA string to a Mac desktop string (removing "iPhone") but keep `navigator.maxTouchPoints > 0`. The original detection used `window.innerWidth` which Safari inflates to 1024+ in that mode — causing `isDesktopDevice()` to return `true` on a phone.
- **Fix:** Detection now uses `window.screen.width` (physical screen CSS pixel width) which is NOT inflated by "Request Desktop Website" mode. Phones max out at ~430px; anything under 768 is treated as mobile regardless of UA. A touch device with `screen.width < 1200` is also treated as mobile.

**Final detection logic (identical in all 4 files):**
```js
function isDesktopDevice() {
  // Mobile UA is the strongest signal
  if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
    return false;
  }
  // screen.width = physical screen size, not inflated by "Request Desktop Website"
  if (window.screen && window.screen.width < 768) return false;
  var hasTouch = ('ontouchstart' in window) || (navigator.maxTouchPoints > 0);
  if (hasTouch && window.screen && window.screen.width < 1200) return false;
  return true;
}
```

**Bug 3: Finger heights too uniform in camera overlay.** The pinky was 71% of the middle finger height in the camera SVG — visually hard to distinguish from the other fingers. The original reference SVG (uploaded by Humza) showed a much more dramatic height difference.
- **Fix:** Pinky reduced to **~55% of middle** (was 71%), ring to ~82%, index to ~79%. Applied to all 3 SVG surfaces:

| Surface | ViewBox | Middle height | Pinky before → after |
|---|---|---|---|
| `html/sizing-capture.html` camera overlay | 480×370 | 245px | 175px → 135px |
| `sizing-capture.blade.php` camera overlay | 400×305 | 180px | 137px → 99px |
| `public/icons/sizing-fingers.svg` illustration | 400×480 | 260px | 160px → 130px (50%) |

**Testing infrastructure used in this session:**
- `python3 -m http.server 8080 --directory "Nails Website"` → serves all HTML mockups as a static site
- `cloudflared tunnel --url http://localhost:8080 --no-autoupdate` → creates a public HTTPS URL (`https://*.trycloudflare.com`) that the phone can access and that satisfies the browser's HTTPS requirement for `getUserMedia`. No account needed.
- This is the standard local → mobile testing workflow for this project going forward.

**Files updated:** `html/sizing-capture.html`, `html/order-form.html`, `resources/views/order/sizing-capture.blade.php`, `resources/views/order/start.blade.php`, `public/icons/sizing-fingers.svg`, `CLAUDE.md` (this entry).

---

### 2026-05-14 (later) — Backend wiring: product page, shop, admin, contact form, UGC, bag clear

Continuation session completing the database wiring for all public-facing pages. All changes in the main Laravel project (`/Users/humzasdesign/Desktop/Nails Website/`).

**`resources/views/product.blade.php` — fully dynamic (was 430 lines of hardcoded data):**
- `@php` block computes `$tierValue`, `$tierLabel`, `$badgeClass`, `$imgSrc`, `$schemaAvailability`, `$waText`, `$leadTime`, `$leadMin`, `$stockLabel` from the `$product` model.
- Gallery thumbnails read from `$product->images` relationship.
- Related products use `@foreach($related as $rp)` with dynamic badge classes per tier.
- Add to bag button passes `data-image="{{ $imgSrc }}"`.

**`app/Http/Controllers/ShopController.php` — `show()` now passes `$related`:**
```php
$related = Product::where('is_active', true)->where('id', '!=', $product->id)
    ->orderBy('sort_order')->orderBy('created_at')->limit(3)->get();
return view('product', compact('product', 'related'));
```

**`app/Filament/Resources/ProductResource.php` — added `->disk('public')` to `ImageColumn` and `FileUpload`.** Filament v4 defaults to the `local` disk (Laravel 11 maps this to `storage/app/private/`) — without `->disk('public')`, uploads go to `private/` and `asset('storage/...')` URLs 404. Same fix applied to `UgcPhotoResource` and `BlogPostResource`.

**`routes/web.php` — contact form and UGC home query:**
- `POST /contact` now calls `ContactMessage::create($validated)` before flashing and redirecting.
- Redirect uses `redirect(route('contact') . '#message-sent')` — browser scrolls to `id="message-sent"` success card.
- Home `GET /` query now eager-loads with `UgcPhoto::with('product')` for the product link in the UGC grid.

**`resources/views/home.blade.php` — UGC section dynamic:**
- Replaced 5 hardcoded Google AI placeholder tiles with a `@foreach($ugcPhotos as $photo)` loop.
- First photo gets `md:row-span-2` (tall tile). Each tile is an `<a>` linking to its linked product's slug (if set) or falling back to `/shop`.
- Hover reveals: full gradient overlay + alt text paragraph + "Shop this set →" chevron (only if product linked).

**`resources/views/order/confirm.blade.php` — bag cleared on page load:**
- `@push('scripts')` calls `window.NbmBag.save([])` on `$(function(){...})`. Falls back to `localStorage.removeItem('nbm.bag')` + `$('#bag-count').addClass('hidden')` if `NbmBag` is not available (e.g. order-layout doesn't include the full app layout).

**Key Filament v4 / Laravel 11 gotcha documented here:** `FileUpload` defaults to `local` disk = `storage/app/private/`. All admin file uploads must specify `->disk('public')` or images will never be publicly accessible. Re-upload any files that were already stored in `private/`.

**Files updated:** `resources/views/product.blade.php`, `resources/views/shop.blade.php`, `resources/views/home.blade.php`, `resources/views/order/confirm.blade.php`, `resources/views/contact.blade.php`, `app/Http/Controllers/ShopController.php`, `app/Filament/Resources/ProductResource.php`, `app/Filament/Resources/UgcPhotoResource.php`, `app/Filament/Resources/BlogPostResource.php`, `app/Filament/Resources/OrderResource.php`, `routes/web.php`.

---

### 2026-05-15 — Domain, SSL, deployment, admin fix, camera SVG polish

**Domain + SSL live:**
- nailsbymona.pk connected to DigitalOcean droplet (157.230.252.62). DNS changed from Shopify A record to the server IP at PKNIC (pk6.pknic.net.pk).
- SSL via Certbot (nginx plugin). Auto-renewal configured. `APP_URL=https://nailsbymona.pk`, `SESSION_SECURE_COOKIE=true`.
- Script at `/root/enable-ssl.sh` on server documents what was done.

**Camera capture UI — full-screen redesign:**
- `state-camera` converted to `position:fixed; inset:0; z-index:50; background:#000` — fills the entire phone screen like a native camera app.
- Top HUD: back button + 4 progress segments (seg-1 to seg-4) + photo counter text.
- Bottom controls: brightness pill + 72px shutter ring / 54px inner disc.
- SVG overlay: `viewBox="0 0 400 870"` (9:19.5 iPhone aspect ratio), `preserveAspectRatio="xMidYMax meet"`. Guides anchor to the bottom of the screen.

**SVG finger guide widths (superseded — see 2026-05-16 entry for final values):**
- These were the initial values from this session. Spacing, widths, and ring height were further refined in the 2026-05-16 session.

**Admin panel 404 fix (critical — Filament v4 breaking change):**
- Filament v4 `Authenticate` middleware blocks all non-local users whose `User` model doesn't implement `FilamentUser`. `abort(403)` fires in production even after successful login.
- Fix: `User` model now implements `Filament\Models\Contracts\FilamentUser` with `canAccessPanel(): bool { return true; }`.
- Without this, the admin panel is completely inaccessible in production (APP_ENV=production).

**Admin dashboard blank (Livewire routes not cached):**
- After login, `livewire.js` returned 404 because route cache didn't include Livewire's routes.
- Fix: `php artisan route:clear && php artisan optimize`.

**Production server state (as of 2026-05-15):**
- `APP_DEBUG=false` ✅
- Queue worker running via Supervisor (`/etc/supervisor/conf.d/nailsbymona-worker.conf`). Restarts automatically on crash and server reboot. Logs at `storage/logs/worker.log`.
- Deploy script at `/root/deploy.sh` — runs `git pull → composer install → migrate --force → optimize → supervisorctl restart worker`. One command for all future deploys.
- DB: 9 products, 1 admin user, first real order NBM-2026-0001 in the system.
- All pages return correct HTTP status (homepage/shop/order pages 200, /admin 302→login).

---

### 2026-05-16 — Frontend bug fixes, email fixes, business rule updates

**Camera overlay — final finger geometry (all confirmed by Humza):**

| Finger | Width | x range | Center x | Top y (camera) | Corner y (camera) |
|--------|-------|---------|----------|----------------|-------------------|
| Pinky  | 70px  | 12–82   | 47       | 451            | 486               |
| Ring   | 75px  | 95–170  | 132      | 348            | 386               |
| Middle | 75px  | 183–258 | 220      | 312            | 350               |
| Index  | 75px  | 271–346 | 308      | 379            | 417               |

- Camera overlay viewBox `0 0 400 870`. All inter-finger gaps are exactly 13px (matches index-middle reference gap).
- Ring finger raised from ~90% to ~93% of middle height (topY 368→348, cornerY 406→386) — user confirmed "looks amazing."
- `public/icons/sizing-fingers.svg` illustration uses same x-coordinates (viewBox 400×480 shares the same width). Ring topY 226→217, cornerY 244→255 in illustration.

**Frontend bug fixes (all deployed):**

| Bug | Fix |
|-----|-----|
| Thumbnail selection border cut off | CSS `outline` instead of `ring` (box-shadow). `outline` is not clipped by parent `overflow:auto`. |
| Bag icon handle arching wrong | SVG arc `sweep=0` → `sweep=1`. In Y-down SVG, sweep=1 arches upward (correct for a bag handle). |
| Product image swipe not working | jQuery `.on('touchstart', fn, {passive:true})` treats 3rd arg as event data — passive never set. Fixed with native `mainWrap.addEventListener('touchstart', fn, {passive:true})`. |
| "Find my profile" button oversized black circle | `rounded-full` → `rounded-xl`, `shrink-0 whitespace-nowrap` on button, `min-w-0 flex-1` on input. |
| Thumb camera heuristic never green | Added `isThumb` check — thumb has one wide nail (fewer edges). Halved both thresholds for thumb states: green 0.12→0.06, amber 0.06→0.03. |

**Email — logo header:**
- Created `public/logo-white.svg` (same paths as `logo-text.svg` with `fill: #ffffff`).
- Email layout `card-header` now has `background-color: #BFA4CE` with `<img src="logo-white.svg">` — proper branded header.
- Sender DP (avatar in email clients): controlled by Gravatar. Register `hello@nailsbymona.pk` at gravatar.com and upload the logo to fix this — not a code change.

**Email — price alignment (critical Gmail gotcha):**
- Gmail strips `<style>` block CSS classes. `display:flex; justify-content:space-between` was ignored, causing product name and price to run together ("Barely There NudeRs. 2,500").
- Fix: replaced all `order-item` and `total-row` divs across all 4 email templates with inline `style="display:table-cell; text-align:right; white-space:nowrap; padding-left:12px"` on price cells. Gmail always preserves inline styles; `display:table-cell` is supported in every email client including Outlook.
- Files updated: `emails/order-placed.blade.php`, `emails/payment-verified.blade.php`, `emails/order-in-production.blade.php`, `emails/order-shipped.blade.php`, `layouts/email.blade.php`.

**Estimated dispatch dates (corrected across all emails):**
- Old: `now()->addWeekdays(config('nbm.lead_time_standard', 7))` — showed ~10 calendar days.
- New: `addDays(6)` in order-placed, `addDays(5)` in payment-verified, `addDays(4)` in order-in-production (payment already verified at that point).
- Config: `lead_time_standard` 7→5, `lead_time_bridal` 12→10.

**Reorder discount: 10% → 5%:**
- `OrderController.php` line: `$subtotal * 0.10` → `$subtotal * 0.05`.
- Views updated: `order/details.blade.php`, `order/partials/price-summary.blade.php`, `size-guide.blade.php`.
- Pricing ladder in this file updated (§19).

**Returning customer indicator in admin:**
- `OrderResource` table: Customer column description now shows `↩ Returning  ·  phone` when `order->is_returning_customer = true`. Visible immediately in the orders list.
- Added "Returning customers" filter to the Orders table filter dropdown.
- No schema change — `is_returning_customer` boolean was already on the `orders` table.

**Gel Nail Polishes expense category:**
- New case `GelPolish = 'gel_polish'` added to `app/Enums/ExpenseCategory.php`.
- Label: "Gel Nail Polishes". Color: `#D4847A` (warm coral, distinct from general Materials lavender).
- Appears second in the list, after Materials & Supplies.

---

### 2026-05-16 (later) — Phase 4 Blog + SEO complete

**Phase 4 complete.** All blog infrastructure was already in place from Phases 1–3. This session added the 5th and final cornerstone post, seeded all 5 posts to production, and confirmed everything live.

**What was already built (Phases 1–3):**
- `BlogController` (`index`, `show`, `subscribe`) with view-count increment, reading-time calc, related-posts (same category), FAQ fallback to general.
- `blog.blade.php` — hero, sticky category filter bar (client-side jQuery, all posts loaded in DOM), featured-post hero card, post grid, subscribe strip, navigation tiles.
- `blog-post.blade.php` — breadcrumb, cover image, article body, FAQ accordion, share strip, related products (via `blog_post_products` pivot), author block, related posts.
- `/sitemap.xml` — dynamic, includes all published blog posts with `priority=0.75` and `lastmod`.
- `/feed.xml` — RSS 2.0, 20 most recent posts, Atom self-link for autodiscovery.
- SEO: `<x-seo>` component has canonical, robots, OG, Twitter Card, hreflang. Blog-post schema: Article + FAQPage + BreadcrumbList. Blog index schema: Blog + BreadcrumbList.
- `BlogPostSeeder` + `FaqSeeder` — idempotent (`firstOrCreate`), called from `DatabaseSeeder`.

**Added this session:**
- 5th cornerstone post: **"Bridal Nail Trends in Pakistan for 2026"** — `BlogCategory::Bridal`, target keyword `bridal nail trends Pakistan 2026`, published 3 days ago. Covers glazed porcelain (Valima), geometric gold (Mehendi), jewel tones (Baraat), 3D accent nails, and the three-night palette approach. Links to `/bridal` and `/shop`.
- `BlogPostSeeder` updated with the 5th post (`bridal-nail-trends-pakistan-2026`).
- Deployed to production and ran both seeders (`BlogPostSeeder` + `FaqSeeder`).

**All 5 cornerstone posts live at nailsbymona.pk/blog:**

| Slug | Category | Target keyword |
|---|---|---|
| `muslim-women-press-on-nails-wudu` | Tutorials | `press on nails wudu Muslim women` |
| `press-on-nails-vs-acrylics-pakistan-brides` | Bridal | `press on nails vs acrylics Pakistani brides` |
| `bridal-nail-trends-pakistan-2026` | Bridal | `bridal nail trends Pakistan 2026` |
| `how-to-apply-press-on-nails` | Tutorials | `how to apply press on nails` |
| `how-to-remove-press-on-nails` | Care | `how to remove press on nails without damage` |

All 5 appear in `/sitemap.xml`. RSS feed at `/feed.xml` autodiscoverable from blog `<head>`.

**Note on pagination:** BlogController uses `.get()` (all posts at once). This works because the category filter is client-side jQuery — all posts must be in the DOM for filtering. Pagination should be added server-side only when post count grows past ~30 and the category filter is converted to URL params.

---

### 2026-05-16 (later still) — Phase 5 polish pass: 5-block security + UX + SEO sweep

Full pre-Phase-5 audit (~60 findings catalogued, ~37 closed across five deploys). The site went from "functionally complete" to "materially safer + more Mona-friendly." All work deployed live to `https://nailsbymona.pk`.

**Block 1 — Security closures** (commits `201065c`, `0988150`):
- **Bag price tampering closed.** `OrderController@store` re-fetches every bag item by slug from the DB before pricing/creating items. localStorage-supplied prices, names, tiers are now ignored — a customer setting `price_pkr: 1` in DevTools can no longer place a Bridal Trio for Rs. 1. qty hard-clamped 1–10 per line.
- **Order creation wrapped in `DB::transaction`** — items, customer stats, and the Order row commit atomically. Partial-write failures impossible.
- **Session allowlist `order_form.authorized_orders`** populated on order placement + successful tracking lookup. Enforced on `/order/confirm/{uuid}`, `/order/{uuid}/track`, and the proof upload endpoint. Random UUIDs no longer leak order data via "View Source."
- **Tracking lookup contact normalization** — case-insensitive email + digit-suffix phone match. `John@Gmail.com` / `0300-1234567` / `+923001234567` / `923001234567` all resolve correctly. Throttle tightened 10/min → 5/min (matches contact form).
- **Private file storage.** Sizing photos + payment proofs moved from `storage/app/public/` (web-accessible) to `storage/app/private/` (local disk). A new auth-gated admin route `GET /admin/files/{category}/{order}/{filename}` is the only way to read them — handled by `App\Http\Controllers\Admin\PrivateFileController` with category whitelist + filename regex + path-traversal guard. Filament infolist now renders images via `OrderSizingPhoto::viewer_url` / `OrderPaymentProof::viewer_url` accessors that produce signed admin URLs.
- **EXIF strip + HEIC/HEIF/webp → JPEG normalization** added to payment proof upload (already in sizing pipeline). PDFs pass through unchanged.
- Confirmed `AutoCancelOrderJob` + `SendPaymentReminderJob` already guard on `payment_status === Awaiting` — finding #18 was a false alarm.

**Block 2 — Settings unification** (commit `6e2c778`):
- **`config/nbm.php` deleted.** It was a parallel source of truth alongside Spatie `StoreSettings` — the admin Settings page wrote to `StoreSettings` but `OrderController` + views read from `config('nbm.*)`. Mona changing her JazzCash number had zero effect on the live checkout. Every read site (24 view replacements + 18 wa.me URLs) now reads from `StoreSettings`.
- **New settings fields** (migration `2026_05_16_220000_add_lead_times_and_discount`): `lead_time_standard_days` (default 5), `lead_time_bridal_days` (default 10), `reorder_discount_percent` (default 5), `bridal_deposit_percent` (default 100 — fixes finding #10, matches CLAUDE.md §7 "Bridal Trio requires full advance"; was hardcoded 50% in `Order::advanceAmountPkr()`).
- **WhatsApp number normalization.** New `StoreSettings::whatsappForWaMe()` returns digits-only. Every `wa.me/{{ … }}` URL site (18) now calls this helper. Save-side: ManageSettings page canonicalizes input to `+<digits>` so spaces/dashes Mona types don't break the URL.
- **ManageSettings page** form now includes all fields including Bridal deposit %, reorder discount %, lead times. Three new form sections: "Shipping", "Advance & deposits", "Production lead times".
- Removed dead `OrderController::paymentDetailsFor()` helper.

**Block 3 — Order workflow + Mona-usability** (commit `249861d`):
- **`Order::generateOrderNumber()` race-fixed.** Inner `DB::transaction` with `lockForUpdate()` on the latest order_number for the year; retries up to 3× if a unique-constraint violation slips through (e.g. empty-table-for-year + two concurrent readers).
- **Order model accessors** `getPaymentAgeHoursAttribute()` + `getPaymentAgeLabelAttribute()` — produce `🟢 awaiting payment · 2h` / `🟡 14h` / `🔴 1d 4h` (thresholds: <12h green, 12–24h amber, >24h red, matching Mona's 24h SLA target). Rendered as description text under the Payment column in OrderResource.
- **`OrderResource` action expansion:**
  - WhatsApp row action — one-tap deep link with order-aware prefill ("Hello, this is Mona. About your order NBM-…").
  - "Confirm: full payment" (replaces "Mark Confirmed") — writes `advance_paid_pkr = total_pkr` + `confirmed_at = now()`.
  - "Confirm: advance only" (new, visible on `requires_advance` orders) — writes `payment_status = PartialAdvance` + `advance_paid_pkr = advanceAmountPkr()`. **Closes finding #11**: PartialAdvance status + `advance_paid_pkr` are now reachable through the UI.
  - "Balance received" (new, visible on PartialAdvance orders) — flips to fully paid.
  - Bulk "Confirm: full payment" action — handles overnight stack of orders in one modal, sends each customer email, reports counts.
- **Edit form expansion** — customer name/phone/email + shipping address/city/postal/notes + `advance_paid_pkr` now editable. Mona can fix checkout typos without DB access.

**Block 4 — SEO + share polish** (commit `318316d`):
- **Legal pages live** — `/privacy`, `/terms`, `/shipping` written with real plain-English content (data handling, refit policy, payment terms, lead times, courier list, damage policy). Three view files at `resources/views/legal/`. Footer links rewired from `url('/privacy')` to `route('privacy')`. **Closes footer 404s.**
- **Custom 404** at `resources/views/errors/404.blade.php` — sitelink tiles to Shop / Bridal / Journal / Help + back-to-home CTA.
- **`<x-seo>` improvements:** new `ogType` prop (default `website`, blog-post passes `"article"`); when a page provides `@graph` schema, Organization is merged into it instead of emitting a duplicate `<script>` block.
- **`<html lang="en-PK">`** (was `en`) matches `hreflang="en-PK"` and `og:locale=en_PK`.
- **RSS autodiscovery** `<link rel="alternate" type="application/rss+xml">` added to layout `<head>`.
- **`robots.txt`** now declares Sitemap + Disallow rules for `/admin`, `/order/`, `/track`.
- **OG default image** — new artisan command `php artisan og:generate` renders a 1200×630 brand card to `public/og-default.jpg` using Intervention. Falls back from bundled fonts to system DejaVu (always present on Ubuntu). Mona can re-run any time to refresh the wordmark.
- **`favicon.ico`** — was 0 bytes, replaced with a copy of `icon-192.png` (browsers accept PNG inside `.ico` extension).
- **Sitemap `lastmod`** for blog posts uses `max(published_at, updated_at)` so small edits without re-publishing propagate to crawlers.

**Block 5 — A11y + dead code + refit workflow** (commit `41b8ec7`):
- **Focus trap + focus restore** on bag drawer and mobile menu. Tab/Shift+Tab cycle within open overlay; ESC closes the visible one; focus returns to the trigger element on close. Initial focus lands on close button (or Checkout if bag has items). Closes the WCAG 2.2 issue flagged 2026-05-07.
- **Deleted 5 dead Blade views** at views root: `order-form.blade.php`, `order-confirmation.blade.php`, `order-tracking.blade.php`, `sizing-capture.blade.php`, `welcome.blade.php`. All had zero references and conflicted with the real views at `views/order/*`.
- **Queued admin notifications.** `NewOrderNotification` + `NewMessageNotification` now `implements ShouldQueue` (used `Queueable` trait without it, so `notify()` was running synchronously on the request thread). `User::all()->each->notify()` replaced with `chunkById(50)` — defensive against admin count growth.
- **Lazy-loading audit** across every public-view `<img>` tag. Heroes (about, bridal, product main, blog featured, blog post cover) get `loading="eager" fetchpriority="high"`; below-fold get `loading="lazy"`. Blade-aware verifier (handles `{{ $post->cover_image }}` containing literal `>` chars) confirms zero gaps.
- **Refit-request workflow** — CLAUDE.md §16 brand promise is now trackable:
  - Migration `2026_05_16_230000_add_refit_columns_to_orders` adds `refit_requested_at`, `refit_shipped_at`, `refit_notes` columns to `orders`.
  - Two Filament actions: "Refit requested" (modal with notes textarea, stamps timestamp, visible on Delivered orders without a refit) and "Refit shipped" (visible once requested but not yet dispatched).
  - "Refit pending" table filter sorts oldest-request-first.
  - Status column description shows `↺ refit pending` / `↺ refit shipped` so Mona can scan the list.
  - View page surfaces a Refit infolist section (hidden if no refit activity).
- **Awaiting-payment filter** now sorts oldest-first — SLA-breached orders rise to the top.

**Critical patterns / gotchas discovered during this session:**

| Pattern | Where it bit | Lesson |
|---|---|---|
| Multi-line `<img>` regex | Bulk lazy-loading insertion | Blade `{{ $post->cover_image }}` contains literal `>` — a `[^>]*?` regex stops there. Always use a Blade-aware parser that treats `{{ }}` and `{!! !!}` as opaque. |
| Filament v4 + auth middleware | `/admin/files/...` returned 500 | Default Laravel `auth` middleware redirects to a route named `login` that doesn't exist in this app (Filament owns login at `/admin/login`). Either Filament's `Authenticate` middleware (panel context required) OR an explicit `Auth::check() ? : redirect('/admin/login')` inside the controller. |
| Settings migration disk | None yet — defensive note | Spatie `addEncrypted()` works the same as `add()` API-wise, but reading mismatched encryption fails silently. Match the existing migration's style (this project uses `add()`, not `addEncrypted()`). |
| Vercel hook noise | Every PR | The repo's `app/` directory triggers the Vercel plugin's Next.js skill suggestions. This is a Laravel project. Per §33, ignore all `nextjs` / `next-cache-components` / `chat-sdk` skill prompts. |
| `product_id` column on `order_items` | Order creation | Column is typed `unsignedBigInteger` but `products.id` is ULID. Writing a ULID into it would fail on MySQL. Block 1 stopped writing `product_id` and relies on `product_slug_snapshot`. Dead column candidate for a future cleanup migration. |

**Findings closed (37):** 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 14, 15, 17 (workaround), 18 (verified), 19, 20, 21, 22, 24, 25, 26, 27, 28, 29, 30, 31, 33, 34, 35, 36 (partial), 37, 39, 40, 44, 45, 48, 59.

**Still open (audit residue):** #36 analytics consent banner, #38 inline footer styles, #41 unused csrf meta tag, #42 extract Filament SVG download partial, #43 push-subscription route guard, #46 OrderItem snapshot defaults edge case, #47 bag badge edge case, #50–58 small nits. None are launch blockers.

**Deploy mechanics confirmed working:**
- `/root/deploy.sh` on `157.230.252.62` — git pull → composer → migrate → optimize → supervisorctl restart.
- All deploys this session: fast-forward only, zero conflicts.
- Two Phase-5 migrations applied: `2026_05_16_220000_add_lead_times_and_discount` (settings table), `2026_05_16_230000_add_refit_columns_to_orders` (orders table).
- `php artisan og:generate` run once on server post-deploy to write `public/og-default.jpg`.
- Orphan public-disk files (test data from before private-disk migration) removed via `rm -rf storage/app/public/sizing storage/app/public/payment-proofs`.

---

### 2026-05-19 — Full audit + Blocks 1–6 shipped, plus Block 7 partial photography swap

A long single-day session. Started with a deep front-end/back-end audit producing ~60 findings, then worked through six prioritized blocks of fixes, each shipped to production with verification. Toward the end Mona delivered her first batch of real sizing photos and we swapped the Google AI placeholders on `/size-guide` for them.

**Block 1 — Critical money/cart bugs** (commit `0004413`)

- **C1** Shop add-to-bag was pushing items with no `slug` field. `OrderController::verifyBag()` drops slug-less items, so customers checking out from the grid silently saw "your bag is empty." Fixed by adding `data-tier="{{ $tierValue }}"` to shop/product buttons and routing through `window.NbmBag.add()` consistently.
- **C2** `/order/payment` hardcoded "50% deposit" (Bridal Trio) and "30% advance" (standard) — but `StoreSettings` had `bridal_deposit_percent = 100` and `advance_percent = 25`. Customer was being told one thing and quoted another. Now reads everything from `$settings`; bridal copy adapts when deposit % is 100 ("paid in full up-front") vs partial.
- **C3** Returning-customer lookup was broken end-to-end. The AJAX flagged `is_returning + from_profile` in the session but the form still submitted `live_camera` (the default radio), and `storeSizing()` validator didn't accept `from_profile`. Now the validator accepts it, a defensive guard forces `from_profile` when the session is flagged returning, and on a successful match the JS auto-redirects to `/order/details` after 900 ms instead of waiting for a Continue tap.
- **C4** Order-confirmation discount line was using a wrong formula (`subtotal + 2·shipping − total`) that overstated the discount by exactly the shipping amount. Now reads `$order->reorder_discount_pkr` directly.

**Block 2 — Lead times + dispatch dates** (commit `0004413` — combined with Block 1)

- New migration `2026_05_19_010000_add_estimated_dispatch_to_orders` adds `orders.estimated_dispatch_at`.
- `OrderController::store()` pins the dispatch date at order placement (`now()->addDays($leadTimeDays)`) — was being recomputed on every render and drifting forward as the order aged.
- New `Order` helpers: `isBridalTrio()` (resilient to unloaded relations — uses `relationLoaded('items')` check), `leadTimeDays()` (settings-driven, bridal vs standard), `estimatedDispatchAt(bool $fromNow = false)` (returns pinned date or recomputed-from-now).
- All three transactional emails (order-placed, payment-verified, order-in-production) now read the dispatch date from the model helper. Order-placed uses the pinned date; the other two use `fromNow: true` so the date is fresh as of verification / production-start.
- Order-placed email's bridal/advance copy aligned with the payment-page fix (same source of truth — `$settings` + `$order->advanceAmountPkr()`).
- All hardcoded lead-time copy in public views (shop, home, about, bridal, product) replaced with `{{ $settings->lead_time_standard_days }}` / `{{ $settings->lead_time_bridal_days }}`. "Working days" qualifier dropped — settings are calendar days, copy is now just "days."

**Block 3 — Order-flow data integrity & admin workflow** (commit `0d17982`)

- **M3** `Customer::findByContact()` now normalizes Pakistani phone numbers via a new `normalizePhoneTail(?string)` static helper that strips country code (`92`) or leading `0` and matches on the last 10 digits. All of `+923001234567` / `923001234567` / `03001234567` / `0300-1234567` / `(0300) 1234567` resolve to the same customer row. Email comparison is case-insensitive. Requires ≥7 useful digits so short inputs can't fan-match.
- **Sec5** `OrderTrackingController::lookup` reuses the same tail-normalization helper. Old behaviour accepted bidirectional suffix match, letting `1234567` match any order ending in those digits.
- **M4** `OrderController::payment()` now calls `verifyBag()` before computing totals. Tampered localStorage prices are corrected at step 3 too, not only at order placement. If verification drops every item (renamed slug, deactivated product), bounce to `/shop` with an error.
- **C6** New `PaymentStatus::Verifying` state wired up. `OrderPaymentProofController::store` transitions `Awaiting → Verifying` once a proof is uploaded. Gives Mona's triage queue a distinct "proof uploaded, needs review" bucket. The "Awaiting payment" filter on the orders table now includes both `Awaiting` and `Verifying`. A new "Proof uploaded — needs review" filter shows only `Verifying`.
- **C7** Filament Confirm / Confirm-Advance / Balance-Received actions (plus the bulk-confirm) now stamp `verified_at = now()` on every unverified `OrderPaymentProof` for the order. The Filament infolist's "Verified" column stops showing "Not verified yet" forever.
- **M8** `AutoCancelOrderJob` now requires an admin-verified proof to short-circuit, not just any proof row. Previously a customer uploading an empty/wrong screenshot indefinitely blocked the 72h auto-cancel.
- **M7** "Mark Shipped" Filament action's confirmation modal now shows a balance-due warning when `payment_status === PartialAdvance`. Mona still has the override, but the easy mistake is flagged.
- **M6** Order-number generator retry budget raised from 3 → 5 attempts with jittered backoff (10–50 ms × attempt). Fallback prefix changed from `NBM-YYYY-0000` to `NBM-YYYY-Txxxx` so admin can spot anomalies.

**Block 4 — Public-facing safety nets** (commit `5435b95`)

- **M2** New `UgcPhoto::scopePublished()` enforces `is_published=true AND face_visible=false` in one place. Home query refactored to use it. Future UGC queries can't accidentally leak face-visible photos.
- **F6** `/track` now sets `<x-seo :noindex="true"/>` explicitly. The other 5 order-flow views already inherited `noindex` from `layouts.order`.
- **Sec1** `PushSubscriptionController` now requires the user to be a `FilamentUser` (defence-in-depth for future customer-auth).
- **M10** `Order` model hooks `updating` + `deleting` events to roll back `Customer::total_orders` and `Customer::lifetime_value_pkr` when an order transitions to Cancelled or is hard-deleted. Idempotent — a re-cancel doesn't double-decrement; deleting an already-cancelled order doesn't decrement twice. Counters clamp at zero.
- **M1** New migration `2026_05_19_020000_drop_product_id_from_order_items` drops the `order_items.product_id` column. It was `unsignedBigInteger` but products use ULIDs — never written. Comments in `OrderController` and `OrderItem::$fillable` cleaned up too.

**Block 5 — Admin/Filament polish** (commit `6a075ad`)

- **A1** Renamed `TopBlogPostsWidget` → `OrdersNeedingAttentionWidget`. Class name and file name now match the actual job (surfacing `Awaiting/Verifying + New` orders).
- **A2** `RecentOrdersWidget` payment_status badge match adds `PartialAdvance => 'info'`.
- **A3** `ExpenseResource` badge match adds `GelPolish => 'danger'`, `Utilities`, `Other` so every category renders colored.
- **A4** `FaqResource` switched from deprecated `->colors([...])` array shape to `->color(fn match)` pattern. Adds `Application` + `Bridal` cases.
- **M9** `OrderStatsWidget` and `FinanceOverview` both compute revenue from `sum('advance_paid_pkr')`. Paid orders contribute full amount; PartialAdvance contributes only the deposit; Awaiting/Verifying contribute zero. The two dashboards now always agree. Bonus: 7-day sparklines moved from 7 separate sums to one `selectRaw('DATE(created_at), COUNT/SUM')->groupBy()` per metric.
- **A8** Added `$navigationIcon` to every Filament resource + `ManageSettings` page. (Follow-up hotfix `25970e6` — see below — removed the group-level icons that collided with these item-level icons.)
- **A14** Removed `DeleteBulkAction` from the Orders table. Added a new migration `2026_05_19_030000_add_soft_deletes_to_orders` (adds `orders.deleted_at`). Order model now uses `SoftDeletes` trait — per-row delete is reversible.
- **A15** `ManageSettings::save()` shows a persistent warning notification if all three payment methods (JazzCash, EasyPaisa, Bank Transfer) are blank. Each payment section also got a `description()` explaining where the values render.
- **A12** New `SubscriberResource` — read-only Filament resource for the blog subscribe list with a header CSV-export action and per-row delete (for GDPR-style removal requests).
- **A13** `Customer.sizingPhotosFromOrders()` aggregates every sizing photo uploaded across all the customer's orders. `CustomerResource` view page now shows them in a collapsible grid (collapsed by default), newest first, using the private-disk `viewer_url`.

**Block 5 hotfix — Filament v4 navigation icon collision** (commit `25970e6`)

I missed a real smoke-test step in Block 5 and only checked that routes returned 302. When the user opened `/admin`, every panel page 500'd with `Navigation group [Orders] has an icon but one or more of its items also have icons. Either the group or its items can have icons, but not both.` Filament v4 enforces a UX rule about icons that the previous group-level icons in `AdminPanelProvider::panel()` violated once I'd added item-level icons in A8. Fix: stripped `->icon(...)` from the navigation groups. Resource-level icons stay.

**Lesson:** every Filament-touching deploy now ends with a tinker-driven kernel render of `/admin` as a logged-in admin user, asserting status < 500 and no `Exception` markers in the response body. Added to my standard smoke-test playbook.

**Block 6 — Product page wiring + Schema.org fixes** (commit `639fbc6`)

- **F1** `ShopController::show()` passes `$faqs` from the active `general`-category `faqs` admin table. `product.blade.php` loops them in the FAQ section; falls back to the original 5 hardcoded questions when the table is empty so the section never renders bare. Mona's FAQ admin edits finally reach customers.
- **F2** Added `BreadcrumbList` JSON-LD to product, shop, and bridal pages. About and contact already had it.
- **F3** Product `Offer` schema gains `image`, `sku` (= slug), `offers.url`. Required for Google Product rich-result eligibility.
- **F4** `made_to_order` stock status now maps to `https://schema.org/MadeToOrder` (was incorrectly `PreOrder`, which implies a future release date).
- **F5** Shop `ItemList` schema populated with one `ListItem` per active product (was an empty container with no entries).
- **F10** FAQ accordion gets proper ARIA — `aria-expanded` toggles on click, `aria-controls` points to a `<div role="region" id="faq-panel-{id}">` panel.
- **F11** Tab buttons become a proper `<div role="tablist">` with `role="tab" + aria-selected + aria-controls` on each button and `role="tabpanel" + aria-labelledby` on each panel. JS maintains `aria-selected`.
- **S1, S2** Contact page's `LocalBusiness` schema reads `email` from `$settings->contact_email` (was hardcoded `hello@nailsbymona.pk`); `openingHours` reads `$settings->business_hours` if it matches the strict Schema.org day-time regex, else a sensible fallback (`Mo-Sa 10:00-19:00`).
- **S7** `og:locale` changed from invalid `en_PK` to Facebook-accepted `en_GB`. `hreflang="en-PK"` retained for Google's PK signal.

**Various follow-up hotfixes after Block 6**

- `dcb6214` — Filament drag-reorder usable on mobile. Monkey-patches `window.Sortable.create` via the `panels::body.end` render hook with `delay: 250 + delayOnTouchOnly + scroll: true + scrollSensitivity: 80`. Quick tap-and-swipe scrolls; press-and-hold enters drag mode and the page auto-scrolls when the dragged row nears a viewport edge.
- `68d4764` — Hotfix: every checkout was 500'ing with `Duplicate entry 'NBM-2026-0001'`. Root cause: Block 5 added `SoftDeletes` to `Order`. Eloquent's default scope filters out soft-deleted rows, but the DB's UNIQUE constraint on `order_number` doesn't. A soft-deleted `NBM-2026-0001` from prior testing was invisible to `generateOrderNumber()`'s `exists()` check but still occupied the slot. Fix: `withTrashed()` on both the `lockForUpdate` SELECT and the TOCTOU `exists()` check in the generator. Comment block in `Order.php` now explains the gotcha so the next schema change doesn't reintroduce it. The orphan soft-deleted row was cleaned up via tinker `forceDelete()` on prod.
- `e1ed349` — iOS Safari scroll-jump on the sizing-step radio cards. `display: none` on the radio inputs inside their wrapping `<label>` caused iOS to try to "scroll the focused control into view" and land at the top of the page. Fixed with the standard visually-hidden pattern (`position: absolute; w/h:1px; clip: rect(0,0,0,0); opacity: 0`). Also dropped the redundant `.sizing-option` click handler that was double-firing change events on iOS.
- `c454aba` — Empty-bag bounce-loop on `/order/start`. Hitting the URL directly (no shop visit) → pick sizing → POST `/order/start/sizing` → 302 to `/order/details` → empty bag → 302 back to `/order/start`. Customer experienced this as "doesn't go forward." Fix: `OrderController::start()` and `storeSizing()` now redirect to `/shop` with a flash `@error('bag')` message when the session bag is empty in production. New banner at the top of `/shop` renders the error so the bounced customer immediately sees why they're back there. Also removed `pointer-events: none` from the visually-hidden radios (it suppressed label-forwarded clicks on some iOS builds).

**Block 7 partial — first photography swap on `/size-guide`** (commits `5bf68e4`, `805f243`, `8d40672`, `f727d66`, `96d7089`, `d9d38c5`, `916db65`, `58523b6`, `a71a3e8`)

Mona delivered 12 photos via `~/Downloads/DSC*.jpg`. Categorized them, processed 6, replaced every Google AI placeholder on the size guide. Multiple iteration rounds because each round revealed a new framing/legibility issue.

Source-photo selection (after viewing all 12):
- `fingers-reference` ← `DSC01693` (sharp fingers, coin, clean tan cloth, no ring)
- `thumb-reference`   ← `DSC01699` (canonical thumb side-profile with coin)
- `fingers-good-alt`  ← `DSC01692` (alternate angle, also clean)
- `bad-blurry`        ← `DSC01706` (everything blurred — perfect counter-example)
- `bad-thumb-too-far` ← `DSC08277` (sleeve in frame, coin far from thumb, scale unclear, hand small)
- `bad-busy-background` ← `DSC01711` (hand on patterned cushion — the alignment heuristic's nemesis)

Processing pipeline (final): `sips -r 90` (or `-r 270` for thumb shots) → `sips -Z 1600 --setProperty formatOptions 82` to resize → Python+PIL crop to true 2:3 (900×1350) top-anchored (bottom-anchored for `bad-thumb-too-far` since its subject is in the lower half) → `cwebp -q 82` to emit WebP variant. Total payload ≈ 1.1 MB across all 6 photos.

Iteration log:
1. **First pass** — landscape orientation kept, square `aspect-square` containers cropped the pinky on the right edge.
2. **Vertical re-orientation** — rotated source to portrait; everything looked right except the coin was too close to the top edge → 3:4 container cropped the coin in half.
3. **`aspect-[3/4]` → `aspect-[2/3]` containers** — natively matched the 2:3 photo dimensions; coin fully visible.
4. **True 2:3 crop on the source** — cropped 900×1600 down to 900×1350 (exact 2:3) so the container fits the photo with zero cropping. Bottom (wrist) trimmed off, framing tighter.
5. **Caption legibility** — white-on-gradient text was hard to read against busy real-photo backgrounds. Restructured each card: photo at top with the Good/Avoid corner pill, caption block underneath on `bg-paper` with dark graphite text. Photo stays uncovered.
6. **Bad-2 caption updated** — now mentions both "too far away" and "coin not close to the nail" as the photo demonstrates both failures simultaneously.

Also replaced the SVG illustration placeholders in Steps 2 & 3 of the four-step "how to take the photos" walkthrough with the canonical real-photo references. Step heroes: Step 1 = `fingers-good-alt`, Step 2 = `fingers-reference`, Step 3 = `thumb-reference`, Step 4 = `thumb-reference` (re-used in the "final checklist" slot).

**Block 7 hotfix — Vite/Tailwind CSS not rebuilding on deploy** (commit `a71a3e8` + `/root/deploy.sh` update)

The colored pills on the Good/Avoid cards reverted to transparent backgrounds after one of my Block 7 edits. Investigation: I had changed pill backgrounds from `bg-success/90` to `bg-success/95` for a slightly bolder opacity. Tailwind v4 generates utility classes by scanning source files at build time and emits ONLY the variants actually used. The deployed CSS bundle was built earlier when the source still said `/90`, so `bg-success/95` simply didn't exist in the served stylesheet — the pill's `background` style resolved to nothing and showed transparent.

Root-root cause: `/root/deploy.sh` was running `composer install` + `migrate` + `optimize` but **never** `npm run build`. Any Tailwind-only edit (new opacity, new arbitrary aspect, new utility) silently never reached production. `public/build/` is gitignored, so committing pre-built CSS isn't an option either.

Two-part fix:
1. Immediate: reverted pill backgrounds to `/90` (which IS in the deployed bundle).
2. Permanent: rewrote `/root/deploy.sh` on the server to run `npm ci --no-audit --no-fund --silent` and `npm run build` between `composer install` and `migrate --force`. Every future deploy now rebuilds the frontend assets.

**Audit closure summary**

| Block | Status | Notes |
|---|---|---|
| 1 — Critical money/cart | ✓ Shipped | C1 C2 C3 C4 fully closed |
| 2 — Lead times + dispatch | ✓ Shipped | Single source of truth via `$settings` + `Order` model |
| 3 — Order-flow data integrity | ✓ Shipped | M3 M4 M6 M7 M8 Sec5 C6 C7 |
| 4 — Public-facing safety nets | ✓ Shipped | M1 M2 M10 F6 Sec1 |
| 5 — Admin/Filament polish | ✓ Shipped | A1–A4 A8 A12 A13 A14 A15 M9. **A7 skipped on Humza's request** ("Record nail sizes" stays visible on every row regardless of order status — convenience for Mona). |
| 6 — Product page + Schema | ✓ Shipped | F1–F5 F10 F11 S1 S2 S7 |
| 7 — Photography swap | ⚠️ Partial | Size guide done. Home / About / Bridal hero photos still using `lh3.googleusercontent.com/aida-public/...` placeholders — waits on Mona's next shoot. |
| 8 — Frontend polish sweep | ⏸ Queued | XSS-proof bag drawer, mobile nav Help link, unified desktop detect, consent banner |
| 9 — Performance / tests / CSP | ⏸ Queued | Q3 work |

---

### 2026-05-20 — Size guide visual polish pass

Sub-session refining the size guide gallery after Mona reviewed the deployed version.

- **Caption legibility** (commit `d9d38c5`): the original white-on-gradient captions overlaid on each Good/Avoid card were hard to read against busy real-photo backgrounds. Restructured to an editorial layout — photo block (2:3, with Good/Avoid corner pill) + paper-coloured caption block underneath with dark graphite text. Each card is now an `<article>` with two stacked children. `min-h` on the caption keeps card heights uniform across the 6-tile grid.
- **Colored label header attempt** (commit `916db65`): added a "✓ GOOD" / "✗ AVOID" header in `text-success` / `text-danger` above each caption description.
- **Reverted the label header** (commit `58523b6`): Humza pointed out that the corner pill on the photo already conveys Good/Avoid — the caption header was redundant noise. Removed; caption block is now just the description.
- **Pill color regression + Vite build fix** (commit `a71a3e8` + `/root/deploy.sh` rewrite): see Block 7 hotfix above. The pills had silently lost their green/red backgrounds because Tailwind utility variants from the latest edits weren't being built. Fixed both the immediate symptom and the underlying deploy-script gap.
- **Final state of `/size-guide` gallery**: 6 cards, each with a 2:3 portrait photo (rotated + cropped from Mona's source, top-anchored so coin sits ~10% from the top), the original colored corner pill (`Good` green / `Avoid` red), and a separate paper-coloured caption block below with description only.
- **Middle Avoid caption text** (commit `96d7089`): updated to mention both failure modes — "Too far away — both the nail and the coin should fill most of the frame. Move the camera closer, and keep the coin right next to the nail so they share the same focal plane." Previous version only mentioned coin-distance, missing the camera-distance issue that the same photo also demonstrated.
- **Steps 2 & 3 hero swap** (commit `805f243`): replaced the SVG U-shape outline illustrations in "Photo 1 — Your fingers" and "Photo 2 — Your thumb" with the real `fingers-reference` and `thumb-reference` photos. Customers now see exactly what a "good" shot looks like at the exact step they're being asked to take it.

**Files touched in this size-guide session**: `resources/views/size-guide.blade.php` heavily, `public/images/sizing/*.jpg|.webp` (regenerated multiple times), `/root/deploy.sh` on the production server.

**Migrations applied this session (all on prod):**
```
2026_05_19_010000_add_estimated_dispatch_to_orders   (Block 2)
2026_05_19_020000_drop_product_id_from_order_items   (Block 4)
2026_05_19_030000_add_soft_deletes_to_orders         (Block 5)
```

---

### 2026-09-11 — Admin payment-method visibility toggles + Bridal Trio refresh

Two small but customer-facing shipped changes and a corresponding pricing/positioning refresh. All work deployed live to `https://nailsbymona.pk` via `/root/deploy.sh`.

**Part 1 — Admin toggle for each payment method** (commit `a085147` on branch `claude/peaceful-elion-ed28a3` → pushed to `main`)

Mona asked for the ability to hide any of the three payment methods (JazzCash / EasyPaisa / Bank Transfer) from the customer checkout page independently from whether the account details for that method are filled in. She may want to add JazzCash details but not yet expose them to customers, or temporarily turn a method off while she reorganises. Old behaviour: all three radios always rendered on `/order/payment`, and the only way to "hide" a method was to blank its account details (which still rendered an empty radio with no explanation).

**New settings fields** (Spatie settings migration `2026_09_11_120000_add_payment_method_toggles.php`):
```
store.jazzcash_enabled       (bool, default true)
store.easypaisa_enabled      (bool, default true)
store.bank_transfer_enabled  (bool, default true)
```
Defaults to `true` so the migration is a no-op for existing installs — nothing changes for customers until Mona flips a toggle.

**`App\Settings\StoreSettings` gained three `public bool` properties** under a new "Payment method visibility (admin-controlled)" comment block, placed right after the existing payment credential fields.

**`App\Filament\Pages\ManageSettings`:**
- Each payment `FormSection` (JazzCash / EasyPaisa / Bank Transfer) now leads with a `Forms\Components\Toggle::make('{method}_enabled')->label('Show on checkout')->columnSpanFull()->inline(false)`. Toggle sits above the account-detail inputs in the same card.
- Section-level `->description(...)` copy added to each block: "Toggle off to hide this option on checkout. When on, customer picks {Method} → these details render on their order-confirmation page."
- `mount()` fills the three new keys in `$this->form->fill([...])`.
- `save()` — new `$boolFields` array (`jazzcash_enabled`, `easypaisa_enabled`, `bank_transfer_enabled`). Loop over `$data` casts int fields to `(int)`, bool fields to `(bool)`, everything else to `(string)`.
- **Guardrail 1 (all disabled):** if all three are off after save, shows a persistent warning notification "Warning: all payment methods are turned off. Customers cannot check out until you turn at least one method back on." and `return`s before the success toast.
- **Guardrail 2 (enabled but blank):** if any *enabled* method has blank primary credentials (`jazzcash_number` / `easypaisa_number` / (`bank_account_no` AND `bank_iban`)), shows a persistent warning "Settings saved — but an enabled method is missing account details" and returns. A disabled method being blank is intentional and produces no warning.

**`resources/views/order/payment.blade.php`:**
- New `@php` block at the top of the checkout `<form>` computes `$firstEnabled` — the first of `jazzcash` / `easypaisa` / `bank_transfer` whose `_enabled` flag is true (or `null` if none).
- Each of the three method cards is wrapped in `@if ($settings->{method}_enabled)`. A disabled method renders nothing — no empty radio, no blank details block.
- The `<input type="radio">` for each card uses `@checked($firstEnabled === '{method}')` instead of the previous hardcoded `checked` on JazzCash. The `.selected` class on the outer `<label>` and the lavender-fill on the option dot inside it also read from `$firstEnabled`, so the visible selection state is always consistent with the checked radio.
- New empty-state banner: if `! $firstEnabled` (all three off), the payment card list renders a single `bg-lavender-wash` card saying "No payment methods are currently available. Please [message us on WhatsApp](wa.me/…) and we'll help you complete your order."
- `<button id="place-order-btn">` gains `@disabled(! $firstEnabled)` + Tailwind `disabled:opacity-50 disabled:cursor-not-allowed` so the CTA is visibly dead in the all-off state.

**`app/Http/Controllers/Order/OrderController.php` (`store()`):**
- New guard block at the very top of the method — reads `StoreSettings` via `app(StoreSettings::class)`, builds `$allowedMethods = array_values(array_filter([...]))` from the three toggles, and if the array is empty, redirects back to `/order/payment` with a `withErrors(['payment_method' => ...])`.
- The `$request->validate([...])` call now uses `'in:' . implode(',', $allowedMethods)` instead of the previous hardcoded `'in:jazzcash,easypaisa,bank_transfer'`.
- The later `$settings = app(StoreSettings::class)` reassignment (used for `lead_time_bridal_days` / `lead_time_standard_days` around line 358 before this session) was removed — the top-of-method binding is reused instead of resolving twice.
- **Why validate on the server too:** a customer with the payment page open when Mona disables a method could otherwise POST a stale method value and slip past the UI-only gate.

**Deploy notes:**
- Settings migration runs via `/root/deploy.sh` automatically (calls `php artisan migrate --force`).
- Verified live via tinker after deploy:
  ```
  jazzcash_enabled: true
  easypaisa_enabled: true
  bank_transfer_enabled: true
  ```

**Confirmation page (`/order/confirm`) not changed:** the payment-details block there already renders only the `$order->payment_method` the customer picked. Since they picked from a filtered list of enabled methods, whatever they see reflects a method that was enabled at order-placement time. No gating needed.

**Confirmation email templates not changed:** same reasoning — customer already committed to a specific method at order-time.

**Part 2 — Bridal section image swap + Rs. 10,000 flat trio pricing** (commit `8c45fc5` on branch `claude/peaceful-elion-ed28a3` → pushed to `main`)

Mona delivered three real photos for the Bridal section — one per event — and asked to bring the Bridal Trio down to a flat Rs. 10,000. The old page was still on the emerald/deep-red/French-tip placeholder trio from Phase 1, with a range of Rs. 11,000–13,500.

**Source photos (in `~/Downloads/`)** and their destinations:

| Event | Source file | Actual design |
|---|---|---|
| **Mehendi** | `WhatsApp Image 2026-09-10 at 22.15.12.jpeg` (3024×4032, iPhone portrait) | Emerald green almond nails with gold French-tip detail + jewelled accents, ruby ring, green-and-gold embroidered outfit |
| **Baraat** | `3146F759-3C77-4CD0-8029-43CE916F2235.jpeg` (3024×4032, iPhone portrait) | Sheer nude base carpeted in gold beadwork + crystal clusters, hand-set piece by piece |
| **Walima (Valima)** | `WhatsApp Image 2026-09-11 at 22.29.18.jpeg` (1280×854, landscape) | Bride's folded hands in prayer with soft nude-to-white ombre nails, mint-and-gold dupatta / sequined lehenga |

**Image processing pipeline** (scratchpad → `public/images/`):
1. Mehendi + Baraat were already 3024×4032 portrait (native 3:4). `sips -Z 1280` down to 960×1280, `sips -Z 640` down to 480×640, `formatOptions 88` for the JPEG.
2. Walima at 1280×854 landscape needed a portrait crop first. `sips -c 854 640` crops from centre to 640×854, then `-Z 1280` → 960×1280 and `-Z 640` → 480×640. Centre crop was verified visually to keep the folded hands intact and the mint-gold dupatta framed nicely.
3. WebP variants via `cwebp -q 82 -quiet {jpg} -o {webp}` for each of the six JPEGs.
4. All 12 files copied into `public/images/`:
   ```
   bridal-mehendi-emerald-480.jpg  / -480.webp / -960.jpg / -960.webp
   bridal-baraat-beaded-480.jpg    / -480.webp / -960.jpg / -960.webp
   bridal-valima-ombre-480.jpg     / -480.webp / -960.jpg / -960.webp
   ```
   The old `bridal-baraat-deep-red-*` and `bridal-valima-french-*` files were left in place (dead references now, but harmless — small dead-code cleanup task for later).

**`resources/views/bridal.blade.php` — three-event panel rewrite (Section 2 "The Collection"):**
- **Mehendi panel:** was a blank img with an italic "Mehendi" text overlay (placeholder). Now uses `<picture>` with WebP/JPEG srcset, real `alt` text describing the emerald design, and a background gradient stripe from deep forest (`#4C6B5A → #2E4A3B → #1A2D24`) as the loading/error fallback. Caption copy: "Deep emerald green with fine gold-outlined tips and jewelled accents — designed to sit beautifully beside your henna and green-and-gold outfits. A grounded, romantic opening to the wedding."
- **Baraat panel:** was the old deep-red stock image + "Deep reds, burgundies, intricate 3D crystals" copy. Now points at `bridal-baraat-beaded-*`, gradient stripe swapped to warm bronze (`#8B7355 → #5C4A38 → #2A1F14`). Caption: "A sheer nude base carpeted in gold beadwork and crystal clusters — hand-set piece by piece. Dramatic under baraat lights, catches every angle in your photographs."
- **Valima panel:** was the old French-tip stock image. Now points at `bridal-valima-ombre-*`, gradient stripe kept in the warm-champagne family (`#D4C5A0 → #E8DCC0 → #F5EED8`). Caption: "A soft nude-to-white ombre finish with an almond shape — quiet, luminous, and effortlessly elegant. The calm ending to a joyful three-night story."
- All three panels now use `width="960" height="1280"` (the true 3:4 dimensions of the medium image) so the browser reserves the correct layout box before the image loads — no CLS. `sizes="(min-width: 768px) 33vw, 100vw"` on both `<source>` and `<img srcset>`.

**Pricing card in `bridal.blade.php` (Section 3 "The Package"):**
- Big price line: `Rs. 11,000 – 13,500` → **`Rs. 10,000`** (single flat rate, no dash).
- Sub-caption: "depending on design complexity" → **"all three nights, one flat price"**.
- Kept the two comparison rows:
  - Bridal Single (one event): Rs. 5,000 – 6,500 *(unchanged, based on the existing single-set tier)*.
  - Three singles separately: Rs. 15,000 – 19,500 *(unchanged — kept struck through as the anchor value the Trio saves against)*.
- Copy line under the two comparisons: "The Trio saves 10–15% vs ordering three singles" → **"The Trio bundles all three nights together — a meaningful saving vs. ordering three singles, and one less thing to coordinate during wedding planning."** (10,000 vs 15,000–19,500 is ~33–49% off, but the copy stays qualitative rather than shouting a big percentage — matches the atelier voice.)
- `data-price="11000"` on both "Add Trio to bag" buttons (hero + pricing card) → `data-price="10000"` via `replace_all: true` on `bridal.blade.php`.
- The bridal.blade.php in this branch still had the pre-fix `sticky top-24` on the pricing card (that fix from the 2026-05-19 session hadn't landed on this worktree yet). Removed `sticky top-24`; card now sits naturally in the grid. Added a matching comment: "Pricing card — sits naturally in the grid; sticky positioning was removed because it made the card follow the viewport as customers scrolled through the checklist beside it."

**JSON-LD schema (top of `bridal.blade.php`):**
- `x-seo :schema` inline JSON-LD `Product`'s `Offer.price` field: `'11000'` → `'10000'`.
- Meta description: `"...Handmade in Mirpur. From Rs. 11,000."` → `"...Handmade in Mirpur. Rs. 10,000 for all three nights."`

**Comparison table** further down the page:
- Row "Cost for 3 events" Trio cell: `Rs. 11,000 – 13,500` → `Rs. 10,000`. Salon acrylics comparison cell (`Rs. 7,500 – 15,000+`) unchanged.

**JS bag fallback:**
- Add-to-bag handler at the bottom of `bridal.blade.php`: `parseInt($(this).data('price') || '11000', 10)` → `... || '10000', 10)`.

**Other views:**
- `resources/views/home.blade.php`:
  - Bridal callout paragraph: "Order four weeks before your mehendi. Starting from Rs. 11,000." → "Order four weeks before your mehendi. Just Rs. 10,000 for all three nights."
  - Pricing tier table row for Bridal Trio: `Rs. 11,000+` → `Rs. 10,000`.
- `resources/views/shop.blade.php`:
  - Bridal callout banner: "Three sets, sized once, packaged in a magnetic keepsake box. From Rs. 11,000." → "... Rs. 10,000." (dropped "From" since it's now flat).

**Seeders (for reproducibility of fresh installs):**
- `database/seeders/DatabaseSeeder.php`: Bridal Trio Classic row's `price_pkr` was `12500` → `10000`. **Note:** the seeder value was 12,500, not 11,000, because it was set later than the view copy; this session brings them into agreement at 10,000.
- `database/seeders/BlogPostSeeder.php`: line 86 (inside the "Press-On Nails vs Acrylics: Which Is Better for Pakistani Brides?" post body): "The Bridal Trio from Nails by Mona costs PKR 11,000–13,500. ..." → "... costs PKR 10,000 flat for all three nights. ..." Seeder is idempotent via `firstOrCreate(['slug' => ...])`, so re-running it will NOT update the already-seeded blog post row on prod — see production DB update note below.

**Live DB update on production (not covered by seeder re-run):**
- The Bridal Trio product row in the live MySQL DB was at `price_pkr = 5000` (Mona had manually reduced it via Filament some time between seeding and this session). Updated to `10000` via tinker on prod:
  ```
  $p = App\Models\Product::where('slug', 'bridal-trio-classic')->first();
  $p->price_pkr = 10000; $p->save();
  ```
  Confirmed `before: 5000` → `after: 10000`.
- The blog-post body copy ("...costs PKR 11,000–13,500...") on the live DB was NOT updated this session. Since the post row is already seeded, re-running the seeder wouldn't touch it. If Mona (or a future session) wants the live blog body updated: either edit via Filament's BlogPostResource RichEditor OR run a targeted `BlogPost::where('slug', 'press-on-nails-vs-acrylics-pakistan-brides')->update([...])` on prod. Flagging this here so it doesn't get lost — the seeder is now correct for future installs but production still shows the old range in one blog post.

**Smoke test after deploy:**
- `curl -sS -o /dev/null -w "%{http_code}"` — `/bridal` → 200, all three new image URLs (jpg + webp) → 200.
- `curl -sS https://nailsbymona.pk/bridal | grep -oE "Rs\.[&nbsp; ]*[0-9,]+" | sort -u` returned:
  ```
  Rs. 0
  Rs. 10,000     ← Trio ✓
  Rs. 15,000     ← "three singles separately" (crossed out)
  Rs. 5,000      ← Bridal Single tier
  Rs. 7,500      ← Salon acrylics comparison range low end
  ```
  All expected. No stray `Rs. 11,000` / `Rs. 13,500` / `Rs. 12,500` on the page.

**Worktree confusion during this session — worth remembering:**
- The session started in worktree `.claude/worktrees/jovial-heisenberg-5302da` on branch `claude/peaceful-elion-ed28a3`. Halfway through the bridal image work the Edit tool started refusing writes to `jovial-heisenberg-5302da` with "belongs to a different worktree" — the session's isolation had been switched to a stale sibling folder `peaceful-elion-ed28a3/` that had the same content but was NOT the git-registered worktree.
- Diagnosis: `.git/worktrees/` registry showed only `dreamy-lederberg-07314e`, `epic-sanderson-dea15a`, and `jovial-heisenberg-5302da`. But the physical `peaceful-elion-ed28a3/` folder had its own `.git` file with `gitdir: .../.git/worktrees/peaceful-elion-ed28a3` pointing at a non-existent registry entry.
- Recovery: applied edits to `peaceful-elion-ed28a3/`, then `cp`'d every changed file (5 blade/PHP files + 12 new images) over to the real registered worktree at `jovial-heisenberg-5302da/`. Committed + pushed from there. Removed the stale `peaceful-elion-ed28a3/` folder afterwards.
- **Guidance for future sessions:** if you see a write refused with "belongs to a different worktree" and the target path looks correct, run `git worktree list` in the main repo AND `ls .git/worktrees/` — a physical folder that lacks a matching registry entry is a stale duplicate. Copy files to the registered worktree and commit there; don't try to `git rebase` or push from the ghost folder.

**Files touched in this session:**
- `app/Settings/StoreSettings.php` — 3 new bool properties.
- `app/Filament/Pages/ManageSettings.php` — Toggle components + `save()` bool handling + two guardrail notifications.
- `app/Http/Controllers/Order/OrderController.php` — dynamic `payment_method` validation gate + dedup redundant `$settings` reassignment.
- `resources/views/order/payment.blade.php` — conditional radio rendering + dynamic default-selected state + empty-state banner + disabled CTA.
- `resources/views/bridal.blade.php` — three panel image swaps + Rs. 10,000 flat trio pricing + copy refresh + drop `sticky top-24` + JS fallback price + schema price + meta description.
- `resources/views/home.blade.php` — bridal callout + pricing tier table row.
- `resources/views/shop.blade.php` — bridal callout banner.
- `database/seeders/DatabaseSeeder.php` — Bridal Trio Classic `price_pkr` 12500 → 10000.
- `database/seeders/BlogPostSeeder.php` — "vs acrylics" post body pricing line rewritten.
- `database/settings/2026_09_11_120000_add_payment_method_toggles.php` — new Spatie settings migration (3 bools).
- `public/images/bridal-mehendi-emerald-{480,960}.{jpg,webp}` — new (4 files).
- `public/images/bridal-baraat-beaded-{480,960}.{jpg,webp}` — new (4 files).
- `public/images/bridal-valima-ombre-{480,960}.{jpg,webp}` — new (4 files).

**Migrations applied this session (on prod, via deploy.sh):**
```
2026_09_11_120000_add_payment_method_toggles
```

**Commits (both on `main`):**
```
a085147  Admin toggle for payment method visibility
8c45fc5  Bridal: three real event photos + Rs. 10,000 flat trio price
```

---

### 2026-09-11 (later) — Instagram launch caption drafts (no code changes)

Humza asked for an Instagram Story caption to launch the website. Drafted three progressively-longer options (single slide / two slides / three slides), each addressing:
- The delay: "we were supposed to launch months ago but internet issues in our area pushed us back"
- The move to the website: browse / size / pay / track all happen there — no more DM hustle
- Explicit CTA: `nailsbymona.pk`, link-in-bio, "Visit website" story sticker, `@nailsbymona` tag

Also flagged three small distribution nudges: use the Story link sticker (higher CTR than link-in-bio), add a "reshare this to help us spread the word 💜" line on the last slide, and 3 hashtags (`#NailsByMona #PakistaniPressOns #CustomFitNails`). Offered Urdu / Roman-Urdu variants and a Reel voiceover version if wanted (Humza did not confirm in this session — pick up in a future one if he needs them).

**No code changes, no deploys.** Captured here purely so a future session knows the launch positioning language Humza has already blessed for social copy.

---

### 2026-09-16 → 2026-09-18 — Custom order links, camera-upload fixes, full payment up front

A long three-day session driven by Mona's feedback on live usage. Thirteen commits, all deployed to `https://nailsbymona.pk` via `/root/deploy.sh`.

**1. Cancelled orders polluting the awaiting-payment queues** (`fecf825`)

Cancelled orders keep their stale `payment_status` (`awaiting` / `verifying`), and every queue only filtered on payment status — so two cancelled orders sat permanently in the dashboard stat and the "Orders needing attention" widget. New `Order::scopeAwaitingPayment()` (Awaiting|Verifying **and** `status != cancelled`) is now the single definition, used by `OrderStatsWidget`, `OrdersNeedingAttentionWidget` and the Orders table's "Awaiting payment" / "Proof uploaded" filters. `payment_age_label` also returns null on cancelled orders. Prod queue went 3 → 1 immediately.

**2. Custom order links — the DM-order workflow** (`d9b4451`, `b4647ec`, `54bda6f`, `73ecf56`)

The gap: customers who ask for a design on Instagram/WhatsApp that isn't in the shop. Mona quoted them in chat, took payment by hand, and none of it existed in the system. Full design is in §7 "Custom (DM) order flow"; data model in §6 `custom_order_requests`.

Implementation notes worth keeping:
- **Session-driven, reuses the entire checkout.** `CustomOrderController::begin()` clears the order-form session, seeds `order_form.custom_request_id` + a one-item bag + customer prefill, then redirects into `/order/camera` (or `/order/details` for saved sizing). `OrderController::resolveBag()` returns `[$custom->toBagItem()]` when that session key is set, otherwise the usual `verifyBag()` — so the quoted price comes from the DB, never the client.
- **No reorder discount on custom orders** (the quote is the agreed price) — `calculateTotals()` takes the request and zeroes the discount.
- **One link = one order.** The request row is `lockForUpdate()`-ed inside the order transaction and flipped to `completed`; a second submit is bounced back to the link page ("already ordered").
- **`start()` redirects custom sessions back to the link page** so the "back to sizing" path doesn't dump the customer on the generic step-1 screen; `initFromBag()` / `start($slug)` clear `custom_request_id` so a shop checkout never inherits one.
- **Phone-optional (2026-09-17).** WhatsApp number became optional and `customer_instagram` was added, because Instagram DMs often have no number. Admin gets "Send on WhatsApp" (prefilled), "Copy message + link" (Alpine `clipboard.writeText` via `Actions\Action::alpineClickHandler()` + `Js::from()`), and "Open Instagram chat" (`ig.me/m/<handle>` — Instagram allows no prefilled text, hence the copy button).
- **Customer records are created with the link, not at checkout** (`syncCustomer()` on the `saved` model event): matched by phone then email, missing fields filled in, never overwriting existing values. Checkout passes that `customer_id` through the session so there's exactly one customer row. Backfilled the one pending prod link ("Aunty samera") by hand.
- **Customers admin:** Instagram shown + editable, a "Custom design links" section on the customer page, custom-link count in the table, filters "From a custom design link" and "Quoted but no order yet".
- **Reverted on Mona's request (`73ecf56`):** the "Record nail sizes" action on links (sizes are recorded on the order once placed, like website orders) and the separate `CustomOrderLinksWidget` dashboard row (dashboard is back to its original three widgets). Custom orders still flow into the normal widgets, marked `✦ Custom`. **Don't reintroduce either without asking.**

**3. Camera sizing upload — "stuck on Submitting…" on iPhone Chrome** (`b4647ec`, `d8b11aa`, `f8a20d3`, `fa002c8`, `a1337f4`)

Four rounds, because each fix revealed the next layer. Final diagnosis is the important part:

| Symptom | Reality |
|---|---|
| `fetch(FormData)` POSTs returning `400` with a 0-byte body, nothing in Laravel's log | iPhone Chrome mangles script-made multipart uploads |
| Button stuck on "Submitting…" | the failure was never surfaced — `alert()` after a failed `res.json()` didn't fire |
| "Almost done" then a ~66s wait | `xhr.upload.onprogress` reports bytes buffered, not bytes sent |
| Switching tabs made it finish in 4s | **the page still held the camera**; iOS suspends capture when the tab is hidden |

Measured on prod with a temporary nginx `log_format` (`$request_time` / `$request_length`, since removed): iPhone Chrome 705 KB → **66s** to arrive, PHP 0.3s; iPhone Safari 628 KB → **2.6s**. After releasing the camera on the preview screen: **3s**.

What shipped:
- **`goTo('preview')` now calls `stopStream()`** — camera released as soon as the last photo is taken; Retake / other-hand restart it (`startStream()` is a no-op when a stream exists). **This was the actual fix. Don't "optimise" the stream back to staying alive during preview.**
- **Native form POST on iOS third-party browsers** (`/CriOS|FxiOS|EdgiOS/`): a hidden `<form enctype="multipart/form-data">` with files attached via `DataTransfer`, falling back to `photos_base64[]` hidden fields; `native_submit=1` makes `OrderSizingPhotoController` redirect to `/order/details` instead of returning JSON. Safari/Android keep the XHR path with a progress %.
- **`OrderSizingPhotoController` accepts base64 data URLs** as well as files (`decodeDataUrl()`), and returns JSON 422s with friendly messages (or a redirect + flash for native submits) instead of Laravel's redirect-back.
- **Captures capped at 1600px / JPEG 0.85**, previews use data URLs, errors render inline (with an "upload from my gallery instead" link) rather than `alert()`, and `localStorage` remembers a device whose multipart uploads broke.
- **`camera-capture.js` is cache-busted** with `?v={{ filemtime(...) }}` — it lives in `public/js/`, outside Vite, so phones were holding the old file.
- **`NbmCamera.init()` now runs before the desktop-handoff branch**, so the "On a phone but seeing this screen?" escape hatch actually starts the camera.

**4. Reorder discount reset after a cancellation** (`b4647ec`)

Sizing-on-file made a customer "returning", which granted the 5% discount even when their only previous order was cancelled. Now `Customer::qualifiesForReorderDiscount()` requires an order at `confirmed` or later, re-checked on every checkout step (`OrderController::reorderDiscountApplies()`), and `orders.is_returning_customer` records that same meaning. Customers with a cancelled order still skip the camera — their details page says "Your sizing is on file" instead of promising a discount. Discount % + label now read from settings. One earlier prod order (NBM-2026-0005) kept its incorrectly-granted Rs. 175.

**5. Full payment up front** (`de51fc2`, `b78c0e6`)

Mona's call: the advance-then-balance flow meant finishing a set, then chasing a second payment by email that customers don't read. Every made-to-measure set is unsellable to anyone else, so:
- `calculateTotals()` sets `requires_advance = false` for all new orders. Checkout, confirmation page, order-placed + reminder emails, `/terms` and the payment FAQ all state full payment before production.
- **Legacy orders are respected:** `Order::advanceAmountPkr()` returns the total unless `requires_advance` is set, and `Order::isLegacyAdvanceOrder()` gates the advance copy plus the "Confirm: advance only" / "Balance received" actions. Fixed along the way: the confirmation page and reminder email had 30% / 50% **hardcoded** while settings said 25% / 100%.
- Advance + bridal-deposit fields removed from admin Settings (section renamed "Discounts"); the `StoreSettings` properties stay for legacy math.
- **WhatsApp status updates:** `Order::whatsappUpdateUrl()` / `whatsappUpdateMessage()` build a status-aware message (awaiting payment, confirmed, in production, shipped + courier tracking link, delivered + refit reminder). Phone normalization handles `0092…`, `+92…`, `03…` and bare `3…` (checkout stores the local 10 digits after a fixed +92 prefix).
- **Then trimmed back (`b78c0e6`)** on Mona's request — she doesn't want to send a message per stage. The per-action "Send on WhatsApp" notification was removed; emails remain the automatic channel, and the order's WhatsApp button stays as an optional grey action. `/terms` + FAQ say payment is confirmed **by email**.
- **WhatsApp Business API was discussed and explicitly deferred** (see §11 Phase 7.5). Until it exists, every message is manual — exactly what Mona doesn't want. The setup path (Meta business verification → dedicated number → approved templates → per-message utility pricing) was walked through; the "free 1,000 conversations/month" note in §25 is out of date.

**6. Nail sizes never saved — a 4-month-old bug** (`89aa450`)

Recording sizes always failed with Livewire's "Error while loading page". Cause: `customer_sizing_profiles.source_order_id` was created with `foreignUlid()` (`char(26)`) while `orders.id` is a 36-char UUID → `SQLSTATE[22001] Data too long for column 'source_order_id'` on every save since May. Migration `2026_09_18_090000_fix_sizing_profile_source_order_id_type` drops the FK, widens to `CHAR(36)`, restores the FK (MySQL path; SQLite is permissive). Verified saving on prod, then deleted the empty profile row the check created and reset that customer's `has_sizing_on_file`. **"Prod has 0 sizing profiles" was the tell — if a feature has never produced a row, suspect the schema.**

**Prod state at end of session:** first real custom-link order placed — **NBM-2026-0007**, "french tips and maroon nails", Rs. 5,000, `is_custom`, 2 sizing photos, awaiting payment. Customers: 5.

**Migrations applied this session:**
```
2026_09_16_120000_create_custom_order_requests_table        (+ orders.is_custom)
2026_09_16_150000_add_instagram_to_custom_order_requests    (phone made nullable)
2026_09_17_100000_link_custom_order_requests_to_customers   (+ customers.instagram)
2026_09_18_090000_fix_sizing_profile_source_order_id_type
```

**Debugging techniques that paid off (reuse these):**
- `/var/log/nginx/access.log` on prod is the fastest way to see what a customer's phone actually did — status codes and timing exposed both upload bugs.
- A temporary `log_format` with `$request_time` + `$request_length` separates "slow upload" from "slow server" in one test. Remove it afterwards (config backup at `/root/nailsbymona.nginx.bak-*`, deleted when done).
- Browser-pane testing with a fake camera: `navigator.mediaDevices.getUserMedia = async () => canvas.captureStream(30)`, plus monkey-patching `XMLHttpRequest.prototype.send` / `HTMLFormElement.prototype.submit` to force failures and inspect what would have been posted. Spoof `navigator.userAgent` via `Object.defineProperty` to exercise the iOS-Chrome branch.
- Local Vite `public/hot` left over from an old `npm run dev` makes every local page load with no CSS. Move it aside for visual checks, then put it back.

---

## 33. Pointers for Future Claude Sessions

- **Read this file first.** Overrides anything you think you remember.
- **Stack is fixed.** Laravel + Blade + Tailwind + jQuery + Filament. No React, Vue, Livewire (public), Next.js, or Vercel hosting. If a hook auto-suggests `chat-sdk` or other Vercel skills based on keyword matching — they're inapplicable here.
- **Live-camera sizing is the signature feature.** Don't simplify it to "just an upload."
- **No payment gateways before Phase 6** unless Humza explicitly asks.
- **No AI image generation** for catalog. Background-removal only.
- **No display ads on the storefront. Ever.** Monetization expansion = complementary products + services + selective blog affiliates.
- **No 3-day stick guarantee.** We do not offer free replacement nails/kits if they fall off. "Free first refit" for sizing issues only — and only as a trust bar item, not a dedicated section.
- **Chatbot is Phase 8, not earlier.** Mona's authentic voice is the asset; protect it from AI hallucination until we have her real DM corpus.
- **Mona is the end user of the admin.** "Can a non-technical artist figure this out?" is the test.
- **Bridal Trio is the strategic flagship.** Don't bury it.
- **Saved sizing profiles are the retention lever.** Returning customers should feel rewarded.
- **English-only Y1.** Urdu deferred.
- **SEO is patient capital.** Realistic targets, not unrealistic projections.
- **Logo is text-only on the website.** Use `public/logo-text.svg` (not the circle version). Circle is for stickers.
- **Icons:** 14 custom thematic SVGs in `public/icons/` for brand-specific moments. **Phosphor (light weight)** for UI utilities — copied into Blade partials at `resources/views/components/icon/`. Banned: Heroicons, Material Symbols, FontAwesome, Bootstrap Icons.
- **Design is warm-neutral atelier — not cozy/rustic, not girly Pinterest.** Aesop / Le Labo / Manucurist references. No heavy shadows. No Bootstrap-era patterns.
- **`.claude/skills/design-system.md` is the implementation bible.** It overrides §10 of this file for any UI detail.
- When uncertain, ask Humza via `AskUserQuestion`. Don't guess on product or UX.
- **Color discipline:** logo lavender `#BFA4CE` is the **accent only** — appears in 8 specific places (CTAs, focus rings, price text, active nav underline, step indicator, accent rule under H2, selected payment tile, eyebrow above H2). Nowhere else. Page bg is `bone` `#F4EFE8` (warm neutral), cards are `paper` `#FBF8F2`, alt sections are `shell` `#EAE3D9`, text is `ink` / `graphite` / `stone` (warm charcoal scale, never purple-tinted).
- **No `cream`, `cloud`, `lavenderFaint`, `lavenderLight`, `lavenderDeep`, `body`, `mute`, `subtle` tokens.** They were removed. Don't reintroduce them.
- **Fonts: Fraunces (display) + DM Sans (body).** Both Google variable fonts. Banned: Cormorant Garamond, Inter, Material Symbols Outlined.
- **No "Order Now" button. Anywhere.** Bag icon in nav is the only commerce CTA in the header. Product page CTA is "Add to bag" → drawer slides in.
- **No `/gallery` page.** UGC lives in Home + Product + Bridal sections via `ugc_photos` table.
- **Mona's face never appears on the website.** Hand-only photography forever. UGC with faces is excluded by `face_visible` flag.
- **No "Ask Mona" / "DM Mona" / "Hi Mona!" copy.** WhatsApp pre-fills are brand-addressed ("Hello Nails by Mona, ..."). CTAs are "Get help" / "Customer care".
- **About page copy is drafted** (2026-04-28 session). Real story from Humza. Mona must review and approve before publish. Key details: BA in Fine Arts, bridal mehndi background, resin crafts, Islamic/wudu personal origin story, 2 years of building from hobby. Copy goes in `docs/about-page-copy.md`.
- **Content architecture plan:** `/Users/humzasdesign/.claude/plans/claude-fetch-the-file-witty-creek.md` — page-by-page sections, H1s, SEO meta, Schema.org, copy direction for all 14 public routes.
- Plan files: original `/Users/humzasdesign/.claude/plans/claude-create-a-new-scalable-kahan.md` (scaffold), this CLAUDE.md is the condensed working reference.
- **Homepage file is `html/home.html`** (renamed from `html/index.html` on 2026-05-09). All internal links updated. When building Blade templates, the route `/` maps to the `home` view, not `index`.
- **All 13 static HTML mockups are complete** (2026-05-09): `home.html` · `shop.html` · `product.html` · `bridal.html` · `size-guide.html` · `about.html` · `contact.html` · `blog.html` · `blog-post.html` · `order-form.html` · `sizing-capture.html` · `order-confirmation.html` · `order-tracking.html`. The `html/` directory is the full visual reference for every public route.
- **Phase 0 + Phase 1 are complete** (2026-05-10). All 9 marketing Blade views + 4 order-flow stubs live.
- **Phases 0–4 are all complete.** Phase 5 (Polish & handoff) is next — real photography swap-in, favicon, SEO audit, `docs/mona-admin-guide.md`.
- **Blog infrastructure (Phase 4):** `BlogController` + `blog.blade.php` + `blog-post.blade.php` + sitemap + RSS + 5 seeded posts all live. `BlogPostSeeder` uses `firstOrCreate(['slug' => ...])` — safe to re-run. Category filter is client-side jQuery (all posts loaded in DOM). Do not add server-side pagination until post count > ~30 and filter is converted to URL params.
- **Filament v4 API (Phase 3 learnings — critical for Phase 2 Blade/Filament work):** `form()` and `infolist()` take `Schema $schema` (not `Form`/`Infolist`), return `Schema`. Use `use Filament\Schemas\Schema`. `$navigationIcon` and `$navigationGroup` need union types not `?string`. `$view` must be non-static. Full patterns in §32 session history (2026-05-13).
- **Sizing overlay SVGs** (created 2026-05-13): `public/icons/sizing-fingers.svg` + `public/icons/sizing-thumb.svg`. Use these as the visual reference for the Blade `<x-order.camera-capture>` component and the `resources/js/camera-capture.js` SVG overlay. Both are lavender `#BFA4CE` dashed U-shapes + coin circle. ViewBox: fingers = `0 0 400 480`, thumb = `0 0 300 480`.
- **Blade directive rules (learned in Phase 1 — do not repeat these bugs):**
  - `@push('head')` must contain only `<style>` / `<link>` HTML. Never put `@php` blocks inside a `@push`. Always close with `@endpush` on the very next blank line after the CSS.
  - `@php` blocks go outside any `@push`/`@section` — at the top-level between directives.
  - `<x-seo>` accepts `:schema` (singular, one JSON string). Merge multiple schemas using `['@context' => 'https://schema.org', '@graph' => [...]]` then `json_encode(..., JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)`.
  - Tailwind v4 uses CSS-based config — class names are kebab-case matching CSS variable names (e.g. `bg-lavender-wash`, `text-lavender-ink`), not camelCase.
- **Correct route names** (from `routes/web.php`): `home` · `shop` · `shop.show` · `bridal` · `size-guide` · `about` · `contact` · `contact.submit` · `blog` · `blog.post` · `order.start` · `order.sizing` · `order.confirm` · `order.track`. The contact form flash key is `contact_success`. The tracking route requires `{order}` UUID — there is no generic `/order/track` lookup URL.
- **`window.NbmBag` global API** (defined in `layouts/app.blade.php`): `add(item)`, `get()`, `save(items)`, `open()`. All product-page and blog-post "Add to bag" buttons call these. Do not add a separate bag implementation on individual pages.
- **Filament v4 production access (CRITICAL):** User model MUST implement `Filament\Models\Contracts\FilamentUser` with `canAccessPanel(): bool`. Without it, Filament v4 blocks all admin access in non-local environments. Already fixed — do not remove this interface.
- **Deploy script:** `/root/deploy.sh` on the server. Runs git pull + composer + migrate + optimize + worker restart in one command.
- **Queue worker:** Running via Supervisor. Config at `/etc/supervisor/conf.d/nailsbymona-worker.conf`. Logs at `storage/logs/worker.log`.
- **Desktop camera handoff (2026-05-14):** `sizing-capture` (HTML + Blade) shows a QR code "Open this on your phone" state when `isDesktopDevice()` returns true. Desktop detection: no mobile UA + (no touch hardware or wide screen). Camera init is fully skipped on desktop. QR uses `api.qrserver.com` image API. Order form Step 1 shows an inline warning note on the camera option for desktop visitors. The `isDesktopDevice()` function is duplicated in 4 files — keep them in sync if the logic ever changes.
- **Final finger SVG geometry (2026-05-16, user confirmed):** All gaps 13px. Pinky x=12–82 (70px, center 47), Ring x=95–170 (75px, center 132), Middle x=183–258 (75px, center 220), Index x=271–346 (75px, center 308). Heights in camera overlay (viewBox 400×870): middle topY=312, ring topY=348, index topY=379, pinky topY=451. Ring finger is ~93% of middle height.
- **Email price layout — Gmail gotcha:** Gmail strips `<style>` block CSS — flex layout is ignored. All `order-item` and `total-row` rows in email templates must use inline `style="display:table-cell; text-align:right; white-space:nowrap"` on price `<span>` elements. Never rely on CSS classes for email layout.
- **Email logo:** `public/logo-white.svg` exists (white fill version of logo-text.svg) for use on coloured email headers. The email layout header has `background-color:#BFA4CE` with the white logo as an `<img>` tag.
- **Reorder discount is 5%** (changed from 10% on 2026-05-16). Now stored as `StoreSettings::reorder_discount_percent` (Phase 5) — Mona can change it from `/admin/manage-settings` without a deploy. Applied in `OrderController::calculateTotals()`.
- **Returning customer indicator:** `OrderResource` Orders table shows `↩ Returning · phone` in the Customer column description for `is_returning_customer = true` orders. "Returning customers" filter also available.
- **Expense categories (current):** Materials & Supplies · Gel Nail Polishes · Packaging · Courier & Shipping · Marketing & Ads · Tools & Equipment · Utilities & Overheads · Other. Defined in `app/Enums/ExpenseCategory.php`. Gel Nail Polishes added 2026-05-16 (color `#D4847A`).

— **Phase 5 pointers** (added 2026-05-16 after the 5-block polish pass — see §32 entry):

- **Settings is the single source of truth.** Every payment/contact/shipping/lead-time/advance value comes from `App\Settings\StoreSettings` (exposed in views as `$settings`). `config/nbm.php` was deleted; **do not reintroduce it.**
- **WhatsApp URLs:** always `wa.me/{{ $settings->whatsappForWaMe() }}`. The helper returns digits-only — never `ltrim($..., '+')` manually because that doesn't strip spaces or dashes.
- **Bridal Trio takes full advance by default** (`$settings->bridal_deposit_percent = 100`). Standard advance is `$settings->advance_percent` (25). Both editable from admin Settings.
- **Lead times come from settings** (`lead_time_standard_days = 5`, `lead_time_bridal_days = 10`). Don't hardcode `addDays(5)` in any new view or email — pull from `$settings`.
- **Private files don't live on the public disk.** Payment proofs + sizing photos use `Storage::disk('local')` (= `storage/app/private/`). Read via `route('admin.private-file', [...])` only. Both photo models expose a `viewer_url` accessor for Filament infolist.
- **Order page authorization:** confirm/track/proof endpoints check `OrderController::sessionMayViewOrder($id)`. Visitors authorize by either (a) placing the order or (b) passing tracking lookup. UUID alone is not sufficient. Tracking lookup normalizes email (lowercase) + phone (digit-suffix match, handles `+92` / `92` / `0` prefixes).
- **Bag prices must be re-fetched server-side.** `OrderController::verifyBag()` looks up each item by slug from the `products` table before creating order items. Items without a slug or with an inactive product are dropped. qty hard-clamped 1–10 per line.
- **Order creation runs in `DB::transaction`.** `Order::generateOrderNumber()` uses `lockForUpdate()` + retry-on-unique-violation. Race-safe.
- **Notifications:** `NewOrderNotification` + `NewMessageNotification` `implements ShouldQueue`. Iterate users with `chunkById(50)` — **don't use `User::all()->each->notify()`** (sync, loads all into memory).
- **WCAG 2.2 — focus traps on overlays.** Bag drawer and mobile menu trap Tab/Shift+Tab + restore focus to trigger on close. ESC closes the visible overlay. Pattern is in `layouts/app.blade.php` `activateFocusTrap()` / `deactivateFocusTrap()`. Re-use the same pattern for any new modal/overlay.
- **Lazy loading.** Hero `<img>` tags use `loading="eager" fetchpriority="high"`. Everything below the fold uses `loading="lazy"`. **When bulk-editing `<img>` tags with regex, never use `[^>]*?>` to match — Blade expressions like `{{ $post->cover_image }}` contain `>` and the regex stops there mid-tag.** Use a Blade-aware parser that treats `{{ }}` / `{!! !!}` as opaque.
- **OG image.** Auto-generated by `php artisan og:generate` (Intervention + DejaVu fallback fonts). Output: `public/og-default.jpg` 1200×630. Re-run to refresh. A designer-made hand-only OG card is a Phase 5 polish item.
- **Legal pages.** `/privacy`, `/terms`, `/shipping` are live with real content at `resources/views/legal/*.blade.php`. Footer links use named routes (`route('privacy')` etc.) — do not revert to `url('/privacy')`.
- **Custom 404** at `resources/views/errors/404.blade.php` — sitelink tiles. Don't replace with Laravel's whoops default.
- **Refit workflow** (CLAUDE.md §16 promise is now tracked):
  - Columns: `orders.refit_requested_at`, `orders.refit_shipped_at`, `orders.refit_notes`.
  - Filament actions: "Refit requested" (Delivered orders without a refit, opens notes modal) and "Refit shipped" (after a refit is requested).
  - "Refit pending" filter sorts oldest-first.
  - Status column description shows `↺ refit pending` / `↺ refit shipped` for at-a-glance scanning.
- **OrderResource confirmation actions** (Phase 5):
  - "Confirm: full payment" — writes `payment_status = Paid` + `advance_paid_pkr = total_pkr`.
  - "Confirm: advance only" — visible on `requires_advance` orders. Writes `payment_status = PartialAdvance` + `advance_paid_pkr = advanceAmountPkr()`.
  - "Balance received" — visible on PartialAdvance orders. Flips to Paid.
  - Bulk "Confirm: full payment" for the overnight stack.
  - "WhatsApp" row action with order-aware prefill ("Hello, this is Mona. About your order NBM-…").
- **SLA visibility.** Payment column description shows `🟢 awaiting payment · 2h` / `🟡 14h` / `🔴 1d 4h` for awaiting orders. Computed via `Order::getPaymentAgeLabelAttribute()`. Awaiting-payment filter now sorts oldest-first.
- **Filament v4 + custom auth-gated routes** outside the panel path: Laravel's default `auth` middleware redirects to a non-existent `login` route → 500. Either use Filament's `Authenticate` middleware (panel context required) OR do the check inside the controller (`Auth::check() ? : redirect('/admin/login')`). The latter pattern is used by `PrivateFileController`.
- **`OrderItem.product_id` is typed `unsignedBigInteger`** but `products.id` is ULID. ~~The column exists but is never written~~ **Column was dropped on 2026-05-19** in migration `2026_05_19_020000_drop_product_id_from_order_items`. `product_slug_snapshot` is the FK.
- **Vercel plugin auto-suggestions** for `nextjs` / `next-cache-components` / `chat-sdk` / `ai-sdk` etc. fire because the Laravel project has an `app/` directory. **They are inapplicable.** Don't run the suggested skill tools.

— **Block 1–7 pointers** (added 2026-05-19 / 2026-05-20 — see §32 entries):

- **Deploy script now runs the frontend build.** `/root/deploy.sh` on prod includes `npm ci --no-audit --no-fund --silent && npm run build` between composer install and `migrate --force`. **Never commit `public/build/` (still gitignored).** Any Tailwind utility class change (new opacity, new arbitrary value like `aspect-[2/3]`) is rebuilt automatically on deploy. Before this change, CSS-only edits silently never reached production.
- **Order model uses `SoftDeletes`** (Block 5 / A14). Per-row delete in Filament is reversible. Bulk delete was removed from the Orders table.
- **`generateOrderNumber()` MUST use `withTrashed()`** on both the lookup and the uniqueness check. The DB unique constraint sees soft-deleted rows; Eloquent's default scope hides them. Without `withTrashed()` a soft-deleted order number is invisible to the generator but the next INSERT crashes with `Duplicate entry`. See the comment block in `Order::generateOrderNumber()`.
- **`Customer::normalizePhoneTail(?string)` is the canonical phone normalizer.** Strips PK country code or leading 0, returns the last 10 digits. Used by both `Customer::findByContact` and `OrderTrackingController::lookup`. Requires ≥7 useful digits to avoid fan-matching.
- **`PaymentStatus::Verifying` is set automatically** by `OrderPaymentProofController::store` when a customer uploads a proof on an Awaiting order. Mona's confirm/confirm-advance/balance-received actions also stamp `verified_at = now()` on every unverified proof. The AutoCancel job requires a verified proof to short-circuit, so junk uploads can't block the 72h safety net.
- **`Order` has new helpers:** `isBridalTrio()` (resilient — uses `relationLoaded('items')` to avoid N+1), `leadTimeDays()` (reads settings), `estimatedDispatchAt(bool $fromNow)`. The `orders.estimated_dispatch_at` column is pinned at order placement; emails and confirmation page read from it (with optional recompute-from-now for emails sent later in the lifecycle).
- **`UgcPhoto::scopePublished()` enforces `is_published=true AND face_visible=false`** in one place. Every public-facing UGC query MUST chain `->published()`. Future product/bridal carousel queries can't accidentally leak face-visible photos.
- **Filament v4 navigation icons:** EITHER on the group OR on the items, not both. We use item-level icons on every resource (in `$navigationIcon`). Groups in `AdminPanelProvider::navigationGroups()` are plain labels — do NOT add `.icon()` back to them. Doing so 500s the entire panel.
- **Filament deploy smoke test:** after any Filament-touching deploy, run a tinker render of `/admin` as a logged-in admin user:
  ```php
  \Auth::login(\App\Models\User::first());
  $resp = app(\Illuminate\Contracts\Http\Kernel::class)
      ->handle(\Illuminate\Http\Request::create('/admin','GET'));
  // assert status < 500 AND no 'Exception' in body
  ```
  Route 302 checks are NOT enough — the panel can boot-fail in ways that only surface when the sidebar actually renders.
- **Filament mobile drag-reorder** uses a SortableJS monkey-patch loaded via `panels::body.end` render hook (`resources/views/filament/sortable-mobile-tuning.blade.php`). Adds `delay: 250 / delayOnTouchOnly / scroll: true / scrollSensitivity: 80` defaults so phones can long-press to drag and the page auto-scrolls near edges.
- **Order pages session guard:** `OrderController::start()` and `storeSizing()` redirect to `/shop` with a flash `@error('bag')` when the session bag is empty in production. `/shop` view renders an error banner at the top of the page. Don't undo this — without it, customers loop infinitely between `/order/start` and `/order/details` when their session bag is missing.
- **Sizing photos on `/size-guide` are real, not Google AI placeholders any more.** Six photos live in `public/images/sizing/` as JPG + WebP pairs:
  - `fingers-reference`, `thumb-reference`, `fingers-good-alt` (Good gallery)
  - `bad-blurry`, `bad-thumb-too-far`, `bad-busy-background` (Avoid gallery)
  Each is 900×1350 (true 2:3 portrait, top-anchored except bad-thumb-too-far). When Mona delivers new photos, process via `sips -r {90|270}` for rotation, Python+PIL for the 900×1350 top-anchored crop, then `cwebp -q 82` for the variant. Replace the JPG/WebP pair, run `php artisan view:cache` to refresh, deploy.
- **The remaining Google AI placeholders** (`lh3.googleusercontent.com/aida-public/...`) live in `home.blade.php`, `about.blade.php`, `shop.blade.php` (bridal callout). These will pull from the Google AI CDN with no SLA — replace as photography becomes available.
- **`og:locale=en_GB`, not `en_PK`.** Facebook silently drops unknown locales. `hreflang="en-PK"` carries the Pakistan signal for Google instead.
- **Schema.org product availability:** `made_to_order` → `https://schema.org/MadeToOrder` (NOT `PreOrder` — that implies a future release date).
- **Product page FAQs are DB-driven.** `ShopController::show()` passes `$faqs` from the `general` category. Product view loops them with `aria-expanded + aria-controls`. Falls back to a hardcoded 5-question set if the table is empty.

— **2026-09-16 → 18 pointers** (custom links, camera upload, full payment — see §32 entry):

- **Every order is paid in full up front.** Don't reintroduce partial-advance copy or settings. `Order::advanceAmountPkr()` returns the total; only `Order::isLegacyAdvanceOrder()` orders (placed before 2026-09-16 with `requires_advance = true`) show advance/balance copy and the "Confirm: advance only" / "Balance received" actions.
- **`Order::scopeAwaitingPayment()` is the only correct "awaiting payment" query.** Cancelled orders keep a stale `payment_status`, so any new queue / stat / filter must chain it (or repeat the `status != cancelled` check).
- **Reorder discount = a previous order at `confirmed` or later**, via `Customer::qualifiesForReorderDiscount()`, re-checked each step by `OrderController::reorderDiscountApplies()`. Sizing-on-file alone is not enough. Custom-link orders never get the discount.
- **Custom links:** `CustomOrderRequest` + `/custom/{token}`. Checkout is the normal one — `OrderController::resolveBag()` prices custom checkouts from the request row. Creating a link creates/matches a `Customer` (`syncCustomer()`); the checkout reuses that `customer_id`, so never `firstOrCreate` a second customer for these. Links expire (7 days default) and place exactly one order (row locked in the order transaction).
- **Deliberately NOT on custom links:** nail-size recording (sizes belong on the placed order, like website orders) and a separate dashboard widget. Mona asked for both to be removed on 2026-09-17 — ask before adding them back.
- **Never let the camera page hold the stream while uploading.** `goTo('preview')` calls `stopStream()`; iPhone Chrome uploads crawl (30–120s for ~700 KB) while a page holds the camera, and finish in ~3s once it's released.
- **iOS third-party browsers (`CriOS|FxiOS|EdgiOS`) upload sizing photos via a native `<form>` POST**, not XHR — `native_submit=1` makes `OrderSizingPhotoController` redirect instead of returning JSON. The endpoint accepts `photos[]` files *or* `photos_base64[]` data URLs; keep both paths working.
- **`public/js/camera-capture.js` is outside Vite** — it must stay cache-busted (`?v={{ filemtime(...) }}`) or phones keep serving the old file.
- **`customer_sizing_profiles.source_order_id` is `CHAR(36)`** because order ids are UUIDs. Don't "tidy" it back to `foreignUlid()`. One profile per customer; `CustomerResource::nailSizesFormData()` / `saveNailSizes()` are the shared helpers the order action uses.
- **WhatsApp messaging is manual and optional.** `Order::whatsappUpdateUrl()` powers one grey button on the order; there are no per-stage prompts, and customer-facing copy promises email confirmation. The Business API is Phase 7.5 — Mona rejected the manual-per-stage workload, so don't wire automatic WhatsApp before the API exists.
- **`robots.txt` disallows `/custom/`** alongside `/admin`, `/order/`, `/track`.

— **2026-09-24 pointers** (admin tap targets + proof alerts):

- **Order rows open on a tap anywhere** — `->recordUrl(...view...)` on the Orders table, `RecentOrdersWidget` and `OrdersNeedingAttentionWidget`. Don't put `->copyable()` back on list columns; it grabs the tap and copies instead of opening. (Copy buttons stay on the order's view page.)
- **Admin notifications go to the bell and to push.** The panel has `->databaseNotifications()` (polls every 30s, `notifications` table). `NewOrderNotification` and `PaymentProofUploadedNotification` both use `['database', WebPushChannel::class]`; the proof one is sent from `OrderPaymentProofController::store`. Both link to the order's **view** page.
- **`public/sw.js` reads `data.data.url`** — `WebPushMessage` nests custom data, so before this fix tapping a push always opened `/admin`.
- `PaymentStatus::Verifying` shows as **"Proof uploaded"** in the admin.
- **Dashboard widgets are not lazy** (`$isLazy = false`) and `OrderStatsWidget` has `$pollingInterval = null` — Filament's default 5s stats polling queued requests ahead of everything else on slow phones. Don't turn either back on.
- **Public-disk images are optimized.** `App\Support\ImageOptimizer` scales originals to 2400px (JPEG q90) and writes a `name-1080.webp` variant (q88) — first pass used 1600px/640px and Humza found it too soft, so keep it generous; `img_variant($path)` serves it in grids/cards (falls back to the original). New uploads are processed on save (AppServiceProvider model events) and Filament FileUploads resize on-device first (2400px). **Sizing photos (private disk, live camera guide) are never touched by this — leave the camera guide's capture settings alone unless Humza asks.** Backfill/redo: `php artisan images:optimize [--force]`. Pre-optimization backup of the live photos: `/root/public-images-backup-20260924-*.tar` (the shop page was ~20 MB of 4–7 MB camera originals).
- **nginx (prod) now has** `http2`, gzip for CSS/JS/SVG/JSON (`/etc/nginx/conf.d/nbm-performance.conf`), 1-year immutable cache on `^~ /build/`, 7-day cache on static images/css/js. Previous site config backed up to `/root/nailsbymona.nginx.bak-20260924-*`. Server is in **Singapore**.
- **The wudu post's slug is `muslim-women-press-on-nails-wudu`.** Two wrong slugs that used to be linked 301 to it (routes/web.php). Google Fonts load non-blocking (preload + `media="print"` swap).
- **Analytics lives in `resources/views/partials/analytics.blade.php`** (GA4 `G-ZC5X3P3PT4` + Clarity `wrhay4fga3`), included by BOTH `layouts.app` and `layouts.order` — the checkout had no tracking before 2026-09-24. Funnel events are queued server-side with `App\Support\Analytics::queue()` and sent once by the next rendered page: `begin_checkout` (bag-init / custom link begin) → `sizing_completed` → `add_shipping_info` → `purchase` (transaction_id = order number, value = total PKR). Client-side: `add_to_cart` inside `NbmBag.add()`, `payment_proof_uploaded` on the confirm page. Use `window.nbmTrack(name, params)` for new events (sends to GA + Clarity). Every add-to-bag button must go through `NbmBag.add()` with a `slug`.
- **Bridal Trio add-to-bag had no slug until 2026-09-24**, so checkout's `verifyBag()` dropped it and customers were bounced to /shop with an empty bag. Buttons now carry `data-slug="bridal-trio-classic"`.
- **Home page redesign is in prototype, not live (2026-09-24).** Research + plan: `docs/ux/home-redesign-2026-09.md`; prototype: `html/home-v2.html` (preview via the `html-mockups` launch config → http://localhost:8081/home-v2.html). Driven by GA/Clarity: ad visitors (≈60% of traffic, ~80% in Instagram's in-app browser) spent ~6 s on home, scrolled ~12%, and the first product/price sat 4 screens down on an 18.5-screen page. Prototype: compact uncovered hero with price, 2-col product grid in the first screen, chips, 3-step fit, why press-ons, compact Bridal Trio, real UGC, payment/trust, mini FAQ, sticky mobile CTA — 8.1 screens. Only real proof: no invented reviews/counts ("Mona's pick" = `is_featured`, not "bestseller"). Awaiting Humza's go-ahead before porting to `home.blade.php`.
- **`storage/app/public/ugc/01KRKC22…jpg` and `01KRKDDF…jpg` are tracked in git but differ on prod** (live data). Never commit changes to them — `git pull` on prod will refuse and the deploy stops.

— **2026-09-11 pointers** (payment toggles + bridal refresh — see §32 entry):

- **Payment methods are individually hideable from `/admin/manage-settings`.** Three `StoreSettings` booleans (`jazzcash_enabled`, `easypaisa_enabled`, `bank_transfer_enabled`) gate whether each `<label class="payment-option">` on `/order/payment` renders at all. All default true. `payment.blade.php` picks `$firstEnabled` at render time and auto-selects that radio; the `<button id="place-order-btn">` is `@disabled(! $firstEnabled)`. If all three are off, an empty-state card points customers to WhatsApp.
- **`OrderController::store()` server-validates `payment_method` against the currently-enabled set,** not a hardcoded `in:jazzcash,easypaisa,bank_transfer` list. A stale customer form-post for a disabled method is rejected with a `withErrors` back to `/order/payment`. Don't reintroduce the hardcoded validator — a mid-checkout admin toggle would slip past it.
- **`ManageSettings::save()` has two persistent warning notifications:** (1) all three payment methods off = customers can't check out; (2) an enabled method has blank primary credentials (`jazzcash_number` / `easypaisa_number` / `bank_account_no+bank_iban`) = customers picking it will see empty "Send to:" lines. A disabled method with blank credentials is deliberate and stays silent.
- **Bridal Trio is Rs. 10,000 flat** (not a range). Every reference: hero meta description, JSON-LD `Offer.price` = `'10000'`, pricing card big line, comparison table row, home + shop callouts, seeder `price_pkr`, "Add Trio to bag" `data-price="10000"` on both hero + pricing buttons. If Mona later wants to reintroduce a range or bump the price, all edit sites are cross-referenced in the 2026-09-11 §32 entry above.
- **Bridal event photos are Mona's real work now.** `public/images/bridal-mehendi-emerald-{480,960}.{jpg,webp}` (emerald), `bridal-baraat-beaded-{480,960}.{jpg,webp}` (gold-beaded), `bridal-valima-ombre-{480,960}.{jpg,webp}` (ombre bride). All are 3:4 portrait crops (960×1280 medium, 480×640 small). The old `bridal-baraat-deep-red-*` and `bridal-valima-french-*` files still exist in `public/images/` but are unreferenced — safe to delete in a future cleanup pass.
- **Live DB blog post drift:** the "vs acrylics" post's seeded body copy still says "PKR 11,000–13,500" on prod even though `BlogPostSeeder.php` was updated to "10,000 flat". The seeder is `firstOrCreate(['slug' => ...])` so re-running does NOT rewrite the body. To fix on prod: edit via Filament BlogPostResource RichEditor, or run a targeted `BlogPost::where('slug', 'press-on-nails-vs-acrylics-pakistan-brides')->update(['content' => ...])` on prod. Flagged but not fixed this session because the post body is long-form and Mona should review any rewrite.
- **`Order` sizing photos aggregation:** `Customer::sizingPhotosFromOrders()` (Block 5, A13) is the source for the collapsible grid on `CustomerResource` view page. If a future session adds a saved-sizing-profile carousel to the customer-facing site, reuse this accessor rather than writing a new query.
- **Phantom-worktree hazard.** A session's isolation can point at a physical folder that is NOT registered in `.git/worktrees/`. Symptom: Edit tool refuses writes to the "correct" path with a "belongs to a different worktree" error. Diagnosis: `git worktree list` in the main repo shows the real registered set; `ls .git/worktrees/` shows the registry entries; a physical folder without a matching registry entry is a stale duplicate. Recovery: apply edits to the phantom folder, then `cp` every changed file into the real registered worktree, commit and push from there. Delete the phantom afterwards.
- **Vercel plugin hooks continue to auto-suggest Next.js / next-cache-components / nextjs skills** on every `app/**` Read. Continue to ignore per §33 — this remains a Laravel project.

---

## 34. Content Architecture (page-by-page reference)

> Full detail in `/Users/humzasdesign/.claude/plans/claude-fetch-the-file-witty-creek.md`. This section is the compact quick-reference.

### Global — Header + Footer

**Header (hybrid nav):**
- **Logo** left
- **Desktop ≥1024px center nav:** Shop · Bridal · About · Journal · Help (5 items, "Journal" labels `/blog`, "Help" labels `/contact`)
- **Right utilities:** Search icon (track order) · **Bag icon** with count badge · Hamburger (only <1024px)
- **No "Order Now" button anywhere.** Bag icon is the only commerce CTA in the header.
- **Mobile/tablet <1024px:** hamburger → full-screen overlay with editorial-sized links + secondary links (Help, Size Guide, Track Order, Care & Reuse) + Instagram/WhatsApp icons in footer of overlay.

**Footer:**
- Trust line: "Handmade in Mirpur. Shipped across Pakistan." (no founder-personal language)
- Columns: Shop · Help · About · Journal
- Social: Instagram · WhatsApp · TikTok (icons only)

### WhatsApp pre-fill per page (all brand-addressed, never personal)

| Page | Message |
|---|---|
| `/` | "Hello Nails by Mona, I'd like to enquire about a custom set." |
| `/shop` | "Hello Nails by Mona, I'm browsing your shop and have a question." |
| `/shop/{slug}` | "Hello Nails by Mona, I'm interested in [Product Name]." |
| `/bridal` | "Hello Nails by Mona, I'm interested in the Bridal Trio for my wedding." |
| `/size-guide` | "Hello Nails by Mona, I need help with my sizing photo." |
| `/contact` | "Hello Nails by Mona, I have a question." |
| `/order/confirm/{order}` | "Hello Nails by Mona, here's my payment proof for order NBM-XXXX." |
| `/order/{order}/track` | "Hello Nails by Mona, I have a question about order NBM-XXXX." |

**CTA labels:** "Get help", "Customer care", "Contact us". **Banned:** "Ask Mona", "DM Mona", "Talk to Mona".

---

### `/` — Home
**H1:** "Custom-fit press-on nails, made by hand."
**Meta title:** `Nails by Mona — Custom-Fit Press-On Gel Nails, Pakistan`
**Schema:** `Organization` + `WebSite`
**Hero image:** full-bleed hand close-up (no face). Overlay `bg-ink/30` for legibility.
**Sections:** Hero · Trust bar (Made to fit · Wudu-friendly · Reusable 3–5×) · "Finally, nails that actually fit" · Featured designs (6) · Bridal Trio callout · **"Worn across Pakistan" UGC section (absorbs the old gallery — hand-only photos, 6–9 tiles)** · Studio teaser (hand+tools, no face) · How it works (4 steps) · Pricing tier table · Journal teaser (3 posts)

---

### `/shop` — Shop
**H1:** "Find your perfect set."
**Meta title:** `Shop Press-On Nails Pakistan — Custom-Fit Gel Sets | Nails by Mona`
**Schema:** `ItemList`
**Sections:** Minimal hero · Sticky filter bar (tier + sort, jQuery) · Product grid (3-col) · Empty state · Trust strip · Bridal callout banner

---

### `/shop/{slug}` — Product Detail
**H1:** Product name
**Meta title:** `{Product Name} — Custom-Fit Press-On Nails Pakistan | Nails by Mona`
**Schema:** `Product` + `FAQPage` + `BreadcrumbList`
**Sections:** Image gallery (swipe on mobile, hand-only shots) · Product info + **"Add to bag"** (primary CTA) + **"Get help"** (secondary, ghost link) · 3-tab accordion (About / Sizing & Fit / Care & Reuse) · FAQ block (4–6 from `faqs` table) · **UGC carousel: "Worn by customers" (3–6 hand-only photos from `ugc_photos` where `placement = product_carousel` and `face_visible = false`)** · Related products (3) · Related Journal posts (2)
**On Add to bag:** bag drawer slides in from right with the item + subtotal + Checkout button.

---

### `/bridal` — Bridal
**H1:** "Your wedding nails, for all three nights."
**Meta title:** `Bridal Press-On Nails Pakistan — Mehendi, Baraat & Valima Trio | Nails by Mona`
**Schema:** `Product` + `FAQPage` + `BreadcrumbList`
**Target keywords:** "bridal press on nails Pakistan", "mehendi nails Pakistan"
**Sections:** Hero (hand on red velvet, gold thread; no face) + **"Add Trio to bag"** primary CTA + "Get help" secondary · Three nights editorial panels (Mehendi / Baraat / Valima — hand-on-fabric photography) · What's included checklist + pricing block · "Order 4 weeks before Mehendi" timeline · **Real bridal gallery (6–9 photos, hand-only — `ugc_photos` where `placement = bridal_gallery`)** · Comparison table (Trio vs. acrylics) · 5 bridal FAQs

---

*(Gallery page removed — UGC consolidated into Home, Product detail, and Bridal sections.)*

---

### `/size-guide` — Size Guide
**H1:** "Getting your size right — it takes two minutes."
**Meta title:** `Press-On Nail Size Guide — How to Measure for Custom Fit | Nails by Mona`
**Schema:** `HowTo` + `BreadcrumbList`
**Sections:** Hero · "What you'll need" (3 items) · 4-step illustrated guide (hand-only photography) · Good vs. bad examples (6 tiles) · Live camera guide callout · Saved-profile section for returning customers · "Need help with your photo? Get help" link → WhatsApp (brand-addressed pre-fill)

---

### `/contact` — Help
**H1:** "How can we help?"
**Meta title:** `Help & Customer Care — Nails by Mona`
**Schema:** `LocalBusiness` + `BreadcrumbList`
**Nav label:** "Help" (not "Contact")
**Sections:** Hero · WhatsApp + socials (2-col, brand-addressed) · Contact form (5 fields + subject dropdown) · 4 common-question tiles · Business hours
**Note:** No personal phone number listed. WhatsApp is the customer-care channel. All pre-fills address "Nails by Mona," never "Mona."

---

### `/about` — About
**H1:** "Made by hand. In Mirpur. With care."
**Meta title:** `About Nails by Mona — Custom-Fit Press-On Gel Nails, Mirpur AJK`
**Schema:** `Organization` + `BreadcrumbList` *(removed `Person` schema — no founder face means no Person markup)*
**Hero:** hand portrait — hands working on a press-on set, with a handwritten "Mona" signature SVG overlaid in the corner. **No face image, ever.** Caption (small, italic): "Hi, I'm Mona — and these are my hands."
**Sections:** Hero (hand+signature) · "How this started" (5 first-person paragraphs in Mona's voice) · Studio photos (3–4 documentary panels — tools, materials, process; no face) · Process timeline (6 steps, illustrated) · "Why I don't take shortcuts" · 3 trust cards (first-person, e.g. "I refit your first set free if it doesn't sit right.") · 3–5 real testimonials (text only, or hand photo from happy customer with attribution by first name + city) · Final CTA: **"Browse the collection"** → `/shop` (never "Talk to me" / "DM me")

**Mona's real story (supplied by Humza 2026-04-28) — key beats:**
- From childhood in Mirpur: always drew, always decorated, always the one doing art at school
- BA in Fine Arts — the natural degree for someone who had no other direction
- At university: outperformed every colleague, multi-skilled artisan
- University + post-grad work: bridal mehndi (henna artist), acrylic designs, paintings, resin proxy plates + name plates (still sells)
- The Islamic insight — Mona is a practicing Muslim. Traditional nail polish + acrylics are not wudu-compatible (water can't reach the nail bed). She wanted beautiful nails but couldn't wear them. Press-ons can be removed before wudu and reapplied after. This is the personal problem she set out to solve — for herself first, then for every other woman in the same position.
- The bridal insight — Pakistani wedding season demands nails, but acrylic removal mid-season is painful and damaging. She saw this as another gap.
- Self-doubt before family support changed everything. ~2 years ago she started as a hobby.
- Built skill, Instagram, customers across Pakistan. Trust is real — built order by order.

**Draft About copy is written in this session — see below in the response text. Mona must review and approve before publish.**

---

### `/blog` — Journal
**Nav label:** "Journal" (route stays `/blog`)
**H1:** "From the studio."
**Meta title:** `Journal — Nail Care, Bridal Guides & Press-On Tutorials | Nails by Mona`
**Schema:** `Blog` + `BreadcrumbList`
**RSS link in `<head>`**
**Sections:** Hero · Category filter pills (jQuery) · Featured post (full-width, hand-only cover image) · Post grid (3-col) · Email subscribe strip → `subscribers` table

---

### `/blog/{slug}` — Journal Post
**H1:** Post title (exact keyword match in natural form)
**Meta title:** `{Post Title} | Nails by Mona`
**Schema:** `Article` + `FAQPage` + `BreadcrumbList`
**Body rules:** Start with substance (not "in this article…") · Real photos every 3–4 paragraphs (hand-only) · Internal links to 2–3 product pages + `/size-guide` or `/bridal` as appropriate · Author byline shows "Nails by Mona Studio" or "Mona" as text only — no face avatar
**Sections:** Breadcrumb · Meta bar · Cover image (hand-only) · Body (6–10 H2s) · FAQ accordion (5–7 from `faqs`) · Related products (3, via `blog_post_products` pivot) · Author block (text only, no avatar) + "Get help" link · Related posts (3)

**4 cornerstone posts at launch:**
1. "Press-On Nails vs Acrylics: Which Is Better for Pakistani Brides?"
2. "How to Apply Press-On Nails — A Foolproof 7-Step Guide"
3. "Bridal Nail Trends in Pakistan for 2026"
4. "How to Remove Press-On Nails Without Damaging Your Natural Nails"

---

### `/order/start/{slug?}` — Order Form
**noindex**
**Header:** Logo only + "Get help" link (no main nav)
**Entry:** From bag drawer "Checkout" button. Bag contents (`localStorage` JSON) are POSTed and stored in session for the duration of the order flow.
**Progress bar:** Step 1 (Sizing) → Step 2 (Details) → Step 3 (Payment) — separate URL per step
**Step 1:** Returning-customer lookup · 3 radio cards (Live camera / Upload / WhatsApp later) · sizing_capture_method tracked
**Step 2:** Name, email, WhatsApp (+92 pre-fill), address, city dropdown, notes · Reorder -10% callout · Price summary (from bag)
**Step 3:** 3 payment radio cards (JazzCash / EasyPaisa / Bank Transfer) with account details rendered server-side from `settings`. All manual. Submitting lands the customer on `/order/confirm/{order}` with the selected method's account details + proof-upload field. Advance/bridal deposit notice shown when applicable. **No COD** (removed 2026-04-30). **No card at MVP** (deferred to Phase 6 — see §26).

---

### `/order/sizing-capture` — Camera Capture
**noindex**
Single camera session, **2-photo state machine** (fingers row → thumb) with optional opt-in for 2 more photos for the other hand (perfect-fit path). Each state shows a dedicated SVG overlay + coin circle for that photo type. Brightness pill (green/amber, 500ms canvas sampling) + edge-contrast alignment border (green when overlay region matches a likely hand+coin; red otherwise — heuristic guide only, does not block capture). Side-by-side preview after the 2 (or 4) photos with per-photo "Retake" links + symmetry disclaimer + perfectionist opt-in.
Fallback: 2 (or 4) labelled file inputs with good/bad thumbnail strip.
**Never auto-start camera** — user presses "Start camera" first (reduces permission declines). **Permission asked once for the whole session**, not per photo.

---

### `/order/confirm/{order}` — Confirmation
**noindex**
Success icon (lavender checkmark) · "Your order is placed, dear. Thank you." · Order summary · Conditional payment card (by method) · Payment proof upload (AJAX, `order_payment_proofs`) · "What happens next" 3-step timeline · "Track this order" link

---

### `/order/{order}/track` — Tracking
**noindex** · URL uses UUID (not integer ID)
Order number + contact lookup (rate-limited) · Vertical status timeline (5 nodes: Placed → Payment → Production → Shipped → Delivered) · Courier tracking links from `config/couriers.php` · Refit reminder banner if delivered within 7 days

---

### Schema.org quick reference

| Route | Schema types |
|---|---|
| `/` | `Organization`, `WebSite` |
| `/shop` | `ItemList` |
| `/shop/{slug}` | `Product`, `FAQPage`, `BreadcrumbList` |
| `/bridal` | `Product`, `FAQPage`, `BreadcrumbList` |
| `/gallery` | `ImageGallery`, `BreadcrumbList` |
| `/size-guide` | `HowTo`, `BreadcrumbList` |
| `/contact` | `LocalBusiness`, `BreadcrumbList` |
| `/about` | `Person`, `Organization`, `BreadcrumbList` |
| `/blog` | `Blog`, `BreadcrumbList` |
| `/blog/{slug}` | `Article`, `FAQPage`, `BreadcrumbList` |
| `/order/*` | None (noindex) |
