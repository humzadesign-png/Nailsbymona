<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

$staged = __DIR__ . '/../storage/app/public/products/staged/';
$dest   = __DIR__ . '/../storage/app/public/products/';

function movePhoto(string $staged, string $dest, string $file): string {
    $newName = Str::ulid() . '.jpeg';
    rename($staged . $file, $dest . $newName);
    return 'products/' . $newName;
}

function attachImages(Product $p, array $files, string $staged, string $dest): void {
    ProductImage::where('product_id', $p->id)->delete();
    foreach (array_values($files) as $i => $f) {
        if (!file_exists($staged . $f)) { echo "  SKIP: $f\n"; continue; }
        $path = movePhoto($staged, $dest, $f);
        ProductImage::create(['product_id' => $p->id, 'path' => $path, 'alt' => $p->name, 'sort_order' => $i]);
        if ($i === 0) $p->update(['cover_image' => $path]);
        echo "  $f → $path\n";
    }
}

// ── Update existing 9 products ──────────────────────────────────────────────
$updates = [
    '01krm46mybwc7eeya6m4ve55e4' => ['V1.jpeg','V2.jpeg','V3.jpeg'],  // Barely There Nude
    '01krm46mynd9ycbtv1sx1az5ew' => ['G1.jpeg','G2.jpeg','G3.jpeg'],  // Dusty Rose Ombre
    '01krm46mz0v510kx2jc7gfs8kg' => ['I1.jpeg','I2.jpeg','I3.jpeg'],  // French + Gold Tips
    '01krm46mzeqr7e8yprrztsbg38' => ['W1.jpeg','W2.jpeg','W3.jpeg'],  // Berry Chrome
    '01krm46mzq4aj9vr7r8wr9dd99' => ['Y1.jpeg','Y2.jpeg','Y3.jpeg'],  // Midnight Glitter Cloud
    '01krm46n01wzrz558qzmvyev7w' => ['T1.jpeg','T2.jpeg','T3.jpeg'],  // Red Rose 3D
    '01krm46n0bkgbd6wvf4fz0g33t' => ['A1.jpeg','A2.jpeg','A3.jpeg'],  // Mehendi Night
    '01krm46n0qwja334s3h3fdztf5' => ['B1.jpeg','B2.jpeg'],            // Baraat Royale
    '01krm46n103t4n181y8zkbtq2e' => ['C1.jpeg','C2.jpeg','C3.jpeg'],  // Bridal Trio
];

foreach ($updates as $id => $files) {
    $p = Product::find($id);
    if (!$p) { echo "NOT FOUND: $id\n"; continue; }
    echo "Updating: {$p->name}\n";
    attachImages($p, $files, $staged, $dest);
}

// ── Create new products ─────────────────────────────────────────────────────
$new = [
    ['name'=>'Classic French Tips',    'slug'=>'classic-french-tips',    'tier'=>'everyday',  'price'=>1900, 'files'=>['D1.jpeg','D2.jpeg','D3.jpeg']],
    ['name'=>'Terracotta Square',      'slug'=>'terracotta-square',      'tier'=>'everyday',  'price'=>1800, 'files'=>['E1.jpeg','E2.jpeg','E3.jpeg']],
    ['name'=>'Peach Gold Flower',      'slug'=>'peach-gold-flower',      'tier'=>'signature', 'price'=>2800, 'files'=>['F1.jpeg','F2.jpeg','F3.jpeg']],
    ['name'=>'Blush Pearl Daisy',      'slug'=>'blush-pearl-daisy',      'tier'=>'signature', 'price'=>3000, 'files'=>['H1.jpeg','H2.jpeg','H3.jpeg']],
    ['name'=>'White Floral Tips',      'slug'=>'white-floral-tips',      'tier'=>'signature', 'price'=>2800, 'files'=>['K1.jpeg']],
    ['name'=>'Blush French Classic',   'slug'=>'blush-french-classic',   'tier'=>'everyday',  'price'=>2000, 'files'=>['L1.jpeg','L2.jpeg']],
    ['name'=>'Peach Pearl Shimmer',    'slug'=>'peach-pearl-shimmer',    'tier'=>'signature', 'price'=>2800, 'files'=>['M1.jpeg','M2.jpeg']],
    ['name'=>'Mini French Square',     'slug'=>'mini-french-square',     'tier'=>'everyday',  'price'=>1900, 'files'=>['N1.jpeg','N2.jpeg']],
    ['name'=>'Cherry Red Almond',      'slug'=>'cherry-red-almond',      'tier'=>'signature', 'price'=>2500, 'files'=>['O1.jpeg','O2.jpeg']],
    ['name'=>'Gold Bow Caramel',       'slug'=>'gold-bow-caramel',       'tier'=>'signature', 'price'=>3000, 'files'=>['P1.jpeg','P2.jpeg','P3.jpeg']],
    ['name'=>'Heart French Tips',      'slug'=>'heart-french-tips',      'tier'=>'signature', 'price'=>2800, 'files'=>['Q1.jpeg','Q2.jpeg']],
    ['name'=>'Leopard Pearl French',   'slug'=>'leopard-pearl-french',   'tier'=>'glam',      'price'=>3800, 'files'=>['R1.jpeg','R2.jpeg']],
    ['name'=>'Bold Gold French',       'slug'=>'bold-gold-french',       'tier'=>'signature', 'price'=>3000, 'files'=>['S1.jpeg','S2.jpeg','S3.jpeg']],
    ['name'=>'Solid Baby Pink',        'slug'=>'solid-baby-pink',        'tier'=>'everyday',  'price'=>1900, 'files'=>['U1.jpeg','U2.jpeg','U3.jpeg']],
    ['name'=>'Silver Stripe French',   'slug'=>'silver-stripe-french',   'tier'=>'signature', 'price'=>2800, 'files'=>['X1.jpeg','X2.jpeg','X3.jpeg']],
    ['name'=>'Rose Gold Shimmer',      'slug'=>'rose-gold-shimmer',      'tier'=>'signature', 'price'=>2800, 'files'=>['Z1.jpeg','Z2.jpeg']],
    ['name'=>'Peach Coral Ombre',      'slug'=>'peach-coral-ombre',      'tier'=>'signature', 'price'=>2800, 'files'=>['AA1.jpeg','AA2.jpeg','AA3.jpeg']],
    ['name'=>'Gold Sequin French',     'slug'=>'gold-sequin-french',     'tier'=>'glam',      'price'=>3800, 'files'=>['BB1.jpeg','BB2.jpeg']],
    ['name'=>'Champagne Satin French', 'slug'=>'champagne-satin-french', 'tier'=>'signature', 'price'=>3000, 'files'=>['CC1.jpeg','CC2.jpeg']],
    ['name'=>'Coral Jelly Plain',      'slug'=>'coral-jelly-plain',      'tier'=>'everyday',  'price'=>1900, 'files'=>['DD1.jpeg','DD2.jpeg','DD3.jpeg']],
];

foreach ($new as $i => $cfg) {
    $p = Product::firstOrCreate(['slug' => $cfg['slug']], [
        'name'         => $cfg['name'],
        'tier'         => $cfg['tier'],
        'price_pkr'    => $cfg['price'],
        'is_active'    => true,
        'is_featured'  => false,
        'stock_status' => 'made_to_order',
        'sort_order'   => 10 + $i,
    ]);
    echo ($p->wasRecentlyCreated ? 'Created' : 'Exists') . ": {$p->name}\n";
    attachImages($p, $cfg['files'], $staged, $dest);
}

@rmdir($staged);
echo "\nDone! Total products: " . Product::count() . "\n";
