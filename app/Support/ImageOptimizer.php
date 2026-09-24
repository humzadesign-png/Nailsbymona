<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Keeps public-disk images (products, UGC, blog, custom-design references)
 * a sensible size. Admin uploads used to be stored straight off the camera —
 * up to 7 MB each — which made /shop a 20 MB page and froze cheap phones
 * while they decoded it.
 *
 * For each image it:
 *   1. scales the original down to MAX_EDGE (2400) px on its longest side and
 *      re-encodes it — only when it is oversized (or over 1.5 MB), so running
 *      it again never re-compresses an already-processed original
 *   2. writes WebP variants next to it — `name-600.webp` and `name-1080.webp`
 *      — for grids/cards. Pages offer both via img_srcset() so phones take
 *      600 px and desktops/large screens 1080 px (2026-09-24: 1080 for every
 *      device made /shop ~4 MB on phones).
 */
class ImageOptimizer
{
    // Generous on purpose: these are the product/brand photos customers
    // judge the work by. 1600px / q82 looked soft (2026-09-24).
    public const MAX_EDGE = 2400;
    public const VARIANT_WIDTH = 1080;

    /** Grid/card variants: width => WebP quality. */
    public const VARIANTS = [600 => 85, 1080 => 88];

    private const RECOMPRESS_ABOVE_BYTES = 1_500_000;

    /** Public-disk path of the grid-size variant for $path. */
    public static function variantPath(string $path, int $width = self::VARIANT_WIDTH): string
    {
        $info = pathinfo($path);
        $dir  = ($info['dirname'] ?? '.') === '.' ? '' : $info['dirname'] . '/';

        return $dir . $info['filename'] . '-' . $width . '.webp';
    }

    /**
     * Optimize one public-disk image. Safe to call repeatedly: it only
     * creates variants that are missing (all of them with $force).
     */
    public static function optimize(?string $path, bool $force = false): bool
    {
        if (! $path) {
            return false;
        }

        $disk = Storage::disk('public');
        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) || ! $disk->exists($path)) {
            return false;
        }

        $missing = array_filter(
            self::VARIANTS,
            fn ($quality, $width) => $force || ! $disk->exists(self::variantPath($path, $width)),
            ARRAY_FILTER_USE_BOTH,
        );
        if ($missing === []) {
            return false;
        }

        // Camera originals can be 24+ MP; GD needs ~4 bytes per pixel.
        @ini_set('memory_limit', '512M');

        try {
            $full    = $disk->path($path);
            $manager = new ImageManager(new Driver());
            $image   = $manager->read($full); // auto-orients from EXIF (Intervention default)

            $oversized = $image->width() > self::MAX_EDGE || $image->height() > self::MAX_EDGE;

            if ($oversized || filesize($full) > self::RECOMPRESS_ABOVE_BYTES) {
                $image->scaleDown(self::MAX_EDGE, self::MAX_EDGE);

                $encoded = match ($ext) {
                    'png'   => $image->toPng(),
                    'webp'  => $image->toWebp(90),
                    default => $image->toJpeg(90),
                };

                // Only replace the original if we actually made it smaller.
                if (strlen((string) $encoded) < filesize($full)) {
                    file_put_contents($full, (string) $encoded);
                }
            }

            foreach ($missing as $width => $quality) {
                $manager->read($full)
                    ->scaleDown($width)
                    ->toWebp($quality)
                    ->save($disk->path(self::variantPath($path, $width)));
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Image optimize failed', ['path' => $path, 'error' => $e->getMessage()]);

            return false;
        }
    }
}
