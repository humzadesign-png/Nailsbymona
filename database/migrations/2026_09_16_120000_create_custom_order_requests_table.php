<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Custom order requests — designs agreed with a customer over Instagram /
 * WhatsApp that aren't in the shop. Mona creates one in the admin panel and
 * sends the customer a private link (/custom/{token}). The customer takes
 * their sizing photos with the live camera guide and pays through the normal
 * checkout, which turns the request into a real Order.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_order_requests', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('token', 64)->unique();

            // Customer (as known from the DM conversation)
            $table->string('customer_name');
            $table->string('customer_phone', 30);
            $table->string('customer_email')->nullable();

            // The agreed design + quote
            $table->string('design_title');
            $table->text('design_description')->nullable();
            $table->json('reference_images')->nullable();   // public-disk paths
            $table->unsignedInteger('price_pkr');
            $table->unsignedInteger('shipping_pkr')->nullable(); // null = standard shipping rules
            $table->unsignedSmallInteger('lead_time_days')->nullable(); // null = standard lead time

            $table->string('status', 20)->default('pending'); // pending|completed|cancelled
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->uuid('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete();

            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_custom')->default(false)->after('is_returning_customer');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_custom');
        });
        Schema::dropIfExists('custom_order_requests');
    }
};
