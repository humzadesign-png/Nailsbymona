<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds `estimated_dispatch_at` to orders.
 *
 * Set once at order placement using StoreSettings lead times (standard or
 * bridal). Pinning the date on the row keeps the customer-facing
 * confirmation/tracking pages stable across reloads — previously the
 * dispatch date was computed at render time and would shift forward as
 * the order aged.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('estimated_dispatch_at')->nullable()->after('delivered_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('estimated_dispatch_at');
        });
    }
};
