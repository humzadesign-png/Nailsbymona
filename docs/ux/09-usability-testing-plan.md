# Usability Testing Plan

> **A 5-user moderated test of the order flow, run during the polish week before launch.** Six tasks, PKR 1,500 per participant, ~3 hours of moderator time. Output: a prioritized fix list before public launch.

---

## Problem framed

By the polish week, the site is feature-complete. Every page exists. Every flow works in happy-path. What we don't know is whether it works for **real Pakistani women on their real phones at their real reading speed**. Designer demos pass; real customers find every gap our internal team learned to look past.

This plan defines a 5-user moderated usability test that catches the gaps before launch. It complements the [card sort](08-card-sort-ia-validation.md) (which validates the IA) by validating the *flows* — can a real customer actually browse, decide, and check out without help?

## Methodology

Standard moderated remote usability testing, with adaptations for context:

- **5 users, moderated remotely.** Nielsen's classic finding: 5 users surface ~85% of usability issues in a single round; more users surface mostly the same issues. We do one round, fix, and ship.
- **Real devices.** Participants use their own phones, not a desktop simulator. Pakistan-mobile is the launch context; we test in it.
- **Think-aloud protocol.** Participants narrate their thoughts as they work. Captures the "why" behind each click.
- **Task-based, not exploratory.** Each session has 6 specific tasks with clear success criteria. Avoids the trap of "what do you think of the site?" feedback that's hard to act on.
- **Recorded with consent.** Audio + screen share via Google Meet. Recordings are deleted after the report is written.

Sourced methodology: Steve Krug's *Rocket Surgery Made Easy* (the classic guide to small-team usability testing) + Jakob Nielsen's research on sample sizes.

## Design rationale

I considered scaling to 8 users for more confidence. Rejected — the marginal information from users 6–8 doesn't justify the cost when the polish week is already tight. Better to do 5, fix, and re-test post-launch if a critical issue emerges.

I also considered an unmoderated test (cheaper, more participants). Rejected because (a) the order flow has a live camera step that requires the moderator to see what's happening on the participant's screen, and (b) qualitative depth matters more at this stage than quantitative confidence.

I scoped the tasks deliberately — six is enough to cover the high-leverage paths without exhausting participants. I left out reorder (Flow 4) because it requires a returning customer; we'll observe that path in production once we have data.

---

## Recruitment

**Target:** 5 participants, Pakistani women, ages 22–35.

**Composition:**
- **3 Sana-shaped:** working professional, no immediate wedding, never tried press-ons.
- **1 Hira-shaped:** engaged or recently engaged, wedding within 12 months.
- **1 Ayesha-shaped:** practicing Muslim, has held the wudu-and-nail-polish question.

Over-recruit Hira and Ayesha because they're the harder personas to find but the highest-leverage to test.

**Source:**
- 3 from Mona's existing IG audience (different from card sort participants — fresh eyes).
- 2 referrals (a friend-of-a-friend invite, with a small bonus to the referrer).

**Exclusion:** anyone who participated in the card sort (their IA expectations are now contaminated).

**Incentive:** PKR 1,500 per session, paid via JazzCash within 24 hours. Total: PKR 7,500.

## Session structure (~60 minutes)

```
0:00–0:05   Intro & consent
            "Hi! Thank you for joining. We're testing a new website for
            a Pakistani nail brand called Nails by Mona. I'll give you
            a few tasks. There are no right or wrong answers — we're
            testing the website, not you. As you work, please say
            what you're thinking out loud, even if it sounds silly.
            Can I record audio and screen share? Recordings are deleted
            after we finish the report."

0:05–0:08   Pre-test questions
            "Do you currently use press-on nails or have you ever?
            Where do you usually shop for beauty products?
            What's your phone? (We're going to use your phone, not
            mine, so this is the real experience.)"

0:08–0:50   Six tasks (~7 min each)

            Task 1 — Browse and pick a design
            "Imagine you saw an Instagram post and tapped through to
            this site. Find a design you'd want to wear to a friend's
            wedding."
            ✅ Successful when: They reach a PDP and verbally commit
            to a choice.

            Task 2 — Capture your sizing
            "You've decided to order. Let's go through ordering it."
            (Continue through bag → checkout → sizing.)
            ✅ Successful when: They produce a usable sizing photo
            via live camera OR upload OR explicitly choose
            "send via WhatsApp."

            Task 3 — Complete checkout
            (Continue from Task 2.)
            "Walk me through the rest of the order, including
            payment selection."
            ✅ Successful when: They reach the confirmation page
            without help.

            Task 4 — Find the Bridal Trio
            "Let's say you're getting married next year. Find the
            three-set bridal package."
            ✅ Successful when: They reach `/bridal` from anywhere
            in ≤ 3 taps.

            Task 5 — Find sizing help
            "You're partway through ordering and you're confused
            about how to take your sizing photos (fingers + thumb).
            Where would you go for help?"
            ✅ Successful when: They find the size guide OR
            initiate a WhatsApp message — both acceptable.

            Task 6 — Track an order
            "Imagine you ordered yesterday and want to check status.
            How would you do that?"
            ✅ Successful when: They reach the tracking page with
            a fake order number we provide.

0:50–0:55   Post-test questions
            "What surprised you?"
            "What felt confusing or slow?"
            "What's missing that you'd expect?"
            "Would you actually order from this brand? Why or why not?"

0:55–1:00   Wrap & thank
            "Thank you. JazzCash to your number — within 24 hours."
```

