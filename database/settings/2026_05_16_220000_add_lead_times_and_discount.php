<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('store.bridal_deposit_percent', 100);
        $this->migrator->add('store.reorder_discount_percent', 5);
        $this->migrator->add('store.lead_time_standard_days', 5);
        $this->migrator->add('store.lead_time_bridal_days', 10);
    }
};
