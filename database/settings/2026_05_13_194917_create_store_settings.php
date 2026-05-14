<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('store.whatsapp_number',   '+923000000000');
        $this->migrator->add('store.instagram_handle',  'nailsbymona');
        $this->migrator->add('store.tiktok_handle',     'nailsbymona');
        $this->migrator->add('store.contact_email',     'hello@nailsbymona.com');
        $this->migrator->add('store.business_hours',    'Mon–Sat, 10am–7pm (PKT)');

        $this->migrator->add('store.jazzcash_number',   '');
        $this->migrator->add('store.jazzcash_name',     '');
        $this->migrator->add('store.easypaisa_number',  '');
        $this->migrator->add('store.easypaisa_name',    '');
        $this->migrator->add('store.bank_name',         '');
        $this->migrator->add('store.bank_account_name', '');
        $this->migrator->add('store.bank_account_no',   '');
        $this->migrator->add('store.bank_iban',         '');

        $this->migrator->add('store.shipping_flat_pkr',    250);
        $this->migrator->add('store.shipping_free_above',  5000);
        $this->migrator->add('store.advance_threshold_pkr', 5000);
        $this->migrator->add('store.advance_percent',       25);
    }
};
