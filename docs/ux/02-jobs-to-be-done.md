# Jobs To Be Done — What customers hire press-on nails to do

> **JTBD framework + switching forces analysis.** What functional, emotional, and social progress are Sana, Hira, and Ayesha trying to make? And what's holding them back from making the switch?

---

## Problem framed

Personas tell us *who*. JTBD tells us *why now*. Customers don't buy press-on nails because they fit a demographic — they hire press-on nails to make specific kinds of progress in their lives. If we name those jobs accurately, our copy, our IA, and our ranking of features all sharpen.

The classic Christensen frame: a customer "fires" their current solution (acrylics, salon polish, no nails at all) and "hires" a new one when the current one fails to make adequate progress on the job. Our task is to (a) understand the job, (b) make our solution the obvious hire, and (c) reduce the anxiety of switching.

## Methodology

For each of the three personas, I named:

1. **Functional job** — the practical task the customer is trying to complete.
2. **Emotional job** — how they want to feel about themselves.
3. **Social job** — how they want others to perceive them.

Then I applied the **Four Forces of Progress** (Push of the current solution, Pull of the new solution, Anxiety about the new solution, Habit of the current solution) to each persona's switching moment, and mapped each force to a specific UX response in our design.

Source material: persona research in [`01-personas.md`](01-personas.md), competitor reviews (CLAUDE.md §17), pain points (CLAUDE.md §18), and Christensen/Klement JTBD methodology.

---

## The jobs, by persona

### Sana — "Help me look polished without rebuilding my Sundays around it"

| Layer | Job statement |
|---|---|
| **Functional** | When my real nails are damaged from acrylics and I have a busy week ahead, help me get a salon-quality look at home in under 10 minutes, so I can stop sacrificing my Sundays to the salon. |
| **Emotional** | I want to feel like I have my act together — that my hands are an intentional part of how I show up, not a chore I keep deferring. |
| **Social** | I want my colleagues, my fiancé's family, and the women in the room to register me as someone who pays attention to detail without trying too hard. |

> **What Sana is firing:** the salon. Not because she dislikes salons — she's been going for years — but because the time, the damage, and the cost are no longer worth the output.

### Hira — "Carry me through three nights without one moment of doubt"

| Layer | Job statement |
|---|---|
| **Functional** | When I'm 6–8 weeks from my wedding and I have three coordinated outfits planned, give me three coordinated nail looks that will not break, lift, or chip across three nights of dancing, photos, and dinners. |
| **Emotional** | I want to feel beautiful, confident, and *taken care of* — like at least one part of my wedding prep is handled by someone who actually understands what I'm walking into. |
| **Social** | I want my mehendi photos to be flawless, my aunts to compliment specifically the nails, and my friends to ask where I got them. |

> **What Hira is firing:** salon acrylics. She's seen them fail at other people's weddings and she's not willing to gamble her own.

### Ayesha — "Stop making me choose between feeling beautiful and praying"

| Layer | Job statement |
|---|---|
| **Functional** | When there's a special occasion and I want to wear nails, give me a product I can apply for the event and remove cleanly before wudu — no residue, no damage, no debate. |
| **Emotional** | I want to feel beautiful and observant *at the same time*, not feel like I have to defend myself for either choice. |
| **Social** | I want my Muslim friends to see me as proof that the trade-off they've made (no nails, ever) is no longer necessary, and I want my non-Muslim colleagues to see me as someone who participates fully without compromise. |

> **What Ayesha is firing:** the binary itself. She's been firing it for years and there hasn't been a viable replacement.

---

## The Four Forces of Progress

For each persona, I mapped the four forces and the UX response that addresses each. This is the most actionable section of this document — every force-and-response pair maps to a concrete design decision in `docs/pages/*` or `docs/ux/05-user-flows.md`.

### Sana's switching forces

```
                    PUSH OF THE OLD                          PULL OF THE NEW
                    ────────────────                         ────────────────
                  Acrylic damage to real nails        →     Reusable, no nail-bed damage
                  3 hours per salon visit             →     5 minutes to apply at home
                  PKR 30–60k/year                     →     PKR 8–12k/year for same look
                  Same look as everyone else           →     Custom-fit, handmade designs

                    HABIT OF THE OLD                         ANXIETY OF THE NEW
                    ────────────────                         ──────────────────
                  Salon every 3 weeks for 5+ years    →     "Will they fall off?"
                  Knows her usual nail tech            →     "Will they fit?"
                  Trusts the routine                   →     "Will they look fake?"
                  Friends all go to salons             →     "Is this brand legit?"
```

| Force | UX response in our design |
|---|---|
| Push (acrylic damage) | Cornerstone Blog Post #1: *Press-On Nails vs Acrylics: Which Is Better?* — answers her exact mental search. |
| Pull (5-min application) | Home page Reel embed showing 5-min application. Clear "How it works" 4-step strip on home and product pages. |
| Habit (salon routine) | Reorder discount + saved sizing path makes the new habit *easier* than the old one within 2 orders. |
| Anxiety (will they fall off?) | UGC carousel on PDP showing 7-day wear. Free first refit guarantee. Care guide included with every order. |

### Hira's switching forces

