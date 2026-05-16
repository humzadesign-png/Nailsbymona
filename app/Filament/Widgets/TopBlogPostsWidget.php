<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopBlogPostsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Orders needing attention';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->where(function ($q) {
                        $q->where('payment_status', PaymentStatus::Awaiting)
                          ->orWhere('payment_status', PaymentStatus::Verifying)
                          ->orWhere('status', OrderStatus::New);
                    })
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order')
                    ->weight('semibold')
                    ->url(fn (Order $r) => OrderResource::getUrl('edit', ['record' => $r])),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->description(fn (Order $r) => $r->customer_phone),

                Tables\Columns\TextColumn::make('total_pkr')
                    ->label('Total')
                    ->formatStateUsing(fn ($state) => 'Rs. ' . number_format($state)),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Payment')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        PaymentStatus::Awaiting  => 'warning',
                        PaymentStatus::Verifying => 'info',
                        default                  => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state->label()),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        OrderStatus::New       => 'warning',
                        OrderStatus::Confirmed => 'success',
                        default                => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state->label()),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Placed')
                    ->since()
                    ->color('gray'),
            ])
            ->emptyStateIcon('heroicon-o-check-circle')
            ->emptyStateHeading('All caught up!')
            ->emptyStateDescription('No orders are waiting for payment verification or confirmation.')
            ->paginated(false);
    }
}
