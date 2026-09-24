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
 *   1. scales the original down to MAX_EDGE px on its longest side and
 *      re-encodes it (same filename, so nothing in the DB changes; EXIF is
 *      dropped by the re-encode)
 *   2. writes a small WebP next to it — `name-640.webp` — for grids/cards,
 *      served through img_variant()
 */
class ImageOptimizer
{
    public const MAX_EDGE = 1600;
    public const VARIANT_WIDTH = 640;

    /** Public-disk path of the grid-size variant for $path. */
    public static function variantPath(string $path, int $width = self::VARIANT_WIDTH): string
    {
        $info = pathinfo($path);
        $dir  = ($info['dirname'] ?? '.') === '.' ? '' : $info['dirname'] . '/';

        return $dir . $info['filename'] . '-' . $width . '.webp';
    }

    /**
     * Optimize one public-disk image. Safe to call repeatedly: it returns
     * early once the variant exists (unless $force).
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

        $variant = self::variantPath($path);
        if (! $force && $disk->exists($variant)) {
            return false;
        }

        // Camera originals can be 24+ MP; GD needs ~4 bytes per pixel.
        @ini_set('memory_limit', '512M');

        try {
            $full    = $disk->path($path);
            $manager = new ImageManager(new Driver());
            $image   = $manager->read($full); // auto-orients from EXIF (Intervention default)

            if ($image->width() > self::MAX_EDGE || $image->height() > self::MAX_EDGE) {
                $image->scaleDown(self::MAX_EDGE, self::MAX_EDGE);
            }

            $encoded = match ($ext) {
                'png'   => $image->toPng(),
                'webp'  => $image->toWebp(82),
                default => $image->toJpeg(82),
            };

            // Only replace the original if we actually made it smaller.
            if (strlen((string) $encoded) < filesize($full)) {
                file_put_contents($full, (string) $encoded);
            }

            $manager->read($full)
                ->scaleDown(self::VARIANT_WIDTH)
                ->toWebp(78)
                ->save($disk->path($variant));

            return true;
        } catch (\Throwable $e) {
            Log::warning('Image optimize failed', ['path' => $path, 'error' => $e->getMessage()]);

            return false;
        }
    }
}
