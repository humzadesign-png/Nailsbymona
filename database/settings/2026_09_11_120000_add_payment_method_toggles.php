<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('store.jazzcash_enabled', true);
        $this->migrator->add('store.easypaisa_enabled', true);
        $this->migrator->add('store.bank_transfer_enabled', true);
    }
};
