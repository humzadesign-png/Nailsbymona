<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Custom order links now create (or match) a Customer as soon as Mona makes
 * the link, so DM customers show up in Customers — with their sizing and
 * quoted design — before they finish checkout.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('instagram', 60)->nullable()->after('whatsapp');
        });

        Schema::table('custom_order_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('token');
            $table->foreign('customer_id')->references('id')->on('customers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('custom_order_requests', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('instagram');
        });
    }
};
