# Personas — Three women we're designing for

> **Sana, Hira, and Ayesha.** The audience for Phase 1 launch. Diaspora customer (Zoya) is deferred to a Year-2 UX refresh — adding her now would dilute focus on the markets we'll actually ship to in the first 12 months.

---

## Problem framed

Press-on gel nails as a category in Pakistan are in a strange middle: aware enough that 8 brands compete for attention, but unfamiliar enough that most women still default to salon acrylics every 3 weeks. The job of these personas is to make three distinct customer types **specific enough to design for** — different enough that copy, imagery, pricing emphasis, and flow priorities have to flex for each, but related enough that one site, one nav, and one order flow serves all three.

## Methodology

Composite personas synthesized from:

- Competitor IG comments and reviews (CLAUDE.md §17 — 8 brands profiled).
- Mona's secondhand reports of her existing DM customer base (~1,000 IG followers, ~30 monthly orders pre-launch).
- Market data on Pakistani working women, wedding spending, and Muslim-majority commerce (CLAUDE.md §16, §27, §28).
- Mona's own origin story (CLAUDE.md §32 — the wudu-compatibility insight).
- One published account from a Pakistani diaspora bride (the only first-person bridal nail testimony I had access to).

Every direct quote in these personas is either drawn from real review text (cited) or labeled `[Hypothesis — validate]`. No fabricated "92% of users feel" statistics. Where a behavior is inferred rather than observed, I marked it explicitly.

## Design rationale

Three personas, not five or seven. The literature on persona work is clear: more than 4–5 personas dilute the design — teams stop using them. I picked the three with the most distinct **trigger moments**, not the three with the most distinct demographics. Sana's trigger is acrylic damage. Hira's is a wedding date 6 weeks away. Ayesha's is a religious obligation conflicting with vanity. Three different clocks, three different emotional registers, three different IA priorities.

I deliberately did not write a "Mona power user" persona for the admin side — that's not a persona, that's a single named human, and `docs/mona-admin-guide.md` serves that need better.

---

# Persona 1 — Sana

> **Working professional · Lahore · 28 · The volume buyer**

```
   ╭─────────────╮
   │   ✋  🎨    │   ← (no face — brand rule)
   │             │       Hand close-up, French-style nails
   │  Sana, 28   │       on a laptop keyboard
   ╰─────────────╯
```

**City:** Lahore (Defence / Gulberg)
**Occupation:** Marketing manager at a tech startup
**Income:** PKR 200–300k/month household; PKR 80–150k personal discretionary
**Device:** iPhone 13 (work-issued) · home Wi-Fi · 4G everywhere else
**Channels:** Instagram (primary discovery), WhatsApp (life), TikTok (entertainment)
**Maritalstatus:** Engaged, wedding next year — but that's not why she's buying nails today

### Quotes

> *"I love how acrylics look but my real nails are destroyed. I literally took a 6-month break and they still aren't right."* — paraphrased from public IG comment threads on competitor brands.

> *"I want something that looks salon-finished, but I have a 9 a.m. meeting tomorrow and I don't have 3 hours."* — `[Hypothesis — validate]`

> *"I'd buy press-ons online but I'm scared they won't fit. The few I've tried from Daraz looked obvious."* — paraphrased from competitor review patterns.

### Goals

- Look polished for office, dinners, weekend brunches without monthly salon trips.
- Stop the cycle of acrylic damage → 6-month repair → acrylic again.
- Find a brand she trusts enough to reorder without re-evaluating every time.

### Frustrations

- Salon: 2.5–3 hours per visit, every 3 weeks.
- Salon damage: thin, peeling natural nails after a year on acrylics.
- Daraz press-ons: cheap-looking, obviously generic, often don't fit.
- "Custom" brands online that aren't actually custom — same 24 sizes as everyone else.

### Motivations

