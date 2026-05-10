# Card Sort & IA Validation Plan

> **A 30-minute, PKR 1,000-per-participant study to validate the locked nav (Shop · Bridal · About · Journal · Help) against how Pakistani women actually think about the content.** Runs in the polish week before launch.

---

## Problem framed

The information architecture for Nails by Mona is locked at five top-level nav buckets: **Shop · Bridal · About · Journal · Help**. That decision was made on the basis of competitor benchmarking, persona work, and conventional commerce IA — all reasonable inputs, but none of them are the same as a real customer trying to find something.

The risk: a structure that *looks* obvious to a designer might not be obvious to a 28-year-old marketing manager in Lahore who's never used the site before. If she searches for "wudu-friendly" and can't find it under any of the five nav labels, she leaves. If "size guide" doesn't surface in a place she expects, she leaves. We can't ask "is the IA good?" once the site is built — we have to test before commit.

This plan defines a lightweight card sort study that costs PKR 8–12k total and 1.5 calendar days of effort, and gives us a pre-launch GO/NO-GO on the IA.

## Methodology

A two-part card sort:

1. **Open card sort** — participants group 18 concept cards into categories of their own choosing and label the categories themselves. Tells us *what mental model they bring*.
2. **Closed card sort** — same participants then sort the same 18 cards into our 5 fixed nav buckets. Tells us *how well our model fits theirs*.

The discrepancy between the two is the design risk.

