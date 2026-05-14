<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Recent orders';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order')->weight('semibold')->copyable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->description(fn (Order $r) => $r->customer_phone),

                Tables\Columns\TextColumn::make('total_pkr')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state)),

                Tables\Columns\TextColumn::make('status')
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
                        PaymentStatus::Refunded       => 'danger',
                        default                       => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state instanceof PaymentStatus ? $state->label() : $state),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Placed')->dateTime('d M, g:ia'),
            ])
            ->actions([
                Actions\Action::make('view')
                    ->url(fn (Order $r) => OrderResource::getUrl('view', ['record' => $r]))
                    ->icon('heroicon-m-eye'),
            ])
            ->paginated(false);
    }
}