```
                    PUSH OF THE OLD                          PULL OF THE NEW
                    ────────────────                         ────────────────
                  Acrylics fail at weddings (seen it)  →    Reusable across 3 events
                  Salon has to schedule appointment     →    One fitting, one shipment
                  Damaged nails on honeymoon            →    No damage, removable cleanly
                  Generic look every other bride has    →    Custom-coordinated for HER

                    HABIT OF THE OLD                         ANXIETY OF THE NEW
                    ────────────────                         ──────────────────
                  Salon is what brides "do"             →   "What if they don't fit?"
                  Aunts and mothers expect salon-look   →   "Can I trust an IG brand for the wedding?"
                  Wedding planner integrates salons     →   "Will the colors match my dupatta?"
                  No alternative was visible            →   "What if I run out of time?"
```

| Force | UX response in our design |
|---|---|
| Push (acrylic failures at weddings) | Cornerstone Blog Post #3: *Bridal Nail Trends in Pakistan for 2026* — addresses her exact research moment. |
| Pull (3 looks, 1 fitting) | `/bridal` hero copy: *"Your wedding nails, for all three nights."* Bridal Trio is THE flagship — never buried. |
| Habit (salon is what brides do) | Real-bride UGC gallery on `/bridal`. Trust signals stacked: founder story + refit + craft photography. |
| Anxiety (timing, fit, trust) | "Order 4 weeks before mehendi" countdown messaging. WhatsApp consultation prominent on `/bridal`. Full advance creates commitment but also signals "we will not let you down." |

### Ayesha's switching forces

```
                    PUSH OF THE OLD                          PULL OF THE NEW
                    ────────────────                         ────────────────
                  Halal polish bubbles, looks bad      →    Gel-quality finish, looks salon
                  "No nails, ever" feels like a loss    →    Removable for wudu, reapply after
                  Excluded from beauty conversations    →    A brand that *gets it*
                  Mainstream brands ignore her          →    Mona is her — same religious context

                    HABIT OF THE OLD                         ANXIETY OF THE NEW
                    ────────────────                         ──────────────────
                  Years of "no nails" routine          →    "Has a scholar weighed in?"
                  Friends accepted her stance           →    "Will they leave residue?"
                  Settled into the trade-off            →    "Is this just clever marketing?"
                  Self-image without nails             →    "What will my mother-in-law say?"
```

| Force | UX response in our design |
|---|---|
| Push (halal polish bubbles) | Cornerstone Blog Post #5: *Can Muslim Women Wear Press-On Nails? Wudu, Nail Polish, and a Simple Solution* — owns the search query nobody else owns. |
| Pull (a brand that gets it) | About page mentions Mona's own faith reasoning honestly. Pillar 4 *Wudu-compatible* is named, not coded. |
| Habit (years of "no nails") | Quiet, confident framing — "Removable for wudu" as a single bullet on every PDP. Discovery, not lecture. |
| Anxiety (residue, scholarly opinion) | The blog post addresses theology directly with citations to mainstream scholarly opinion. Care guide demonstrates clean removal. |

---

## Cross-persona pattern: the universal anxiety

All three personas share a single dominant anxiety, expressed differently:

> *"Will this brand actually deliver on what it promises, or is it another Daraz disappointment?"*

For Sana it's "will they fit?" For Hira it's "will they survive my wedding?" For Ayesha it's "is the wudu story real or marketing?" Different objects, same root: *trust in a small online brand.*

This is the most important insight in this document. The **single highest-leverage UX investment** is anything that builds trust on the first visit. Specifically:

1. **Founder story** — Mona is real, named, located in Mirpur, has a verifiable craft.
2. **Hand-only craft photography** — shows the work, not the polish.
3. **Real-customer UGC** — proof from people like the visitor, not influencer-paid posts.
4. **Free first refit** — explicit, generous, and the only guarantee we make.
5. **Process transparency** — How it works, where the studio is, what materials are used.

Every page must answer "is this real?" before it asks for a click.

---

## Design rationale

I almost wrote separate JTBD frameworks for the order flow, the bridal flow, and the reorder flow — three separate documents. I collapsed them into this single persona-keyed document because the *jobs* are persona-specific, not flow-specific. Sana hires our service to save her Sundays whether she's on her first order or her tenth. Hira hires us to carry her through three wedding nights. The flows differ; the jobs don't.

The Four Forces analysis is the most useful part of this document for build decisions. When a designer or developer asks "should we add X feature?", the test is: which force does it address? If it addresses none of them, it's noise. If it addresses one for one persona, ship it for that persona only.

## Success metrics

- Conversion rate by persona-equivalent traffic source (IG ad targeting Sana vs. organic search targeting Ayesha vs. /bridal direct).
- Bounce rate on pages that should address each force (< 50% on Cornerstone Post #5 = pull is working for Ayesha).
- Time-to-first-order from first visit, by source (Hira's bridal journey will be longer; Sana's should be < 2 visits).
- Cart abandonment broken out by persona-equivalent path — high abandonment at the sizing step suggests the Anxiety force isn't being addressed.

## Reflection

The jobs framework loses its power if we let it abstract too far. "Help me feel beautiful" is not a job — it's a category. "When my real nails are damaged from acrylics and I have a busy week ahead" is a job, because it specifies the *struggling moment*. I tried hard to keep the job statements concrete enough to be useful, but I'm aware I drifted into abstraction in places (especially Hira's emotional job — "taken care of" is honest but generic).

The single thing I'd validate first with primary research is the **anxiety hierarchy**. I've ranked anxieties by my best inference, but a 5-user qualitative study would let us put hard numbers on which anxieties most often kill conversion. That's a Phase 5 task — see [`09-usability-testing-plan.md`](09-usability-testing-plan.md).
