<?php

if (! function_exists('format_pkr')) {
    function format_pkr(int $amount): string
    {
        return 'Rs. ' . number_format($amount);
    }
}

if (! function_exists('img_variant')) {
    /**
     * URL for a public-disk image at card size: the WebP variant made by
     * App\Support\ImageOptimizer when it exists, otherwise the original.
     * Pair with img_srcset() so phones get the 600 px file.
     */
    function img_variant(?string $path, int $width = \App\Support\ImageOptimizer::VARIANT_WIDTH): string
    {
        if (! $path) {
            return '';
        }

        $variant = \App\Support\ImageOptimizer::variantPath($path, $width);

        return \Illuminate\Support\Facades\Storage::disk('public')->exists($variant)
            ? asset('storage/' . $variant)
            : asset('storage/' . $path);
    }
}

if (! function_exists('img_srcset')) {
    /**
     * srcset listing every WebP variant that exists ("…-600.webp 600w,
     * …-1080.webp 1080w"). Empty string when none exist, so the <img> falls
     * back to its src.
     */
    function img_srcset(?string $path): string
    {
        if (! $path) {
            return '';
        }

        $disk = \Illuminate\Support\Facades\Storage::disk('public');

        return collect(array_keys(\App\Support\ImageOptimizer::VARIANTS))
            ->map(fn ($w) => [$w, \App\Support\ImageOptimizer::variantPath($path, $w)])
            ->filter(fn ($v) => $disk->exists($v[1]))
            ->map(fn ($v) => asset('storage/' . $v[1]) . ' ' . $v[0] . 'w')
            ->implode(', ');
    }
}
