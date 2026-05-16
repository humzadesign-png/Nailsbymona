@props([
    'title'       => 'Nails by Mona — Custom-Fit Press-On Gel Nails, Pakistan',
    'description' => 'Handmade, custom-fit press-on gel nails. Live-camera sizing. Wudu-friendly. Reusable 3–5×. Shipped across Pakistan from Mirpur.',
    'ogImage'     => null,
    'ogType'      => 'website',
    'canonical'   => null,
    'noindex'     => false,
    'schema'      => null,
])

@php
    $canonical = $canonical ?? request()->url();
    $ogImage   = $ogImage   ?? asset('og-default.jpg');

    // Organization payload — used by every page.
    $orgPayload = [
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
    ];

    // If the page passed its own @graph schema, merge Organization into it
    // rather than emitting a second standalone block. Otherwise we render
    // a single Organization-only block below.
    $combinedSchema = null;
    if ($schema) {
        $decoded = json_decode($schema, true);
        if (is_array($decoded) && isset($decoded['@graph']) && is_array($decoded['@graph'])) {
            array_unshift($decoded['@graph'], $orgPayload);
            $combinedSchema = json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }

    $orgOnlySchema = json_encode(
        ['@context' => 'https://schema.org'] + $orgPayload,
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
    );
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
<meta property="og:type"        content="{{ $ogType }}">
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

{{-- JSON-LD: combined (Organization + page schema) OR Organization alone --}}
@if ($combinedSchema)
    <script type="application/ld+json">{!! $combinedSchema !!}</script>
@elseif ($schema)
    {{-- Page passed a non-@graph schema (single type). Emit it AND Organization separately. --}}
    <script type="application/ld+json">{!! $schema !!}</script>
    <script type="application/ld+json">{!! $orgOnlySchema !!}</script>
@else
    <script type="application/ld+json">{!! $orgOnlySchema !!}</script>
@endif
