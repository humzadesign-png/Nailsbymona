<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Intervention\Image\Typography\FontFactory;

/**
 * Generate the default Open Graph share image at public/og-default.jpg.
 *
 *   php artisan og:generate
 *
 * The output is a 1200x630 brand card: cream background, lavender wordmark,
 * tagline beneath. Used as the OG image whenever a page doesn't supply its
 * own (i.e. everything except blog posts that set a cover image).
 */
class GenerateOgImage extends Command
{
    protected $signature   = 'og:generate {--out=public/og-default.jpg}';
    protected $description = 'Generate the default 1200x630 Open Graph share image.';

    public function handle(): int
    {
        $out  = base_path($this->option('out'));
        $w    = 1200;
        $h    = 630;

        // Brand tokens (from CLAUDE.md §10 design system)
        $bone      = '#F4EFE8';
        $lavender  = '#bfa4ce';
        $graphite  = '#3D3540';

        // Find a usable TTF — prefer bundled brand fonts, fall back to system DejaVu.
        $serif = $this->firstExisting([
            public_path('fonts/og-serif.ttf'),
            '/usr/share/fonts/truetype/dejavu/DejaVuSerif-Bold.ttf',
            '/Library/Fonts/Georgia.ttf',
            '/System/Library/Fonts/Supplemental/Georgia.ttf',
        ]);
        $sans = $this->firstExisting([
            public_path('fonts/og-sans.ttf'),
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/Library/Fonts/Arial.ttf',
            '/System/Library/Fonts/Supplemental/Arial.ttf',
        ]);

        if (! $serif || ! $sans) {
            $this->error('No TTF fonts found. Install fonts-dejavu (apt) or bundle public/fonts/og-serif.ttf + og-sans.ttf.');
            return self::FAILURE;
        }

        $manager = new ImageManager(new Driver());
        $img     = $manager->create($w, $h)->fill($bone);

        // Thin lavender accent rule (matches §10 "h-0.5 w-10 bg-lavender" pattern).
        $img->drawRectangle(540, 200, function ($r) use ($lavender) {
            $r->size(120, 4);
            $r->background($lavender);
        });

        $img->text('Nails by Mona', $w / 2, $h / 2 + 10, function (FontFactory $f) use ($lavender, $serif) {
            $f->filename($serif);
            $f->size(120);
            $f->color($lavender);
            $f->align('center');
            $f->valign('middle');
        });

        $img->text('Custom-fit press-on gel nails · Handmade in Mirpur', $w / 2, $h / 2 + 130, function (FontFactory $f) use ($graphite, $sans) {
            $f->filename($sans);
            $f->size(36);
            $f->color($graphite);
            $f->align('center');
            $f->valign('middle');
        });

        $img->text(parse_url(config('app.url'), PHP_URL_HOST) ?? 'nailsbymona.pk', $w / 2, $h - 70, function (FontFactory $f) use ($graphite, $sans) {
            $f->filename($sans);
            $f->size(26);
            $f->color($graphite);
            $f->align('center');
            $f->valign('middle');
        });

        @mkdir(dirname($out), 0755, true);
        $img->toJpeg(88)->save($out);

        $this->info("OG image written: {$out} ({$w}×{$h})");
        return self::SUCCESS;
    }

    private function firstExisting(array $paths): ?string
    {
        foreach ($paths as $p) {
            if (is_file($p)) {
                return $p;
            }
        }
        return null;
    }
}
