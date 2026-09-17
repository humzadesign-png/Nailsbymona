<?php

namespace App\Filament\Resources;

use App\Enums\CustomOrderStatus;
use App\Filament\Resources\CustomOrderRequestResource\Pages;
use App\Models\CustomOrderRequest;
use App\Settings\StoreSettings;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section as FormSection;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;

/**
 * Custom order links — designs agreed with customers in Instagram / WhatsApp
 * DMs. Mona fills in the design + quote, then sends the private link. The
 * customer takes sizing photos with the camera guide and pays through the
 * normal checkout, which creates a regular Order marked "Custom".
 */
class CustomOrderRequestResource extends Resource
{
    protected static ?string $model = CustomOrderRequest::class;
    protected static string | \BackedEnum | null $navigationIcon  = 'heroicon-o-link';
    protected static string | \UnitEnum   | null $navigationGroup = 'Orders';
    protected static ?int    $navigationSort  = 2;
    protected static ?string $navigationLabel = 'Custom order links';
    protected static ?string $modelLabel      = 'custom order link';
    protected static ?string $pluralModelLabel = 'custom order links';

    public static function getNavigationBadge(): ?string
    {
        $waiting = CustomOrderRequest::where('status', CustomOrderStatus::Pending)
            ->where('expires_at', '>', now())
            ->count();

        return $waiting ? (string) $waiting : null;
    }

