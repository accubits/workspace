<?php

namespace Modules\SocialModule\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\SocialModule\Repositories\MessageRepositoryInterface;
use Modules\SocialModule\Repositories\Message\MessageRepository;
use Modules\SocialModule\Repositories\ActivityStreamRepositoryInterface;
use Modules\SocialModule\Repositories\ActivityStream\ActivityStreamRepository;
use Modules\SocialModule\Repositories\Announcement\AnnouncementRepository;
use Modules\SocialModule\Repositories\AnnouncementRepositoryInterface;
use Modules\SocialModule\Repositories\Common\CommonRepository;
use Modules\SocialModule\Repositories\CommonRepositoryInterface;
use Modules\SocialModule\Repositories\EventRepositoryInterface;
use Modules\SocialModule\Repositories\Event\EventRepository;
use Modules\SocialModule\Repositories\PollRepositoryInterface;
use Modules\SocialModule\Repositories\Poll\PollRepository;
use Modules\SocialModule\Repositories\AppreciationRepositoryInterface;
use Modules\SocialModule\Repositories\Appreciation\AppreciationRepository;
use Modules\SocialModule\Repositories\CommentsRepositoryInterface;
use Modules\SocialModule\Repositories\Comments\CommentsRepository;
use Modules\SocialModule\Repositories\AppreciationCommentsRepositoryInterface;
use Modules\SocialModule\Repositories\Comments\AppreciationCommentsRepository;
use Modules\SocialModule\Repositories\Comments\EventCommentsRepository;
use Modules\SocialModule\Repositories\EventCommentsRepositoryInterface;
use Modules\SocialModule\Repositories\Comments\PollCommentsRepository;
use Modules\SocialModule\Repositories\PollCommentsRepositoryInterface;

class SocialModuleServiceProvider extends ServiceProvider
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
            CommonRepositoryInterface::class,
            CommonRepository::class
        );
        $this->app->bind(
            MessageRepositoryInterface::class,
            MessageRepository::class
        );
        $this->app->bind(
            ActivityStreamRepositoryInterface::class,
            ActivityStreamRepository::class
        );
        $this->app->bind(
            AnnouncementRepositoryInterface::class,
            AnnouncementRepository::class
        );
        $this->app->bind(
            EventRepositoryInterface::class,
            EventRepository::class
        );
        $this->app->bind(
            PollRepositoryInterface::class,
            PollRepository::class
        );
        $this->app->bind(
            AppreciationRepositoryInterface::class,
            AppreciationRepository::class
        );

        $this->app->bind(
            CommentsRepositoryInterface::class,
            CommentsRepository::class
        );
        $this->app->bind(
            AppreciationCommentsRepositoryInterface::class,
            AppreciationCommentsRepository::class
        );
        $this->app->bind(
            EventCommentsRepositoryInterface::class,
            EventCommentsRepository::class
        );
        $this->app->bind(
            PollCommentsRepositoryInterface::class,
            PollCommentsRepository::class
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
            __DIR__.'/../Config/config.php' => config_path('socialmodule.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'socialmodule'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/socialmodule');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/socialmodule';
        }, \Config::get('view.paths')), [$sourcePath]), 'socialmodule');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/socialmodule');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'socialmodule');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'socialmodule');
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