## Metrics

For each task:

| Metric | How measured | Target |
|---|---|---|
| **Completion rate** | Did they finish without moderator help? | ≥ 4/5 (80%) |
| **Time on task** | Stopwatch from prompt to success | Task 1: ≤ 3 min · Task 2: ≤ 4 min · Task 3: ≤ 3 min · Task 4: ≤ 30 sec · Task 5: ≤ 1 min · Task 6: ≤ 1 min |
| **Errors** | Misclicks, confusion points, "where do I go now" moments | ≤ 2 per task |
| **Severity rating per issue** | Cosmetic / Minor / Major / Critical | Logged per finding |

System Usability Scale (SUS) score at the end of the session — 10 standard questions, 1–5 Likert. Industry benchmark: 68 average; 80+ is good. Target: **SUS ≥ 75 average across 5 participants.**

## Pre-test instrumentation

Before testing begins, the team must instrument:

- **PostHog (or alternative)** event tracking on every step of the order form. Each step transition fires an event with the step name. PII (phone, email, address) masked.
- **`sizing_capture_method`** field correctly populating on test orders.
- **Session recording** with PII fields masked (phone, email, address inputs).
- **Test order isolation** — test orders go through the real flow but Mona is told to not produce them. A `test_order = true` flag in the orders table keeps them out of production stats.

Without instrumentation, a usability test produces qualitative feedback only — useful, but harder to triangulate against quantitative behavior post-launch.

## Output template

Findings logged in `docs/ux/test-results-{YYYY-MM-DD}.md` with this structure:

```markdown
# Usability Test Results — {date}

## Participants
| # | Persona | Age | Device | Outcome |
|---|---|---|---|---|

## Quantitative Summary
| Task | Completion | Avg time | Errors | Notes |
|---|---|---|---|---|

## SUS scores
P1: __ · P2: __ · P3: __ · P4: __ · P5: __ · Average: __

## Findings (sorted by severity)

### Critical (must-fix before launch)
1. **Title** — Description. Affected participants: P_, P_. Recommendation: ___.

### Major (fix before launch if possible)
...

### Minor (post-launch backlog)
...

### Cosmetic (nice-to-have)
...

## Memorable quotes

> "..." — P3, on Task 4

## Decision
GO / GO-WITH-EDITS / RE-WORK
```

## Schedule

- **Day -7 to -5:** Instrument tracking; recruit 5 participants; book sessions.
- **Day -4 to -2:** Run sessions (5 × 1 hour = 5 hours moderator time, spread over 2-3 days).
- **Day -1:** Write report; triage findings; make fixes if Critical.
- **Day 0:** Launch.

## Budget

| Item | Cost |
|---|---|
| Google Meet | Free |
| PostHog (free tier) or alternative | Free |
| Participant incentives (5 × PKR 1,500) | PKR 7,500 |
| Referrer bonus (× 1) | PKR 500 |
| Mona's time (recruitment) | ~2 hours |
| Moderator time (sessions + reporting + triage) | ~12 hours |
| **Total cash budget** | **PKR 8,000** |

## What we're NOT testing

I want this explicit so we don't pretend coverage we don't have:

- **Returning customer flow (Flow 4).** Requires a real returning customer; we'll learn from production data instead.
- **Bridal Trio full path.** Hira-shaped participant will start the path but won't complete a real PKR 12k order in a test. We test the flow; production validates the conversion.
- **Manual payment verification (Flow 6).** Backstage flow involving Mona; not customer-facing in the moment. Validated by watching the auto-reminder cadence and Filament admin queue during the first 50 live orders.
- **Long-term retention.** Tested only by month 6 retention numbers; no usability test answers this.
- **Performance on a 3-year-old budget Android.** Most participants will have iPhones or recent Androids. We separately enforce performance via the Lighthouse budget in [`10-accessibility-checklist.md`](10-accessibility-checklist.md).

These are gaps. Some close themselves with production data; some need separate testing. Naming them keeps us honest.

## Reflection

Two things I'm cautious about going in:

1. **Recruitment bias from Mona's IG followers.** They're already pre-disposed to like the brand. Mitigation: 2 of 5 are friend-of-a-friend referrals, expanding the pool. But it's not a clean fix; flag the bias in the report.

2. **The test is happening on completed code, not a prototype.** Most usability tests run on Figma prototypes earlier in the cycle. Ours runs on the real site because the live camera flow needs real device APIs. The risk: Critical findings might require code changes 7 days before launch. The mitigation: budget Day -1 explicitly for fixes; if we hit something Critical that can't be fixed in a day, push launch by a week. Don't ship a known-bad flow.

The single most valuable session is likely the **Hira-shaped participant.** The bridal flow has the least real-world data informing it (built mostly from inference). One real bride walking through `/bridal` and the order form will teach us more than the other four sessions combined. Recruit her first; don't compromise on finding her.
