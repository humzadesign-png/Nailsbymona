# User Flows — Six task flows in ASCII

> **Branching task flows for the highest-leverage moments.** Where journey maps trace the full emotional arc, these flows trace the literal click-by-click branches a user can take, including failure paths and recovery routes.

---

## Problem framed

Designers and developers need different views of the same product. Journey maps are good for understanding *why* a user clicks; flow diagrams are good for understanding *what happens when they do*. Without a flow document, edge cases get invented during implementation — usually badly. The flows below are the explicit branching trees for the six highest-stakes tasks. Everything else can be inferred from these.

## Methodology

Six flows, picked because they cover the highest-traffic + highest-failure paths:

1. **Browse → Add to bag → Checkout** (the main commerce path).
2. **Sizing capture — live camera happy path** (the differentiator).
3. **Sizing capture — fallback paths** (where the differentiator gracefully degrades).
4. **Returning-customer lookup** (the retention path).
5. **Bridal Trio order** (the high-AOV variant).
6. **Manual payment verification** (the operational path between customer's payment proof and Mona's Filament admin).

Flows are in markdown + ASCII, not Figma, because (a) they live in the repo and version with the code, (b) Mona can read them on her phone, (c) they survive any rendering environment, and (d) they're portfolio-friendly when screenshotted.

## Legend

```
┌──────────┐    A box is a screen, page, or system action.
│  Action  │
└──────────┘

   │
   ▼            An arrow is a transition.

◇──────◇        A diamond-bordered box is a decision point.

[Y]   [N]       Branch labels — Yes / No, or specific labels.

★ STATE         A persistent state change (DB write, localStorage update).

⚠ FAILURE       A failure path — what happens when something goes wrong.

→ RECOVERS      A recovery path — how the user gets back on track.
```

---

## Flow 1 — Browse → Add to bag → Checkout

> The main commerce path. From `/shop` to a placed order. This is the spine. Every other flow either feeds into it or branches off it.

```
┌─────────────────────┐
│   /shop             │   User lands on the shop grid
│   (product grid)    │   Filters by tier (Everyday / Signature / Glam / Bridal)
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│   /shop/{slug}      │   Product detail page
│   (PDP)             │   Photos · Description · "Add to bag" CTA · UGC · FAQ
└──────────┬──────────┘
           │
           │ User taps "Add to bag"
           ▼
   ◇──────────────────◇
   │  Bag empty       │
   │  before this?    │
   ◇──────┬───────────◇
   │      │
  [Y]    [N]
   │      │
   │      ▼
   │   ★ Update bag in localStorage (nbm.bag key, JSON)
   │   ★ Increment bag icon count badge
   │      │
   │      ▼
   │   Bag drawer slides in from right
   │   Shows: this item + previously added items + subtotal
   │      │
   ▼      ▼
   ★ Initialize bag in localStorage
   ★ Bag icon count = 1
   │
   ▼
┌─────────────────────┐
│   Bag drawer        │   "Continue shopping" or "Checkout"
│   (right slide-in)  │
└─────┬───────────────┘
      │
      ├── User taps "Continue shopping" → drawer closes, returns to /shop
      │
      └── User taps "Checkout"
              │
              ▼
       ┌─────────────────────┐
       │   /order/start      │   Multi-step order form
       │   step 1 — sizing   │   Returning lookup → branch to Flow 4
       └─────────┬───────────┘   First-time → Flows 2 or 3
                 │
                 ▼ (sizing complete — see Flows 2/3)
       ┌─────────────────────┐
       │   /order/start      │   Step 2 — name, email, WhatsApp,
       │   step 2 — details  │   address, city, postal, notes
       └─────────┬───────────┘
                 │
                 ▼
       ┌─────────────────────┐
       │   /order/start      │   Step 3 — payment method radio:
       │   step 3 — payment  │   JazzCash · EasyPaisa · Bank Transfer (all manual proof upload)
       └─────────┬───────────┘
                 │
                 ▼
              ◇──────────────◇
              │ Total ≥      │
              │ PKR 5,000?   │
              ◇──────┬───────◇
                     │
              ┌──[Y]─┴─[N]──┐
              ▼              ▼
         ★ Set            Continue to
         requires_         place order
         advance = true    (no advance gate)
              │              │
              ▼              ▼
         Show advance       Submit
         block              order
         "20–30% required"
              │
              ▼
         Submit order
              │
              ▼
       ┌─────────────────────┐
       │   /order/confirm/   │   Order number
       │   {order}           │   Account details for chosen method
       │                     │   Payment-proof upload field
       └─────────────────────┘
              │
              ▼
       ★ Order in DB (status = new, payment_status = awaiting)
       ★ Confirmation email sent
       ★ localStorage nbm.bag cleared
              │
              ▼
       Customer transfers funds in their app/bank,
       returns to confirmation page (or email link),
       uploads screenshot/receipt
              │
              ▼
       Mona reviews proof in Filament admin
       → marks payment_status = paid (or verifying if unclear)
              │
              ▼
       Status emails kick in
       (Confirmed → In Production → Shipped → Delivered)
```

