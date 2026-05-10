# Page: Contact `/contact`

> ⚠ **2026-04-29 design revision applies.** Canonical design tokens, nav pattern, photography rules, copy guidelines, and CTA labels are in `00-global.md` and `.claude/skills/design-system.md`. In particular: bag-drawer pattern (no "Add to bag"), brand-addressed WhatsApp pre-fills (no "Get help"/"Hello Nails by Mona,"), hand-only photography (no faces), Fraunces + DM Sans fonts, warm-neutral palette (`bone`/`paper`/`shell`/`ink`/`graphite`/`stone`), `/gallery` removed.

---

> Design tokens, typography, and patterns: see `00-global.md` + `.claude/skills/design-system.md` — those files are authoritative.

---

**Purpose:** Give customers a warm, non-intimidating way to reach Mona — and pre-answer the questions most people would message about.
**Template:** `resources/views/contact.blade.php`
**Layout:** `layouts.app`
**Schema.org:** `LocalBusiness` + `BreadcrumbList`

---

## SEO

| Field | Value |
|---|---|
| H1 | Let's talk. |
| Meta title | Contact Nails by Mona — WhatsApp, Instagram & Message Form |
| Meta description | Get in touch with Mona for custom nail orders, bridal inquiries, or sizing help. WhatsApp is fastest. Based in Mirpur, AJK — shipping Pakistan-wide. |
| Canonical | `https://nailsbymona.pk/contact` |
| OG image | Default brand OG (no specific photo needed) |
| Breadcrumb | Home > Contact |

---

## Section 1 — Hero

**Background:** `bg-paper`
**Height:** Slim — no full-height hero. Get straight to the contact options.

**H1 (serif, font-light):**
> Let's talk.

**Subheadline:**
> I reply personally. WhatsApp is fastest — usually within a few hours.

*No image in hero — warm, clean, conversational.*

---

## Section 2 — "How to reach me"

**Background:** `bg-paper`
**Layout:** 2-column (desktop), stacked (mobile)

---

**Left column — WhatsApp (primary, most prominent)**

WhatsApp icon (`whatsapp.svg`, large, green) centred above text.

**Heading (H3):** Fastest: WhatsApp

Large CTA button:
> "Message Mona on WhatsApp →"

→ WhatsApp deep-link (pre-filled: "Hello Nails by Mona, I have a question about an order.")

**Hours note (small, text-mute):**
> I'm usually available 10am–9pm PKT, Monday to Saturday. I do read late messages — I just might reply in the morning, dear.

---

**Right column — Other channels**

**Instagram:**
`instagram.svg` icon + @nailsbymona
> "DMs welcome — I check Instagram daily."

**Email:**
Envelope icon (Heroicons) + [email address — fill in before launch]
> "For longer questions or order concerns. I aim to reply within 24 hours."

**Location:**
Map pin icon (Heroicons) + "Mirpur, Azad Kashmir, Pakistan"
> "All orders shipped nationally. No in-person studio visits."

---

## Section 3 — Contact Form

**Background:** `bg-shell`
**Layout:** Centred, max-width 640px

**Heading (H2):** Or leave me a message here.

**Form fields:**

| Field | Type | Required | Placeholder |
|---|---|---|---|
| Full name | text | Yes | Your name |
| Email address | email | Yes | your@email.com |
| Phone / WhatsApp | tel | No | +92 — "Recommended for faster reply" |
| Subject | select | Yes | What's this about? |
| Message | textarea (4 rows) | Yes | Tell me what you need... |

**Subject dropdown options:**
- General inquiry
- Order question
- Bridal inquiry
- Sizing help
- Something else

**Submit button:** "Send message" (`bg-lavender rounded-full`)

**Below button (small, text-mute):**
> I read every message myself and reply within 24 hours. If it's urgent, WhatsApp is much faster.

**On success (inline — no page redirect):**
> *Your message is with me now. I'll get back to you soon, dear. — Mona*

Show as a green success strip above the form, form fields remain (don't clear immediately).

**Backend:** POST → `ContactMessageController@store` → saves to `contact_messages` table → sends email notification to Mona's email address → `throttle:5,1` rate limit.

---

## Section 4 — "Most people message me about one of these:"

**Background:** `bg-paper`

**Heading (H2):** Most people message me about one of these.

**4 question tiles (jQuery accordion — click to expand answer):**

**Tile 1 — Sizing**
*Q: I don't know how to take my sizing photo*
A: The size guide walks you through it step by step — with photos for each step and good/bad examples.
Link → `/size-guide`

**Tile 2 — Order Status**
*Q: I placed an order and want to know where it is*
A: Use the order tracking page — enter your order number and the email or phone number you used when ordering.
Link → `/order/{order}/track` (tracking page — explain the lookup process)

**Tile 3 — Payment**
*Q: What payment methods do you accept?*
A: I accept JazzCash, EasyPaisa, and bank transfer. Your account details are sent automatically on the confirmation page after you place your order, and you upload a screenshot of your payment for me to verify (usually within a few hours). I don't offer Cash on Delivery, and card payments are coming later in the year.

**Tile 4 — Bridal**
*Q: I'm getting married and want nails for all three events*
A: The Bridal Trio page has everything you need — what's included, pricing, timelines, and FAQs. Or message me directly and we'll plan it together.
Link → `/bridal`

---

## Section 5 — Business Hours

**Background:** `bg-paper`
**Layout:** Centred text block

**Heading (H3):** When I'm around.

**Hours:**
```
Monday – Saturday:  10am – 9pm PKT
Sunday:             Limited — I try to check messages, no promises
Eid & public holidays: Closed — I'll note it on Instagram in advance
```

---

## Internal Links from This Page

| Destination | Trigger |
|---|---|
| `/size-guide` | Tile 1 answer |
| `/order/{order}/track` | Tile 2 answer |
| `/bridal` | Tile 4 answer |
| WhatsApp | Section 2 primary CTA |

---

## Assets Needed

- [ ] Mona's real WhatsApp number (replace placeholder before launch)
- [ ] Mona's contact email address
- [ ] Confirm `throttle:5,1` rate limit on form
- [ ] Set up email notification to Mona's inbox on form submit
