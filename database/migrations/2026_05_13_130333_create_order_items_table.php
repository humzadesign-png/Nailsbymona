<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->unsignedBigInteger('product_id')->nullable(); // nullable — Phase 3 adds products table FK
            $table->string('product_name_snapshot');
            $table->string('product_tier_snapshot', 30)->nullable();
            $table->string('product_slug_snapshot')->nullable();
            $table->unsignedInteger('unit_price_pkr');
            $table->unsignedSmallInteger('qty')->default(1);
            $table->text('sizing_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
