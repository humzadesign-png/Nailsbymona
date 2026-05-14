<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_sizing_profiles', function (Blueprint $table) {
            // Right hand — stored as nullable strings so Mona can use her own notation
            // (e.g. "XS", "4.5", "7mm", "3") without locking into a unit up front.
            $table->string('size_r_thumb',  20)->nullable()->after('notes');
            $table->string('size_r_index',  20)->nullable()->after('size_r_thumb');
            $table->string('size_r_middle', 20)->nullable()->after('size_r_index');
            $table->string('size_r_ring',   20)->nullable()->after('size_r_middle');
            $table->string('size_r_pinky',  20)->nullable()->after('size_r_ring');

            // Left hand
            $table->string('size_l_thumb',  20)->nullable()->after('size_r_pinky');
            $table->string('size_l_index',  20)->nullable()->after('size_l_thumb');
            $table->string('size_l_middle', 20)->nullable()->after('size_l_index');
            $table->string('size_l_ring',   20)->nullable()->after('size_l_middle');
            $table->string('size_l_pinky',  20)->nullable()->after('size_l_ring');

            // Track which order the sizes were recorded from
            $table->foreignUlid('source_order_id')
                  ->nullable()
                  ->after('size_l_pinky')
                  ->constrained('orders')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customer_sizing_profiles', function (Blueprint $table) {
            $table->dropForeign(['source_order_id']);
            $table->dropColumn([
                'size_r_thumb', 'size_r_index', 'size_r_middle', 'size_r_ring', 'size_r_pinky',
                'size_l_thumb', 'size_l_index', 'size_l_middle', 'size_l_ring', 'size_l_pinky',
                'source_order_id',
            ]);
        });
    }
};
