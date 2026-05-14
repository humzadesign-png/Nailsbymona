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
        Schema::create('order_sizing_photos', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->string('path');                        // storage path (ULID filename)
            $table->string('photo_type', 20);             // PhotoType enum: fingers|thumb|fingers_other|thumb_other
            $table->string('mime_type', 50)->nullable();
            $table->unsignedInteger('file_size')->nullable(); // bytes
            $table->timestamp('uploaded_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_sizing_photos');
    }
};
