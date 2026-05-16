<?php

namespace App\Filament\Widgets;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class OrderStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getColumns(): int | array | null
    {
        return ['default' => 2, 'lg' => 4];
    }

    protected function getStats(): array
    {
        $todayOrders = Order::whereDate('created_at', today())->count();

        $monthRevenue = Order::whereIn('payment_status', [PaymentStatus::Paid, PaymentStatus::PartialAdvance])
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('total_pkr');

        $awaitingPayment = Order::where('payment_status', PaymentStatus::Awaiting)->count();

        $inProduction = Order::where('status', OrderStatus::InProduction)->count();

        // 7-day sparklines
        $dailyOrders = $this->dailyOrderCounts(7);
        $dailyRevenue = $this->dailyRevenue(7);

        return [
            Stat::make('Orders today', $todayOrders)
                ->description($this->weekTrend($dailyOrders) . ' vs last 7 days')
                ->chart($dailyOrders)
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make('Revenue this month', 'Rs. ' . number_format($monthRevenue))
                ->description('Paid + advance orders')
                ->chart($dailyRevenue)
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Awaiting payment', $awaitingPayment)
                ->description($awaitingPayment > 0 ? 'Need verification in Filament' : 'All payments verified')
                ->icon('heroicon-o-clock')
                ->color($awaitingPayment > 0 ? 'warning' : 'gray'),

            Stat::make('In production', $inProduction)
                ->description('Sets being made right now')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('info'),
        ];
    }

    private function dailyOrderCounts(int $days): array
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $data[] = Order::whereDate('created_at', now()->subDays($i))->count();
        }
        return $data;
    }

    private function dailyRevenue(int $days): array
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $data[] = (int) Order::whereIn('payment_status', [PaymentStatus::Paid, PaymentStatus::PartialAdvance])
                ->whereDate('created_at', now()->subDays($i))
                ->sum('total_pkr');
        }
        return $data;
    }

    private function weekTrend(array $daily): string
    {
        $yesterday = $daily[count($daily) - 2] ?? 0;
        $today     = $daily[count($daily) - 1] ?? 0;
        if ($yesterday === 0) return $today > 0 ? '↑' : '—';
        $diff = $today - $yesterday;
        return $diff > 0 ? '↑' . $diff : ($diff < 0 ? '↓' . abs($diff) : '—');
    }
}
