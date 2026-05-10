# Journey Maps — Four end-to-end stories

> **The longitudinal view.** Personas tell us who customers are; journeys tell us what they walk through, from first IG impression to a delivered package and beyond. Where the personas + jobs framework is static, journeys are choreographed.

---

## Problem framed

A website looks like a set of pages but is experienced as a sequence of moments. Without journey maps, design decisions get made page-by-page — and the seams show. Customers don't see "the home page" and "the product page" as separate things; they experience a continuous emotional arc. Mapping that arc surfaces (a) the moments where emotion drops below the floor (the leak points), and (b) the moments where small investments compound (the leverage points).

## Methodology

Four journeys, one per high-leverage path:

1. **Sana — first-time discovery → first order.** The volume path. If this journey leaks, nothing else matters.
2. **Hira — Bridal Trio journey, 6–8 week timeline.** The high-AOV path. Different emotional shape, different content needs.
3. **Ayesha — wudu-compatible buyer.** The SEO-driven path. Entry point is a blog post, not the homepage.
4. **Returning-customer reorder.** The retention path. Where saved sizing and -10% reorder discount earn their keep.

Each journey is a 7-column table: **Stage · Actions · Touchpoints · Thoughts · Emotions · Pain points · Opportunities.** I added a small ASCII emotion curve so the high-low shape is visible at a glance.

Each journey ends with **3 prioritized opportunity cards** (P0/P1/P2) that feed forward into [`07-pain-points-opportunities.md`](07-pain-points-opportunities.md).

---

# Journey 1 — Sana, first-time order

> **The volume path.** A 28-year-old marketing manager in Lahore, tired of acrylic damage, sees an IG Reel and decides to try.

### Emotion curve

```
HIGH      ●(Discover)                              ●(Receive)  ●(Apply)
              ●(Land)                  ●(Order)
                                                                            ●(Reorder)
MID                ●(Browse)
                          ●(PDP)
                                                       ●(Wait)
LOW                                ●(Sizing)
            ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
            T1: 1 wk     T2: 30s    T3: 5m   T4: 2m  T5: 5d   T6: 1m    T7: 2m
            (saving      (lands)    (browse)  (sizing)(wait)   (apply)   (reorder)
             reel)
```

### The journey, stage by stage

