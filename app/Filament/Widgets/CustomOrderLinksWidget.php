<?php

namespace App\Filament\Widgets;

use App\Enums\CustomOrderStatus;
use App\Filament\Resources\CustomOrderRequestResource;
use App\Models\CustomOrderRequest;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Dashboard row for custom designs quoted over Instagram / WhatsApp.
 * Orders placed through a link also count in OrderStatsWidget — this widget
 * shows the part of the pipeline that lives outside the shop: links sent,
 * links opened, and how much of this month's revenue came from them.
 */
class CustomOrderLinksWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getColumns(): int | array | null
    {
        return ['default' => 2, 'lg' => 3];
    }

    protected function getStats(): array
    {
        $pending = CustomOrderRequest::where('status', CustomOrderStatus::Pending)
            ->where('expires_at', '>', now());

        $waiting = (clone $pending)->count();
        $opened  = (clone $pending)->whereNotNull('opened_at')->count();

        $ordersThisMonth = Order::where('is_custom', true)
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();

        $revenueThisMonth = (int) Order::where('is_custom', true)
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('advance_paid_pkr');

        return [
            Stat::make('Custom links waiting', $waiting)
                ->description($waiting > 0
                    ? $opened . ' of them opened by the customer'
                    : 'No links waiting on a customer')
                ->icon('heroicon-o-link')
                ->color($waiting > 0 ? 'warning' : 'gray')
                ->url(CustomOrderRequestResource::getUrl('index')),

            Stat::make('Custom orders this month', $ordersThisMonth)
                ->description('Placed through a design link')
                ->icon('heroicon-o-sparkles')
                ->color($ordersThisMonth > 0 ? 'primary' : 'gray'),

            Stat::make('Custom revenue this month', 'Rs. ' . number_format($revenueThisMonth))
                ->description('Cash received on custom orders')
                ->icon('heroicon-o-banknotes')
                ->color($revenueThisMonth > 0 ? 'success' : 'gray'),
        ];
    }
}
