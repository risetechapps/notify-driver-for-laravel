<?php

namespace RiseTechApps\Notify;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use RiseTechApps\Address\Events\Address\AddressCreateOrUpdateBillingEvent;
use RiseTechApps\Address\Events\Address\AddressCreateOrUpdateDefaultEvent;
use RiseTechApps\Address\Events\Address\AddressCreateOrUpdateDeliveryEvent;
use Illuminate\Support\Facades\Notification;
use RiseTechApps\Notify\Channel\NotifyChannel;

class NotifyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        Notification::extend('notify', function ($app) {
            return new NotifyChannel();
        });

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('notify.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'notify');

        // Register the main class to use with the facade
        $this->app->singleton('notify', function () {
            return new Notify;
        });
    }
}
