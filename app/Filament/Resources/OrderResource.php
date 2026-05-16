<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\OrderResource\Pages;
use App\Mail\OrderInProduction;
use App\Mail\OrderShipped;
use App\Mail\PaymentVerified;
use App\Models\Customer;
use App\Models\CustomerSizingProfile;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Filament\Forms;
use Filament\Infolists;
use Filament\Schemas\Components\Section as InfoSection;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static string | \UnitEnum | null $navigationGroup = 'Orders';
    protected static ?int    $navigationSort  = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) Order::where('status', OrderStatus::New)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    // ── Table ─────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order')
                    ->searchable()
                    ->weight('semibold')
                    ->copyable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn (Order $r) => $r->is_returning_customer
                        ? '↩ Returning  ·  ' . $r->customer_phone
                        : $r->customer_phone
                    ),

                Tables\Columns\TextColumn::make('total_pkr')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        OrderStatus::New          => 'gray',
                        OrderStatus::Confirmed    => 'warning',
                        OrderStatus::InProduction => 'primary',
                        OrderStatus::Shipped      => 'info',
                        OrderStatus::Delivered    => 'success',
                        OrderStatus::Cancelled    => 'danger',
                        default                   => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state instanceof OrderStatus ? $state->label() : $state),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        PaymentStatus::Awaiting       => 'warning',
                        PaymentStatus::Verifying      => 'primary',
                        PaymentStatus::Paid           => 'success',
                        PaymentStatus::PartialAdvance => 'info',
                        PaymentStatus::Refunded       => 'danger',
                        default                       => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state instanceof PaymentStatus ? $state->label() : $state)
                    ->description(fn (Order $r) => $r->payment_age_label),

                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Method')
                    ->formatStateUsing(fn ($state) => $state instanceof PaymentMethod ? $state->label() : $state)
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('city')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Placed')
                    ->dateTime('d M Y, g:ia')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(OrderStatus::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()])),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options(collect(PaymentStatus::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()])),
                Tables\Filters\Filter::make('awaiting_payment')
                    ->label('Awaiting payment')
                    ->query(fn ($query) => $query->where('payment_status', PaymentStatus::Awaiting)),
                Tables\Filters\Filter::make('returning_customers')
                    ->label('Returning customers')
                    ->query(fn ($query) => $query->where('is_returning_customer', true)),
            ])
            ->actions([
                Actions\ViewAction::make(),

                Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                    ->color('gray')
                    ->visible(fn (Order $r) => filled($r->customer_phone))
                    ->url(function (Order $r) {
                        $digits = preg_replace('/\D+/', '', $r->customer_phone ?? '');
                        $msg    = "Hello, this is Mona. About your order {$r->order_number} —";
                        return "https://wa.me/{$digits}?text=" . rawurlencode($msg);
                    })
                    ->openUrlInNewTab(),

                Actions\Action::make('confirm')
                    ->label('Confirm: full payment')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Order $r) => $r->status === OrderStatus::New)
                    ->requiresConfirmation()
                    ->modalHeading('Confirm full payment')
                    ->modalDescription(fn (Order $r) =>
                        'This marks the full Rs. ' . number_format($r->total_pkr) .
                        ' as paid and confirms the order. A confirmation email is sent to the customer.')
                    ->modalSubmitActionLabel('Yes, confirm full payment')
                    ->action(function (Order $r) {
                        $r->update([
                            'status'           => OrderStatus::Confirmed,
                            'payment_status'   => PaymentStatus::Paid,
                            'advance_paid_pkr' => $r->total_pkr,
                            'confirmed_at'     => now(),
                        ]);
                        try {
                            Mail::to($r->customer_email)->send(new PaymentVerified($r));
                        } catch (\Throwable $e) {
                            \Log::error('PaymentVerified mail failed', ['order' => $r->id, 'e' => $e->getMessage()]);
                        }
                        Notification::make()->title('Order confirmed — email sent to customer.')->success()->send();
                    }),

                Actions\Action::make('confirm_advance')
                    ->label('Confirm: advance only')
                    ->icon('heroicon-o-banknotes')
                    ->color('info')
                    ->visible(fn (Order $r) => $r->status === OrderStatus::New && $r->requires_advance)
                    ->requiresConfirmation()
                    ->modalHeading('Confirm advance payment')
                    ->modalDescription(fn (Order $r) =>
                        'The customer paid the Rs. ' . number_format($r->advanceAmountPkr()) .
                        ' advance (of Rs. ' . number_format($r->total_pkr) . ' total). ' .
                        'Production can start. The balance is collected before dispatch.')
                    ->modalSubmitActionLabel('Yes, advance received')
                    ->action(function (Order $r) {
                        $r->update([
                            'status'           => OrderStatus::Confirmed,
                            'payment_status'   => PaymentStatus::PartialAdvance,
                            'advance_paid_pkr' => $r->advanceAmountPkr(),
                            'confirmed_at'     => now(),
                        ]);
                        try {
                            Mail::to($r->customer_email)->send(new PaymentVerified($r));
                        } catch (\Throwable $e) {
                            \Log::error('PaymentVerified mail failed', ['order' => $r->id, 'e' => $e->getMessage()]);
                        }
                        Notification::make()->title('Advance recorded — email sent to customer.')->success()->send();
                    }),

                Actions\Action::make('mark_balance_paid')
                    ->label('Balance received')
                    ->icon('heroicon-o-currency-rupee')
                    ->color('success')
                    ->visible(fn (Order $r) => $r->payment_status === PaymentStatus::PartialAdvance)
                    ->requiresConfirmation()
                    ->modalHeading('Mark balance as paid')
                    ->modalDescription(fn (Order $r) =>
                        'Confirms the customer paid the remaining Rs. ' .
                        number_format($r->total_pkr - (int) $r->advance_paid_pkr) .
                        '. Total received: Rs. ' . number_format($r->total_pkr) . '.')
                    ->modalSubmitActionLabel('Yes, balance received')
                    ->action(function (Order $r) {
                        $r->update([
                            'payment_status'   => PaymentStatus::Paid,
                            'advance_paid_pkr' => $r->total_pkr,
                        ]);
                        Notification::make()->title('Balance recorded — order is fully paid.')->success()->send();
                    }),
                Actions\Action::make('in_production')
                    ->label('In Production')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('primary')
                    ->visible(fn (Order $r) => $r->status === OrderStatus::Confirmed)
                    ->requiresConfirmation()
                    ->modalHeading('Move to In Production')
                    ->modalDescription('This will notify the customer that their set is now being crafted.')
                    ->modalSubmitActionLabel('Yes, start production')
                    ->action(function (Order $r) {
                        $r->update(['status' => OrderStatus::InProduction]);
                        try {
                            Mail::to($r->customer_email)->send(new OrderInProduction($r));
                        } catch (\Throwable $e) {
                            \Log::error('OrderInProduction mail failed', ['order' => $r->id, 'e' => $e->getMessage()]);
                        }
                        Notification::make()->title('Order moved to production — email sent to customer.')->success()->send();
                    }),
                Actions\Action::make('ship')
                    ->label('Mark Shipped')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (Order $r) => $r->status === OrderStatus::InProduction)
                    ->modalHeading('Mark as Shipped')
                    ->modalDescription('Enter the tracking details below. A shipping confirmation email with the tracking number and estimated delivery will be sent to the customer.')
                    ->modalSubmitActionLabel('Ship & notify customer')
                    ->form([
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Tracking number')
                            ->required(),
                        Forms\Components\Select::make('courier')
                            ->label('Courier')
                            ->options([
                                'tcs'      => 'TCS',
                                'leopards' => 'Leopards',
                                'mp'       => 'M&P',
                                'blueex'   => 'BlueEx',
                            ])->required(),
                    ])
                    ->action(function (Order $r, array $data) {
                        $r->update([
                            'status'          => OrderStatus::Shipped,
                            'tracking_number' => $data['tracking_number'],
                            'courier'         => $data['courier'],
                            'shipped_at'      => now(),
                        ]);
                        try {
                            Mail::to($r->customer_email)->send(new OrderShipped($r));
                        } catch (\Throwable $e) {
                            \Log::error('OrderShipped mail failed', ['order' => $r->id, 'e' => $e->getMessage()]);
                        }
                        Notification::make()->title('Order marked as shipped — email sent to customer.')->success()->send();
                    }),
                Actions\Action::make('deliver')
                    ->label('Mark Delivered')
                    ->icon('heroicon-o-home')
                    ->color('success')
                    ->visible(fn (Order $r) => $r->status === OrderStatus::Shipped)
                    ->action(fn (Order $r) => $r->update(['status' => OrderStatus::Delivered, 'delivered_at' => now()]))
                    ->requiresConfirmation(),
                self::recordNailSizesAction(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('confirm_bulk')
                        ->label('Confirm: full payment')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Confirm full payment on selected orders')
                        ->modalDescription('Marks each selected new order as fully paid and confirmed. A confirmation email is sent for each. Orders not in "New" status are skipped.')
                        ->modalSubmitActionLabel('Yes, confirm all')
                        ->action(function ($records) {
                            $confirmed = 0;
                            $skipped   = 0;
                            foreach ($records as $r) {
                                if ($r->status !== OrderStatus::New) {
                                    $skipped++;
                                    continue;
                                }
                                $r->update([
                                    'status'           => OrderStatus::Confirmed,
                                    'payment_status'   => PaymentStatus::Paid,
                                    'advance_paid_pkr' => $r->total_pkr,
                                    'confirmed_at'     => now(),
                                ]);
                                try {
                                    Mail::to($r->customer_email)->send(new PaymentVerified($r));
                                } catch (\Throwable $e) {
                                    \Log::error('PaymentVerified mail failed', ['order' => $r->id, 'e' => $e->getMessage()]);
                                }
                                $confirmed++;
                            }
                            $title = "Confirmed {$confirmed} order" . ($confirmed === 1 ? '' : 's');
                            if ($skipped) {
                                $title .= " · skipped {$skipped} not in New status";
                            }
                            Notification::make()->title($title)->success()->send();
                        }),
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // ── Infolist (view page) ──────────────────────────────────────────────────

    public static function infolist(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            InfoSection::make('Order')
                ->columns(3)
                ->columnSpan(1)
                ->compact()
                ->schema([
                    Infolists\Components\TextEntry::make('order_number')->weight('bold')->copyable(),
                    Infolists\Components\TextEntry::make('status')
                        ->formatStateUsing(fn ($state) => $state->label())
                        ->badge()
                        ->color(fn ($state) => match($state) {
                            OrderStatus::New          => 'gray',
                            OrderStatus::Confirmed    => 'warning',
                            OrderStatus::InProduction => 'primary',
                            OrderStatus::Shipped      => 'info',
                            OrderStatus::Delivered    => 'success',
                            OrderStatus::Cancelled    => 'danger',
                        }),
                    Infolists\Components\TextEntry::make('payment_status')
                        ->formatStateUsing(fn ($state) => $state->label())
                        ->badge()
                        ->color(fn ($state) => match($state) {
                            PaymentStatus::Awaiting       => 'warning',
                            PaymentStatus::Verifying      => 'primary',
                            PaymentStatus::Paid           => 'success',
                            PaymentStatus::PartialAdvance => 'info',
                            PaymentStatus::Refunded       => 'danger',
                        }),
                    Infolists\Components\TextEntry::make('created_at')->dateTime('d M Y, g:ia')->label('Placed'),
                    Infolists\Components\TextEntry::make('payment_method')
                        ->formatStateUsing(fn ($state) => $state?->label() ?? '—'),
                    Infolists\Components\TextEntry::make('total_pkr')
                        ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state))
                        ->label('Total'),
                ]),

            InfoSection::make('Customer')
                ->columns(2)
                ->columnSpan(1)
                ->compact()
                ->schema([
                    Infolists\Components\TextEntry::make('customer_name'),
                    Infolists\Components\TextEntry::make('customer_email')->copyable(),
                    Infolists\Components\TextEntry::make('customer_phone')->copyable(),
                    Infolists\Components\TextEntry::make('city'),
                    Infolists\Components\TextEntry::make('shipping_address')->columnSpanFull(),
                    Infolists\Components\TextEntry::make('notes')->columnSpanFull()->placeholder('—'),
                ]),

            InfoSection::make('Items')
                ->columnSpan(1)
                ->compact()
                ->schema([
                    Infolists\Components\RepeatableEntry::make('items')
                        ->schema([
                            Infolists\Components\TextEntry::make('product_name_snapshot')->label('Product'),
                            Infolists\Components\TextEntry::make('qty')->label('Qty'),
                            Infolists\Components\TextEntry::make('unit_price_pkr')
                                ->label('Unit price')
                                ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state)),
                            Infolists\Components\TextEntry::make('lineTotalPkr')
                                ->label('Line total')
                                ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state)),
                        ])
                        ->columns(4),
                ]),

            InfoSection::make('Shipping & Totals')
                ->columns(3)
                ->columnSpan(1)
                ->compact()
                ->schema([
                    Infolists\Components\TextEntry::make('subtotal_pkr')
                        ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state)),
                    Infolists\Components\TextEntry::make('shipping_pkr')
                        ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state)),
                    Infolists\Components\TextEntry::make('total_pkr')
                        ->label('Total')->weight('bold')
                        ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state)),
                    Infolists\Components\TextEntry::make('tracking_number')->placeholder('—'),
                    Infolists\Components\TextEntry::make('courier')
                        ->formatStateUsing(fn ($state) => $state?->label() ?? '—'),
                ]),

            InfoSection::make('Sizing Photos')
                ->description('Photos uploaded by the customer for nail sizing. Served from the private disk — only logged-in admins can view.')
                ->columnSpanFull()
                ->compact()
                ->schema([
                    Infolists\Components\TextEntry::make('sizing_capture_method')
                        ->label('Capture method')
                        ->formatStateUsing(fn ($state) => $state?->label() ?? '—'),
                    Infolists\Components\RepeatableEntry::make('sizingPhotos')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('photo_type')
                                ->label('Type')
                                ->formatStateUsing(fn ($state) => $state?->label() ?? $state),
                            Infolists\Components\TextEntry::make('viewer_url')
                                ->label('Photo')
                                ->html()
                                ->formatStateUsing(fn ($state) => $state
                                    ? '<a href="' . e($state) . '" target="_blank" rel="noopener">
                                          <img src="' . e($state) . '" alt="Sizing photo" style="max-height:200px;border-radius:8px;display:block">
                                       </a>
                                       <a href="' . e($state) . '" download
                                          class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-500 underline underline-offset-2">
                                          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                          </svg>
                                          Download
                                       </a>'
                                    : '—'
                                ),
                        ])
                        ->columns(2),
                ])
                ->collapsible(),

            InfoSection::make('Payment Proofs')
                ->description('Screenshots or receipts uploaded by the customer. Served from the private disk — only logged-in admins can view.')
                ->columnSpanFull()
                ->compact()
                ->schema([
                    Infolists\Components\RepeatableEntry::make('paymentProofs')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('uploaded_at')
                                ->label('Uploaded')
                                ->dateTime('d M Y, g:ia'),
                            Infolists\Components\TextEntry::make('verified_at')
                                ->label('Verified')
                                ->dateTime('d M Y, g:ia')
                                ->placeholder('Not verified yet'),
                            Infolists\Components\TextEntry::make('viewer_url')
                                ->label('Proof')
                                ->columnSpanFull()
                                ->html()
                                ->formatStateUsing(function ($state, $record) {
                                    if (! $state) {
                                        return '—';
                                    }
                                    $isPdf = ($record->mime_type ?? '') === 'application/pdf';
                                    if ($isPdf) {
                                        return '<a href="' . e($state) . '" target="_blank" rel="noopener"
                                                   class="inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-500 underline underline-offset-2">
                                                   Open PDF receipt
                                                </a>';
                                    }
                                    return '<a href="' . e($state) . '" target="_blank" rel="noopener">
                                              <img src="' . e($state) . '" alt="Payment proof" style="max-height:300px;border-radius:8px;display:block">
                                            </a>
                                            <a href="' . e($state) . '" download
                                               class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:text-primary-500 underline underline-offset-2">
                                              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                              </svg>
                                              Download
                                            </a>';
                                }),
                        ])
                        ->columns(2),
                ])
                ->collapsible(),
        ]);
    }

    // ── Form (edit) ───────────────────────────────────────────────────────────

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Section::make('Order status')->columns(2)->compact()->schema([
                Forms\Components\Select::make('status')
                    ->options(collect(OrderStatus::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()]))
                    ->required(),
                Forms\Components\Select::make('payment_status')
                    ->options(collect(PaymentStatus::cases())->mapWithKeys(fn ($e) => [$e->value => $e->label()]))
                    ->required(),
                Forms\Components\TextInput::make('advance_paid_pkr')
                    ->label('Advance paid (PKR)')
                    ->numeric()->prefix('Rs.')
                    ->helperText('Amount the customer has paid so far. Equal to total = fully paid.'),
                Forms\Components\TextInput::make('tracking_number'),
                Forms\Components\Select::make('courier')
                    ->options(['tcs' => 'TCS', 'leopards' => 'Leopards', 'mp' => 'M&P', 'blueex' => 'BlueEx']),
            ]),

            \Filament\Schemas\Components\Section::make('Customer (snapshot)')
                ->description('Editable in case the customer mistyped at checkout. Original customer record stays linked.')
                ->columns(2)->compact()->schema([
                    Forms\Components\TextInput::make('customer_name')->required()->maxLength(100),
                    Forms\Components\TextInput::make('customer_phone')->required()->maxLength(30),
                    Forms\Components\TextInput::make('customer_email')->required()->email()->maxLength(150),
                ]),

            \Filament\Schemas\Components\Section::make('Shipping address')->columns(2)->compact()->schema([
                Forms\Components\Textarea::make('shipping_address')->required()->rows(2)->columnSpanFull(),
                Forms\Components\TextInput::make('city')->required()->maxLength(100),
                Forms\Components\TextInput::make('postal_code')->maxLength(20),
                Forms\Components\Textarea::make('notes')->label('Order notes')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view'  => Pages\ViewOrder::route('/{record}'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    // ── "Record nail sizes" action — reused on table row and view page header ─

    public static function recordNailSizesAction(): Actions\Action
    {
        return Actions\Action::make('record_sizes')
            ->label('Record nail sizes')
            ->icon('heroicon-o-finger-print')
            ->color('gray')
            ->visible(fn (Order $r) => $r->customer_id !== null)
            ->fillForm(function (Order $r): array {
                $profile = CustomerSizingProfile::where('customer_id', $r->customer_id)
                    ->latest()
                    ->first();

                return $profile ? $profile->only([
                    'size_r_thumb', 'size_r_index', 'size_r_middle', 'size_r_ring', 'size_r_pinky',
                    'size_l_thumb', 'size_l_index', 'size_l_middle', 'size_l_ring', 'size_l_pinky',
                    'notes',
                ]) : [];
            })
            ->form(CustomerResource::nailSizesFormSchema())
            ->action(function (Order $r, array $data): void {
                // Upsert: create profile if none exists, otherwise update the latest one
                $profile = CustomerSizingProfile::updateOrCreate(
                    ['customer_id' => $r->customer_id],
                    array_merge($data, ['source_order_id' => $r->id])
                );

                // Mark the customer as having sizing on file and stamp verified
                $profile->update(['verified_by_admin_at' => now()]);
                Customer::where('id', $r->customer_id)
                    ->update(['has_sizing_on_file' => true]);

                Notification::make()
                    ->title('Nail sizes saved for this customer.')
                    ->success()
                    ->send();
            });
    }
}