- Time scarcity. Every hour saved is an hour with her fiancé or a Netflix episode.
- Self-respect. Her hands are visible in every meeting; she wants them to look intentional.
- Cost rationality. PKR 30–60k/year on salon adds up; press-ons at PKR 8–12k/year for the same look is an obvious win.

### Objections

- "Will they fall off in front of a client?"
- "Will the glue damage my nails like acrylics did?"
- "I've never measured my nails — what if they don't fit?"

### Brand pillar that resonates most

**Pillar 2: Handmade in Mirpur.** Sana has the disposable income to value craft over the cheapest option. Pillar 1 (custom fit) is a close second — but only after Pillar 2 earns her trust.

### Products she'll buy

- **Everyday Plain (PKR 1,800–2,200)** for first try.
- **Signature (PKR 2,500–3,500)** as her standard reorder once trust is built.
- Repeat purchases ~6× per year.
- Will eventually buy the Bridal Trio for her own wedding — but that's a separate decision tree, not this purchase.

### Channel preference

Instagram → website. Will not buy on Daraz. Will WhatsApp Mona's brand account for a question once, then expects the answer to be on the site for next time.

### Trigger moment

A Sunday evening. She's looked at her natural nails after taking off acrylics for a wedding she went to three weeks ago. They're still ridged and yellow. An IG Reel from Nails by Mona shows a customer applying a French ombre set in 5 minutes. She saves the Reel. She comes back to it Tuesday on her commute.

### What this persona means for the design

- **The home page hero must read in 2 seconds on mobile.** Sana is on a 6-inch screen, on the metro, possibly with one earphone in. She gives the page 3–5 seconds to interest her.
- **The shop grid must filter by tier and style fast.** Sana has shopped on Net-a-Porter, Mango, Sephora — she knows what a competent commerce experience feels like and will bounce if ours feels DIY.
- **Reorder must feel like a reward.** The -10% reorder discount and saved-sizing path is the lever that turns Sana from a one-time customer into a 6-times-a-year customer.
- **Sana is the "default" user.** Every flow we design must work for her without modification. The other two personas extend; Sana is the baseline.

---

# Persona 2 — Hira

> **Bride-to-be · Karachi · 25 · The high-AOV emotional buyer**

```
   ╭─────────────╮
   │   💍  ✋    │   ← (no face — brand rule)
   │             │       Hand on red velvet, henna pattern
   │  Hira, 25   │       just visible on the wrist
   ╰─────────────╯
```

**City:** Karachi (Clifton / DHA)
**Occupation:** Junior architect, two years in
**Income:** PKR 150k personal; PKR 3.5–5M total wedding budget (pooled family contribution — typical for a mid-range Pakistani wedding)
**Device:** iPhone 14 Pro (gift from fiancé) · home Wi-Fi · 5G in Karachi
**Channels:** Instagram (heavy — bridal accounts, photographers, makeup artists), Pinterest (wedding boards), WhatsApp (life and wedding logistics)
**Marital status:** Wedding in 8 weeks. Mehendi night, Baraat night, Valima reception. Three outfits, three looks, three nail expectations.

### Quotes

> *"I've been planning my mehendi look for a year. The nails should match the henna, not fight it."* — `[Hypothesis — validate; sourced from Pakistani bridal blog comments]`

> *"My friend got acrylics for her wedding. By the Valima they were lifting and her photographer had to retouch every photo. I'm not making that mistake."* — paraphrased from a Pakistani wedding-blog comment thread.

> *"I don't want to look like every other bride on Instagram. I want my nails to feel like mine."* — `[Hypothesis — validate]`

### Goals

- Three coordinated, distinct nail looks for three wedding events.
- Zero risk of nails lifting or breaking during the events (especially during photos).
- Matching aesthetic with the dupatta, jewelry, henna, and makeup artist's overall palette.

### Frustrations

