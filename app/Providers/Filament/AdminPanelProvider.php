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
            ->navigationGroups([
                NavigationGroup::make('Orders')->icon('heroicon-o-shopping-bag'),
                NavigationGroup::make('Catalogue')->icon('heroicon-o-sparkles'),
                NavigationGroup::make('Content')->icon('heroicon-o-pencil-square'),
                NavigationGroup::make('Customers')->icon('heroicon-o-users'),
                NavigationGroup::make('Settings')->icon('heroicon-o-cog-6-tooth'),
            ])
            ->renderHook(
                'panels::topbar.start',
                fn () => view('filament.topbar-logo'),
            )
            ->renderHook(
                'panels::head.end',
                fn () => view('filament.pwa-head'),
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
                \App\Filament\Widgets\TopBlogPostsWidget::class,
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
