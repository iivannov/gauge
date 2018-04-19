<?php

namespace Iivannov\Gauge\Platform\Laravel\Providers;

use Iivannov\Gauge\Drivers\FileDriver;
use Iivannov\Gauge\Drivers\HttpDriver;
use Iivannov\Gauge\Drivers\JsonFileDriver;
use Iivannov\Gauge\Drivers\RuntimeDriver;
use Iivannov\Gauge\State\DisabledStateResolver;
use Iivannov\Gauge\State\FileStateResolver;
use Iivannov\Gauge\State\UrlStateResolver;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\ServiceProvider;


class GaugeServiceProvider extends ServiceProvider
{

    public function boot(\Illuminate\Database\Connection $connection, \Illuminate\Contracts\Events\Dispatcher $dispatcher)
    {
        // Register assets needed to be published
        $this->publish();

        if ($this->isApplicationRunningInConsole()) {
            $this->bootInConsoleMode();
        } else {
            $this->bootInHttpMode($connection, $dispatcher);
        }
    }


    public function register()
    {
        $settings = $this->app->make(\Illuminate\Contracts\Config\Repository::class)->get('gauge');

        $this->registerStateDriver($settings);

        $this->registerLogDriver($settings);
    }

    private function registerStateDriver($settings)
    {
        $this->app->bind(\Iivannov\Gauge\Contracts\StateResolver::class, function () use ($settings) {

            if(!isset($settings['state'])) {
                throw new \RuntimeException('Missing configuration for "state" in config/gauge.php');
            }

            switch ($settings['state']) {
                case 'url' :
                    return new UrlStateResolver();
                case 'file' :
                    return new FileStateResolver(storage_path('framework'), 'gauge');
                default:
                    return new DisabledStateResolver();            }
        });
    }

    private function registerLogDriver($settings)
    {
        $this->app->bind(\Iivannov\Gauge\Contracts\LogDriver::class, function () use ($settings) {

            switch ($settings['driver']) {

                case 'runtime' :
                    return new RuntimeDriver();

                case 'file' :
                    /** @var \Illuminate\Contracts\Filesystem\Factory $filesystem */
                    $filesystem = $this->app->make(\Illuminate\Contracts\Filesystem\Factory::class);
                    /** @var  \Illuminate\Http\Request $request */
                    $request = $this->app->make(\Illuminate\Http\Request::class);

                    if($settings['drivers']['file']['disk']) {
                        return new FileDriver($request, $filesystem->disk($settings['drivers']['file']['disk']));
                    }

                    return new FileDriver($request, $filesystem->disk());

                case 'json' :
                    /** @var \Illuminate\Contracts\Filesystem\Factory $filesystem */
                    $filesystem = $this->app->make(\Illuminate\Contracts\Filesystem\Factory::class);
                    /** @var  \Illuminate\Http\Request $request */
                    $request = $this->app->make(\Illuminate\Http\Request::class);

                    if($settings['drivers']['json']['disk']) {
                        return new JsonFileDriver($request, $filesystem->disk($settings['drivers']['json']['disk']));
                    }

                    return new JsonFileDriver($request, $filesystem->disk());

                default:
                    throw new \RuntimeException('Missing or invalid Gauge configuration in config/gauge.php');
            }

        });
    }

    private function isApplicationRunningInConsole()
    {
        if (method_exists($this->app, 'runningInConsole')) {
            return $this->app->runningInConsole();
        }

        return false;
    }

    private function publish()
    {
        $this->publishes([__DIR__ . '/../../config/laravel.php' => config_path('gauge.php')], 'gauge');
    }


    private function bootInConsoleMode()
    {
        $this->commands([
            \Iivannov\Gauge\Commands\Toggle::class,
        ]);
    }

    private function bootInHttpMode(\Illuminate\Database\Connection $connection, \Illuminate\Contracts\Events\Dispatcher $dispatcher)
    {
        // Enable the query log for the currently used DB connection
        $connection->enableQueryLog();

        // Wait for kernel to handle the request and listen for the event
        $dispatcher->listen(\Illuminate\Foundation\Http\Events\RequestHandled::class, \Iivannov\Gauge\Listeners\HandleQueryLog::class);
    }

}
