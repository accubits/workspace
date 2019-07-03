<?php

namespace Modules\HrmManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\HrmManagement\Repositories\Calender\CalenderRepository;
use Modules\HrmManagement\Repositories\CalenderInterface;
use Modules\HrmManagement\Repositories\Common\CommonRepository;
use Modules\HrmManagement\Repositories\CommonRepositoryInterface;
use Modules\HrmManagement\Repositories\Leave\LeaveRepository;
use Modules\HrmManagement\Repositories\LeaveInterface;
use Modules\HrmManagement\Repositories\Report\ReportRepository;
use Modules\HrmManagement\Repositories\ReportInterface;
use Modules\HrmManagement\Repositories\Time\TimeRepository;
use Modules\HrmManagement\Repositories\TimeInterface;
use Modules\HrmManagement\Repositories\KraModuleRepositoryInterface;
use Modules\HrmManagement\Repositories\KraModule\KraModuleRepository;
use Modules\HrmManagement\Repositories\TrainingModuleRepositoryInterface;
use Modules\HrmManagement\Repositories\TrainingModule\TrainingModuleRepository;
use Modules\HrmManagement\Repositories\PerformanceRepositoryInterface;
use Modules\HrmManagement\Repositories\Performance\PerformanceRepository;

class HrmManagementServiceProvider extends ServiceProvider
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
            TimeInterface::class,
            TimeRepository::class
        );

        $this->app->bind(
            ReportInterface::class,
            ReportRepository::class
        );

        $this->app->bind(
            CalenderInterface::class,
            CalenderRepository::class
        );

        $this->app->bind(
            CommonRepositoryInterface::class,
            CommonRepository::class
        );

        $this->app->bind(
            LeaveInterface::class,
            LeaveRepository::class
        );

        $this->app->bind(
            KraModuleRepositoryInterface::class,
            KraModuleRepository::class
        );

        $this->app->bind(
            TrainingModuleRepositoryInterface::class,
            TrainingModuleRepository::class
        );
        
        $this->app->bind(
            PerformanceRepositoryInterface::class,
            PerformanceRepository::class
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
            __DIR__.'/../Config/config.php' => config_path('hrmmanagement.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'hrmmanagement'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/hrmmanagement');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/hrmmanagement';
        }, \Config::get('view.paths')), [$sourcePath]), 'hrmmanagement');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/hrmmanagement');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'hrmmanagement');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'hrmmanagement');
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
