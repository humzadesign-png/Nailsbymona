<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayOrders = Order::whereDate('created_at', today())->count();

        $weekRevenue = Order::whereIn('payment_status', [PaymentStatus::Paid, PaymentStatus::PartialAdvance])
            ->where('created_at', '>=', now()->startOfWeek())
            ->sum('total_pkr');

        $awaitingPayment = Order::where('payment_status', PaymentStatus::Awaiting)->count();

        $inProduction = Order::where('status', OrderStatus::InProduction)->count();

        return [
            Stat::make('Orders today', $todayOrders)
                ->description('New orders placed today')
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make('Revenue this week', 'Rs. ' . number_format($weekRevenue))
                ->description('Paid + partial advance orders')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Awaiting payment', $awaitingPayment)
                ->description('Orders not yet verified')
                ->icon('heroicon-o-clock')
                ->color($awaitingPayment > 0 ? 'warning' : 'gray'),

            Stat::make('In production', $inProduction)
                ->description('Sets being made right now')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('info'),
        ];
    }
}
