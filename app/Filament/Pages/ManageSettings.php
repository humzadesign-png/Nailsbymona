<?php

namespace App\Filament\Pages;

use App\Settings\StoreSettings;
use Filament\Forms;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class ManageSettings extends Page
{
    protected static ?string                     $navigationLabel = 'Settings';
    protected static string | \UnitEnum | null   $navigationGroup = 'Settings';
    protected static ?int                        $navigationSort  = 1;

    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(StoreSettings::class);

        $this->form->fill([
            'whatsapp_number'    => $settings->whatsapp_number,
            'instagram_handle'   => $settings->instagram_handle,
            'tiktok_handle'      => $settings->tiktok_handle,
            'contact_email'      => $settings->contact_email,
            'business_hours'     => $settings->business_hours,

            'jazzcash_number'    => $settings->jazzcash_number,
            'jazzcash_name'      => $settings->jazzcash_name,
            'easypaisa_number'   => $settings->easypaisa_number,
            'easypaisa_name'     => $settings->easypaisa_name,
            'bank_name'          => $settings->bank_name,
            'bank_account_name'  => $settings->bank_account_name,
            'bank_account_no'    => $settings->bank_account_no,
            'bank_iban'          => $settings->bank_iban,

            'shipping_flat_pkr'     => $settings->shipping_flat_pkr,
            'shipping_free_above'   => $settings->shipping_free_above,
            'advance_threshold_pkr' => $settings->advance_threshold_pkr,
            'advance_percent'       => $settings->advance_percent,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                FormSection::make('Contact & Social')->columns(2)->schema([
                    Forms\Components\TextInput::make('whatsapp_number')
                        ->label('WhatsApp number')->required()
                        ->helperText('Include country code, e.g. +923001234567'),
                    Forms\Components\TextInput::make('contact_email')->label('Contact email')->email(),
                    Forms\Components\TextInput::make('instagram_handle')
                        ->label('Instagram handle')->prefix('@'),
                    Forms\Components\TextInput::make('tiktok_handle')
                        ->label('TikTok handle')->prefix('@'),
                    Forms\Components\TextInput::make('business_hours')
                        ->label('Business hours')->columnSpanFull()
                        ->placeholder('Mon–Sat, 10am–7pm (PKT)'),
                ]),

                FormSection::make('JazzCash')->columns(2)->schema([
                    Forms\Components\TextInput::make('jazzcash_number')->label('Mobile number'),
                    Forms\Components\TextInput::make('jazzcash_name')->label('Account name'),
                ]),

                FormSection::make('EasyPaisa')->columns(2)->schema([
                    Forms\Components\TextInput::make('easypaisa_number')->label('Mobile number'),
                    Forms\Components\TextInput::make('easypaisa_name')->label('Account name'),
                ]),

                FormSection::make('Bank Transfer')->columns(2)->schema([
                    Forms\Components\TextInput::make('bank_name')->label('Bank name'),
                    Forms\Components\TextInput::make('bank_account_name')->label('Account name'),
                    Forms\Components\TextInput::make('bank_account_no')->label('Account number'),
                    Forms\Components\TextInput::make('bank_iban')->label('IBAN')->placeholder('PK36 SCBL 0000001123456702'),
                ]),

                FormSection::make('Shipping & Advance')->columns(2)->schema([
                    Forms\Components\TextInput::make('shipping_flat_pkr')
                        ->label('Flat shipping rate (PKR)')->numeric()->required()->prefix('Rs.'),
                    Forms\Components\TextInput::make('shipping_free_above')
                        ->label('Free shipping above (PKR)')->numeric()->prefix('Rs.')
                        ->helperText('Set to 0 to disable free shipping.'),
                    Forms\Components\TextInput::make('advance_threshold_pkr')
                        ->label('Advance required above (PKR)')->numeric()->required()->prefix('Rs.'),
                    Forms\Components\TextInput::make('advance_percent')
                        ->label('Advance percentage')->numeric()->required()->suffix('%'),
                ]),
            ]);
    }

    public function save(): void
    {
        $data     = $this->form->getState();
        $settings = app(StoreSettings::class);

        $intFields = ['shipping_flat_pkr', 'shipping_free_above', 'advance_threshold_pkr', 'advance_percent'];

        foreach ($data as $key => $value) {
            $settings->{$key} = in_array($key, $intFields) ? (int) $value : (string) ($value ?? '');
        }

        $settings->save();

        Notification::make()
            ->title('Settings saved.')
            ->success()
            ->send();
    }
}
