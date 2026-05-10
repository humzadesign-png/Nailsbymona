# Page: About `/about`

> **2026-04-29 design revision applies. Authoritative files:** `00-global.md` + `.claude/skills/design-system.md`. Anything in this file that contradicts those is obsolete — the revision wins.
>
> **Critical changes for this page:**
> - **No face image. Anywhere.** Mona's face is never shown on the website. The narrative stays in first-person — her name, her story, her voice are public assets — but the visual identity is hands and craft only. Any line in the body below that says "candid face portrait acceptable" or "Mona looking at camera" is **obsolete and must not be implemented.** Use a hand portrait + handwritten "Mona" signature SVG instead.
> - **H1:** "Made by hand. In Mirpur. With care." (or kept short and editorial — hand-led, not face-led). The previous "Hi, I'm Mona. I make every set myself." can stay as a caption *next to* the hand portrait, not as the H1 over a face image.
> - **OG image:** hand portrait + signature SVG. **Not** a face portrait.
> - **Schema.org:** `Organization` + `BreadcrumbList` only. **Removed:** `Person` schema (no founder image means we don't need Person markup).
> - **WhatsApp pre-fill** (only on the "Get help" link if any): `Hello Nails by Mona, I have a question.` **Banned:** "Ask Mona", "DM me", "Hi Mona!".
> - **Final CTA:** **"Browse the collection"** → `/shop`. **Banned:** "Talk to me", "DM me", "Ask Mona".
> - **Fonts:** Fraunces (display) + DM Sans (body). **Banned:** Cormorant Garamond, Inter.
> - **Palette:** `bone`/`paper`/`shell`/`ink`/`graphite`/`stone`. Lavender is accent only. **Banned tokens:** `cream`, `cloud`, `lavenderFaint`, `lavenderLight`, `body`, `mute`, `subtle`.
> - **Full drafted copy:** `docs/about-page-copy.md` — copy stays. Mona must review and approve before publish.

**Purpose:** The moat. Competitors cannot copy a real person's story. Make the visitor want to buy from Mona specifically — not from a category, but from her. (**Without ever showing her face** — the brand sells the craft + signature voice, not the face.)
**Template:** `resources/views/about.blade.php`
**Layout:** `layouts.app`
**Schema.org:** `Organization` + `BreadcrumbList` *(Person schema removed in the 2026-04-29 revision — no founder image means no Person markup needed)*

---

## SEO

| Field | Value |
|---|---|
| H1 | Hi, I'm Mona. I make every set myself. |
| Meta title | About Mona — The Story Behind Nails by Mona, Mirpur AJK |
| Meta description | Meet Mona — the founder, artisan, and sole maker behind Nails by Mona. Custom-fit press-on gel nails made by hand in Mirpur, Azad Kashmir, Pakistan. |
| Canonical | `https://nailsbymona.pk/about` |
| OG image | Hand portrait — Mona's hands working on a press-on set, with handwritten "Mona" signature SVG overlay (**no face**) |
| Breadcrumb | Home > About |
| Target keywords | "Nails by Mona", "custom press on nails Pakistan handmade", "press on nails Mirpur" |

---

## Section 1 — Hero

**Background:** Full-width hand portrait — Mona's hands working on a press-on set on a clean studio surface (window light, warm). Handwritten "Mona" signature SVG overlaid in the lower-right corner. **No face.** Caption beneath, italic small: *"Hi, I'm Mona — and these are my hands."*

**H1 (large serif, font-light):**
> Hi, I'm Mona. I make every set myself.

**Subheadline:**
> No factory. No drop-shipping. Just me, my studio, and a lot of care — in Mirpur, Azad Kashmir.

**No CTA in the hero.** Let the story pull them in.

---

## Section 2 — "How this started"

**Background:** `bg-paper`
**Layout:** Single column, max-width 740px, centred. Generous line-height. Serif body text for this section only — gives it an editorial feel.

**Section label:** MY STORY

**Heading (H2):** How this started.

---

### The drafted copy (Mona's voice — she must review and approve before publish)

**Paragraph 1 — The beginning**

> From the time I was very young, I drew things. On notebooks, on walls, on my own hands. Growing up in Mirpur, I was always the girl in class who was decorating something — the one teachers asked to make the bulletin board, the one who couldn't sit through a lesson without doodling in the margins. It was never a plan. It was just how I was made.

**Paragraph 2 — The education**

> When it came time to choose a degree, there was never really a question. I studied Fine Arts — because nothing else made sense for someone who had spent her whole life making things. In university, I discovered I wasn't just drawn to one medium. I could do bridal mehndi, sit with a bride for three hours and design something intricate and personal just for her. I painted. I worked with resin — the kind of detailed, precise work where you pour layers and wait and correct and pour again. I made personalised name plates and gift pieces that people still message me about years later. My hands knew how to learn.

**Paragraph 3 — The personal insight (the Islamic angle — most important paragraph)**

> But here's the thing nobody talks about: I am a practicing Muslim. And for years, I watched beautiful nail designs and wished — genuinely wished — I could wear them. Traditional nail polish isn't compatible with wudu. Water has to reach the nail bed for ablution to be valid, and a coat of polish blocks that. Salon acrylics have the same problem — you can't take them off five times a day. I kept hearing other women say the same thing. We wanted our nails to be beautiful. We didn't want to compromise our prayers to do it. Press-on nails changed that for me completely. You remove them before wudu. You put them back on after. They stay on for days at a time — and on your terms. I started making them because I needed them for myself. And then I realised how many other women needed them too.

**Paragraph 4 — The bridal insight**

> The bridal angle came from watching the women around me. Pakistani wedding season is something else — three events minimum, each with its own look, its own lehenga, its own vibe. I saw brides going to salons the morning of their mehndi to get acrylics done, then doing it again for baraat, then again for valima — each session two to three hours, each one leaving their nails thinner and more fragile than before. And all of it rushed, because appointments run late and weddings start early and nobody has time for mistakes. I knew press-ons could be the answer. One order, weeks in advance, three coordinated looks waiting in a box. No last-minute panic. No damaged nail beds the week after your wedding.

**Paragraph 5 — Building it (the honest story)**

> I won't pretend the beginning was easy. There was a period — honestly, longer than I'd like to admit — where I doubted whether this was real, whether people would trust something made by one person in Mirpur rather than a bigger brand. My family believed in me before I fully believed in myself. My husband encouraged me to keep going when I wanted to step back. And slowly, order by order, it became real. Customers from Lahore, Karachi, Islamabad — women who found me on Instagram, placed one order, and came back. That trust is the thing I'm most careful about protecting. Every set that leaves my hands, I've checked. Every measurement I've taken seriously. If something isn't right, I'd rather start over than send it.

**Closing line (italicised, slightly larger):**
> *This is still a one-person business. That's not a limitation — it's the point.*

---

## Section 3 — "Where your nails are made" (studio documentary section)

**Background:** `bg-shell`
**Layout:** 2-column photo grid (desktop), stacked (mobile). 3–4 panels, each with a brief personal caption.

**Section label:** THE STUDIO
**H2:** Where your nails are made.

**Panel 1:**
Photo: Mona's workspace — gel lamp on the table, forms laid out, brushes in a holder, small bottles of gel in various colours.
Caption: *"My worktable. Messy during a busy week, organised at the start of every new order."*

**Panel 2:**
Photo: Close-up of nails mid-construction on a form — two or three nails in different stages.
Caption: *"Each nail is built individually. Base, colour layers, art if there is any, topcoat. I cure each layer under the lamp before the next one goes on."*

**Panel 3:**
Photo: The magnetic box being assembled — satin lining, nails in their tray, handwritten name card.
Caption: *"The last step before dispatch. I write the name card myself. Every time."*

**Panel 4 (optional — if photo available):**
Photo: Mona's hands at work, brush in hand, close-up of the nail-building process.
Caption: *"I made over [X] sets last year. These hands know what they're doing."*

---

## Section 4 — "From your photo to your door" (visual process timeline)

**Background:** `bg-paper`
**Layout:** Numbered vertical timeline (desktop: alternating left/right; mobile: single column left-aligned)

**Section label:** THE PROCESS
**H2:** From your photo to your door.

**Step 1:**
Icon: `camera.svg`
**Heading:** You share your sizing photo
> You take one photo using my size guide — your hand flat, a Rs. 5 coin in the corner, shot straight down. That's it. No salon visits. No measurements to take. I work out every nail width from the photo.

**Step 2:**
Icon: `whatsapp.svg`
**Heading:** We align on design
> I message you on WhatsApp within a day or two to confirm the design direction — colour, finish, any specific elements. For bridal orders, this is where we talk about your lehenga colours and coordinate across all three looks.

**Step 3:**
Icon: `nail.svg`
**Heading:** I build your set
> I build each nail individually on a form. Gel base, colour layers, any hand-painting or charm work, topcoat — cured under the lamp between each stage. Custom orders take 5–9 working days. Bridal Trio takes 10–14.

**Step 4:**
Icon: `heart.svg`
**Heading:** Quality check
> Before I pack anything, I wear-test a spare nail. I check the finish, the cure, the colour payoff. If something isn't right at this stage — I start over. That's not exceptional. That's just the minimum.

**Step 5:**
Icon: `package.svg`
**Heading:** Packed and shipped
> Your nails go into the magnetic box, wrapped in tissue, with glue, a prep pad, and an application guide. I send you your tracking number the same day I hand the parcel to the courier.

**Step 6:**
Icon: `sparkle.svg`
**Heading:** You wear them
> You apply them in under ten minutes. They last 7–10 days. With careful removal, you'll get 3–5 wears from a single set. And if you send me a photo of them on — honestly, it makes my day.

---

## Section 5 — "Why I don't take shortcuts"

**Background:** `bg-paper`
**Layout:** Single column, max-width 680px, centred

**Section label:** THE STANDARD
**H2:** Why I don't take shortcuts.

**Copy:**

> I could buy pre-made nails and put my name on them. A lot of brands do. I'm not going to.
>
> The reason I started this business was because the options that existed didn't do what they said they would. The sizing was off. The finish wasn't what the photos showed. The materials didn't last. I have spent two years building the skills to do this properly — not by reading a course, but by making set after set after set and understanding what works and what doesn't.
>
> Every nail I make is built from a gel base on a nail form. Every colour is applied in layers and cured properly. Every pair of sizing photos I receive — fingers and thumb, each with a coin for scale — I actually measure from. If your coin-to-nail ratio tells me your pinky is 10mm wide, I make your pinky nail 10mm wide — not "close enough." Not a standard from a pack.
>
> You'll never receive a set from me that I haven't personally checked. That's not a promise I made for marketing — it's a limit of capacity I've deliberately kept. When this business grows to a point where I need help, I will train someone. I will not just hand off the quality check.

---

## Section 6 — Guarantees (first-person)

**Background:** `bg-paper`
**Layout:** 3 cards, side-by-side (desktop), stacked (mobile)

**Heading (H2):** What I stand behind.

**Card 1 — 3-Day Stick Guarantee**
Icon: `heart.svg`
**Heading:** 3-day stick guarantee
> If your nails lift within 3 days of correct application, I'll replace them. No debate, no lengthy back-and-forth. I make them again and I ship them again.

**Card 2 — Free First Refit**
Icon: `sizing.svg`
**Heading:** Free first refit
> If your first order doesn't fit perfectly, I resize it at no charge. I'd rather take the extra time and materials to get it right than have you wearing nails that don't feel like yours.

**Card 3 — Real Replies**
Icon: `whatsapp.svg`
**Heading:** Real replies, from me
> When you message me on WhatsApp, you're talking to me. Not an assistant, not a chatbot. I respond personally, usually within a few hours. If I'm in the middle of an order, I'll let you know.

---

## Section 7 — Customer testimonials

**Background:** `bg-shell`
**Layout:** 3-column pull-quote grid (desktop), single column (mobile)

**Heading (H2):** What customers say.

**Note:** These must be real, pulled from Mona's actual DMs or Instagram comments. First name + city only (no surnames). Get Mona's permission before publishing any specific message.

**Format per quote:**
```
"[Quote text — keep under 50 words, authentic voice, don't edit to sound polished]"
— [First name], [City]
```

**Placeholder structure for design purposes (replace before launch):**
> *"I'd been hesitant about press-ons my whole life because nothing ever fit properly. Mona's set was the first time I felt like these were actually my nails."*
> — Ayesha, Lahore

> *"Ordered for my baraat and valima. Both sets were perfect. Got so many questions about them at the wedding — people genuinely couldn't tell they weren't gel salon nails."*
> — Hira, Karachi

> *"Really was not expecting the quality at this price. The packaging alone is beautiful. Will definitely reorder."*
> — Sara, Islamabad

---

## Section 8 — Final CTA

**Background:** `bg-paper`
**Layout:** Centred text block

**Heading (H3):** Curious about something?

**Copy:**
> I'm genuinely happy to answer questions before you order. Ask me anything — sizing, design options, timelines, whether a specific look is possible. WhatsApp is fastest.

**CTAs (side by side):**
- Primary: "Get help on WhatsApp →" (pre-filled: "Hello Nails by Mona, I have a question before I order.")
- Secondary (outlined): "Shop the collection" → `/shop`

---

## Internal Links from This Page

| Destination | Trigger |
|---|---|
| `/shop` | Final CTA section |
| `/size-guide` | Referenced implicitly in process step 1 |
| WhatsApp | Hero section, final CTA |

---

## Assets Needed

- [ ] Hand portrait: Mona's hands working on a press-on set, with signature SVG overlay (**no face — never a face**)
- [ ] Studio photo 1: worktable overview — gel lamp, forms, brushes, bottles
- [ ] Studio photo 2: nails mid-construction on form (close-up)
- [ ] Studio photo 3: magnetic box being assembled, name card visible
- [ ] Studio photo 4 (optional): Mona's hands at work, brush to nail
- [ ] 3–5 real customer testimonials (from DMs/IG comments, with Mona's permission)
- [ ] Mona's review and approval of Section 2 copy before publish

---

## Copy Notes

- **Section 2 copy is drafted** and must be reviewed and approved by Mona before publishing.
- The Islamic/wudu paragraph (Paragraph 3) is the most important paragraph on this page — it is Mona's personal story, not marketing copy. Do not soften or genericise it.
- The tone throughout is first-person, warm, honest, and specific. Avoid vague words like "passionate" or "dedicated" — use concrete details instead.
- Serif body text for Section 2 only gives it an editorial, personal letter feel that distinguishes it from the product pages.
