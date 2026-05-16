@extends('layouts.app')

@push('head')
<style>
.filter-tab.active-tab { color: #BFA4CE; border-bottom-color: #BFA4CE; }
.post-card { transition: opacity 0.2s; }
.post-card.hidden { display: none; }
</style>
@endpush

@php
    $blogSchema = json_encode([
        '@context' => 'https://schema.org',
        '@graph'   => [
            [
                '@type'     => 'Blog',
                'name'      => 'The Journal — Nails by Mona',
                'url'       => route('blog'),
                'publisher' => ['@type' => 'Organization', 'name' => 'Nails by Mona'],
            ],
            [
                '@type'           => 'BreadcrumbList',
                'itemListElement' => [
                    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home',    'item' => route('home')],
                    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Journal', 'item' => route('blog')],
                ],
            ],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
@endphp

@section('seo')
<x-seo
    title="Journal — Nail Care, Bridal Guides & Press-On Tutorials | Nails by Mona"
    description="Everything I know about nails, bridal prep, and press-on care — written for real people, not search engines. New posts 2–3 times a month."
    :schema="$blogSchema"
/>
<link rel="alternate" type="application/rss+xml" title="The Journal — Nails by Mona" href="{{ route('feed') }}">
@endsection

@section('content')

<!-- HERO -->
<section class="bg-shell pt-16 pb-14 md:pt-20 md:pb-18">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <p class="font-sans text-eyebrow text-lavender uppercase mb-5">The Journal</p>
    <h1 class="font-serif text-display-lg text-ink mb-5 max-w-[18ch]" style="font-variation-settings:'opsz' 144,'SOFT' 30">From the studio.</h1>
    <p class="font-sans text-body-lg text-graphite max-w-[48ch]">Everything I know about nails, bridal prep, and press-on care &mdash; written for real people, not search engines.</p>
  </div>
</section>

<!-- FILTER TABS -->
<div id="filter-bar" class="sticky top-[72px] z-40 bg-paper border-b border-hairline overflow-x-auto">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="flex items-center gap-0 whitespace-nowrap">
      <button class="filter-tab active-tab font-sans text-caption font-semibold text-lavender border-b-2 border-lavender py-4 px-4 mr-1 transition-colors duration-150 bg-transparent" data-filter="all">All</button>
      <button class="filter-tab font-sans text-caption font-medium text-stone hover:text-ink py-4 px-4 border-b-2 border-transparent transition-colors duration-150 bg-transparent" data-filter="bridal">Bridal</button>
      <button class="filter-tab font-sans text-caption font-medium text-stone hover:text-ink py-4 px-4 border-b-2 border-transparent transition-colors duration-150 bg-transparent" data-filter="tutorials">Tutorials</button>
      <button class="filter-tab font-sans text-caption font-medium text-stone hover:text-ink py-4 px-4 border-b-2 border-transparent transition-colors duration-150 bg-transparent" data-filter="trends">Trends</button>
      <button class="filter-tab font-sans text-caption font-medium text-stone hover:text-ink py-4 px-4 border-b-2 border-transparent transition-colors duration-150 bg-transparent" data-filter="care">Care</button>
      <button class="filter-tab font-sans text-caption font-medium text-stone hover:text-ink py-4 px-4 border-b-2 border-transparent transition-colors duration-150 bg-transparent" data-filter="behind_scenes">Behind the Scenes</button>
    </div>
  </div>
</div>


@if($featured)
<!-- FEATURED POST -->
<section class="bg-paper pt-12 md:pt-16 pb-0">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <p class="font-sans text-eyebrow text-lavender uppercase mb-6">Featured</p>
    <a href="{{ route('blog.post', $featured->slug) }}" class="group block rounded-2xl border border-hairline/80 overflow-hidden hover:shadow-card-hover transition-shadow duration-300">
      <div class="flex flex-col md:flex-row">
        <!-- Cover image -->
        <div class="md:w-[55%] shrink-0 aspect-[16/9] md:aspect-auto md:min-h-[380px] img-wrap-fallback relative overflow-hidden" style="background: linear-gradient(135deg, #D4C5E2 0%, #EAE3D9 40%, #F2ECF8 80%, #C8B4D8 100%)">
          @if($featured->cover_image)
          <img src="{{ asset('storage/' . $featured->cover_image) }}"
               alt="{{ $featured->cover_image_alt ?? $featured->title }}"
               class="w-full h-full object-cover"
               loading="eager" fetchpriority="high">
          @else
          <div class="absolute inset-0 flex items-center justify-center opacity-20">
            <svg class="w-24 h-24" viewBox="0 0 24 24" fill="none" stroke="#BFA4CE" stroke-width="1"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
          </div>
          @endif
          <div class="absolute bottom-4 left-4">
            <span class="inline-block bg-lavender-wash text-lavender-ink font-sans text-eyebrow uppercase rounded-full px-3 py-1">{{ $featured->category->label() }}</span>
          </div>
        </div>
        <!-- Post info -->
        <div class="md:w-[45%] p-8 md:p-10 flex flex-col justify-center">
          <h2 class="font-serif text-display text-ink mb-4 group-hover:text-lavender-ink transition-colors duration-200" style="font-variation-settings:'opsz' 144,'SOFT' 30; font-size:clamp(1.5rem,2.5vw,2rem); line-height:1.1">{{ $featured->title }}</h2>
          @if($featured->excerpt)
          <p class="font-sans text-body text-graphite mb-6 leading-relaxed">{{ $featured->excerpt }}</p>
          @endif
          <div class="flex items-center gap-2 mb-7 flex-wrap">
            <span class="font-sans text-caption text-stone">{{ ($featured->published_at ?? $featured->created_at)->format('j M Y') }}</span>
            <span class="text-ash">·</span>
            <span class="font-sans text-caption text-stone">{{ max(1, (int) ceil(str_word_count(strip_tags($featured->content ?? '')) / 200)) }} min read</span>
            <span class="text-ash">·</span>
            <span class="font-sans text-caption text-stone">by Mona</span>
          </div>
          <span class="inline-flex items-center gap-2 bg-lavender text-white font-sans text-caption font-medium tracking-wide rounded-full px-6 py-3 transition-colors duration-200 group-hover:bg-lavender-dark self-start">
            Read article
            <svg class="w-3.5 h-3.5" viewBox="0 0 256 256" fill="none" stroke="currentColor" stroke-width="20" stroke-linecap="round" stroke-linejoin="round"><line x1="40" y1="128" x2="216" y2="128"/><polyline points="144 56 216 128 144 200"/></svg>
          </span>
        </div>
      </div>
    </a>
  </div>
</section>
@endif


<!-- POST GRID -->
<section class="bg-paper pt-12 pb-16 md:pt-14 md:pb-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div id="post-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">

      @forelse($gridPosts as $post)
      @php $mins = max(1, (int) ceil(str_word_count(strip_tags($post->content ?? '')) / 200)); @endphp
      <article class="post-card" data-category="{{ $post->category->value }}">
        <a href="{{ route('blog.post', $post->slug) }}" class="post-card-link group block rounded-2xl overflow-hidden bg-paper border border-hairline/80 hover:shadow-card transition-shadow duration-300 h-full flex flex-col">
          <div class="aspect-[16/9] img-wrap-fallback overflow-hidden relative shrink-0" style="background: linear-gradient(135deg, #EAE3D9 0%, #FBF8F2 60%, #D4C8BE 100%)">
            @if($post->cover_image)
            <img src="{{ asset('storage/' . $post->cover_image) }}"
                 alt="{{ $post->cover_image_alt ?? $post->title }}"
                 class="w-full h-full object-cover post-card-img" loading="lazy">
            @endif
          </div>
          <div class="p-6 flex flex-col flex-1">
            <span class="inline-block bg-lavender-wash text-lavender-ink font-sans text-eyebrow uppercase rounded-full px-3 py-1 mb-4 self-start">{{ $post->category->label() }}</span>
            <h3 class="font-serif text-ink mb-3 post-card-title transition-colors duration-200 flex-1" style="font-size:1.2rem; font-weight:300; line-height:1.3; font-variation-settings:'opsz' 144,'SOFT' 30">{{ $post->title }}</h3>
            @if($post->excerpt)
            <p class="font-sans text-caption text-stone mb-4 leading-relaxed">{{ Str::limit($post->excerpt, 120) }}</p>
            @endif
            <div class="flex items-center gap-2 flex-wrap mt-auto">
              <span class="font-sans" style="font-size:0.75rem; color:#B5A99C">{{ ($post->published_at ?? $post->created_at)->format('j M Y') }}</span>
              <span style="color:#B5A99C">·</span>
              <span class="font-sans" style="font-size:0.75rem; color:#B5A99C">{{ $mins }} min read</span>
              <span style="color:#B5A99C">·</span>
              <span class="font-sans" style="font-size:0.75rem; color:#B5A99C">by Mona</span>
            </div>
          </div>
        </a>
      </article>
      @empty
      @endforelse

    </div>

    <!-- Empty state (shown when filter has no matches, or no posts at all) -->
    @if($gridPosts->isEmpty() && !$featured)
    <div class="text-center py-20">
      <p class="font-sans text-body text-stone mb-2">New articles coming soon.</p>
      <p class="font-sans text-caption text-stone">Check back in a few days &mdash; Mona writes 2&ndash;3 times a month.</p>
    </div>
    @endif
    <div id="no-posts" class="hidden text-center py-16">
      <p class="font-sans text-body text-stone mb-2">No articles in this category yet.</p>
      <button class="filter-tab font-sans text-caption font-medium text-lavender hover:text-lavender-dark transition-colors duration-200 bg-transparent" data-filter="all">View all articles &rarr;</button>
    </div>

  </div>
</section>


<!-- SUBSCRIBE STRIP -->
<section class="bg-shell py-16 md:py-20 border-y border-hairline/50">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <div class="flex flex-col md:flex-row md:items-center gap-10 md:gap-16">
      <!-- Text -->
      <div class="md:w-1/2">
        <p class="font-sans text-eyebrow text-lavender uppercase mb-4">The Studio Letter</p>
        <h2 class="font-serif text-display text-ink mb-3" style="font-variation-settings:'opsz' 144,'SOFT' 30; font-size:clamp(1.75rem,3vw,2.5rem); line-height:1.1">Get new posts before they go on Instagram.</h2>
        <div class="h-0.5 w-10 bg-lavender mb-5"></div>
        <p class="font-sans text-body text-graphite leading-relaxed">I write 2&ndash;3 times a month &mdash; bridal guides, nail care tips, and whatever I'm obsessing over in the studio. No spam, ever. Unsubscribe whenever you want.</p>
      </div>
      <!-- Form -->
      <div class="md:w-1/2">
        @if(session('subscribed'))
        <div class="bg-paper border border-hairline/80 rounded-2xl px-6 py-5">
          <p class="font-sans text-body text-graphite">You&rsquo;re in. I&rsquo;ll be in touch soon. &mdash; Mona</p>
        </div>
        @else
        <form id="subscribe-form" action="{{ route('subscribe') }}" method="POST" novalidate>
          @csrf
          <div class="flex flex-col sm:flex-row gap-3">
            <input id="subscribe-email" name="email" type="email" placeholder="your@email.com"
                   class="flex-1 font-sans text-body bg-paper border border-hairline rounded-full px-5 py-3.5 text-ink placeholder:text-ash focus:outline-none focus:ring-2 focus:ring-lavender/40 focus:border-lavender transition-colors duration-200"
                   required>
            <button type="submit" class="shrink-0 bg-lavender hover:bg-lavender-dark text-white font-sans text-caption font-medium tracking-wide rounded-full px-7 py-3.5 transition-colors duration-200">Subscribe</button>
          </div>
          <p class="font-sans text-caption text-stone mt-3">No account needed. Just your email.</p>
        </form>
        <div id="subscribe-success" class="hidden bg-paper border border-hairline/80 rounded-2xl px-6 py-5">
          <p class="font-sans text-body text-graphite">You&rsquo;re in. I&rsquo;ll be in touch soon. &mdash; Mona</p>
        </div>
        @endif
      </div>
    </div>
  </div>
</section>


<!-- NAVIGATION TILES -->
<section class="bg-paper py-16 md:py-20">
  <div class="max-w-7xl mx-auto px-6 lg:px-10">
    <p class="font-sans text-eyebrow text-lavender uppercase mb-5">Browse by topic</p>
    <h2 class="font-serif text-display text-ink mb-3" style="font-variation-settings:'opsz' 144,'SOFT' 30; font-size:clamp(1.75rem,3vw,2.25rem); line-height:1.1">More ways to find what you need.</h2>
    <div class="h-0.5 w-10 bg-lavender mb-10"></div>
    <div class="grid sm:grid-cols-3 gap-5">

      <button class="filter-trigger text-left bg-shell hover:shadow-card rounded-2xl p-8 border border-hairline/60 transition-shadow duration-300 group" data-filter="bridal">
        <div class="w-11 h-11 rounded-xl bg-lavender-wash flex items-center justify-center mb-5">
          <svg class="w-5 h-5 text-lavender-ink" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <h3 class="font-serif text-ink mb-2 group-hover:text-lavender-ink transition-colors duration-200" style="font-size:1.125rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">Bridal guide</h3>
        <p class="font-sans text-caption text-stone">Planning a wedding? Start here. Everything from nail trends to the Bridal Trio timeline.</p>
      </button>

      <button class="filter-trigger text-left bg-shell hover:shadow-card rounded-2xl p-8 border border-hairline/60 transition-shadow duration-300 group" data-filter="tutorials">
        <div class="w-11 h-11 rounded-xl bg-lavender-wash flex items-center justify-center mb-5">
          <svg class="w-5 h-5 text-lavender-ink" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <h3 class="font-serif text-ink mb-2 group-hover:text-lavender-ink transition-colors duration-200" style="font-size:1.125rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">Application &amp; care</h3>
        <p class="font-sans text-caption text-stone">How to apply, remove, and reuse. Make every set last as long as possible.</p>
      </button>

      <button class="filter-trigger text-left bg-shell hover:shadow-card rounded-2xl p-8 border border-hairline/60 transition-shadow duration-300 group" data-filter="care">
        <div class="w-11 h-11 rounded-xl bg-lavender-wash flex items-center justify-center mb-5">
          <svg class="w-5 h-5 text-lavender-ink" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
        <h3 class="font-serif text-ink mb-2 group-hover:text-lavender-ink transition-colors duration-200" style="font-size:1.125rem; font-weight:300; font-variation-settings:'opsz' 144,'SOFT' 30">Nail care &amp; recovery</h3>
        <p class="font-sans text-caption text-stone">Keeping your natural nails healthy between sets. Post-removal recovery tips.</p>
      </button>

    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
$(function() {
  // Category filter tabs
  function activateFilter(filter) {
    $('.filter-tab, .filter-trigger').each(function() {
      const $el = $(this);
      const isActive = $el.data('filter') === filter;
      if ($el.hasClass('filter-tab')) {
        $el.toggleClass('active-tab', isActive)
           .toggleClass('text-lavender font-semibold border-lavender', isActive)
           .toggleClass('text-stone font-medium border-transparent', !isActive);
      }
    });

    let visible = 0;
    $('.post-card').each(function() {
      const cat = $(this).data('category');
      const show = filter === 'all' || cat === filter;
      $(this).toggleClass('hidden', !show);
      if (show) visible++;
    });
    $('#no-posts').toggleClass('hidden', visible > 0);
  }

  $('.filter-tab').on('click', function() { activateFilter($(this).data('filter')); });

  $('.filter-trigger').on('click', function() {
    const filter = $(this).data('filter');
    activateFilter(filter);
    const $bar = $('#filter-bar');
    if ($bar.length) $('html, body').animate({ scrollTop: $bar.offset().top - 80 }, 300);
  });

  // Subscribe form AJAX
  $('#subscribe-form').on('submit', function(e) {
    e.preventDefault();
    const email = $('#subscribe-email').val().trim();
    if (!email) return;
    $.post('{{ route('subscribe') }}', { email: email, _token: '{{ csrf_token() }}' })
      .done(function() {
        $('#subscribe-form').hide();
        $('#subscribe-success').removeClass('hidden');
      })
      .fail(function() {
        $('#subscribe-form').hide();
        $('#subscribe-success').removeClass('hidden');
      });
  });
});
</script>
@endpush