I picked 18 cards (sweet spot per Donna Spencer's classic IA work — fewer is unrevealing, more is exhausting at 30 minutes). The cards span concrete products, topics, transactional pages, and edge cases.

I'll run this **moderated remotely** via Google Meet + a shared Google Sheet (no special tooling required), because OptimalSort + similar tools cost USD and we can do this for free with one shared spreadsheet. The trade-off is moderator time, but it's worth the cost savings and the qualitative depth from being able to ask "why did you put it there?" in real time.

## Design rationale

I considered a tree test instead (test whether users can find a specific item in our IA), but tree tests assume your IA is roughly right and are best for fine-tuning. We're earlier — we want to know if our top-level model is a fit at all. Card sorting is the right instrument for that question.

I also considered an unmoderated card sort with 30+ participants (cheaper per insight). Rejected because (a) we can recruit at most 8–12 from Mona's IG audience without reaching to people who don't fit the persona, and (b) the qualitative "why" is more valuable than statistical confidence at this scale.

I deliberately did *not* use the existing site nav labels in any prompts shown to participants — I want their mental model uncontaminated by our taxonomy. Closed sort happens after open sort; only then do they see our 5 buckets.

---

## The 18 cards

Concepts span the full content surface: products, topics, transactional pages, edge cases. Each card is a concept name + a one-line description on the back (so participants don't trip on naming conventions).

| # | Card label (front) | One-line description (back) |
|---|---|---|
| 1 | "Custom-fit press-on nails" | Press-on nails sized to fit your specific hand |
| 2 | "Bridal Trio" | Three coordinated nail sets for Mehendi, Baraat, and Valima |
| 3 | "Size guide" | How to measure your nails for a custom fit |
| 4 | "Wudu-friendly nails" | Press-on nails that can be removed before prayer and reapplied after |
| 5 | "How to apply press-on nails" | Step-by-step application tutorial |
| 6 | "Press-on nails vs acrylics" | Comparison of press-ons and salon acrylics |
| 7 | "Refit policy" | What happens if your first set doesn't fit |
| 8 | "Order tracking" | Check the status of your order |
| 9 | "Reorder discount" | -10% off your next order with saved sizing |
| 10 | "Mona's story" | The founder's background and how the brand started |
| 11 | "Bridal nail trends 2026" | What's trending for Pakistani brides this year |
| 12 | "Care guide" | How to make your nails last longer |
| 13 | "Removing nails without damage" | How to safely remove press-ons |
| 14 | "Payment methods" | JazzCash, EasyPaisa, bank transfer (manual proof upload) |
| 15 | "WhatsApp customer care" | Get help from Nails by Mona |
| 16 | "Studio + materials" | Where and how the nails are made |
| 17 | "FAQ" | Answers to common questions |
| 18 | "Shipping across Pakistan" | Delivery times, courier partners, free first refit |

## Recruitment

**Target:** 8–12 Pakistani women, ages 22–35, urban (Lahore / Karachi / Islamabad), have purchased beauty products online before.

**Sample composition:** Aim for the persona mix — ~5 Sana-shaped (working professional, no current/upcoming wedding), ~3 Hira-shaped (engaged or recently engaged, wedding planning), ~2 Ayesha-shaped (practicing Muslim with strong religious framing in their daily decisions). Over-recruit Ayesha-shaped because they're the highest-uncertainty segment.

**Source:** Mona's Instagram audience. Run a poll Story: *"Nails by Mona is launching a website. We need 8 women to help us make it easier to use. 30 minutes on Google Meet, PKR 1,000 thank-you. DM us if interested."* Realistic response rate from a 1k-follower account: 12–25 DMs in 24 hrs, of which 8–10 will follow through.

**Exclusion criteria:** Anyone who has worked at a UX agency or studied UX (small risk in Pakistan, but worth checking). Friends/family of Mona who'd give socially-desirable answers.

**Incentive:** PKR 1,000 per session via JazzCash, paid within 24 hours. Total budget: PKR 8–12k.

## Session structure (30 minutes)

```
0:00–0:03   Intro & consent
            "Hi, thank you for joining. We're testing how people group
            different topics on a nails website. There are no right
            or wrong answers — this is feedback for us, not a test of
            you. Can I record? Audio only."

0:03–0:05   Warm-up question
            "When you shop online for beauty products, what kinds of
            categories do you usually expect to see?"

0:05–0:18   Open card sort  (13 minutes)
            Share screen of Google Sheet with 18 cards in a column.
            "I'll show you 18 topics. Drag them into groups that make
            sense to you. Then label each group with a short name."
            Moderator stays silent except for clarifying questions.
            At the end, ask: "Walk me through your groups. Why these?"

0:18–0:25   Closed card sort  (7 minutes)
            "Now I'm going to show you the 5 nav buckets the site
            actually uses. Sort the same 18 cards into those buckets."
            Buckets: Shop · Bridal · About · Journal · Help
            (Allow "I'm not sure" as a 6th bucket — labeled "I'd put
            this somewhere else.")
            At the end: "Anything that felt forced?"

0:25–0:30   Wrap + thank
            "What's one thing you wish the site had that I didn't ask
            about?"
            "Thank you. JazzCash to your number — give me 24 hours."
```

## Success criteria

**The IA passes if:**

1. **≥ 70% agreement** on category placement for the 10 most common cards in the closed sort (cards #1, #2, #3, #5, #8, #10, #14, #15, #17, #18).
2. **≤ 2 cards** end up in the "I'd put this somewhere else" bucket per participant on average.
3. **The open sort produces categories that broadly map to Shop / Bridal / About / Journal / Help.** Not exact label match — a participant who labels "About" as "The Brand" still confirms the bucket exists in their mental model.

**The IA needs revision if:**

- Any single card has < 50% placement agreement in closed sort.
- The open sort surfaces a top-level category we don't have (e.g., everyone groups "shipping," "payment," "tracking," "FAQ," and "WhatsApp" into one bucket they call "Customer Service" — that's a louder signal than just labeling Help differently).
- Participants struggle audibly on more than 3 cards.

**Hard fails (full IA review required):**

- "Wudu-friendly nails" doesn't get sorted into Journal (where Cornerstone Post #5 lives) consistently — suggests the IA is failing the Ayesha persona.
- "Bridal Trio" doesn't get its own bucket in open sort, AND ends up < 50% in Bridal in closed sort — suggests Bridal shouldn't be a top-level nav slot.
- Any participant abandons mid-task ("I don't know where any of these go") — suggests our content doesn't map to a recognizable mental model at all.

## What we'll change if results disagree

A pre-decided decision tree (so the post-test debate is structured, not vibes-based):

| Finding | Decision |
|---|---|
| One card consistently misplaced | Re-label the card or re-categorize it (small change, no IA-level revision). |
| "Wudu-friendly nails" sorts into Shop more than Journal | Add a "Wudu-friendly" filter chip on `/shop`, in addition to the blog post in Journal. |
| Open sort surfaces "Customer Service" as a clear top-level category | Rename "Help" to something more directly recognizable — but keep the bucket. (Closed sort tells us if "Help" was the issue.) |
| "Bridal Trio" needs its own nav slot | Already has one. Confirm we're keeping it. |
| < 70% agreement on multiple cards | Run a second 5-person sort 2 weeks later with revised IA. Don't ship without resolving. |
| Open sort produces 6–8 categories, all roughly mapping to our 5 | Pass — minor language tweaks possible. |

## Reporting

Output: a single `docs/ux/card-sort-results-{date}.md` document with:

1. Participant demographics summary.
2. Open sort results — categories named by participants, frequency-counted.
3. Closed sort results — agreement percentage per card.
4. Key qualitative quotes (3–5).
5. Decision: GO / GO-WITH-EDITS / RE-WORK.
6. Action items mapped to phase.

Estimated reporting effort: half a day after the last session.

## Schedule

- **Week before launch (Phase 5 polish week):** Recruitment, sessions, reporting.
- **Day -10 to -7:** Recruitment via IG poll.
- **Day -6 to -3:** 8–12 sessions, 30 min each (~6 hours moderator time).
- **Day -2 to -1:** Reporting + decisions.
- **Day 0:** Apply any nav copy changes before launch.

## Tools and budget

| Item | Cost |
|---|---|
| Google Meet (moderation) | Free |
| Google Sheets (card sort medium) | Free |
| Recording (Google Meet built-in) | Free |
| Participant incentive (10 × PKR 1,000) | PKR 10,000 |
| Mona's time (recruitment via IG) | ~2 hours |
| Moderator time (sessions + reporting) | ~10 hours |
| **Total cash budget** | **PKR 10,000** |

## Reflection

Two things I considered but rejected:

1. **Hiring a Pakistani UX research firm.** PKR 200k+ per study. Not justified at this stage. The questions we're asking are well-suited to a small DIY study; a firm would add rigor we don't yet need.

2. **Skipping validation entirely** because the IA is "obvious." This was tempting — the IA *is* conventional. But conventional doesn't mean validated, and the cost of being wrong is the cost of a relaunch. PKR 10k to know we're right is cheap insurance.

The risk in this plan is **recruitment bias** — Mona's IG followers skew toward people who already like Mona's work and may be more sympathetic to whatever IA we put in front of them. The Phase 5 usability test (`09-usability-testing-plan.md`) partially mitigates this by recruiting from a slightly wider pool, but I'd flag it as a known limitation and re-test in Year 2 with a non-fan sample if we have a major IA revision.