| Stage | Actions | Touchpoints | Thoughts | Emotion | Pain | Opportunity |
|---|---|---|---|---|---|---|
| **1. Discover** | Sees Mona's "5-minute application" Reel during a metro commute. Saves it. | Instagram | "Wait, what is this? It looks salon-quality." | High curiosity | Reel has no clear bio link / no website mentioned in caption | **P0**: Every Reel ends with "nailsbymona.pk in bio." |
| **2. Land** | A week later opens IG, taps the saved Reel, taps profile, taps website link. | IG bio link → `/` | "Okay, this is a real brand. Mirpur is real. The hand photos are different from Daraz." | High but cautious | If site is slow on her 4G, she bounces in 4 sec | **P0**: Lighthouse mobile ≥ 90; LCP < 2.5s on 3G. |
| **3. Browse** | Taps "Shop." Filters by tier. Taps 3 designs. | `/shop` → `/shop/{slug}` | "These are pretty. Is this PKR 2,500 reasonable? Compared to a salon, yes." | Mid | Filter is hidden behind a small icon; takes her 8s to find it | **P1**: Filter bar is visible-by-default on `/shop`, not collapsed. |
| **4. PDP** | Reads the description. Looks at UGC. Reads "Removable for wudu" without reaction. Reads about the refit. | `/shop/{slug}` | "Free first refit if it doesn't fit? Okay, that's actually generous." | Mid-high | UGC photos are too few — only 2 customer hands shown | **P0**: At least 4 UGC photos per popular product, refreshed weekly. |
| **5. Add to bag** | Taps "Add to bag." Drawer slides in. Taps "Checkout." | Bag drawer → `/order/start/{slug}` | "Let's see what this checkout is like." | Mid-high | None at this stage | n/a |
| **6. Sizing** | Reaches the sizing step. Sees three options: Live camera / Upload / WhatsApp later. Hesitates. | `/order/start/{slug}` step 1 | "Live camera? On my work phone? In the metro? No, I'll do this at home." | LOW (anxiety spike) | She abandons here. Rate proxy: 30% of users, per industry baselines on multi-step forms | **P0**: Save-to-resume — email her a link to come back. |
| **7. Resume + sizing** | At home that night, opens the link from her email. Tries live camera — fingers photo first, then thumb in the same session. SVG overlay + green/red border guide each frame. | Camera capture screen (2-photo state machine) | "Oh this is actually clever. The border turns green when I'm in position. Two close-ups and I'm done — way faster than I expected." | Mid (rebuilding) | If alignment border stays red on a busy backdrop, customer doesn't know how to fix it | **P1**: Heuristic helper text on persistent-red ("Try a darker, plain surface like a sleeve or cushion"). |
| **8. Photo + details** | Captures, confirms, fills name/address/WhatsApp. | `/order/start/{slug}` step 2 | "+92 pre-fill on WhatsApp is nice." | Mid-high | None | n/a |
| **9. Payment** | Picks JazzCash. Sees account details. Notes that she'll need to send proof after. | `/order/start/{slug}` step 3 | "JazzCash, send proof, wait 5–9 days. Got it." | Mid | "Send payment proof" feels like extra work | **P1**: Confirmation email contains the JazzCash details + reminder, so she can pay tomorrow without re-finding it. |
| **10. Order placed** | Submits. Lands on confirmation page. Gets order number `NBM-2026-0042`. | `/order/confirm/{order}` | "5–9 days. I'll set a reminder." | High (relief + anticipation) | Confirmation page has a lot of info — could overwhelm | **P2**: Hierarchy — order number first, then payment instructions, then "what happens next." |
| **11. Pay** | Next morning sends JazzCash. Uploads payment proof on confirmation page. | `/order/confirm/{order}` | "Hope they got it." | Mid (waiting for ack) | No instant acknowledgment that proof was received | **P0**: Auto-email "Payment proof received, verifying within 24 hrs." |
| **12. Wait** | 5 days. Mostly forgets about it. | (offline) | "When did I order again?" | Mid (waning) | No proactive status email | **P0**: Status emails: Confirmed → In Production → Shipped → Delivered. |
| **13. Receive** | TCS delivers. Opens the package. Care card. Brush-on glue sample. Handwritten thank-you. | (offline) | "This is more thoughtful than I expected." | HIGH (delight) | None | n/a — this is the design working |
| **14. Apply** | Watches the application Reel on the package QR code. Applies. 7 minutes. | QR → IG Reel | "These actually fit. They look real." | HIGH | None | n/a |
| **15. Reorder** | 6 weeks later sees a new design drop. Logs in via phone+email lookup. Sizing pre-filled. -10% applied. | `/shop` → bag → `/order/start/...` | "Two taps. Done." | HIGH (delight + loyalty) | None | n/a — this is the retention design working |

### Three opportunity cards

> **P0 · Save-to-resume on the order flow.**
> Sana abandons at sizing because she's on the metro. Without a save-to-resume mechanism, that's a permanently lost order. Implementation: email her a link to come back to her partially filled cart + sizing step. Cost: half a day of dev. Impact: estimated 10–15% recapture of step-1 abandoners.

> **P0 · Status email cadence.**
> The 5-day wait between order and shipment is the largest emotional dip in the entire journey. Three short status emails (Confirmed, In Production, Shipped) at no operational cost transform "Did they forget?" into "They're working on mine right now." Cost: 2 hours of Mailtrap/SMTP setup + 3 templates. Impact: customer-service inquiry volume drops by an estimated 40%.