**Why this shape:**
- The bag drawer is the *only* commerce CTA. No "Order Now" button anywhere in the funnel.
- The bag persists in `localStorage` so closing the tab doesn't lose state — important on Pakistan mobile where tabs get killed by the OS aggressively.
- The advance-payment gate is decided at submit-time, not pre-emptively, so the customer doesn't see "PKR 5,000 minimum" anywhere — it's about *this* order, not a generic threshold.

---

## Flow 2 — Sizing capture, live camera (happy path)

> The differentiator. Single camera session, **2-photo state machine** (fingers → thumb), green/red alignment heuristic, optional opt-in for 2 more photos for the other hand. Permission asked once for the whole session. Replaced the old single-photo flow on 2026-05-07 — see CLAUDE.md §32.

```
┌─────────────────────────────────────┐
│   Sizing step                        │
│   (in /order/start step 1)           │
│                                      │
│   "How would you like to fit         │
│    your nails?"                      │
│                                      │
│   ◉ Take photos with our guide      │
│   ◯ Upload from gallery              │
│   ◯ I'll send via WhatsApp           │
└────────────┬────────────────────────┘
             │
             │ User selects "Take photos with our guide"
             ▼
┌─────────────────────────────────────┐
│   Camera explainer screen            │
│   (State A in 12-sizing-capture.md)  │
│                                      │
│   "We'll need 2 close-up photos —    │
│    fingers and thumb. About 90 sec." │
│                                      │
│   [Start camera]                     │
└────────────┬────────────────────────┘
             │
             │ User taps "Start camera"
             │ Permission requested ONCE for the whole session
             ▼
   ◇──────────────────────────────◇
   │  Browser supports             │
   │  getUserMedia + HTTPS?        │
   ◇──────────┬───────────────────◇
              │
       ┌──[Y]─┴─[N]──┐
       │              │
       ▼              ▼
   getUserMedia()    ⚠ Switch to Flow 3
   permission        (fallback) with
   granted?          friendly copy
       │
   ┌─[Y]┴─[N]─────────┐
   │                   │
   ▼                   ▼
   Live video           ⚠ Switch to
   feed renders         Flow 3 (fallback)
   in <video>
       │
       ▼
┌─────────────────────────────────────┐
│   STATE B — Photo 1 of 2: Fingers    │
│                                      │
│   Title strip: "Photo 1 of 2"        │
│   Progress bar: 50%                  │
│                                      │
│   ┌──────────────────────────┐       │
│   │   <video> back camera    │       │
│   │                          │       │
│   │   ┏━━━SVG overlay━━━━┓   │       │
│   │   ┃ 4-finger row outline┃   │       │
│   │   ┃ + coin circle above┃   │       │
│   │   ┃   middle finger    ┃   │       │
│   │   ┗━━━━━━━━━━━━━━━━━━┛   │       │
│   │   ↑ Border: green/red    │       │
│   └──────────────────────────┘       │
│                                      │
│   ✓ Good lighting (brightness pill)  │
│   [Shutter] (large, white+lavender)  │
└────────────┬────────────────────────┘
             │
             │ Two heuristics tick at 500ms:
             │   1. Brightness check  →  pill green/amber
             │   2. Edge contrast in fingers region →
             │      border green / amber / red
             │
             │ User taps Shutter (when border green)
             │ — heuristic GUIDES, doesn't block
             ▼
   <canvas>.drawImage(video) → toBlob()
   ★ captures.fingers = blob
             │
             ▼
┌─────────────────────────────────────┐
│   STATE C — Photo 2 of 2: Thumb      │
│                                      │
│   Same camera feed continues.        │
│   No permission re-prompt.           │
│                                      │
│   Title strip: "Photo 2 of 2"        │
│   Progress bar: 100%                 │
│                                      │
│   ┏━━━SVG overlay━━━━┓               │
│   ┃ Single thumb       ┃               │
│   ┃ vertical outline   ┃               │
│   ┃ + coin circle above┃               │
│   ┃   thumbnail        ┃               │
│   ┗━━━━━━━━━━━━━━━━━━┛               │
│   Border: green/red (thumb region)   │
│                                      │
│   [Shutter]                          │
└────────────┬────────────────────────┘
             │
             │ Same heuristic logic.
             │ User taps Shutter.
             ▼
   ★ captures.thumb = blob
             │
             ▼
┌─────────────────────────────────────┐
│   STATE D — Preview                  │
│                                      │
│   ┌────────┬────────┐                │
│   │Fingers │ Thumb  │  (thumbnails)  │
│   │[Retake]│[Retake]│                │
│   └────────┴────────┘                │
│                                      │
│   Mental checklist (4 items):        │
│   ☐ Coin visible · ☐ All nails       │
│   in outline · ☐ In focus ·          │
│   ☐ Even lighting                    │
│                                      │
│   ┌────────────────────────┐         │
│   │ Symmetry disclaimer    │         │
│   │ "We size both hands    │         │
│   │  from these photos.    │         │
│   │  Free first refit."    │         │
│   └────────────────────────┘         │
│                                      │
│   [Submit my sizing]                 │
│   [Add my other hand →]              │
└──┬─────────────────────┬─────────────┘
   │                     │
[Submit]            [Add other hand]
   │                     │
   ▼                     ▼
   Skip to              ┌──────────────────┐
   STATE F              │ STATE E (optional)│
                        │                  │
                        │ Photo 3 of 4:    │
                        │ Other-hand fingers│
                        │   ↓              │
                        │ Photo 4 of 4:    │
                        │ Other-hand thumb │
                        │   ↓              │
                        │ Returns to       │
                        │ STATE D as 4-    │
                        │ thumbnail preview│
                        │ (no disclaimer,  │
                        │  no opt-in CTA)  │
                        │   ↓              │
                        │ [Submit my sizing]│
                        └────────┬─────────┘
                                 │
                                 ▼
   ┌─────────────────────────────────┐
   │  STATE F — Submit               │
   │  POST 2 (or 4) blobs to         │
   │  /order/sizing-photos           │
   │  with photo_type for each       │
   │                                 │
   │  ★ Server strips EXIF +          │
   │    converts HEIC → JPEG         │
   │  ★ Saves with ULID filenames    │
   │  ★ orders.sizing_capture_method │
   │    = "live_camera"              │
   │  ★ order_sizing_photos rows     │
   │    inserted with photo_type     │
   └─────────────┬───────────────────┘
                 │
                 ▼
   Continue to step 2 of order flow
```

