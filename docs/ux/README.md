# `docs/ux/` — UX Research & Design

This folder is the user-research and service-design layer for **Nails by Mona**. It complements the strategy material in [`CLAUDE.md`](../../CLAUDE.md) and the page-by-page content blueprints in [`docs/pages/`](../pages/) by answering the question those documents don't: **who are we designing for, and what do they need from us moment to moment?**

Each file is a self-contained, portfolio-grade artifact. You can read them in any order, but the recommended sequence below builds context cleanly: start with the case study, then meet the users, understand their jobs, walk their journeys, see how the service supports them, and finally read the principles and validation plans that hold the whole thing together.

---

## Recommended reading order

| # | File | Purpose | ~Read time |
|---|---|---|---|
| 1 | [`00-case-study.md`](00-case-study.md) | Portfolio entry point. The narrative version of everything in this folder. | 8 min |
| 2 | [`01-personas.md`](01-personas.md) | Three primary personas — Sana, Hira, Ayesha. | 7 min |
| 3 | [`02-jobs-to-be-done.md`](02-jobs-to-be-done.md) | Functional / emotional / social jobs + switching forces. | 5 min |
| 4 | [`03-empathy-maps.md`](03-empathy-maps.md) | Says · Thinks · Does · Feels per persona. | 4 min |
| 5 | [`04-journey-maps.md`](04-journey-maps.md) | Four end-to-end journeys with emotion curves. | 9 min |
| 6 | [`05-user-flows.md`](05-user-flows.md) | Six branching task flows in ASCII. | 8 min |
| 7 | [`06-service-blueprint.md`](06-service-blueprint.md) | Five-swimlane service blueprint, frontstage and backstage. | 7 min |
| 8 | [`07-pain-points-opportunities.md`](07-pain-points-opportunities.md) | Prioritized pain matrix + Impact × Effort 2x2. | 5 min |
| 9 | [`08-card-sort-ia-validation.md`](08-card-sort-ia-validation.md) | Plan to validate the locked nav with real users. | 4 min |
| 10 | [`09-usability-testing-plan.md`](09-usability-testing-plan.md) | 5-user moderated test plan for the polish week. | 5 min |
| 11 | [`10-accessibility-checklist.md`](10-accessibility-checklist.md) | WCAG 2.2 AA + Pakistan-mobile context. | 4 min |
| 12 | [`11-ux-principles.md`](11-ux-principles.md) | 10 project-specific UX principles. | 4 min |

Total: about an hour of reading to come up to full speed on the customer side of this project.

---

## How this folder relates to the rest of the project

```
CLAUDE.md                 Strategy, IA, data model, brand pillars  (the WHAT and WHY)
   │
   ├── docs/pages/        Page-by-page content blueprints           (what each route says)
   │
   ├── docs/ux/  ← here   Customer research & service design        (who the user is, where they get stuck, how the service holds together)
   │
   └── .claude/skills/
       design-system.md   Tokens, components, spacing, typography   (HOW it looks)
```

If you're designing a new page, read the relevant `docs/pages/*` blueprint first, then the relevant journey in `04-journey-maps.md`, then check `11-ux-principles.md` for the rules that catch you when you wobble.

If you're writing copy, read `01-personas.md` and `03-empathy-maps.md` first to anchor your voice, then `11-ux-principles.md` for the brand-voice rule.

If you're building a flow, read `05-user-flows.md` and `06-service-blueprint.md` first to understand what happens both before and after the screen you're building.

---

## A note on evidence vs. hypothesis

Everything in this folder was synthesized from desk research: the existing strategy work in `CLAUDE.md`, competitor profiles, Mona's recounted DM conversations, market data on Pakistani e-commerce, and one diaspora bride's published blog account. **No primary user research has been conducted yet.**

That's intentional — the project is pre-launch, and primary research with a non-existent product is hard to design. The two validation plans in this folder ([card sort](08-card-sort-ia-validation.md) and [usability testing](09-usability-testing-plan.md)) close that gap during the polish week before launch.

Where I extrapolated, I labeled it. You'll see `Hypothesis (validate)` callouts throughout. Trust those labels. They mark the parts of the design that need to survive contact with real users before we ship them with full confidence.

---

## Conventions used in these documents

- **Personas** are written as if they're real — first names, ages, cities, devices, quotes — to make them sticky. They are composites synthesized from competitor reviews, market research, and Mona's secondhand reports, not real individuals. Every quote is either traced or labeled hypothesis.
- **ASCII diagrams** use a documented legend at the top of `05-user-flows.md`.
- **Priority labels** are P0 (must ship), P1 (ship if time), P2 (post-launch).
- **References to CLAUDE.md sections** use the `§` symbol (e.g., "see §18" → CLAUDE.md section 18).
