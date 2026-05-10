# Quarterly SEO Audit Checklist — Nails by Mona

> Run this checklist once per quarter. Takes ~2 hours.
> Tools needed: Google Search Console, Bing Webmaster Tools, browser dev tools.

---

## Technical health

- [ ] Sitemap at `/sitemap.xml` is accessible and up to date
- [ ] `robots.txt` allows Googlebot on all public routes, disallows `/admin` and `/order/*`
- [ ] All public pages return HTTP 200 (no accidental noindex or 404)
- [ ] HTTPS green lock on all pages
- [ ] No redirect chains longer than 1 hop
- [ ] No duplicate title tags across pages
- [ ] No duplicate meta descriptions across pages
- [ ] Canonical tag on every page matches the actual URL

---

## Core Web Vitals (mobile)

Run `https://pagespeed.web.dev/` on these pages:

| Page | LCP target | INP target | CLS target |
|---|---|---|---|
| `/` (Home) | < 2.5s | < 200ms | < 0.1 |
| `/shop` | < 2.5s | < 200ms | < 0.1 |
| `/shop/{slug}` (any product) | < 2.5s | < 200ms | < 0.1 |
| `/blog/{slug}` (any post) | < 2.5s | < 200ms | < 0.1 |

- [ ] All pages pass on mobile
- [ ] Images have explicit `width` and `height` (prevents CLS)
- [ ] All images served as WebP or AVIF with JPEG fallback
- [ ] No render-blocking scripts in `<head>` (jQuery is deferred/async)

---

## On-page SEO

For the top 5 pages by traffic:

- [ ] H1 contains the target keyword in natural phrasing
- [ ] Title tag is 50–60 characters, includes brand name at end
- [ ] Meta description is 150–160 characters, includes a CTA
- [ ] Internal links: each page links to at least 2 other pages
- [ ] Images have descriptive alt text (not "image1.jpg")
- [ ] Schema.org JSON-LD present and correct (validate at `schema.org/validator`)

---

## Blog health

- [ ] At least 2 posts published this quarter
- [ ] Each post has FAQ section (triggers FAQPage schema)
- [ ] Each post has "Related products" block populated
- [ ] Blog index `<head>` includes RSS link
- [ ] Sitemap includes all published blog posts

---

## Search Console review

- [ ] No manual actions reported
- [ ] Check "Coverage" — fix any Valid with warnings
- [ ] Check "Core Web Vitals" report — fix any URLs marked Poor
- [ ] Check top 10 queries by impressions → update meta descriptions if CTR < 3%
- [ ] Check top 10 queries by clicks → are we ranking page 1? If not, strengthen those posts

---

## Backlinks + off-page

- [ ] Google Business Profile — new photos added this quarter?
- [ ] NAP (Name, Address, Phone) consistent on GBP, IG bio, website Contact page
- [ ] Any new Pakistani beauty directory listings to claim?
- [ ] Any blog posts published this quarter that could pitch to other sites as guest posts?

---

## Fixes log

| Date | Issue found | Fix applied | Result |
|---|---|---|---|
| | | | |