- Salons demand acrylic appointments scheduled around 5 other beauty appointments.
- Acrylic damage post-wedding — she'll be in honeymoon photos two weeks later with destroyed nails.
- Generic "bridal" sets from competitors that look like pre-wedding shoots, not actual Pakistani weddings.
- The lead-time math: most "custom" brands quote 7–9 days, but she also needs a backup plan if the first set is wrong.

### Motivations

- Wedding photos last forever. This is documentation, not consumption.
- Family scrutiny. Aunts will pick apart everything from the saree pleats to the nail color.
- Self-image. This is the most photographed week of her life so far.

### Objections

- "What if they don't fit and there's no time to redo?"
- "What if the colors look different in person than online?"
- "Can I trust an Instagram brand for my wedding day?"

### Brand pillar that resonates most

**Pillar 3: Built for big days.** This is the only pillar Hira cares about today. Pillars 1, 2, 4 are reasons to believe — but the headline is "we made nails for the three nights of your wedding."

### Products she'll buy

- **Bridal Trio (PKR 11,000–13,500)** — three coordinated sets, one fitting, one shipment.
- Possibly an Everyday Plain set later, after the wedding.
- Will refer 1–3 friends over the following 12 months — bridal word-of-mouth is the strongest acquisition channel for this category.

### Channel preference

Instagram (where bridal nail content lives) → website (to reassure her parents this is a real business). She'll WhatsApp the brand at least 3 times before ordering — she's not impulsive about a wedding decision.

### Trigger moment

8 weeks before her mehendi. Her makeup artist mentions nails. She panics — she's been putting off the decision because acrylics felt wrong but she didn't know the alternative. That night she searches "bridal press on nails Pakistan" on Google and Instagram simultaneously. She lands on `/bridal` if the SEO is right.

### What this persona means for the design

- **`/bridal` must own the brand experience.** Different photography (hand on red velvet, gold thread, henna), different copy register (more poetic), different urgency cues ("Order 4 weeks before mehendi").
- **The Bridal Trio is the strategic flagship.** It must never be buried as one product among many in `/shop`. It gets its own nav slot, its own page, its own checkout flow with full-advance gating.
- **Trust signals must be denser on `/bridal` than anywhere else.** Real-bride UGC, the founder story, the refit guarantee — all stacked. Hira will leave at the first whiff of risk.
- **WhatsApp consultation must feel like a service, not a fallback.** Hira will message before ordering. The pre-fill must be brand-addressed, the response time must be under 4 hours, and Mona's tone must match the gravity of the decision.

---

# Persona 3 — Ayesha

> **Practicing Muslim · Islamabad · 32 · The under-served hidden segment**

```
   ╭─────────────╮
   │   🕌  ✋    │   ← (no face — brand rule)
   │             │       Hand mid-prayer, wudu drops on wrist,
   │  Ayesha, 32 │       no nail polish visible
   ╰─────────────╯
```

**City:** Islamabad (E-7, F-7)
**Occupation:** Pediatrician at a private hospital
**Income:** PKR 350k+/month
**Device:** iPhone 12 · home Wi-Fi · works in a hospital with patchy signal — relies on offline-tolerant apps
**Channels:** Instagram (selective — follows ~50 accounts), WhatsApp (heavy — family + work groups), occasional YouTube (Islamic content, parenting)
**Marital status:** Married 6 years, two children. Active in her local community — eid gatherings, school events, family weddings

### Quotes

> *"I want my nails to look done for Eid but I can't pray with polish on, so what's the point?"* — paraphrased from r/MuslimWomen and Pakistani Islamic-lifestyle forum threads.

> *"I tried halal nail polish but the salon staff didn't know how to apply it and it bubbled."* — `[Hypothesis — validate; sourced from product-review patterns on halal cosmetic brands]`

> *"My friends wear acrylics and I just stopped going to salons with them because everyone knows I won't get them."* — `[Hypothesis — validate]`

### Goals

