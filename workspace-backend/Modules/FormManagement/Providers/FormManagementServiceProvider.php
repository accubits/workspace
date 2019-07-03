<?php

namespace Modules\FormManagement\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\FormManagement\Repositories\FormCreatorRepositoryInterface;
use Modules\FormManagement\Repositories\FormCreator\FormCreatorRepository;
use Modules\FormManagement\Repositories\FormFetcherRepositoryInterface;
use Modules\FormManagement\Repositories\FormFetcher\FormFetcherRepository;
use Modules\FormManagement\Repositories\FormSubmissionRepositoryInterface;
use Modules\FormManagement\Repositories\FormSubmissionResponseRepositoryInterface;
use Modules\FormManagement\Repositories\FormSubmission\FormSubmissionRepository;
use Modules\FormManagement\Repositories\FormSubmissionResponse\FormSubmissionResponseRepository;
class FormManagementServiceProvider extends ServiceProvider
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
        $this->app->bind(FormCreatorRepositoryInterface::class,
            FormCreatorRepository::class);

        $this->app->bind(FormFetcherRepositoryInterface::class,
            FormFetcherRepository::class);

        $this->app->bind(FormSubmissionRepositoryInterface::class,
            FormSubmissionRepository::class);

        $this->app->bind(FormSubmissionResponseRepositoryInterface::class,
            FormSubmissionResponseRepository::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('formmanagement.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'formmanagement'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/formmanagement');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/formmanagement';
        }, \Config::get('view.paths')), [$sourcePath]), 'formmanagement');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/formmanagement');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'formmanagement');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'formmanagement');
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