**Why this shape:**
- **Single camera session.** Permission requested once. Switching from State B (fingers) to State C (thumb) is just an SVG overlay swap — no UI flicker, no re-prompt. Reduces abandonment between photos.
- **Heuristic guides, human decides.** The green/red border is a *suggestion* based on brightness + edge contrast. It does not auto-capture and does not block capture. We don't trust the heuristic enough to take agency away from the customer.
- **Per-photo retake** preserves the other captures in memory. Retaking the thumb photo doesn't lose the fingers blob.
- **Symmetry disclaimer is explicit, not buried.** Customers should know we're sizing both hands from one set of photos. Hiding this would erode trust if they noticed later.
- **Opt-in, not forced.** Asking everyone for 4 photos when 95% don't need them is friction without value. The 5% who genuinely have asymmetric hands self-select via the opt-in CTA on the preview screen.

---

## Flow 3 — Sizing capture, fallback paths

> When the happy path doesn't work, the user must not bounce. Three fallback branches, all equally valid, none framed as failure.

```
                                     ┌─────────────────────┐
                                     │   Sizing step        │
                                     └──────────┬──────────┘
                                                │
                                                ▼
                            ◇────────────────────────────────────◇
                            │   Why is the user here?            │
                            ◇──┬──────────┬──────────┬───────────◇
                               │          │          │
              ┌────────────────┘          │          └────────────────┐
              │                           │                            │
              │ Camera permission         │ HTTPS missing               │ User just chose
              │ denied                    │ (dev/staging)               │ "I'll send via
              │                           │ OR iOS Safari < 11          │  WhatsApp"
              ▼                           ▼                             │
   ┌─────────────────────┐    ┌─────────────────────┐                  │
   │   Friendly copy     │    │   Friendly copy     │                  │
   │                     │    │                     │                  │
   │   "Camera blocked   │    │   "Camera isn't     │                  │
   │   in your browser.  │    │   available — let's │                  │
   │   That's okay —     │    │   upload 2 photos   │                  │
   │   upload 2 photos   │    │   instead."         │                  │
   │   from your gallery │    │                     │                  │
   │   instead."         │    │                     │                  │
   │                     │    │                     │                  │
   │   [Upload photos]   │    │   [Upload photos]   │                  │
   └──────────┬──────────┘    └──────────┬──────────┘                  │
              │                          │                              │
              └──────────┬───────────────┘                              │
                         │                                              │
                         ▼                                              ▼
   ┌──────────────────────────────────────┐         ┌─────────────────────┐
   │   2 labelled file inputs:            │         │   Notes capture     │
   │                                      │         │                     │
   │   [ Fingers photo ]  required        │         │   "Tell us anything │
   │   [ Thumb photo ]    required        │         │    relevant about   │
   │                                      │         │    your nails"      │
   │   ┌────────────────────────────┐     │         │                     │
   │   │ Symmetry disclaimer        │     │         │   [Continue]        │
   │   │ + [Add my other hand →]    │     │         └──────────┬──────────┘
   │   │   reveals 2 more inputs    │     │                    │
   │   └────────────────────────────┘     │                    ▼
   │                                      │         ★ sizing_capture_method
   │   <input type="file"                 │           = "whatsapp_pending"
   │    accept="image/*"                  │         ★ Order placed with no photos
   │    capture="environment">            │         ★ Mona requests photos via
   │                                      │           WhatsApp after order received
   │   File picker opens (per slot)       │
   │   ★ Strip EXIF (server-side)         │
   │   ★ Convert HEIC → JPEG              │
   │   ★ Reject if > 8MB per file         │
   │   ★ Each upload tagged with          │
   │     photo_type =                     │
   │      fingers / thumb /               │
   │      fingers_other / thumb_other     │
   │   ★ sizing_capture_method            │
   │     = "upload"                       │
   │                                      │
   │   Submit only when both required     │
   │   slots are filled                   │
   └──────────────┬───────────────────────┘
                  │
                  ▼
       Continue to step 2 of order flow
```

