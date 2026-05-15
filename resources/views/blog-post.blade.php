@extends('layouts.app')

@push('head')
<style>
  .article-body { max-width: 740px; margin: 0 auto; }
  .article-body p { font-family: 'DM Sans', ui-sans-serif, system-ui; font-size: 1.0625rem; line-height: 1.8; color: #3A332E; margin-bottom: 1.5rem; }
  .article-body h2 { font-family: 'Fraunces', Georgia, serif; font-variation-settings: 'opsz' 144, 'SOFT' 30; font-size: clamp(1.35rem, 2.5vw, 1.65rem); font-weight: 300; color: #1A1614; line-height: 1.2; margin-top: 3rem; margin-bottom: 1rem; }
  .article-body h3 { font-family: 'DM Sans', ui-sans-serif, system-ui; font-size: 1.1rem; font-weight: 600; color: #1A1614; margin-top: 2rem; margin-bottom: 0.5rem; }
  .article-body blockquote { border-left: 3px solid #BFA4CE; padding-left: 1.5rem; margin: 2rem 0; font-style: italic; color: #3A332E; }
  .article-body ul, .article-body ol { padding: 0; margin-bottom: 1.5rem; }
  .article-body ul { list-style: none; }
  .article-body ul li { padding-left: 1.5rem; position: relative; margin-bottom: 0.75rem; font-size: 1.0625rem; line-height: 1.7; color: #3A332E; }
  .article-body ul li::before { content: '—'; position: absolute; left: 0; color: #BFA4CE; }
  .article-body ol li { margin-bottom: 0.75rem; font-size: 1.0625rem; line-height: 1.7; color: #3A332E; padding-left: 0.25rem; }
  .article-body a { color: #5B4570; text-decoration: underline; text-decoration-color: #BFA4CE; text-underline-offset: 3px; }
  .article-body a:hover { color: #BFA4CE; }
  .article-body img { max-width: 100%; border-radius: 12px; margin: 1.5rem 0; }

  /* FAQ accordion */
  .faq-answer { display: none; }
  .faq-item.open .faq-answer { display: block; }
  .faq-item.open .faq-icon { transform: rotate(45deg); }
  .faq-icon { transition: transform 0.2s ease; }

  /* Related card hover */
  .related-card:hover .related-card-title { color: #BFA4CE; }
  .related-card:hover .related-card-img { transform: scale(1.03); }
  .related-card-img { transition: transform 0.3s ease; }
</style>
@endpush

@php
$faqEntities = $faqs->map(fn($f) => [
    '@type'          => 'Question',
    'name'           => $f->question,
    'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer],
])->values()->all();

$blogPostSchema = json_encode([
    '@context' => 'https://schema.org',
    '@graph'   => array_filter([
        [
            '@type'         => 'Article',
            'headline'      => $post->title,
            'description'   => $post->excerpt,
            'author'        => ['@type' => 'Person', 'name' => 'Mona'],
            'publisher'     => ['@type' => 'Organization', 'name' => 'Nails by Mona'],
            'datePublished' => ($post->published_at ?? $post->created_at)->toIso8601String(),
            'dateModified'  => $post->updated_at->toIso8601String(),
            'url'           => route('blog.post', $post->slug),
            'image'         => $post->cover_image ? asset('storage/' . $post->cover_image) : null,
        ],
        $faqEntities ? [
            '@type'      => 'FAQPage',
            'mainEntity' => $faqEntities,
        ] : null,
        [
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',    'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Journal', 'item' => route('blog')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title, 'item' => route('blog.post', $post->slug)],
            ],
        ],
    ]),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

@section('seo')
  <x-seo
    :title="($post->meta_title ?: $post->title) . ' | Nails by Mona'"
    :description="$post->meta_description ?: $post->excerpt"
    :og-image="$post->og_image ? asset('storage/' . $post->og_image) : ($post->cover_image ? asset('storage/' . $post->cover_image) : null)"
    :schema="$blogPostSchema"
  />
@endsection

@section('content')

<!-- ARTICLE HEADER -->
<section class="bg-bone pt-10 pb-8 md:pt-12 md:pb-10">
  <div class="max-w-[740px] mx-auto px-6">

    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="flex items-center gap-2 mb-8 flex-wrap">
      <a href="{{ route('home') }}" class="font-sans text-caption text-stone hover:text-ink transition-colors duration-200">Home</a>
      <svg class="w-3 h-3 text-ash shrink-0" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="16" stroke-linecap="round"><polyline points="96 48 160 128 96 208"/></svg>
      <a href="{{ route('blog') }}" class="font-sans text-caption text-stone hover:text-ink transition-colors duration-200">Journal</a>
      <svg class="w-3 h-3 text-ash shrink-0" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="16" stroke-linecap="round"><polyline points="96 48 160 128 96 208"/></svg>
      <span class="font-sans text-caption text-lavender-ink">{{ $post->category->label() }}</span>
    </nav>

    <!-- Category + H1 -->
    <span class="inline-block bg-lavender-wash text-lavender-ink font-sans text-eyebrow uppercase rounded-full px-3 py-1 mb-5">{{ $post->category->label() }}</span>
    <h1 class="font-serif text-ink mb-6" style="font-size:clamp(1.75rem,4vw,2.6rem); font-weight:300; line-height:1.15; letter-spacing:-0.01em; font-variation-settings:'opsz' 144,'SOFT' 30">{{ $post->title }}</h1>

    <!-- Meta bar -->
    <div class="flex items-center gap-2 flex-wrap pb-8 border-b border-hairline">
      <span class="font-sans text-caption text-stone">{{ ($post->published_at ?? $post->created_at)->format('j M Y') }}</span>
      <span class="text-ash">&middot;</span>
      <span class="font-sans text-caption text-stone">{{ $readingMinutes }} min read</span>
      <span class="text-ash">&middot;</span>
      <span class="font-sans text-caption text-stone">by Mona</span>
    </div>

  </div>
</section>


<!-- COVER IMAGE -->
@if($post->cover_image)
<section class="bg-paper py-0">
  <div class="max-w-[900px] mx-auto px-6">
    <div class="rounded-2xl overflow-hidden aspect-[16/9] relative">
      <img src="{{ asset('storage/' . $post->cover_image) }}"
           alt="{{ $post->cover_image_alt ?? $post->title }}"
           class="w-full h-full object-cover" loading="eager">
    </div>
  </div>
</section>
@endif


<!-- ARTICLE BODY -->
<section class="bg-paper pt-10 pb-8 md:pt-14 md:pb-10">
  <div class="px-6">
    <div class="article-body">
      {!! $post->content !!}

      <!-- Share strip -->
      <div class="flex flex-wrap items-center gap-5 pt-8 mt-8 border-t border-hairline">
        <span class="font-sans text-caption text-stone">Share this article:</span>
        <a href="https://wa.me/?text={{ urlencode($post->title . ' — ' . route('blog.post', $post->slug)) }}"
           target="_blank" rel="noopener"
           class="font-sans text-caption text-stone hover:text-lavender-ink transition-colors duration-200">Share on WhatsApp</a>
        <button id="copy-link" class="font-sans text-caption text-stone hover:text-lavender-ink transition-colors duration-200 bg-transparent">
          <span id="copy-link-label">Copy link</span>
        </button>
      </div>
    </div>
  </div>
</section>


@if($faqs->isNotEmpty())
<!-- FAQ SECTION -->
<section class="bg-shell py-14 md:py-16">
  <div class="max-w-[740px] mx-auto px-6">

    <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Common questions</p>
    <h2 class="font-serif text-ink mb-3" style="font-size:clamp(1.5rem,3vw,2rem); font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30; line-height:1.2">Questions about this topic.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-8"></div>

    <div class="divide-y divide-hairline">
      @foreach($faqs as $faq)
      <div class="faq-item py-5">
        <button class="faq-toggle w-full flex items-start justify-between gap-4 text-left bg-transparent">
          <span class="font-sans font-medium text-ink" style="font-size:1rem; line-height:1.5">{{ $faq->question }}</span>
          <svg class="faq-icon w-4 h-4 text-stone shrink-0 mt-0.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round"><line x1="40" y1="128" x2="216" y2="128"/><line x1="128" y1="40" x2="128" y2="216"/></svg>
        </button>
        <div class="faq-answer pt-3 pb-1">
          <p class="font-sans text-body text-graphite leading-relaxed">{{ $faq->answer }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif


@if($post->products->isNotEmpty())
<!-- RELATED PRODUCTS -->
<section class="bg-paper pt-14 pb-16 md:pt-16 md:pb-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <p class="font-sans text-eyebrow text-lavender uppercase mb-4">Mentioned in this article</p>
    <h2 class="font-serif text-ink mb-3" style="font-size:clamp(1.5rem,3vw,2rem); font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30; line-height:1.2">Designs you might like.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>

    <div class="grid sm:grid-cols-3 gap-6">
      @foreach($post->products->take(3) as $product)
      <div class="bg-paper rounded-2xl border border-hairline/80 overflow-hidden related-card">
        <a href="{{ route('product', $product->slug) }}" class="block aspect-square img-wrap-fallback overflow-hidden relative" style="background: linear-gradient(135deg, #EAE3D9 0%, #FBF8F2 60%, #D9D2C6 100%)">
          @if($product->cover_image)
          <img src="{{ asset('storage/' . $product->cover_image) }}"
               alt="{{ $product->name }}"
               class="w-full h-full object-cover related-card-img" loading="lazy">
          @endif
          <span class="absolute top-3 left-3 inline-block bg-paper/90 text-graphite font-sans text-eyebrow uppercase rounded-full px-3 py-1">{{ $product->tier->label() }}</span>
        </a>
        <div class="p-5">
          <p class="font-serif text-ink mb-1 related-card-title transition-colors duration-200" style="font-size:1rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">{{ $product->name }}</p>
          <p class="font-sans text-caption text-lavender font-medium mb-4">From Rs. {{ number_format($product->price_pkr) }}</p>
          <button class="add-to-bag-btn w-full border border-ink text-ink hover:bg-ink hover:text-bone font-sans text-caption font-medium tracking-wide rounded-full py-2.5 transition-colors duration-200"
                  data-slug="{{ $product->slug }}"
                  data-name="{{ $product->name }}"
                  data-price="{{ $product->price_pkr }}">
            Add to bag
          </button>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif


<!-- AUTHOR BLOCK -->
<section class="bg-shell py-10 md:py-12 border-y border-hairline/50">
  <div class="max-w-[740px] mx-auto px-6">
    <div class="flex flex-col sm:flex-row items-start gap-6">
      <div class="w-14 h-14 rounded-full bg-lavender-wash border border-hairline/80 shrink-0 flex items-center justify-center">
        <span class="font-serif text-lavender-ink" style="font-size:1.25rem; font-variation-settings:'opsz' 144,'SOFT' 30; font-weight:300">M</span>
      </div>
      <div class="flex-1">
        <p class="font-sans font-semibold text-ink mb-0.5">Written by Mona</p>
        <p class="font-sans text-caption text-stone mb-3">Nails by Mona Studio, Mirpur, Azad Kashmir</p>
        <p class="font-sans text-body text-graphite leading-relaxed mb-5">I handmake every press-on gel nail set myself in my studio in Mirpur. If you have a question about anything in this post &mdash; or want a set made &mdash; get in touch on WhatsApp.</p>
        <a href="https://wa.me/{{ ltrim($settings->whatsapp_number, '+') }}?text={{ urlencode('Hello Nails by Mona, I read your article "' . $post->title . '" and have a question.') }}"
           target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 bg-lavender hover:bg-lavender-dark text-white font-sans text-caption font-medium tracking-wide rounded-full px-6 py-3 transition-colors duration-200">
          Get help on WhatsApp &rarr;
        </a>
      </div>
    </div>
  </div>
</section>


@if($relatedPosts->isNotEmpty())
<!-- RELATED POSTS -->
<section class="bg-paper pt-14 pb-16 md:pt-16 md:pb-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <p class="font-sans text-eyebrow text-lavender uppercase mb-4">More from the studio</p>
    <h2 class="font-serif text-ink mb-3" style="font-size:clamp(1.5rem,3vw,2rem); font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30; line-height:1.2">Keep reading.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>

    <div class="grid sm:grid-cols-3 gap-6">
      @foreach($relatedPosts as $related)
      @php $relMins = max(1, (int) ceil(str_word_count(strip_tags($related->content ?? '')) / 200)); @endphp
      <a href="{{ route('blog.post', $related->slug) }}" class="group block rounded-2xl overflow-hidden border border-hairline/80 hover:shadow-card transition-shadow duration-300">
        <div class="aspect-[16/9] img-wrap-fallback overflow-hidden" style="background: linear-gradient(135deg, #EAE3D9 0%, #FBF8F2 60%, #D9D2C6 100%)">
          @if($related->cover_image)
          <img src="{{ asset('storage/' . $related->cover_image) }}"
               alt="{{ $related->cover_image_alt ?? $related->title }}"
               class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-[1.03]" loading="lazy">
          @endif
        </div>
        <div class="bg-paper p-5">
          <span class="inline-block bg-lavender-wash text-lavender-ink font-sans text-eyebrow uppercase rounded-full px-3 py-1 mb-3">{{ $related->category->label() }}</span>
          <h3 class="font-serif text-ink mb-2 group-hover:text-lavender-ink transition-colors duration-200" style="font-size:1.1rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30; line-height:1.3">{{ $related->title }}</h3>
          <p class="font-sans text-caption text-stone">{{ $relMins }} min read &mdash; by Mona</p>
        </div>
      </a>
      @endforeach
    </div>

    <div class="mt-10 text-center">
      <a href="{{ route('blog') }}" class="inline-flex items-center gap-2 font-sans text-caption font-medium text-stone hover:text-ink transition-colors duration-200">
        <svg class="w-3.5 h-3.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="18" stroke-linecap="round" stroke-linejoin="round"><line x1="216" y1="128" x2="40" y2="128"/><polyline points="112 56 40 128 112 200"/></svg>
        Back to the Journal
      </a>
    </div>
  </div>
</section>
@endif

@endsection

@push('scripts')
<script>
$(function(){
  // Add to bag
  $('.add-to-bag-btn').on('click', function() {
    const $btn = $(this);
    const slug = $btn.data('slug');
    const name = $btn.data('name');
    const price_pkr = parseInt($btn.data('price'));
    const items = window.NbmBag.get();
    const existing = items.find(i => i.slug === slug);
    if (existing) { existing.qty++; } else { items.push({ slug, name, price_pkr, qty: 1 }); }
    window.NbmBag.save(items);
    window.NbmBag.open();
  });

  // FAQ accordion
  $('.faq-toggle').on('click', function() {
    const $item = $(this).closest('.faq-item');
    const isOpen = $item.hasClass('open');
    $('.faq-item').removeClass('open');
    if (!isOpen) $item.addClass('open');
  });

  // Copy link
  $('#copy-link').on('click', function() {
    if (navigator.clipboard) {
      navigator.clipboard.writeText(window.location.href).then(() => {
        $('#copy-link-label').text('Copied!');
        setTimeout(() => $('#copy-link-label').text('Copy link'), 2000);
      });
    }
  });
});
</script>
@endpush
