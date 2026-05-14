<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_sizing_profiles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('customer_id')->constrained()->cascadeOnDelete();
            $table->text('notes')->nullable();
            $table->timestamp('verified_by_admin_at')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_sizing_photos', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('customer_sizing_profile_id')->constrained('customer_sizing_profiles')->cascadeOnDelete();
            $table->string('path');
            $table->string('photo_type'); // App\Enums\PhotoType
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_sizing_photos');
        Schema::dropIfExists('customer_sizing_profiles');
    }
};
