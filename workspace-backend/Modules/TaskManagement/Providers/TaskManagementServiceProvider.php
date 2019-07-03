<?php

namespace Modules\TaskManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\TaskManagement\Console\CreateRepeatTypeDayCommand;
use Modules\TaskManagement\Repositories\Comment\CommentRepository;
use Modules\TaskManagement\Repositories\CommentRepositoryInterface;
use Modules\TaskManagement\Repositories\ListTaskRepositoryInterface;
use Modules\TaskManagement\Repositories\Task\ListTaskRepository;
use Modules\TaskManagement\Repositories\Task\TaskFilterRepository;
use Modules\TaskManagement\Repositories\Task\TaskRepository;
use Modules\TaskManagement\Repositories\TaskFilterRepositoryInterface;
use Modules\TaskManagement\Repositories\TaskRepositoryInterface;

class TaskManagementServiceProvider extends ServiceProvider
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
            TaskRepositoryInterface::class,
            TaskRepository::class
        );

        $this->app->bind(
            ListTaskRepositoryInterface::class,
            ListTaskRepository::class
        );

        $this->app->bind(
            CommentRepositoryInterface::class,
            CommentRepository::class
        );

        $this->app->bind(
            TaskFilterRepositoryInterface::class,
            TaskFilterRepository::class
        );

        $this->commands([
            CreateRepeatTypeDayCommand::class
        ]);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('taskmanagement.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'taskmanagement'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/taskmanagement');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/taskmanagement';
        }, \Config::get('view.paths')), [$sourcePath]), 'taskmanagement');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/taskmanagement');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'taskmanagement');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'taskmanagement');
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