**Why this shape:**
- All three fallbacks lead to the same downstream step. The user never feels they're on a "lesser" path.
- The "I'll send via WhatsApp" option is intentionally available to first-time users who aren't ready for either camera or upload — pretending it doesn't exist would just push them to abandon.
- Each fallback has a *specific* friendly copy framed for its trigger. Generic "An error occurred" copy is banned.

---

## Flow 4 — Returning-customer lookup

> The retention design. A returning customer must feel rewarded, not re-onboarded.

```
┌─────────────────────────────────────┐
│   /order/start (top of step 1)       │
│                                      │
│   "Returning? Find your details"    │
│   ┌─────────────────────────┐        │
│   │ Phone:  +92 ___ ____    │        │
│   │ Email:  ___@____.___    │        │
│   └─────────────────────────┘        │
│   [Find me]                          │
│                                      │
│   First time? Continue below ↓       │
└────────────┬────────────────────────┘
             │
             │ User taps "Find me"
             ▼
   POST /api/customer-lookup
        { phone, email }
             │
             ▼
   ◇────────────────────────◇
   │  Customer record       │
   │  exists?               │
   ◇──────────┬─────────────◇
              │
       ┌──[Y]─┴─[N]──────┐
       │                  │
       ▼                  ▼
   ★ Load customer        Friendly error:
   ★ Pre-fill form        "We didn't find a
     fields with           match — let's set
     name, address,        up your details
     city, postal, etc.    fresh."
   ★ Apply -10%            (Continues as
     reorder discount      first-time flow)
     in bag drawer
       │
       ▼
   ◇────────────────────────◇
   │  has_sizing_on_file?   │
   ◇──────────┬─────────────◇
              │
       ┌──[Y]─┴─[N]──────┐
       │                  │
       ▼                  ▼
   "Use sizing from      Continue to
   order NBM-2026-0042?" sizing step
   (2 photos on file —    (Flow 2: 2-photo
    fingers + thumb)       capture)
   [Yes, use saved] (default)
   [Take new photos] (secondary)
       │
       ▼
   Skip sizing step entirely
   ★ sizing_capture_method = "from_profile"
       │
       ▼
   Continue to step 2 (details)
```

