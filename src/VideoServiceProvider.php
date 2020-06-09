<?php

namespace Quill\Video;

use Vellum\Module\Quill;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Quill\Video\Listeners\RegisterVideoModule;
use Quill\Video\Listeners\RegisterVideoPermissionModule;
use Quill\Video\Resource\VideoResource;
use App\Resource\Video\VideoRootResource;
use Quill\Video\Models\VideoObserver;

class VideoServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadModuleCommands();
        $this->loadRoutesFrom(__DIR__ . '/routes/video.php');
        $this->loadViewsFrom(__DIR__ . '/views', 'video');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/config/video.php', 'video');

        VideoResource::observe(VideoObserver::class);

        if (class_exists('App\Resource\Video\VideoRootResource')) {
        	VideoRootResource::observe(VideoObserver::class);
        }

        // $this->publishes([
        //     __DIR__ . '/config/video.php' => config_path('video.php'),
        // ], 'video.config');

        // $this->publishes([
        //    __DIR__ . '/views' => resource_path('/vendor/video'),
        // ], 'video.views');

        $this->publishes([
        	__DIR__ . '/database/factories/VideoFactory.php' => database_path('factories/VideoFactory.php'),
            __DIR__ . '/database/seeds/VideoTableSeeder.php' => database_path('seeds/VideoTableSeeder.php'),
        ], 'video.migration');
    }

    public function register()
    {
        Event::listen(Quill::MODULE, RegisterVideoModule::class);
        Event::listen(Quill::PERMISSION, RegisterVideoPermissionModule::class);
    }

    public function loadModuleCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([

            ]);
        }
    }
}
