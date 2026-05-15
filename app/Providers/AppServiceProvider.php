<?php

namespace App\Providers;

use App\Settings\StoreSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Mail\MailManager;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share StoreSettings globally so all public views can use $settings
        View::composer('*', function ($view) {
            static $settings = null;
            $settings ??= app(StoreSettings::class);
            $view->with('settings', $settings);
        });

        // Register Brevo as a custom Laravel mailer transport (uses HTTPS API, not SMTP)
        $this->app->resolving(MailManager::class, function (MailManager $manager) {
            $manager->extend('brevo', function (array $config) {
                $factory = new BrevoTransportFactory();
                return $factory->create(new Dsn(
                    'brevo+api',
                    'default',
                    $config['key'] ?? config('services.brevo.key'),
                ));
            });
        });
    }
}
