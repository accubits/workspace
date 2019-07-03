<?php

namespace Modules\CRM\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\CRM\Repositories\DefaultLeadFormRepositoryInterface;
use Modules\CRM\Repositories\DefaultLeadForm\DefaultLeadFormRepository;
use Modules\CRM\Repositories\LeadRepositoryInterface;
use Modules\CRM\Repositories\Lead\LeadRepository;
use Modules\CRM\Repositories\LeadFormRepositoryInterface;
use Modules\CRM\Repositories\LeadForm\LeadFormRepository;

class CRMServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            DefaultLeadFormRepositoryInterface::class,
            DefaultLeadFormRepository::class
            );
        $this->app->bind(
            LeadRepositoryInterface::class,
            LeadRepository::class
            );
        $this->app->bind(
            LeadFormRepositoryInterface::class,
            LeadFormRepository::class
            );
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('crm.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'crm'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/crm');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/crm';
        }, \Config::get('view.paths')), [$sourcePath]), 'crm');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/crm');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'crm');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'crm');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
