<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add a `deleted_at` column to `orders` so a deleted order can be restored
 * (Filament's per-row delete sets the timestamp; the row stays in the DB).
 *
 * Combined with the removal of bulk-delete on the Orders table (Block 5 / A14),
 * this gives Mona a recoverable safety net against accidental deletions.
 *
 * Existing global scopes / queries are unaffected — Eloquent's SoftDeletes
 * trait automatically excludes deleted rows from default queries.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'deleted_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'deleted_at')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
