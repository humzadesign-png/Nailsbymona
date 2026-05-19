<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Drop the dead `order_items.product_id` column.
 *
 * History: original column was typed `unsignedBigInteger` but `products.id`
 * is a 26-char ULID. Writing the real product id would fail on MySQL's
 * strict type checking. The application has always avoided this by using
 * `product_slug_snapshot` (string) as the de-facto FK and never writing
 * `product_id`. Removing the column closes the trap-door for future devs.
 *
 * Down migration restores the (still-useless) column so we can roll back
 * without dropping the table.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('order_items', 'product_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropColumn('product_id');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('order_items', 'product_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->unsignedBigInteger('product_id')->nullable()->after('order_id');
            });
        }
    }
};
