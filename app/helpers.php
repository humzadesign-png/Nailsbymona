<?php

if (! function_exists('format_pkr')) {
    function format_pkr(int $amount): string
    {
        return 'Rs. ' . number_format($amount);
    }
}

if (! function_exists('img_variant')) {
    /**
     * URL for a public-disk image at grid/card size: the 640px WebP made by
     * App\Support\ImageOptimizer when it exists, otherwise the original.
     */
    function img_variant(?string $path): string
    {
        if (! $path) {
            return '';
        }

        $variant = \App\Support\ImageOptimizer::variantPath($path);

        return \Illuminate\Support\Facades\Storage::disk('public')->exists($variant)
            ? asset('storage/' . $variant)
            : asset('storage/' . $path);
    }
}
