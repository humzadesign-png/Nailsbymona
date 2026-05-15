@props([
    'title'       => 'Nails by Mona — Custom-Fit Press-On Gel Nails, Pakistan',
    'description' => 'Handmade, custom-fit press-on gel nails. Live-camera sizing. Wudu-friendly. Reusable 3–5×. Shipped across Pakistan from Mirpur.',
    'ogImage'     => null,
    'canonical'   => null,
    'noindex'     => false,
    'schema'      => null,
])

@php
    $canonical = $canonical ?? request()->url();
    $ogImage   = $ogImage   ?? asset('og-default.jpg');

    // Build Organization schema as PHP — avoids Blade parsing @context / @type as directives
    $orgSchema = json_encode([
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => 'Nails by Mona',
        'url'      => config('app.url'),
        'logo'     => asset('logo-text.svg'),
        'sameAs'   => [
            'https://instagram.com/' . ($settings->instagram_handle ?? 'nailsbymona'),
            'https://tiktok.com/@' . ($settings->tiktok_handle ?? 'nailsbymona'),
        ],
        'contactPoint' => [
            '@type'             => 'ContactPoint',
            'contactType'       => 'customer service',
            'availableLanguage' => 'English',
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
@endphp

<title>{{ $title }}</title>

@if ($noindex)
    <meta name="robots" content="noindex, nofollow">
@else
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ $canonical }}">
@endif

<meta name="description" content="{{ $description }}">

{{-- Open Graph --}}
<meta property="og:title"       content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:type"        content="website">
<meta property="og:url"         content="{{ $canonical }}">
<meta property="og:image"       content="{{ $ogImage }}">
<meta property="og:locale"      content="en_PK">
<meta property="og:site_name"   content="Nails by Mona">

{{-- Twitter / X --}}
<meta name="twitter:card"        content="summary_large_image">
<meta name="twitter:title"       content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image"       content="{{ $ogImage }}">

{{-- hreflang --}}
<link rel="alternate" hreflang="en-PK" href="{{ $canonical }}">

{{-- Page-specific JSON-LD (passed via :schema prop) --}}
@if ($schema)
    <script type="application/ld+json">{!! $schema !!}</script>
@endif

{{-- Organization schema — on every page --}}
<script type="application/ld+json">{!! $orgSchema !!}</script>