**Why this shape:**
- The lookup is *above the fold* on step 1, not buried. Returning customers shouldn't have to find the shortcut.
- Phone + email both required. Either alone allows enumeration; both together require intent. (Security note: this also rate-limits to throttle:5,1 — see CLAUDE.md §15.)
- Saved sizing is the **default action** with "Take new photo" as a secondary link — the opposite of treating saved data as opt-in.
- The -10% appears in the **bag drawer** the moment lookup succeeds, not at confirmation. Recognition + reward in the same motion.

---

## Flow 5 — Bridal Trio order (high-AOV variant)

> Same shape as the standard order flow but with three additions: WhatsApp consultation entry, full-advance gate, and bridal-tier confirmation copy.

```
┌─────────────────────┐
│   /bridal           │   Bridal page (different photography, different copy)
└──────────┬──────────┘
           │
           ├── Branch A: User taps "Add Bridal Trio to bag"
           │       │
           │       ▼
           │   Standard bag drawer flow → checkout
           │       │
           │       ▼
           │   Bridal-specific changes:
           │   ★ Wedding date capture in step 2
           │     ("When is your mehendi?")
           │   ★ Full advance required at step 3
           │     (full advance via JazzCash / EasyPaisa / Bank Transfer; no half-payment options)
           │   ★ Bridal-tier confirmation email
           │     references wedding date, sets
           │     production expectations
           │
           └── Branch B: User taps "Get help" (secondary CTA)
                   │
                   ▼
              WhatsApp deep-link with brand-addressed pre-fill:
              "Hello Nails by Mona, I'm interested in the
               Bridal Trio for my wedding."
                   │
                   ▼
              Consultation conversation
                   │
                   ▼
              Mona shares a custom link back to /bridal with
              suggested designs (manual today, possibly templated
              in Phase 5)
                   │
                   └── User returns to Branch A flow
```

**Decision points unique to bridal:**

```
   ◇────────────────────────────◇
   │  Wedding date < 4 weeks?    │
   ◇──────┬─────────────────────◇
          │
   ┌──[Y]─┴─[N]──┐
   │             │
   ▼             ▼
   ⚠ Show       Standard
   urgency       bridal copy:
   block:        "We'll begin
   "Less than    crafting your
   4 weeks —     trio after we
   please        receive your
   message us    advance."
   first to
   confirm
   we can ship
   in time."
   [Get help]
```

**Why this shape:**
- Wedding date is captured in step 2 specifically so we can branch on it for downstream messaging (status emails reference it, mid-production photo timing, etc.).
- Full advance is non-negotiable for Bridal Trio — operational reality (we will not buy materials for a PKR 12k custom order without commitment) communicated as care, not as fence.
- The < 4 week branch is a deliberate "Get help" deflection — bridal customers who order with too little time deserve a manual confirmation, not an automated promise we might not keep.

---

## Flow 6 — Manual payment verification

> The operational path that bridges customer ↔ Mona ↔ Filament. At MVP every order is paid up front via JazzCash, EasyPaisa, or bank transfer (no COD, no card gateway yet). The customer uploads a payment screenshot, Mona verifies in Filament, the order moves into production. Phase 6 (SafePay) automates the JazzCash/EasyPaisa/Card path — bank transfer stays manual.

```
┌─────────────────────────────┐
│   Order placed                │
│   payment_method =            │
│   jazzcash | easypaisa |      │
│   bank_transfer               │
│   payment_status = awaiting   │
└──────────┬──────────────────┘
           │
           ▼
┌─────────────────────────────┐
│   /order/confirm/{uuid}       │
│   Account details for         │
│   selected method             │
│   + proof upload field        │
└──────────┬──────────────────┘
           │
           ▼
   Customer transfers funds in
   their app/bank, returns to
   confirmation page (or email
   link), uploads screenshot
           │
           ▼
   ◇────────────────────────────◇
   │  Proof uploaded within     │
   │  72 hours?                 │
   ◇──────┬─────────────────────◇
          │
   ┌──[Y]─┴─[N]──┐
   │             │
   ▼             ▼
   ★ Save proof   Auto-cancel
     in           order at 72h
     order_       (with
     payment_     notification
     proofs       email; can be
   ★ Order        restored if
     visible in   customer
     Filament     reaches out)
     "Pending
     verification"
     queue
          │
          ▼
┌─────────────────────────────┐
│   Filament Orders kanban     │
│   "Pending verification"      │
│   column (sorted oldest first)│
│                              │
│   NBM-2026-0043               │
│   PKR 2,800 · JazzCash        │
│   [View proof] [Mark paid]    │
│   [Mark verifying]            │
└──────────┬──────────────────┘
           │
           ▼
   Mona reviews proof
           │
           ▼
   ◇────────────────────────────◇
   │  Proof matches expected    │
   │  amount + method?          │
   ◇──────┬─────────────────────◇
          │
   ┌──[Y]─┴─[N]──────────┐
   │                      │
   ▼                      ▼
   ★ payment_status       ★ payment_status
     = paid                 = verifying
   ★ order status         Mona WhatsApps
     = confirmed          customer for
   "Order confirmed"       clarification
   email fires            (using brand-
                          addressed
                          pre-fill)
          │                       │
          │                       ▼
          │              ◇──────────────◇
          │              │ Resolved?    │
          │              ◇──────┬───────◇
          │                     │
          │              ┌──[Y]─┴─[N]──┐
          │              │              │
          │              ▼              ▼
          │              Re-mark        Mark
          │              paid;          cancelled
          │              continue       (or hold
          │              normally       for further
          │                             contact)
          │
          ▼
   Status emails:
   Confirmed → In Production → Shipped → Delivered
```

