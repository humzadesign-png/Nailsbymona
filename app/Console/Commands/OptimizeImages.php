<?php

namespace App\Console\Commands;

use App\Support\ImageOptimizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--force : Redo images that already have a variant}';

    protected $description = 'Shrink public-disk images to 2400px and create 1080px WebP grid variants';

    private const DIRS = ['products', 'ugc', 'blog', 'custom-designs'];

    public function handle(): int
    {
        $disk = Storage::disk('public');
        $before = 0;
        $after  = 0;
        $done   = 0;

        foreach (self::DIRS as $dir) {
            foreach ($disk->allFiles($dir) as $path) {
                // Skip the variants themselves.
                if (Str::endsWith($path, '-' . ImageOptimizer::VARIANT_WIDTH . '.webp')) {
                    continue;
                }

                $size = $disk->size($path);
                if (ImageOptimizer::optimize($path, (bool) $this->option('force'))) {
                    $before += $size;
                    $after  += $disk->size($path);
                    $done++;
                    $this->line(sprintf('  %s  %s → %s', $path, $this->mb($size), $this->mb($disk->size($path))));
                }
            }
        }

        $this->info(sprintf('Optimized %d images: %s → %s', $done, $this->mb($before), $this->mb($after)));

        return self::SUCCESS;
    }

    private function mb(int $bytes): string
    {
        return round($bytes / 1048576, 2) . ' MB';
    }
}
