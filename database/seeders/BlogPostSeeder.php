<?php

namespace Database\Seeders;

use App\Enums\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'admin@nailsbymona.test')->first();
        if (! $author) {
            return;
        }

        $posts = [
            [
                'slug'            => 'muslim-women-press-on-nails-wudu',
                'title'           => 'Can Muslim Women Wear Press-On Nails? Wudu, Nail Polish, and a Simple Solution',
                'excerpt'         => 'For years, I wore my nails plain — not because I didn\'t care, but because I couldn\'t find a way to have beautiful nails without compromising my prayers. This is the solution I found, and it\'s the reason I started making press-ons in the first place.',
                'category'        => BlogCategory::Tutorials,
                'target_keyword'  => 'press on nails wudu Muslim women',
                'meta_title'      => 'Can Muslim Women Wear Press-On Nails? Wudu & Nail Polish Explained',
                'meta_description'=> 'Press-on nails and wudu explained — remove before ablution, reapply after. The solution Mona found for herself after years of wearing her nails plain as a practicing Muslim.',
                'is_published'    => true,
                'published_at'    => now()->subDays(7),
                'content'         => <<<'HTML'
<p>For years, I wore my nails plain. Not because I didn't care about how they looked — I care enormously about things like that — but because I couldn't find a way to have beautiful nails without compromising my prayers. This is the solution I found, and it's the reason I started making press-ons in the first place.</p>

<h2>The question I kept asking myself</h2>
<p>Growing up, I was always decorating things — drawings, mehndi designs, anything with colour and detail. I studied Fine Arts. I built a small business making bridal henna, resin name plates, paintings. All of these things were fine. And then I turned to nails.</p>
<p>Every option I looked at created the same problem. Nail polish, gel, shellac, acrylics — they all coat the nail bed and form a physical barrier. Water cannot reach the natural nail during wudu. For me, that was not a compromise I was willing to make five times a day.</p>

<h2>Why traditional nail polish causes the problem</h2>
<p>Wudu — the Islamic ritual of ablution before prayer — requires water to reach every part of the body it covers, including the nails, entirely. Traditional nail polish forms a waterproof film by design. No water gets through.</p>
<p>Most scholars of Islamic jurisprudence consider wudu invalid if any part of the nails is coated with a waterproof barrier. This explains why so many Muslim women — in Pakistan and across the world — wear their nails plain every day. The problem is that "wear them plain" is the only answer most of us are ever given. Nobody talks about alternatives.</p>

<h2>How press-on nails are different</h2>
<p>Press-on nails don't coat your nail. They sit on top of it, attached with nail glue or adhesive tabs. The critical difference is that they are designed to be removed and reapplied.</p>
<p>A well-fitting press-on set — one made to your exact nail measurements — takes about 3 minutes to remove with warm water. You soak your fingertips briefly, the adhesive softens gently, and the nails lift cleanly from the sides. No acetone. No filing. No damage to the nail bed.</p>
<p>Before wudu: you remove your press-ons and set them aside. Water reaches your natural nail completely, unobstructed. Your wudu is valid. After prayer: you dry your hands fully, then reapply. The whole process — removal to reapplication — takes about five minutes.</p>
<p>The key word is <em>fit</em>. This only works reliably with a custom-fit set. A generic one-size-fits-all press-on will pop off mid-day or won't lift cleanly. See the <a href="/size-guide">size guide</a> for how to get your measurements right — it takes two minutes and it changes everything.</p>

<h2>My own daily routine</h2>
<p>I've been doing this for over two years — since I started making press-ons and realised this was the answer I'd been looking for. On a typical day, I remove before Fajr wudu in the morning and reapply after. In practice, I'm removing and reapplying 2–3 times per day on active prayer days.</p>
<p>A few things I've learned that make the routine easier:</p>
<ul>
<li>Keep your nails in a small lidded dish on your wudu shelf. Don't leave them on the wet bathroom counter — moisture is their enemy when not on your hands.</li>
<li>Use adhesive tabs rather than brush-on glue if you're removing frequently. Tabs release gently in warm water and can be swapped between wears.</li>
<li>After removing, dry your hands for a full two minutes before reapplying. Moisture under the nail breaks the adhesive bond faster.</li>
</ul>

<blockquote>So many Muslim women have been carrying this problem silently for years. Wanting beautiful nails. Feeling like they had to choose. I started making press-ons because I needed this for myself.</blockquote>

<p>If you're curious about trying a set, start with the <a href="/size-guide">size guide</a>. Getting the fit right is everything. Browse the <a href="/shop">full collection</a> when you're ready.</p>
HTML,
            ],

            [
                'slug'            => 'press-on-nails-vs-acrylics-pakistan-brides',
                'title'           => 'Press-On Nails vs Acrylics: Which Is Better for Pakistani Brides?',
                'excerpt'         => 'The acrylic trap Pakistani brides fall into — and a better answer for all three nights of your wedding. An honest comparison from someone who has done both.',
                'category'        => BlogCategory::Bridal,
                'target_keyword'  => 'press on nails vs acrylics Pakistani brides',
                'meta_title'      => 'Press-On Nails vs Acrylics for Pakistani Brides — Honest Comparison',
                'meta_description'=> 'The acrylic trap Pakistani brides fall into — and why press-ons are a smarter choice for Mehendi, Baraat, and Valima. An honest guide from Mona\'s studio.',
                'is_published'    => true,
                'published_at'    => now()->subDays(13),
                'content'         => <<<'HTML'
<p>Most Pakistani brides book their nail appointment as an afterthought — the day before the Mehendi, squeezed in between venue calls and dupatta alterations. They choose acrylics because everyone does, and three days later they're soaking them off in acetone while their hands are still shaking from the baraat.</p>
<p>I've watched this pattern repeat for years. And every time, I think: there is a better way to do this.</p>

<h2>What acrylics actually do to your nails during a wedding</h2>
<p>Acrylics are bonded with a primer specifically designed to resist lifting. The nail tech etches the surface of your natural nail, applies the primer, and builds the acrylic extension on top. This is not a temporary treatment — it's a structural bond designed to last two to three weeks under daily stress.</p>
<p>To remove them, you soak in acetone for 20–40 minutes, then file, then soak again. This is not something you can do between Baraat and Valima photography. It is not something you can do before morning prayer.</p>

<h2>The three-night problem</h2>
<p>A Pakistani wedding typically spans three events: Mehendi, Baraat, Valima. Each night calls for a different look — warm terracotta and gold for Mehendi, dramatic and regal for Baraat, softer and romantic for Valima. Most brides wearing acrylics wear one colour across all three. Not because they want to. Because there is no other option.</p>
<p>With press-ons, you have three separate sets, made to your exact nail measurements, for each night. You switch sets the same way you switch your jewellery. The Bridal Trio package is designed specifically for this — three coordinated sets shipped together, made by the same hand, with a consistent sizing profile.</p>

<h2>The real cost comparison</h2>
<p>A salon acrylic set costs PKR 2,500–5,000 per application. Over a three-night wedding period, if you want fresh nails for each event (which most brides don't even consider possible), you're looking at PKR 7,500–15,000 — plus acetone damage and salon time that you don't have.</p>
<p>The Bridal Trio from Nails by Mona costs PKR 11,000–13,500. Three custom-fit sets, coordinated, shipped together, with a refit guarantee if anything doesn't sit right. And you keep them — they can be reused 3–5 times, meaning future events like Eid or anniversaries are already sorted.</p>

<h2>What actually matters on your wedding day</h2>
<p>Your nails will be in every photograph. The close-ups during ring exchange. The mehndi application. The dupatta adjustment. Hands are prominent in Pakistani wedding photography in a way that's unique to our celebrations — and what's on them matters.</p>
<p>Custom-fit press-ons look finished in a way that standard sizes don't. They cover the nail bed cleanly, sit flush, and stay put. The difference in the photographs is visible.</p>
<p>Order your Bridal Trio at least 4 weeks before your Mehendi. That gives enough time for sizing, production, and one fitting if needed. Browse the <a href="/bridal">bridal page</a> for everything that's included.</p>
HTML,
            ],

            [
                'slug'            => 'how-to-apply-press-on-nails',
                'title'           => 'How to Apply Press-On Nails — A Foolproof 7-Step Guide',
                'excerpt'         => 'Prep is 80% of the result. Here\'s exactly how to get a salon finish from home, every time — including the mistakes that shorten wear time.',
                'category'        => BlogCategory::Tutorials,
                'target_keyword'  => 'how to apply press on nails',
                'meta_title'      => 'How to Apply Press-On Nails — 7-Step Guide for a Salon Finish',
                'meta_description'=> 'Step-by-step application guide for custom press-on nails — from nail prep to final press. The mistakes that shorten wear time and how to avoid them.',
                'is_published'    => true,
                'published_at'    => now()->subDays(20),
                'content'         => <<<'HTML'
<p>The most common complaint I hear about press-on nails is that they fall off too quickly. Nine times out of ten, the problem isn't the nails or the glue — it's the preparation. Prep is 80% of the result. Get the surface right and everything else follows.</p>
<p>Here is the exact process I walk every customer through.</p>

<h2>What you'll need</h2>
<ul>
<li>Your custom press-on set</li>
<li>Nail glue or adhesive tabs (both included in your order)</li>
<li>Nail file or buffer</li>
<li>Rubbing alcohol (or the prep pad in your kit)</li>
<li>Orange stick or cuticle pusher</li>
</ul>

<h2>Step 1 — Start with completely dry, clean nails</h2>
<p>No hand cream, no oils, no moisture. If you've just washed your hands, wait 10 minutes. Oils and moisture are the enemy of adhesive bonds. If your nails feel at all soft or damp, wait longer.</p>

<h2>Step 2 — Push back your cuticles</h2>
<p>Use an orange stick after soaking in warm water for 2–3 minutes. You don't need to cut anything. Just gently push the cuticle back so the nail surface is fully exposed. A press-on that sits on top of the cuticle will lift at the base within a day.</p>

<h2>Step 3 — Buff the surface lightly</h2>
<p>A light buff with a 180-grit buffer creates micro-texture that the adhesive grips. You're not thinning the nail — just removing the natural shine from the top layer. One or two light passes is enough.</p>

<h2>Step 4 — Wipe with alcohol</h2>
<p>This removes any remaining oil, dust from buffing, and dead skin cells. Use the prep pad in your kit, or a cotton pad with rubbing alcohol. Let it dry completely — 30 seconds. This step is the one most people skip. Don't skip it.</p>

<h2>Step 5 — Apply your adhesive</h2>
<p>For glue: apply a thin layer to the press-on, not your natural nail. A little goes a long way — a thick layer actually reduces bond strength. For tabs: peel and press firmly onto your natural nail, making sure to press the edges down.</p>

<h2>Step 6 — Press and hold</h2>
<p>Align the press-on with your cuticle line first, then press down from base to tip. Hold firm pressure for 30–60 seconds per nail. Don't rush this. The bond forms under pressure.</p>

<h2>Step 7 — Don't get your nails wet for 2 hours</h2>
<p>The adhesive needs time to cure fully. Avoid washing dishes, swimming, or anything wet for at least 2 hours after application. After that, you're fine — the bond is set.</p>

<h2>How to make them last longer</h2>
<p>Wear gloves for washing dishes. Avoid using your nails as tools — prying open packages, scraping labels. Apply a tiny drop of glue to any edges that start to lift, and press down immediately. Caught early, lifting is always fixable.</p>
<p>Have questions about your specific set? Get in touch on <a href="/contact">WhatsApp</a> — I reply within a few hours.</p>
HTML,
            ],

            [
                'slug'            => 'how-to-remove-press-on-nails',
                'title'           => 'How to Remove Press-On Nails Without Damaging Your Natural Nails',
                'excerpt'         => 'Never peel, never force. The warm-water soak method that protects your nail bed and keeps your set reusable for next time.',
                'category'        => BlogCategory::Care,
                'target_keyword'  => 'how to remove press on nails without damage',
                'meta_title'      => 'How to Remove Press-On Nails Safely — No Damage, Keep Them Reusable',
                'meta_description'=> 'The right way to remove press-on nails — warm water soak, gentle lift, no acetone needed. How to clean and store them for 3–5 reuses.',
                'is_published'    => true,
                'published_at'    => now()->subDays(27),
                'content'         => <<<'HTML'
<p>Peeling press-on nails off is tempting and almost always wrong. It tears the top layer of your natural nail, leaves adhesive residue that's hard to remove, and damages the press-on so it can't be reused. Here is the right way — it takes 5 minutes and leaves both your nails and your set intact.</p>

<h2>The warm water soak method</h2>
<p>Fill a bowl with warm water. Not hot — warm. Soak your fingertips for 60–90 seconds. The adhesive softens in warm water, and the bond weakens enough that the nails can be lifted cleanly.</p>
<p>After soaking, use an orange stick to gently slide under the edge of the press-on at the base (near the cuticle). Apply light, steady pressure — not force. The nail should release slowly and cleanly. If it's resisting, soak for another 30 seconds and try again.</p>
<p>Never peel, never force. Patience here protects your nail bed.</p>

<h2>Removing adhesive tabs vs glue</h2>
<p>Adhesive tabs release much more easily in warm water — they're designed for frequent removal. Most tabs release with a single gentle lift after soaking. Glue takes a bit longer. If you applied a thick layer of glue, you may need 2–3 rounds of soaking.</p>
<p>If there's any glue residue left on your natural nail after removal, don't scrape it off. Soak again briefly, then rub gently with a cotton pad dampened with rubbing alcohol. The residue will dissolve without any pressure on the nail.</p>

<h2>How to clean and store your press-ons for reuse</h2>
<p>After removing, each press-on will have adhesive residue on the underside. Peel off any tab residue with your fingers — it comes off easily at this point. For glue residue, apply a small amount of acetone to a cotton bud and rub the underside only. Never apply acetone to the top surface — it will cloud the gel finish.</p>
<p>Once clean, store your press-ons in the original tray or a small lidded box, in order. Keeping them organised by finger means reapplication takes 5 minutes instead of 15.</p>

<h2>How many times can press-ons be reused?</h2>
<p>With proper removal and care, 3–5 reuses is realistic. The limiting factor is usually the gel surface on the underside — repeated adhesive applications gradually reduce the surface area available for a good bond. If a press-on is no longer holding well even with fresh adhesive, it's reached the end of its life for that nail.</p>
<p>The top surface — the gel design — lasts much longer than the adhesive side. Your nails will look exactly the same in wear 5 as in wear 1.</p>

<h2>When to do a nail break</h2>
<p>After 3–4 weeks of continuous wear (including any days between sets), give your natural nails 2–3 days without press-ons. Apply a nail oil or treatment during this time. Your nails will be fine — they don't require extended breaks the way acrylics do — but a short rest lets you monitor them and keeps the nail bed healthy long-term.</p>
<p>Questions about your specific set? <a href="/contact">Get in touch</a> — I'm happy to help.</p>
HTML,
            ],
        ];

        foreach ($posts as $data) {
            BlogPost::firstOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['author_id' => $author->id])
            );
        }
    }
}
