<?php

namespace Database\Seeders;

use App\Enums\ProductTier;
use App\Enums\StockStatus;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── Admin user ────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@nailsbymona.test'],
            [
                'name'     => 'Mona',
                'password' => Hash::make('password'),
            ]
        );

        // ── Demo products ─────────────────────────────────────────────────────
        $products = [
            [
                'name'          => 'Barely There Nude',
                'slug'          => 'barely-there-nude',
                'tier'          => ProductTier::Everyday,
                'price_pkr'     => 1900,
                'description'   => 'A clean, barely-there nude that goes with everything. Perfect for the office or everyday wear. Sheer finish with a natural nail-like look.',
                'stock_status'  => StockStatus::InStock,
                'lead_time_days'=> 5,
                'is_active'     => true,
                'is_featured'   => true,
                'sort_order'    => 1,
            ],
            [
                'name'          => 'Dusty Rose Ombre',
                'slug'          => 'dusty-rose-ombre',
                'tier'          => ProductTier::Signature,
                'price_pkr'     => 2800,
                'description'   => 'A soft blush-to-rose gradient that photographs beautifully. Hand-painted ombre fade, gel finish. Pairs perfectly with Mehendi jewellery.',
                'stock_status'  => StockStatus::MadeToOrder,
                'lead_time_days'=> 7,
                'is_active'     => true,
                'is_featured'   => true,
                'sort_order'    => 2,
            ],
            [
                'name'          => 'French + Gold Tips',
                'slug'          => 'french-gold-tips',
                'tier'          => ProductTier::Signature,
                'price_pkr'     => 3200,
                'description'   => 'A modern twist on the classic French. Clean white tip with a hand-painted gold line. Timeless, versatile, and sharper in person.',
                'stock_status'  => StockStatus::MadeToOrder,
                'lead_time_days'=> 7,
                'is_active'     => true,
                'is_featured'   => false,
                'sort_order'    => 3,
            ],
            [
                'name'          => 'Berry Chrome',
                'slug'          => 'berry-chrome',
                'tier'          => ProductTier::Signature,
                'price_pkr'     => 3000,
                'description'   => 'Deep berry with a mirror-chrome finish. Makes a statement without trying. Winter essential.',
                'stock_status'  => StockStatus::MadeToOrder,
                'lead_time_days'=> 7,
                'is_active'     => true,
                'is_featured'   => true,
                'sort_order'    => 4,
            ],
            [
                'name'          => 'Midnight Glitter Cloud',
                'slug'          => 'midnight-glitter-cloud',
                'tier'          => ProductTier::Glam,
                'price_pkr'     => 4200,
                'description'   => 'Deep navy base with hand-placed iridescent glitter clouds. 3D raised detail gives these real depth. Made to be stared at.',
                'stock_status'  => StockStatus::MadeToOrder,
                'lead_time_days'=> 9,
                'is_active'     => true,
                'is_featured'   => true,
                'sort_order'    => 5,
            ],
            [
                'name'          => 'Red Rose 3D',
                'slug'          => 'red-rose-3d',
                'tier'          => ProductTier::Glam,
                'price_pkr'     => 4600,
                'description'   => 'Hand-sculpted 3D rose on each nail. Gel base with full rose relief detail. Takes two working days to build. Worth every minute.',
                'stock_status'  => StockStatus::MadeToOrder,
                'lead_time_days'=> 10,
                'is_active'     => true,
                'is_featured'   => false,
                'sort_order'    => 6,
            ],
            [
                'name'          => 'Mehendi Night',
                'slug'          => 'mehendi-night',
                'tier'          => ProductTier::BridalSingle,
                'price_pkr'     => 5500,
                'description'   => 'Warm terracotta with gold leaf details and a delicate henna-inspired pattern on the accent nail. Made for the night you get your mehndi done.',
                'stock_status'  => StockStatus::MadeToOrder,
                'lead_time_days'=> 10,
                'is_active'     => true,
                'is_featured'   => false,
                'sort_order'    => 7,
            ],
            [
                'name'          => 'Baraat Royale',
                'slug'          => 'baraat-royale',
                'tier'          => ProductTier::BridalSingle,
                'price_pkr'     => 6000,
                'description'   => 'Deep burgundy with 24K gold foil and hand-placed Swarovski-grade stones. The most photographed nail of the night.',
                'stock_status'  => StockStatus::MadeToOrder,
                'lead_time_days'=> 10,
                'is_active'     => true,
                'is_featured'   => false,
                'sort_order'    => 8,
            ],
            [
                'name'          => 'Bridal Trio — Classic',
                'slug'          => 'bridal-trio-classic',
                'tier'          => ProductTier::BridalTrio,
                'price_pkr'     => 12500,
                'description'   => 'Three coordinated sets for Mehendi, Baraat, and Valima. One fitting, three statements. Shipped together in a rigid magnetic gift box with satin lining, glue, prep kit, and a handwritten name card. Order 4 weeks before your Mehendi.',
                'stock_status'  => StockStatus::MadeToOrder,
                'lead_time_days'=> 14,
                'is_active'     => true,
                'is_featured'   => true,
                'sort_order'    => 9,
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
