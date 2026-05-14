<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ugc_photos', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('image_path');
            $table->string('alt')->nullable();
            $table->string('placement'); // App\Enums\UgcPlacement
            $table->foreignUlid('product_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('face_visible')->default(false);
            $table->boolean('is_published')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ugc_photos');
    }
};
