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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('order_number', 20)->unique(); // NBM-YYYY-####
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();

            // Customer snapshot (in case customer record changes)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');

            // Shipping
            $table->text('shipping_address');
            $table->string('city', 100);
            $table->string('postal_code', 20)->nullable();
            $table->text('notes')->nullable();

            // Financials (integers — PKR, no decimals)
            $table->unsignedInteger('subtotal_pkr');
            $table->unsignedInteger('reorder_discount_pkr')->default(0);
            $table->unsignedInteger('shipping_pkr')->default(300);
            $table->unsignedInteger('total_pkr');
            $table->unsignedInteger('advance_paid_pkr')->default(0);

            // Flags
            $table->boolean('requires_advance')->default(false);
            $table->boolean('is_returning_customer')->default(false);

            // Enums stored as strings
            $table->string('payment_method', 30); // PaymentMethod enum
            $table->string('payment_status', 30)->default('awaiting'); // PaymentStatus enum
            $table->string('status', 30)->default('new'); // OrderStatus enum
            $table->string('sizing_capture_method', 30)->nullable(); // SizingCaptureMethod enum

            // Shipping tracking
            $table->string('tracking_number')->nullable();
            $table->string('courier', 20)->nullable(); // CourierType enum

            // Timestamps for status changes
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('production_started_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