> **P1 · Reorder discount surfacing.**
> The -10% reorder discount is built into the data model but Sana might never know about it if it's only revealed at checkout. Adding a visible "Welcome back, your reorder discount is ready" line on the home page after phone+email lookup turns it from a transaction surprise into a retention narrative. Impact: ~15% increase in second-order rate.

---

# Journey 2 — Hira, Bridal Trio

> **The high-AOV emotional path.** A 25-year-old bride-to-be in Karachi, 6–8 weeks before mehendi. The most consequential single purchase she'll make for her wedding nails.

### Emotion curve

```
HIGH                                                    ●(Mehendi night)
                                                                       ●(Valima)
                                       ●(Receive)
              ●(Search)                                                            ●(Recommend)
MID                ●(Lands /bridal)
                                ●(WhatsApp consult)
                                                ●(Order)
                                                            ●(Track)
LOW       ●(Anxiety triggered)
            ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
            T1: now    T2: 1d    T3: 2-7d   T4: 1d    T5: 5-9d   T6: wedding   T7: post
```

### The journey, stage by stage

| Stage | Actions | Touchpoints | Thoughts | Emotion | Pain | Opportunity |
|---|---|---|---|---|---|---|
| **1. Anxiety triggered** | Makeup artist mentions nails. Hira realizes she has no plan. | (offline) | "I cannot do acrylics. My friend lifted at her Valima. What else is there?" | LOW (panic) | n/a | n/a |
| **2. Search** | Googles "bridal press on nails Pakistan." Searches IG hashtags. | Google → SERP | "Several brands are doing this. Which one is real?" | Mid | Most competitor sites have no bridal page — Hira has to scroll a generic shop and infer | **P0**: Own the keyword "bridal press on nails Pakistan" — Cornerstone Post #3 + `/bridal` H1 + meta. |
| **3. Lands `/bridal`** | Taps the search result. Lands on `/bridal`. Hero is hand on red velvet, gold thread. | `/bridal` | "Okay, this is bridal-coded. They get it." | Mid-high | First scroll must communicate "this is bridal" within 2 seconds — not a generic product grid | **P0**: `/bridal` hero is unmistakably bridal — different photography style than `/shop`. |
| **4. Reads** | Reads "Three nights, three looks." Sees the included checklist. Sees real-bride UGC. | `/bridal` | "Wait, this is exactly what I need. Mehendi + Baraat + Valima as one package?" | High | If the bridal UGC gallery has fewer than 6 photos, doubt creeps in | **P0**: Build to 6+ real-bride UGC photos before launch. Pre-launch: gift sets to 3–5 brides for content. |
| **5. WhatsApp consult** | Taps the WhatsApp link. Pre-fill: "Hello Nails by Mona, I'm interested in the Bridal Trio for my wedding." | WhatsApp | "I want to know if she'll work with my dupatta colors." | Mid-high (waiting for reply) | If reply takes > 2 hours, anxiety spikes; if reply is curt, trust drops | **P0**: Customer Care SLA: bridal inquiries answered within 1 hour, 9am–9pm; full-paragraph response, never bullet lists. |
| **6. Decision conference** | Sends `/bridal` link to her sister. Discusses with mom. Cross-checks with henna artist. | (offline + WhatsApp) | "All three are saying it looks legit." | High | n/a | n/a |
| **7. Order** | Returns to `/bridal`. Adds Bridal Trio to bag. Begins checkout. Notices full-advance gate. | `/order/start/bridal-trio` | "Full advance? Okay, but for PKR 12k I'd want to see they're committed too." | Mid-high (with caution) | Full-advance gate could feel adversarial if not framed warmly | **P0**: Full-advance copy: "We make every Trio to order, just for you. We require full advance so we can begin immediately." |
| **8. Sizing** | Uses live camera. Captures fingers (4-finger row + coin), then thumb in the same session. Considers the "Add my other hand →" opt-in (perfect-fit path) given how high-stakes her wedding is — taps in. 4 photos total. | Camera capture (2-photo + opt-in to 4) | "This is well-designed. The overlay is reassuring. For my wedding I want every nail right — I'll do all four." | High | n/a | n/a |
| **9. Payment** | Pays via JazzCash + bank transfer combo. Uploads two payment proofs. | `/order/confirm/{order}` | "I want her to know I'm a serious order." | High (commitment) | None if confirmation copy is warm | **P1**: Bridal-tier confirmation email is bespoke — references her wedding date, sets expectations for production. |
| **10. Track** | Over the next 5–9 days, watches the tracking page. Asks Mona via WhatsApp once for an update. | `/order/{order}/track` | "I want to know exactly where this is." | Mid (waiting) | If status doesn't update for 4+ days, panic spike | **P0**: Bridal orders get an extra mid-production photo via WhatsApp ("Here's how your set is coming along"). |
| **11. Receive** | Box arrives. Opens with her sister. Three coordinated sets, each in its own pouch, each labeled. | (offline) | "She actually packaged each night separately. This is *intentional*." | HIGH | None | n/a — this is what excellent looks like |
| **12. Mehendi → Baraat → Valima** | Applies each set on the relevant night. None lift. None break. | (offline) | "This was the best decision I made in wedding prep." | PEAK | None | n/a |
| **13. Recommend** | Tags Nails by Mona in her wedding photos. Tells 2–3 friends getting married next year. | Instagram + WhatsApp | "Everyone needs to know about this." | High (advocacy) | If brand doesn't repost or thank her, the moment dies | **P0**: Mona watches for tags, reshares to story with thanks, sends a small post-wedding gift (PKR 500 cuticle oil). |