    // ── Table ─────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('design_title')
                    ->label('Design')
                    ->weight('semibold')
                    ->searchable()
                    ->description(fn (CustomOrderRequest $r) => 'Rs. ' . number_format($r->price_pkr)),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable(['customer_name', 'customer_phone', 'customer_instagram'])
                    ->description(fn (CustomOrderRequest $r) => collect([
                        $r->customer_phone,
                        $r->customer_instagram ? '@' . $r->customer_instagram : null,
                        $r->customer?->has_sizing_on_file ? '✓ sizing on file' : null,
                    ])->filter()->implode('  ·  ') ?: null)
                    ->url(fn (CustomOrderRequest $r) => $r->customer_id
                        ? CustomerResource::getUrl('view', ['record' => $r->customer_id])
                        : null),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->state(fn (CustomOrderRequest $r) => self::statusLabel($r))
                    ->color(fn (CustomOrderRequest $r) => match (true) {
                        $r->status === CustomOrderStatus::Completed => 'success',
                        $r->status === CustomOrderStatus::Cancelled => 'danger',
                        $r->isExpired()                             => 'gray',
                        default                                     => 'warning',
                    })
                    ->description(fn (CustomOrderRequest $r) => $r->status === CustomOrderStatus::Pending && ! $r->isExpired()
                        ? ($r->opened_at ? 'Customer opened the link' : 'Not opened yet')
                        : null),

                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order')
                    ->placeholder('—')
                    ->url(fn (CustomOrderRequest $r) => $r->order_id
                        ? OrderResource::getUrl('view', ['record' => $r->order_id])
                        : null),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Link valid until')
                    ->date('j M Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('waiting')
                    ->label('Waiting on customer')
                    ->query(fn ($query) => $query
                        ->where('status', CustomOrderStatus::Pending)
                        ->where('expires_at', '>', now())),
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(CustomOrderStatus::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()])),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\ActionGroup::make(self::linkActions()),
            ]);
    }

    /** Shared by the table rows and the edit page header. */
    public static function linkActions(): array
    {
        return [
            Actions\Action::make('whatsapp')
                ->label('Send on WhatsApp')
                ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                ->color('success')
                ->visible(fn (CustomOrderRequest $record) => $record->isUsable() && $record->whatsappUrl())
                ->url(fn (CustomOrderRequest $record) => $record->whatsappUrl())
                ->openUrlInNewTab(),

            // Copies the full message (greeting + link + expiry) — paste it into an
            // Instagram / Facebook / SMS chat. Runs in the browser, no server round trip.
            Actions\Action::make('copy_message')
                ->label('Copy message + link')
                ->icon('heroicon-o-clipboard-document')
                ->color('primary')
                ->visible(fn (CustomOrderRequest $record) => $record->isUsable())
                ->alpineClickHandler(fn (CustomOrderRequest $record) =>
                    'window.navigator.clipboard.writeText(' . Js::from($record->shareMessage()) . ')'
                    . '.then(() => new FilamentNotification().title(\'Message copied — paste it into the chat\').success().send())'
                    . '.catch(() => alert(\'Copy failed — use the Copy message button on the edit page.\'))'
                ),

            Actions\Action::make('instagram')
                ->label('Open Instagram chat')
                ->icon('heroicon-o-camera')
                ->color('gray')
                ->tooltip('Tap "Copy message + link" first, then paste it into the chat.')
                ->visible(fn (CustomOrderRequest $record) => $record->isUsable() && $record->instagramUrl())
                ->url(fn (CustomOrderRequest $record) => $record->instagramUrl())
                ->openUrlInNewTab(),

            Actions\Action::make('view_customer')
                ->label('Open customer')
                ->icon('heroicon-o-user')
                ->color('gray')
                ->visible(fn (CustomOrderRequest $record) => $record->customer_id !== null)
                ->url(fn (CustomOrderRequest $record) => CustomerResource::getUrl('view', ['record' => $record->customer_id])),

            Actions\Action::make('extend')
                ->label('Extend 7 days')
                ->icon('heroicon-o-clock')
                ->color('gray')
                ->visible(fn (CustomOrderRequest $record) => $record->status === CustomOrderStatus::Pending)
                ->requiresConfirmation()
                ->modalDescription('The link will stay valid for 7 more days from today.')
                ->action(function (CustomOrderRequest $record) {
                    $record->update([
                        'expires_at' => now()->addDays(CustomOrderRequest::DEFAULT_EXPIRY_DAYS)->endOfDay(),
                    ]);
                    Notification::make()->title('Link extended until ' . $record->expires_at->format('j M'))->success()->send();
                }),

            Actions\Action::make('cancel_link')
                ->label('Cancel link')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (CustomOrderRequest $record) => $record->status === CustomOrderStatus::Pending)
                ->requiresConfirmation()
                ->modalDescription('The customer will no longer be able to use this link to order.')
                ->action(function (CustomOrderRequest $record) {
                    $record->update(['status' => CustomOrderStatus::Cancelled]);
                    Notification::make()->title('Link cancelled.')->success()->send();
                }),

            Actions\Action::make('view_order')
                ->label('View order')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary')
                ->visible(fn (CustomOrderRequest $record) => $record->order_id !== null)
                ->url(fn (CustomOrderRequest $record) => OrderResource::getUrl('view', ['record' => $record->order_id])),
        ];
    }

    /** Read-only text with a copy-to-clipboard button (Alpine is bundled with Filament). */
    private static function copyRow(string $label, string $text, string $buttonLabel): string
    {
        return '<div x-data="{ copied: false, text: ' . e(json_encode($text)) . ' }" class="mb-3">'
            . '<p class="mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">' . e($label) . '</p>'
            . '<div class="flex flex-wrap items-start gap-3">'
            . '<pre class="flex-1 min-w-0 whitespace-pre-wrap break-all rounded-lg bg-gray-100 dark:bg-gray-800 px-3 py-2 text-sm font-sans">' . e($text) . '</pre>'
            . '<button type="button" class="text-sm font-semibold text-primary-600 hover:underline"'
            . ' x-on:click="navigator.clipboard.writeText(text); copied = true; setTimeout(() => copied = false, 2000)"'
            . ' x-text="copied ? \'✓ Copied\' : \'' . e($buttonLabel) . '\'"></button>'
            . '</div></div>';
    }

    private static function statusLabel(CustomOrderRequest $r): string
    {
        if ($r->status === CustomOrderStatus::Pending && $r->isExpired()) {
            return 'Expired';
        }

        return $r->status->label();
    }

    // ── Form ──────────────────────────────────────────────────────────────────

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            FormSection::make('Customer link')
                ->description('Send on WhatsApp with the button at the top, or copy the message and paste it into Instagram.')
                ->visible(fn (?CustomOrderRequest $record) => $record !== null)
                ->schema([
                    Forms\Components\Placeholder::make('link')
                        ->label('')
                        ->content(function (?CustomOrderRequest $record) {
                            if (! $record) {
                                return '';
                            }

                            return new HtmlString(
                                self::copyRow('Link', $record->publicUrl(), 'Copy link')
                                . self::copyRow('Message (for Instagram, Facebook, SMS…)', $record->shareMessage(), 'Copy message')
                                . '<p class="mt-3 text-sm text-gray-500">Status: ' . e(self::statusLabel($record))
                                . ($record->opened_at ? ' · opened ' . e($record->opened_at->diffForHumans()) : ' · not opened yet')
                                . '</p>'
                            );
                        }),
                ]),

            FormSection::make('Customer')
                ->description('Only the name is required. Add a WhatsApp number or Instagram handle — whichever chat the customer messaged you on. They can correct details at checkout.')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('customer_name')
                        ->label('Name')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('customer_phone')
                        ->label('WhatsApp number (optional)')
                        ->tel()
                        ->maxLength(30)
                        ->placeholder('03XX XXXXXXX')
                        ->helperText('Shows the "Send on WhatsApp" button.'),
                    Forms\Components\TextInput::make('customer_instagram')
                        ->label('Instagram handle (optional)')
                        ->prefix('@')
                        ->maxLength(60)
                        ->placeholder('username')
                        ->rule('regex:/^@?[A-Za-z0-9._\/:]+$/')
                        ->helperText('Shows the "Open Instagram chat" button.'),
                    Forms\Components\TextInput::make('customer_email')
                        ->label('Email (optional)')
                        ->email()
                        ->maxLength(150),
                ]),

            FormSection::make('Design & quote')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('design_title')
                        ->label('Design name')
                        ->required()
                        ->maxLength(120)
                        ->placeholder('e.g. Maroon chrome with gold flakes')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('design_description')
                        ->label('What you agreed')
                        ->rows(4)
                        ->maxLength(2000)
                        ->placeholder('Shape, length, colours, charms — the customer sees this text.')
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('reference_images')
                        ->label('Reference photos (optional)')
                        ->helperText('Up to 4 photos the customer sees on their link. Hands and nails only — no faces.')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->maxFiles(4)
                        ->maxSize(8192)
                        ->disk('public')
                        ->directory('custom-designs')
                        ->visibility('public')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('price_pkr')
                        ->label('Quoted price')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->prefix('Rs.'),
                    Forms\Components\TextInput::make('shipping_pkr')
                        ->label('Shipping')
                        ->numeric()
                        ->minValue(0)
                        ->prefix('Rs.')
                        ->helperText(function () {
                            $s = app(StoreSettings::class);
                            return 'Leave blank for the standard rate (Rs. ' . number_format($s->shipping_flat_pkr)
                                . ($s->shipping_free_above > 0 ? ', free above Rs. ' . number_format($s->shipping_free_above) : '')
                                . '). Enter 0 for free shipping.';
                        }),
                    Forms\Components\TextInput::make('lead_time_days')
                        ->label('Making time (days)')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(90)
                        ->helperText(fn () => 'Leave blank for the standard ' . app(StoreSettings::class)->lead_time_standard_days . ' days.'),
                    Forms\Components\DatePicker::make('expires_at')
                        ->label('Link valid until')
                        ->native(false)
                        ->displayFormat('j M Y')
                        ->minDate(today())
                        ->default(now()->addDays(CustomOrderRequest::DEFAULT_EXPIRY_DAYS)->endOfDay())
                        ->required(),
                ]),

            FormSection::make('Private notes')
                ->collapsed()
                ->schema([
                    Forms\Components\Textarea::make('admin_notes')
                        ->label('Notes (only you see these)')
                        ->rows(3)
                        ->maxLength(2000),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCustomOrderRequests::route('/'),
            'create' => Pages\CreateCustomOrderRequest::route('/create'),
            'edit'   => Pages\EditCustomOrderRequest::route('/{record}/edit'),
        ];
    }
}
