<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

/**
 * Dashboard widget surfacing orders that need Mona's hand:
 *   • Status: New (not yet confirmed)
 *   • Payment: Awaiting (no proof yet) or Verifying (proof uploaded)
 *
 * Renamed from TopBlogPostsWidget in Block 5 — class name and file name
 * now match the actual job. CLAUDE.md §21 originally specified a separate
 * top-blog-posts widget; that's a future build, not this one.
 */
class OrdersNeedingAttentionWidget extends BaseWidget
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
                        PaymentStatus::Awaiting       => 'warning',
                        PaymentStatus::Verifying      => 'primary',
                        PaymentStatus::PartialAdvance => 'info',
                        PaymentStatus::Paid           => 'success',
                        PaymentStatus::Refunded       => 'danger',
                        default                       => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state instanceof PaymentStatus ? $state->label() : (string) $state),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        OrderStatus::New          => 'warning',
                        OrderStatus::Confirmed    => 'success',
                        OrderStatus::InProduction => 'primary',
                        OrderStatus::Shipped      => 'info',
                        OrderStatus::Delivered    => 'success',
                        OrderStatus::Cancelled    => 'danger',
                        default                   => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => $state instanceof OrderStatus ? $state->label() : (string) $state),

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