### Three opportunity cards

> **P0 · Bridal Customer Care SLA.**
> Bridal customers are the single highest-leverage customer segment by AOV (PKR 11–13.5k vs PKR 2.5–3.5k average). A 1-hour response SLA, 9am–9pm, for any inquiry pre-fill containing the word "bridal" or "wedding" is operationally costless (Mona's existing WhatsApp behavior, just formalized) and is the single biggest trust signal she can send.

> **P0 · Mid-production WhatsApp photo.**
> Bridal customers wait 10–14 days. Around day 5, sending a single in-progress photo of their actual set ("Here's how your Mehendi set is coming along") via WhatsApp transforms anxiety into anticipation. Cost: 60 seconds per bridal order. Impact: estimated 25% drop in mid-wait inquiries; massive trust compound.

> **P0 · Real-bride UGC gallery on `/bridal`.**
> The gallery is the single highest-conversion asset on the bridal page. Without 6+ real photos, Hira's anxiety wins. Pre-launch: gift sets to 3–5 brides willing to send back hand-only photos with attribution. Operational cost: ~PKR 50–60k in product. Impact: every future bridal customer's conversion math.

---

# Journey 3 — Ayesha, the wudu-compatible buyer

> **The SEO-driven path.** A 32-year-old pediatrician in Islamabad, who has held a question for years and finally Googles it.

### Emotion curve

```
HIGH                                       ●(Read About)        ●(First Eid wear)
                                                                              ●(Recommend)
              ●(Lands blog post)
                          ●(Reads)
                                                  ●(Order)
MID
                                ●(Cross-checks)         ●(Wait)

LOW       ●(Searches with low expectations)
            ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
            T1: now    T2: 10m    T3: 30m    T4: 1d   T5: 5-9d   T6: Eid    T7: post
```

### The journey, stage by stage

| Stage | Actions | Touchpoints | Thoughts | Emotion | Pain | Opportunity |
|---|---|---|---|---|---|---|
| **1. Search** | Googles "press on nails wudu" late at night. | Google → SERP | "I doubt anyone has actually addressed this properly." | LOW (resigned) | If we don't rank #1 for this query, she never finds us | **P0**: Cornerstone Post #5 must rank #1 within 6 months. SEO-engineered: target keyword in H1, in first paragraph, in 2x H2s, in URL slug, in meta. |
| **2. Lands blog post** | Cornerstone Post #5 is the top result. She taps. | `/blog/can-muslim-women-wear-press-on-nails` | "Oh — this is a Pakistani brand actually addressing this." | Mid (cautious wonder) | If post is short, generic, or treats Islam as a marketing angle, she leaves | **P0**: Post is 2,000+ words, theologically careful, cites mainstream scholarly opinion. |
| **3. Reads** | Reads end-to-end. The blog post explicitly addresses wudu, removal, residue, theological framing. | (still on blog post) | "She actually understands. She's not selling — she's explaining." | High (relief) | None if post quality is high | n/a |
| **4. Reads About** | Taps About from the post header. Reads Mona's origin story. | `/about` | "She started this business *because of* this exact problem. That's not marketing — that's authentic." | Highest emotion peak | If About page lacks the religious origin story, the trust collapses | **P0**: About page mentions Mona's faith reasoning honestly — neither hidden nor performed. |
| **5. Cross-checks** | Reads the FAQ on the post. Taps a related product. Reads "Removable for wudu" on the PDP. | `/shop/{slug}` | "Even on the product page they're saying it. Quietly. Not selling it." | High | None | n/a |
| **6. Order** | Adds an Everyday set to bag. Goes through standard checkout. | Standard order flow | "First-time order. Let's see if the experience matches." | Mid (cautious commitment) | None — flow is unchanged from Sana's | n/a |
| **7. Wait + Receive** | 5–9 days. Receives. Same packaging quality. | Standard flow | "Same care as any customer. I'm not 'the Muslim customer' — I'm just a customer." | High | None | n/a — this is the design working |
| **8. First Eid wear** | Wears for Eid. Removes for wudu twice that day. Reapplies. | (offline) | "It actually works. I forgot what it felt like to have nails for an event." | PEAK | If glue is hard to remove cleanly, the entire pillar collapses | **P0**: Care card explicitly addresses removal-and-reapply for prayer; brush-on glue sample is included. |
| **9. Recommend** | Tells her sister, two cousins, three hospital colleagues. | (offline + WhatsApp) | "I have to tell other Muslim women — they don't know this exists." | Highest (advocacy) | If brand doesn't have a referral lever, this energy dissipates | **P1**: Referral mechanism — share-a-friend code with PKR 200 off both. |

### Three opportunity cards

> **P0 · SEO investment in Cornerstone Post #5.**
> Ayesha's entire acquisition path is a single search query. Owning that query is non-negotiable. The post must be rigorous (cite scholars), keyword-engineered, and never linkrotted. Post-launch task: monthly check on SERP position; refresh annually.

> **P0 · Care card with prayer-specific instructions.**
> The biggest residual anxiety is "will the removal be clean?" Including a care card that specifically addresses removing-and-reapplying for prayer (not buried in a generic care guide) closes the loop. Cost: one card design. Impact: turns first-time Ayesha into vocal advocate.

> **P1 · Referral mechanism for Ayesha-shaped customers.**
> Ayesha's network is the most under-served and most receptive segment. A simple share-code (PKR 200 off both customers) lights up word-of-mouth in a community that mainstream marketing can't reach. Implementation: Phase 5+ — needs accounts or persistent device tracking. Defer if it complicates the no-account guest checkout.

---

# Journey 4 — Returning-customer reorder

> **The retention path.** Sana, second order, six weeks after her first.

### Emotion curve

```
HIGH                                          ●(Reorder placed)
                                  ●(-10% applied)
                       ●(Sizing pre-filled)            ●(Receive)
MID    ●(Sees new drop)
                ●(Logs in)
LOW
            ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─
            T1: now   T2: 30s   T3: 5s    T4: 5s    T5: 5-9d
```

### The journey, stage by stage

| Stage | Actions | Touchpoints | Thoughts | Emotion | Pain | Opportunity |
|---|---|---|---|---|---|---|
| **1. Sees new drop** | Sees a new design on Mona's IG story. Taps the bio link. | IG → `/shop` | "Oh, the marble ombre. I want that." | Mid-high | None | n/a |
| **2. Lookup** | At checkout, types phone + email. System finds her. | `/order/start/...` | "It already knows me." | High | If lookup is slow or ambiguous, the magic breaks | **P0**: Lookup is fast (< 500ms), error states are warm ("We didn't find a match — let's set up your sizing now"). |
| **3. Sizing pre-filled** | "We have your sizing on file from order NBM-2026-0042 — fingers + thumb. Use that?" Yes. | Sizing step | "Good — I'm not redoing the 2-photo dance." | High | If the fallback is "or take new photos," the choice should be opt-in not forced | **P0**: Default is "use saved sizing"; "take new photos" is a secondary action. |
| **4. -10% applied** | Reorder discount applied automatically. Visible in cart. | Bag drawer | "Oh, -10%. Nice." | High (small delight) | If discount only appears at confirmation, the moment is missed | **P0**: -10% appears in the bag drawer the moment she's recognized as a returning customer. |
| **5. Pay + place** | Standard payment flow. Order placed in 2 taps from `/shop`. | Standard flow | "That was the easiest reorder I've ever done." | Highest | None | n/a |
| **6. Wait + Receive** | Standard 5–9 day production. Reorder gets a small note: "Welcome back, here's a sample ribbon for your set." | Standard flow | "She remembered me." | Highest | None | n/a |

### Three opportunity cards

> **P0 · Visible reorder discount in the bag drawer.**
> The -10% must appear *as soon as we recognize her*, not at checkout confirmation. Recognition + reward in the same motion is what makes loyalty feel earned, not transactional.

> **P0 · Saved-sizing as default, not opt-in.**
> The retention design fails if returning customers have to make an active choice to skip the camera step. Default to saved sizing; let them opt out if they want to update. Reduces friction by an entire screen.

> **P1 · Personalized small touch in the package.**
> A handwritten "Welcome back" note or a tiny ribbon in her preferred color is the kind of investment that compounds. Cost: 30 seconds + a ribbon. Impact: tagged stories, sister referrals.

---

## Cross-journey patterns

When you stack the four journeys, three patterns emerge:

1. **The biggest emotion drop is always the wait.** Sana waits 5 days, Hira waits 10–14 days, Ayesha waits 5–9 days. Status emails / WhatsApp updates / mid-production photos are the cheapest, highest-leverage UX investments in the entire stack.

2. **The biggest emotion peak is always packaging unboxing.** Not the product, the *opening*. This is where craft photography, handwritten notes, brush-on glue samples, and the care card pay off disproportionately.

3. **The biggest leak point is always the sizing step.** Across all four journeys, sizing is where customers either invest or abandon. The live camera is the differentiator; the save-to-resume is the safety net; the saved-sizing path for returning customers is the retention lever.

## Success metrics by journey

| Journey | Primary metric | Secondary metric |
|---|---|---|
| Sana — first order | Cart-to-order conversion ≥ 35% | Reorder rate ≥ 25% by month 12 |
| Hira — Bridal Trio | Bridal-page-to-order conversion ≥ 8% | Post-wedding referral rate (qualitative tracking via "How did you hear about us") |
| Ayesha — wudu buyer | Cornerstone Post #5 organic rank #1 by month 6 | Conversion from post to order ≥ 4% |
| Returning customer | Second-order rate ≥ 25% | Time-to-second-order < 8 weeks |

## Reflection

The bridal journey (Hira) is the most assumption-heavy of the four — built mostly from inference rather than observation. The Phase 5 usability test must include at least one bride-to-be even if she's harder to recruit than the working professional segment. One real Hira walking through `/bridal` with us is worth a hundred competitor-review-thread synthesis sessions.

The reorder journey (Sana, second order) is intentionally short. Returning customers don't want to read; they want to transact. A short journey is the right journey here.