- Wear beautiful nails for Eid, weddings, and family events without compromising her five daily prayers.
- Be able to remove nails before wudu and reapply afterward without ruin or damage.
- Find a brand that *understands* — not a brand that "happens to be okay" because she can remove them.

### Frustrations

- The mainstream beauty industry treats her segment as invisible.
- Halal nail polishes (water-permeable) bubble, peel, and don't look professional.
- Has to choose between religious observance and feeling put-together — a false binary nobody else has solved for her.
- Her own Muslim friends have settled into "no nails, ever" and don't know about press-ons.

### Motivations

- Identity. She's both observant and modern; she rejects the implication that she has to give up one for the other.
- Family events. Her mother-in-law's family weddings, Eid gatherings — these matter, and looking put-together matters.
- Discovery joy. If a brand actually understands her constraint and solves for it, she'll be a vocal advocate in her community for years.

### Objections

- "Are these really compatible with wudu? Has anyone asked a scholar?"
- "Will they leave residue that water can't penetrate?"
- "Will the application process feel haram-adjacent — like I'm hiding something?"

### Brand pillar that resonates most

**Pillar 4: Wudu-compatible.** This is her *only* entry point. The other three pillars are reasons to stay, but Pillar 4 is what makes her stop scrolling.

### Products she'll buy

- **Everyday Plain (PKR 1,800–2,200)** for general wear.
- **Signature (PKR 2,500–3,500)** for Eid and family events.
- 4–6 sets per year — lower frequency than Sana, higher loyalty.
- Will tell her sister, her cousins, and her hospital colleagues — high-multiplier word-of-mouth.

### Channel preference

Google search → blog post → website. She will not buy from Instagram alone — she needs the religious framing to be explicit and findable, which means **Cornerstone Blog Post #5** is her gateway. Once on the site, she'll spend 8–10 minutes reading before adding to bag. She is the most patient buyer of the three.

### Trigger moment

She Googles "press on nails wudu" or "can Muslim women wear nail polish" — late at night, in her own time, in her own space. She's been holding the question for years. She lands on Mona's blog post.

### What this persona means for the design

- **Cornerstone Blog Post #5 is critical.** It's not a content side project — it's Ayesha's only acquisition path. It must rank #1 for "press on nails wudu" within 6 months. It must be theologically careful, not flippant.
- **The About page must mention Mona's own faith reasoning** — without overclaiming. Ayesha will sniff out performative religiosity instantly.
- **Brand voice must be respectful, not "halal-branded."** No green crescents, no "for the modest woman" copy. Just confident, warm, inclusive language that doesn't single her out as a special case.
- **Pillar 4 should appear on every product page** as a quiet bullet ("Removable for wudu"), not a billboard. Discovery, not lecture.

---

## Reflection

These three personas cover roughly 80% of Year 1 traffic, by my best estimate. The 20% they don't cover includes: gift-buyers (men buying for partners), salon professionals (a small B2B channel for the future), trend-following Gen Z who'll find us through TikTok rather than IG. None of those are Y1 priorities, but worth flagging for a Y2 persona refresh.

The biggest gap in confidence is **Ayesha**. The wudu-compatibility insight is real (Mona is herself this customer), but the rest of the persona — her decision process, her objections, her trust threshold — is hypothesis-heavy because there's almost no public material about how Pakistani Muslim women shop for beauty products that interact with religious practice. The Phase 5 usability test should over-recruit on this segment if we can find them — even one Ayesha-shaped user in the test would teach us more than the other four combined.

## Success metrics

- Sana — measured by repeat-customer rate (target ≥ 25% by month 12) and AOV (target PKR 2,800–3,500).
- Hira — measured by Bridal Trio share of revenue (target 25–35% by month 12) and post-wedding referral rate.
- Ayesha — measured by Cornerstone Post #5 organic ranking (target #1 for "press on nails wudu" by month 6) and conversion rate from that post to first order (target ≥ 4%).
