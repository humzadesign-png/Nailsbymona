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

            'shipping_flat_pkr'        => $settings->shipping_flat_pkr,
            'shipping_free_above'      => $settings->shipping_free_above,
            'advance_threshold_pkr'    => $settings->advance_threshold_pkr,
            'advance_percent'          => $settings->advance_percent,
            'bridal_deposit_percent'   => $settings->bridal_deposit_percent,
            'reorder_discount_percent' => $settings->reorder_discount_percent,

            'lead_time_standard_days'  => $settings->lead_time_standard_days,
            'lead_time_bridal_days'    => $settings->lead_time_bridal_days,
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

                FormSection::make('Shipping')->columns(2)->schema([
                    Forms\Components\TextInput::make('shipping_flat_pkr')
                        ->label('Flat shipping rate (PKR)')->numeric()->required()->prefix('Rs.'),
                    Forms\Components\TextInput::make('shipping_free_above')
                        ->label('Free shipping above (PKR)')->numeric()->prefix('Rs.')
                        ->helperText('Set to 0 to disable free shipping.'),
                ]),

                FormSection::make('Advance & deposits')->columns(2)->schema([
                    Forms\Components\TextInput::make('advance_threshold_pkr')
                        ->label('Advance required above (PKR)')->numeric()->required()->prefix('Rs.')
                        ->helperText('Orders ≥ this amount must pay a partial advance up-front.'),
                    Forms\Components\TextInput::make('advance_percent')
                        ->label('Advance percentage')->numeric()->required()->suffix('%')
                        ->helperText('Typical: 20–30%.'),
                    Forms\Components\TextInput::make('bridal_deposit_percent')
                        ->label('Bridal Trio deposit')->numeric()->required()->suffix('%')
                        ->helperText('Bridal Trio orders pay this percentage up-front. 100 = full advance.'),
                    Forms\Components\TextInput::make('reorder_discount_percent')
                        ->label('Returning-customer discount')->numeric()->required()->suffix('%')
                        ->helperText('Discount applied at checkout when sizing-on-file is matched.'),
                ]),

                FormSection::make('Production lead times')->columns(2)->schema([
                    Forms\Components\TextInput::make('lead_time_standard_days')
                        ->label('Standard lead time (calendar days)')->numeric()->required()->suffix('days')
                        ->helperText('Used in customer-facing copy + dispatch estimates.'),
                    Forms\Components\TextInput::make('lead_time_bridal_days')
                        ->label('Bridal Trio lead time (calendar days)')->numeric()->required()->suffix('days'),
                ]),
            ]);
    }

    public function save(): void
    {
        $data     = $this->form->getState();
        $settings = app(StoreSettings::class);

        $intFields = [
            'shipping_flat_pkr', 'shipping_free_above',
            'advance_threshold_pkr', 'advance_percent',
            'bridal_deposit_percent', 'reorder_discount_percent',
            'lead_time_standard_days', 'lead_time_bridal_days',
        ];

        foreach ($data as $key => $value) {
            $settings->{$key} = in_array($key, $intFields) ? (int) $value : (string) ($value ?? '');
        }

        // Normalize WhatsApp number to a canonical `+<digits>` form so
        // `wa.me/{digits}` URLs always work no matter how Mona types it.
        $digits = preg_replace('/\D+/', '', $settings->whatsapp_number ?? '');
        $settings->whatsapp_number = $digits !== '' ? '+' . $digits : '';

        $settings->save();

        Notification::make()
            ->title('Settings saved.')
            ->success()
            ->send();
    }
}
