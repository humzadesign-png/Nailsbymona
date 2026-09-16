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

        // Revenue = cash actually received (advance_paid_pkr), NOT promised
        // totals. Paid orders contribute full amount; PartialAdvance orders
        // contribute only their deposit; Awaiting/Verifying contribute zero.
        // Matches FinanceOverview's calculation so the two dashboards always
        // agree (M9 from the audit).
        $monthRevenue = (int) Order::where('created_at', '>=', now()->startOfMonth())
            ->sum('advance_paid_pkr');

        // SLA queue includes both Awaiting (no proof yet) and Verifying
        // (proof uploaded, needs Mona's review) — the unified bucket Mona
        // works from each morning. Cancelled orders are excluded.
        $awaitingPayment = Order::awaitingPayment()->count();

        $inProduction = Order::where('status', OrderStatus::InProduction)->count();

        // 7-day sparklines — one grouped query each instead of 7 separate sums.
        $dailyOrders  = $this->dailyOrderCounts(7);
        $dailyRevenue = $this->dailyRevenue(7);

        return [
            Stat::make('Orders today', $todayOrders)
                ->description($this->dayOnDayTrend($dailyOrders))
                ->chart($dailyOrders)
                ->icon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make('Revenue this month', 'Rs. ' . number_format($monthRevenue))
                ->description('Cash received (advance + full payments)')
                ->chart($dailyRevenue)
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Awaiting payment', $awaitingPayment)
                ->description($awaitingPayment > 0 ? 'Awaiting + Verifying' : 'All payments verified')
                ->icon('heroicon-o-clock')
                ->color($awaitingPayment > 0 ? 'warning' : 'gray'),

            Stat::make('In production', $inProduction)
                ->description('Sets being made right now')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('info'),
        ];
    }

    /** Order counts per day over the last N days (oldest → today). One SQL. */
    private function dailyOrderCounts(int $days): array
    {
        $start = now()->subDays($days - 1)->startOfDay();
        $rows  = Order::query()
            ->selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $start)
            ->groupBy('d')
            ->pluck('c', 'd')
            ->all();
        return $this->fillDailyBuckets($rows, $days);
    }

    /** Cash received per day (advance_paid_pkr) over the last N days. One SQL. */
    private function dailyRevenue(int $days): array
    {
        $start = now()->subDays($days - 1)->startOfDay();
        $rows  = Order::query()
            ->selectRaw('DATE(created_at) as d, SUM(advance_paid_pkr) as v')
            ->where('created_at', '>=', $start)
            ->groupBy('d')
            ->pluck('v', 'd')
            ->all();
        return $this->fillDailyBuckets($rows, $days);
    }

    /**
     * Convert a [date_string => value] map into an N-element array
     * indexed oldest-to-newest, with missing days as 0.
     */
    private function fillDailyBuckets(array $rows, int $days): array
    {
        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $key    = now()->subDays($i)->toDateString();
            $data[] = (int) ($rows[$key] ?? 0);
        }
        return $data;
    }

    /** Today vs yesterday — short label for the stat card description. */
    private function dayOnDayTrend(array $daily): string
    {
        $yesterday = $daily[count($daily) - 2] ?? 0;
        $today     = $daily[count($daily) - 1] ?? 0;
        if ($yesterday === 0) {
            return $today > 0 ? '↑ from 0 yesterday' : 'No orders yesterday either';
        }
        $diff = $today - $yesterday;
        if ($diff === 0) return 'Same as yesterday';
        return ($diff > 0 ? '↑ ' : '↓ ') . abs($diff) . ' vs yesterday';
    }
}