**Why this shape:**
- **Pakistani SMB norm.** Manual JazzCash + EasyPaisa + bank-transfer-with-proof is the actual transaction pattern most small Pakistani brands run today. Customers know it; Mona already runs it via DM. No customer-side learning curve.
- **24-hour verification SLA.** Mona checks Filament once or twice daily; the kanban surfaces oldest pending proofs first. Auto-cancel at 72h prevents zombie orders.
- **Verifying status (not just paid/failed).** When a proof is unclear (wrong amount, blurry screenshot, wrong method), `verifying` lets Mona pause without rejecting — she can WhatsApp the customer and resolve quickly without losing the order.
- **No gateway, no webhook, no PCI scope.** Zero infrastructure dependencies. Cheapest, fastest path to MVP launch.
- **Phase 6 (SafePay) replaces three-quarters of this flow** — JazzCash and EasyPaisa become gateway-mediated (real-time confirmation), card becomes a real option, only bank transfer stays manual. The architecture for that is in CLAUDE.md §26.

---

## Cross-flow patterns

When you stack the six flows, three patterns repeat:

1. **Every flow has a graceful fallback.** Camera fails → upload. Upload fails → WhatsApp. Lookup fails → first-time flow. Proof unclear → `verifying` + WhatsApp clarification rather than outright reject. There is no dead end in this product.

2. **Every persistent state change is annotated with `★`.** This makes the data model implications of each flow legible at a glance — useful for the Phase 0/1 build to confirm the schema covers every state mentioned.

3. **No flow has more than 8 boxes deep on the happy path.** If a happy path is more than 8 steps, the flow is wrong, not the diagram. (The bridal flow is the longest at 7 steps.)

## Success metrics

- Step-by-step funnel completion in PostHog (target: 70% step-1 → step-2; 80% step-2 → step-3; 90% step-3 → confirmation).
- `sizing_capture_method` distribution: target ≥ 60% live_camera, ≤ 30% upload, ≤ 10% whatsapp_pending by month 3.
- `photo_type` distribution: track how many customers add the optional `fingers_other` + `thumb_other` photos vs. stick with the default 2-photo flow. Hypothesis: 5–10% opt-in rate.
- Returning-customer lookup success rate (target: ≥ 95% of attempts succeed when match exists).
- Payment-proof upload completion rate (target: ≥ 90% of orders placed → proof received within 72h before auto-cancel).
- Verification SLA (target: < 24h from proof upload to `payment_status = paid` for ≥ 90% of orders).
- `verifying` rate (proof needed clarification): target ≤ 10% — anything higher signals confirmation-page copy or upload UI needs work.

## Reflection

The flows have one gap I'm aware of: **failure recovery for partial uploads on flaky 4G**. With 2 (or 4) blobs in a single submit request, a connection drop mid-upload could lose all of them. Best practice: client-side retry with progress indicator, blobs persisted in IndexedDB so a tab reload doesn't wipe state. I left this out of the diagrams to keep them readable, but it's a real edge case for the build phase — flag for `docs/pages/12-sizing-capture.md` to address.

The bridal flow assumes Mona will reply to consultation messages personally during launch; in Phase 8, when chatbot is in scope, that branch will need a chatbot variant. The shape doesn't change, but the actor does.
