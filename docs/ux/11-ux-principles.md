# Ten UX Principles — Project-specific, opinionated, falsifiable

> **Not Nielsen heuristics.** These are 10 principles that resolve real tradeoffs in *this* product, with examples and anti-examples. When a designer or developer is unsure which way to go, these decide.

---

## Problem framed

Generic UX principles ("be consistent," "give feedback") are easy to nod along with and impossible to argue against — which makes them useless when a real decision splits the team. A principle is useful only when you can imagine choosing the *other* side and have a defensible reason not to. The 10 below are written that way: each one names what we deliberately *don't* do, not just what we do.

These principles should be re-read at the start of every design or build phase. If we ever write code or copy that violates one without explicit reasoning, the principle has either expired or been broken — both worth a conversation.

## Methodology

Synthesized from the journey maps, the service blueprint, the pain matrix, and the design system. Each principle traces back to a specific decision the project has already made — they're descriptive of locked-in choices, not aspirational. That's intentional; aspirational principles get ignored.

## Format

Each principle:

- **One sentence** stating the principle.
- **Rationale** — why we chose this side of the tradeoff.
- **Concrete example** — what it looks like in this product.
- **Anti-example** — what it would look like if we got it wrong.

---

# 1. Show the craft, never the maker's face.

**Rationale.** A face on the website would invite DM spam, conflate the brand with a single person, and cap the brand's transferability. Hand-only photography reads more premium AND solves three operational problems at once — discipline disguised as aesthetic.

**Example.** The About page hero is a hand portrait — hands working on a press-on set — with a handwritten "Mona" SVG signature in the corner. The caption: "Hi, I'm Mona — and these are my hands."

**Anti-example.** Founder selfie on About; Mona's face in IG-style story embeds on the home page; customer testimonials with face photos. Banned.

---

# 2. Bag, not Order Now.

**Rationale.** A single commerce CTA — the bag icon — keeps the funnel clean, lets customers accumulate and compare without committing prematurely, and makes every page read with the same visual hierarchy.

**Example.** The PDP CTA is "Add to bag." Tapping it slides a drawer in from the right with the item, a subtotal, and a "Checkout" button. The bag icon in the top nav is the only commerce CTA in the header.

**Anti-example.** A floating "Order Now" button on every product card. A "Buy now" CTA on the home page hero. A second CTA below "Add to bag" labeled "Quick checkout." All banned.

---

# 3. The camera is the differentiator — make it feel safe, not scary.

**Rationale.** Live camera is what no competitor does. But camera permissions are a fragile UX surface — auto-prompting kills grant rates. Treating the camera step with care turns an anxiety-rich moment into the part of the flow customers tell their friends about.

**Example.** The camera doesn't auto-start. A pre-screen explains *why* we need the camera and what we'll show ("hand outline + coin"). Only then does the user tap "Start camera" and grant permission. If denied, the UI gracefully switches to upload with friendly, non-judgmental copy.

**Anti-example.** Landing on the sizing step and immediately triggering `getUserMedia` without explanation. A "We need your camera, please grant permission" modal that reads like a system error. Either of these tanks the grant rate by 30–50%.

---

# 4. WhatsApp is help, not checkout.

**Rationale.** WhatsApp is a customer-care channel, not a commerce surface. Making it primary would: collapse the brand back into "Mona's personal IG," create a parallel funnel that bypasses analytics, and exhaust Mona at scale. WhatsApp is the safety net for the design, not the design.

**Example.** Every page has a "Get help" link in the footer with a brand-addressed pre-fill ("Hello Nails by Mona, I'd like to..."). The PDP has a small ghost-link "Get help" beneath the primary "Add to bag" CTA. Bridal pages have it more prominently because consultation is a real bridal need.

**Anti-example.** A floating WhatsApp bubble on every page that says "Order via WhatsApp." A "Buy now via WhatsApp" CTA next to "Add to bag." A homepage hero CTA that says "DM Mona to order." All banned.

---

# 5. Bridal time-pressure is real — surface the 4-week rule everywhere.

**Rationale.** A bride 4 weeks from her mehendi is a fundamentally different user than a working professional browsing on her commute. The most respectful thing we can do is surface the time math early so she can plan, not panic at checkout.

**Example.** The `/bridal` hero copy includes "Order 4 weeks before mehendi." The order form's bridal step prompts for wedding date and conditionally shows urgency messaging if < 4 weeks. The Bridal Trio confirmation email references her wedding date.

**Anti-example.** Treating the Bridal Trio as just another product in the shop grid. Hiding the 4-week guideline in fine print at checkout. Allowing a < 2-week bridal order to be placed without a manual confirmation step. All banned.

---

# 6. Returning customers get rewarded, not re-onboarded.

**Rationale.** The retention path is the difference between a one-shot brand and a brand that compounds. Returning customers have already given us their data, their trust, and their first money. Asking them to do the camera dance again is a tax on loyalty.

**Example.** The order flow's first step has a phone+email lookup above the fold. On match, sizing is pre-filled (default action), the -10% reorder discount appears immediately in the bag drawer, and the package gets a small handwritten "Welcome back" note.

**Anti-example.** Hiding the lookup behind a small "Returning customer? Sign in" link in the corner. Surfacing the -10% only at checkout confirmation. Making "take new sizing photos" the default and "use saved" the secondary action. All banned.

