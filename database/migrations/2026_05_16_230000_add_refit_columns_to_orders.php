<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds free-first-refit tracking columns to orders.
     *
     * Mona promises a free refit if any nail doesn't sit right within 7 days
     * of delivery (CLAUDE.md §16, §32 2026-05-16). Until now there was no
     * structured way to record that a refit was requested or shipped — Mona
     * tracked it from memory in WhatsApp. These columns + Filament actions
     * give her one-click recording.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('refit_requested_at')->nullable()->after('delivered_at');
            $table->timestamp('refit_shipped_at')->nullable()->after('refit_requested_at');
            $table->text('refit_notes')->nullable()->after('refit_shipped_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['refit_requested_at', 'refit_shipped_at', 'refit_notes']);
        });
    }
};
