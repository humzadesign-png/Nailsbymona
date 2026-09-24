<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Nails by Mona')
            // Bell in the top bar: new orders + payment proofs land here
            // (alongside phone push). Polls so it updates without a reload.
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->brandLogo(asset('logo-text.svg'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('logo-text.svg'))
            ->colors([
                'primary' => [
                    50  => '247, 243, 252',
                    100 => '238, 229, 247',
                    200 => '220, 203, 237',
                    300 => '196, 168, 223',
                    400 => '175, 138, 212',
                    500 => '191, 164, 206', // #BFA4CE — brand lavender
                    600 => '155, 127, 180',
                    700 => '122, 95, 153',
                    800 => '88, 65, 115',
                    900 => '58, 40, 80',
                    950 => '33, 20, 50',
                ],
            ])
            // Group icons removed (Block 5 follow-up): Filament v4 forbids
            // having icons on BOTH a navigation group AND its items. Each
            // resource now owns its own $navigationIcon, so the groups
            // act as plain labels above their item lists.
            ->navigationGroups([
                NavigationGroup::make('Orders'),
                NavigationGroup::make('Catalogue'),
                NavigationGroup::make('Content'),
                NavigationGroup::make('Customers'),
                NavigationGroup::make('Finance')->collapsible(false),
                NavigationGroup::make('Settings'),
            ])
            ->renderHook(
                'panels::topbar.start',
                fn () => view('filament.topbar-logo'),
            )
            ->renderHook(
                'panels::head.end',
                fn () => view('filament.pwa-head'),
            )
            ->renderHook(
                // Mobile-friendly reorder defaults for every drag-sort table
                // (Products, UGC Photos, FAQs). Adds a long-press delay so
                // the page stays scrollable on phones. See the partial for
                // the SortableJS config it injects.
                'panels::body.end',
                fn () => view('filament.sortable-mobile-tuning'),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                \App\Filament\Widgets\OrderStatsWidget::class,
                \App\Filament\Widgets\RecentOrdersWidget::class,
                \App\Filament\Widgets\OrdersNeedingAttentionWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
