<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * `customer_sizing_profiles.source_order_id` was created with foreignUlid()
 * (char 26) but orders use UUIDs (char 36), so saving nail sizes from an
 * order always failed with "Data too long for column 'source_order_id'".
 * Widen it to char(36) and restore the foreign key.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('customer_sizing_profiles', 'source_order_id')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            // Drop the FK first — MySQL won't alter a column another key references.
            $exists = DB::selectOne("
                SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'customer_sizing_profiles'
                  AND COLUMN_NAME = 'source_order_id'
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            ");
            if ($exists) {
                DB::statement("ALTER TABLE customer_sizing_profiles DROP FOREIGN KEY {$exists->CONSTRAINT_NAME}");
            }

            DB::statement('ALTER TABLE customer_sizing_profiles MODIFY source_order_id CHAR(36) NULL');

            Schema::table('customer_sizing_profiles', function (Blueprint $table) {
                $table->foreign('source_order_id')->references('id')->on('orders')->nullOnDelete();
            });

            return;
        }

        // SQLite (local dev) is happy to store the longer value as-is.
        Schema::table('customer_sizing_profiles', function (Blueprint $table) {
            $table->char('source_order_id', 36)->nullable()->change();
        });
    }

    public function down(): void
    {
        // Intentionally irreversible: reverting to char(26) would truncate
        // every recorded order reference.
    }
};
