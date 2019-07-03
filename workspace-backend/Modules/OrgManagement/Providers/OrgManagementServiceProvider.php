<?php

namespace Modules\OrgManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\OrgManagement\Repositories\Employee\EmployeePermissionRepository;
use Modules\OrgManagement\Repositories\Employee\EmployeeRepository;
use Modules\OrgManagement\Repositories\EmployeePermissionRepositoryInterface;
use Modules\OrgManagement\Repositories\EmployeeRepositoryInterface;
use Modules\OrgManagement\Repositories\OrganizationRepositoryInterface;
use Modules\OrgManagement\Repositories\Organization\OrganizationRepository;
use Modules\OrgManagement\Repositories\DepartmentRepositoryInterface;
use Modules\OrgManagement\Repositories\Department\DepartmentRepository;
use Modules\OrgManagement\Repositories\LicenseRepositoryInterface;
use Modules\OrgManagement\Repositories\License\LicenseRepository;

class OrgManagementServiceProvider extends ServiceProvider
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
            EmployeeRepositoryInterface::class,
            EmployeeRepository::class
        );

        $this->app->bind(
            EmployeePermissionRepositoryInterface::class,
            EmployeePermissionRepository::class
        );

        $this->app->bind(
            OrganizationRepositoryInterface::class,
            OrganizationRepository::class
        );

        $this->app->bind(
            DepartmentRepositoryInterface::class,
            DepartmentRepository::class
        );
        
        $this->app->bind(
            LicenseRepositoryInterface::class,
            LicenseRepository::class
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
            __DIR__.'/../Config/config.php' => config_path('orgmanagement.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'orgmanagement'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/orgmanagement');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/orgmanagement';
        }, \Config::get('view.paths')), [$sourcePath]), 'orgmanagement');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/orgmanagement');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'orgmanagement');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'orgmanagement');
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