---

# 7. Trust is earned with proof, not promises.

**Rationale.** Small online brands earn first-order trust by *showing*, not *claiming*. "100% authentic" is a phrase any scammer uses. A photo of Mona's actual studio with materials laid out, a refit guarantee with no asterisks, a real customer's hand wearing a real product — these are non-fakeable.

**Example.** Founder name + Mirpur location + craft photography in the homepage hero. UGC carousel on every PDP. The free first refit shown as a trust badge near the price. Studio photos on About showing tools and materials.

**Anti-example.** "We're the most trusted Pakistani nail brand!" copy on the homepage. "100% satisfaction guaranteed!" stickers without explaining what that means. Trust badges from third-party verifications that don't actually exist. All banned.

---

# 8. Pakistan-mobile first, desktop second.

**Rationale.** Most customers will arrive on a 3-year-old Android, on patchy 4G, in landscape, with one earbud in. Designs that work on a 27" monitor and "scale down" don't actually work on the launch device. Build for the constraint; the desktop will be fine.

**Example.** Lighthouse mobile ≥ 90 enforced before any visual polish. Touch targets ≥ 44 × 44 px. SSR-first (no hydration delay). HEIC accepted on the upload. The bag drawer is full-width on mobile, partial-width on desktop — not the other way around.

**Anti-example.** Designs that look stunning at 1440px but require horizontal scrolling at 360px. Hover-only interactions with no touch alternative. JS-only navigation that breaks on patchy connections. All banned.

---

# 9. Brand voice is warm, not chatty.

**Rationale.** The brand should feel like a calm, confident shopkeeper — warm, articulate, attentive. Not a teenage friend. Words like "dear" and "ji" are welcome; emoji clusters and "Hi babe!" are not. The voice should make a 50-year-old aunt opening the page on her phone feel comfortable, not embarrassed.

**Example.** Confirmation page: "Your order is placed, dear. Thank you for trusting us with your nails." Empty cart: "Your bag is empty for now. Browse the collection?" Error message: "Something didn't work — let's try that again."

**Anti-example.** "Yay! 🎉 Your order is on the way! 💅✨" Confirmation copy littered with exclamation marks and emoji. "OMG you're gonna love these!" anywhere on a product page. "Oopsie, something went wrong! 😱" error states. All banned.

---

# 10. When in doubt, route to a human.

**Rationale.** Every complex path in the product has an exit lane to WhatsApp. We don't pretend to handle every edge case automatically — we're a small artisan brand, and the humility of routing to a human is *part of the brand*.

**Example.** The sizing step's third option is "I'll send via WhatsApp later" — we won't insist on the camera. The bridal flow's < 4 week branch deflects to WhatsApp instead of accepting the order automatically. The contact form sends to Mona's inbox; the WhatsApp link is never far from any page.

**Anti-example.** Rejecting an order because the wedding date is too close, with no human-handoff option. Hiding the WhatsApp deep-link several taps deep so customers can't find it when they need it. Replacing all human contact with a chatbot before we have Mona's voice corpus (Phase 8 reasoning). All banned.

---

## How to use these principles

When designing a new flow or page, walk it past every principle in order:

1. Does the imagery show craft without showing faces?
2. Is the bag the only commerce CTA?
3. If the camera is involved, does it feel safe?
4. Is WhatsApp positioned as help, not checkout?
5. If a bridal customer hits this, does the time math surface?
6. If a returning customer hits this, are they rewarded?
7. Are claims backed by proof, not adjectives?
8. Does this work on a 3-year-old Android on 4G?
9. Does the voice feel warm, not chatty?
10. Is there a human exit lane?

If any answer is "no," that's the design conversation to have before shipping.

## Success metrics

Principles are validated by *what we don't see*. Specifically:

- No "Order Now" buttons in the codebase (`grep -r "Order Now" resources/views/`).
- No emoji clusters in source copy (`grep -E "[\u{1F300}-\u{1F9FF}].{0,5}[\u{1F300}-\u{1F9FF}]"`).
- No founder selfies in `storage/app/public/`.
- No third-party trust-badge images.
- WhatsApp pre-fills always start with "Hello Nails by Mona" (`grep "Hello Nails" resources/views/`).

A simple lint script can enforce these. Worth a half-day of build effort in Phase 1.

## Reflection

The principle I'm most uncertain about is **#9 (Brand voice is warm, not chatty)**. The line between warm and chatty is subjective, and Pakistani communication norms are themselves warmer and more familiar than typical Western e-commerce voice. "Dear" and "ji" are warm in Pakistani English; they'd read as overly familiar in American English. The brand voice is calibrated to Pakistani readers, which is correct, but it does mean an outsider reviewer might mis-flag warm-but-correct copy as chatty. A short voice guide with examples (deferred from this round per Humza's choice) would close that gap; if the principle keeps causing edits to bounce back and forth, write the voice guide.

The principle I expect to be challenged most often is **#2 (Bag, not Order Now)**. Other Pakistani brands do use "Order Now" buttons; Mona's existing IG audience may expect them; a pressured designer or marketer will, at some point, push for "just one more CTA." That's why the principle is on the list — to make the conversation explicit when it happens.
