<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use App\Models\CustomerSizingProfile;
use Filament\Forms;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section as InfoSection;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static string | \UnitEnum | null   $navigationGroup = 'Customers';
    protected static ?int    $navigationSort  = 1;

    // ── Table ─────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->weight('semibold')
                    ->description(fn (Customer $r) => $r->email),
                Tables\Columns\TextColumn::make('phone')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('city')->toggleable(),
                Tables\Columns\IconColumn::make('has_sizing_on_file')->label('Sizing saved')->boolean(),
                Tables\Columns\TextColumn::make('total_orders')->label('Orders')->sortable(),
                Tables\Columns\TextColumn::make('lifetime_value_pkr')
                    ->label('Lifetime value')
                    ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state ?? 0))
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_ordered_at')
                    ->label('Last order')->dateTime('d M Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('has_sizing_on_file')->label('Sizing on file'),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([Actions\DeleteBulkAction::make()]),
            ]);
    }

    // ── Infolist (view page) ──────────────────────────────────────────────────

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            InfoSection::make('Customer')->columns(2)->schema([
                Infolists\Components\TextEntry::make('name')->weight('bold'),
                Infolists\Components\TextEntry::make('email')->copyable(),
                Infolists\Components\TextEntry::make('phone')->copyable(),
                Infolists\Components\TextEntry::make('whatsapp')->label('WhatsApp')->copyable(),
                Infolists\Components\TextEntry::make('city'),
                Infolists\Components\TextEntry::make('postal_code')->label('Postal code'),
                Infolists\Components\TextEntry::make('default_shipping_address')
                    ->label('Default address')->columnSpanFull(),
                Infolists\Components\TextEntry::make('notes')
                    ->label("Mona's notes")->columnSpanFull()->placeholder('—'),
            ]),

            InfoSection::make('Stats')->columns(3)->schema([
                Infolists\Components\TextEntry::make('total_orders')->label('Total orders'),
                Infolists\Components\TextEntry::make('lifetime_value_pkr')
                    ->label('Lifetime value')
                    ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state ?? 0)),
                Infolists\Components\TextEntry::make('last_ordered_at')
                    ->label('Last order')->dateTime('d M Y')->placeholder('Never'),
                Infolists\Components\IconEntry::make('has_sizing_on_file')
                    ->label('Sizing on file')->boolean(),
            ]),

            InfoSection::make('Saved Nail Sizes')
                ->description('Sizes recorded by Mona from the customer\'s sizing photos.')
                ->collapsible()
                ->columns(1)
                ->schema([
                    // Right hand — 5 columns
                    InfoSection::make('Right Hand')->columns(5)->compact()->schema([
                        Infolists\Components\TextEntry::make('sizingProfile.size_r_thumb') ->label('Thumb') ->placeholder('—'),
                        Infolists\Components\TextEntry::make('sizingProfile.size_r_index') ->label('Index') ->placeholder('—'),
                        Infolists\Components\TextEntry::make('sizingProfile.size_r_middle')->label('Middle')->placeholder('—'),
                        Infolists\Components\TextEntry::make('sizingProfile.size_r_ring')  ->label('Ring')  ->placeholder('—'),
                        Infolists\Components\TextEntry::make('sizingProfile.size_r_pinky') ->label('Pinky') ->placeholder('—'),
                    ]),
                    // Left hand — 5 columns
                    InfoSection::make('Left Hand')->columns(5)->compact()->schema([
                        Infolists\Components\TextEntry::make('sizingProfile.size_l_thumb') ->label('Thumb') ->placeholder('—'),
                        Infolists\Components\TextEntry::make('sizingProfile.size_l_index') ->label('Index') ->placeholder('—'),
                        Infolists\Components\TextEntry::make('sizingProfile.size_l_middle')->label('Middle')->placeholder('—'),
                        Infolists\Components\TextEntry::make('sizingProfile.size_l_ring')  ->label('Ring')  ->placeholder('—'),
                        Infolists\Components\TextEntry::make('sizingProfile.size_l_pinky') ->label('Pinky') ->placeholder('—'),
                    ]),
                    InfoSection::make('')->compact()->schema([
                        Infolists\Components\TextEntry::make('sizingProfile.notes')
                            ->label('Sizing notes')->placeholder('—'),
                        Infolists\Components\TextEntry::make('sizingProfile.verified_by_admin_at')
                            ->label('Last verified')->dateTime('d M Y, g:ia')->placeholder('Not verified'),
                    ])->columns(2),
                ]),
        ]);
    }

    // ── Form (edit) ───────────────────────────────────────────────────────────

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            FormSection::make('Contact')->columns(2)->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\TextInput::make('phone'),
                Forms\Components\TextInput::make('whatsapp')->label('WhatsApp'),
            ]),

            FormSection::make('Address')->columns(2)->schema([
                Forms\Components\Textarea::make('default_shipping_address')
                    ->label('Default address')->columnSpanFull(),
                Forms\Components\TextInput::make('city'),
                Forms\Components\TextInput::make('postal_code')->label('Postal code'),
            ]),

            FormSection::make('Notes')->schema([
                Forms\Components\Textarea::make('notes')
                    ->label('Private notes (not visible to customer)')->rows(3),
                Forms\Components\Toggle::make('has_sizing_on_file')->label('Sizing on file'),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCustomers::route('/'),
            'view'   => Pages\ViewCustomer::route('/{record}'),
            'edit'   => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    // ── Reusable sizing form schema (used here and in OrderResource) ──────────

    public static function nailSizesFormSchema(): array
    {
        return [
            FormSection::make('Right Hand')
                ->description('Sizes for the customer\'s right hand. Use any notation you prefer — XS/S/M/L or a number.')
                ->columns(5)
                ->schema([
                    Forms\Components\TextInput::make('size_r_thumb') ->label('Thumb') ->maxLength(20),
                    Forms\Components\TextInput::make('size_r_index') ->label('Index') ->maxLength(20),
                    Forms\Components\TextInput::make('size_r_middle')->label('Middle')->maxLength(20),
                    Forms\Components\TextInput::make('size_r_ring')  ->label('Ring')  ->maxLength(20),
                    Forms\Components\TextInput::make('size_r_pinky') ->label('Pinky') ->maxLength(20),
                ]),

            FormSection::make('Left Hand')
                ->description('Leave blank if the same as right hand (symmetry assumed).')
                ->columns(5)
                ->schema([
                    Forms\Components\TextInput::make('size_l_thumb') ->label('Thumb') ->maxLength(20),
                    Forms\Components\TextInput::make('size_l_index') ->label('Index') ->maxLength(20),
                    Forms\Components\TextInput::make('size_l_middle')->label('Middle')->maxLength(20),
                    Forms\Components\TextInput::make('size_l_ring')  ->label('Ring')  ->maxLength(20),
                    Forms\Components\TextInput::make('size_l_pinky') ->label('Pinky') ->maxLength(20),
                ]),

            FormSection::make('Notes')->schema([
                Forms\Components\Textarea::make('notes')
                    ->label('Sizing notes (Mona only)')
                    ->placeholder('e.g. "Slightly wider nail beds, recommended S for thumb but M for others."')
                    ->rows(2),
            ]),
        ];
    }

    // ── "Edit nail sizes" action — used on the customer view page header ──────

    public static function editNailSizesAction(): Actions\Action
    {
        return Actions\Action::make('edit_nail_sizes')
            ->label('Edit nail sizes')
            ->icon('heroicon-o-finger-print')
            ->color('gray')
            ->fillForm(function (Customer $record): array {
                $profile = $record->sizingProfile;

                return $profile ? $profile->only([
                    'size_r_thumb', 'size_r_index', 'size_r_middle', 'size_r_ring', 'size_r_pinky',
                    'size_l_thumb', 'size_l_index', 'size_l_middle', 'size_l_ring', 'size_l_pinky',
                    'notes',
                ]) : [];
            })
            ->form(self::nailSizesFormSchema())
            ->action(function (Customer $record, array $data): void {
                CustomerSizingProfile::updateOrCreate(
                    ['customer_id' => $record->id],
                    array_merge($data, ['verified_by_admin_at' => now()])
                );

                $record->update(['has_sizing_on_file' => true]);

                Notification::make()
                    ->title('Nail sizes saved.')
                    ->success()
                    ->send();
            });
    }
}
