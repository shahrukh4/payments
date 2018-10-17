<?php

namespace Shahrukh\Payments;

use Illuminate\Support\ServiceProvider;
use Shahrukh\Payments\Repositories\PayPal;
use Shahrukh\Payments\Repositories\StripePay;

/*
|--------------------------------------------------
| Service provider for handling the control of Payment gateways
|--------------------------------------------------
| Written By- Shahrukh Anwar(17-10-2018)
*/
class PaymentsServiceProvider extends ServiceProvider{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'shahrukh');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'shahrukh');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(){
        $this->mergeConfigFrom(__DIR__.'/../config/payments.php', 'payments');

        // Register the service the package provides.
        /*$this->app->singleton('payments', function ($app) {
            return new Payments;
        });*/

        $this->app->bind(Payment::class, function ($app) {
            //return new StripePay();
            return new PayPal();
        });

        $this->app->alias(Payment::class, 'Payment');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'Payment'
            //'stripe', 
            //'stripe.config',
        ];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/payments.php' => config_path('payments.php'),
        ], 'payments.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/shahrukh'),
        ], 'payments.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/shahrukh'),
        ], 'payments.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/shahrukh'),
        ], 'payments.views');*/

        // Registering package commands.
        // $this->commands([]);
    }

    /**
     * Register the Stripe API class.
     *
     * @return void
     */
    protected function registerStripe(){
        $this->app->singleton('stripe', function ($app) {
            $config     = $app['config']->get('services.stripe');
            $secret     = isset($config['secret']) ? $config['secret'] : null;
            $version    = isset($config['version']) ? $config['version'] : null;

            return new Stripe($secret, $version);
        });

        $this->app->alias('stripe', 'Cartalyst\Stripe\Stripe');
    }

    /**
     * Register the config class.
     *
     * @return void
     */
    protected function registerConfig(){
        $this->app->singleton('stripe.config', function ($app) {
            return $app['stripe']->getConfig();
        });

        $this->app->alias('stripe.config', 'Cartalyst\Stripe\Config');
        $this->app->alias('stripe.config', 'Cartalyst\Stripe\ConfigInterface');
    }
}
