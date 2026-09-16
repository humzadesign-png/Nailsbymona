<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Custom order links can be sent over Instagram as well as WhatsApp, so the
 * WhatsApp number becomes optional and an Instagram handle can be stored.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_order_requests', function (Blueprint $table) {
            $table->string('customer_phone', 30)->nullable()->change();
            $table->string('customer_instagram', 60)->nullable()->after('customer_phone');
        });
    }

    public function down(): void
    {
        Schema::table('custom_order_requests', function (Blueprint $table) {
            $table->dropColumn('customer_instagram');
        });
    }
};
