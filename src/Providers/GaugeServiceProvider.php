<?php

namespace Iivannov\Gauge\Providers;

use Iivannov\Gauge\Drivers\FileDriver;
use Iivannov\Gauge\Drivers\HttpDriver;
use Iivannov\Gauge\Drivers\JsonFileDriver;
use Iivannov\Gauge\Drivers\RuntimeDriver;
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

        $this->app->bind(\Iivannov\Gauge\Contracts\StateResolver::class, function () use ($settings) {

            switch ($settings['state']) {
                case 'url' :
                    return new UrlStateResolver();
                case 'file' :
                    return new FileStateResolver();
                default:
                    throw new \RuntimeException('Missing or invalid Gauge configuration in config/gauge.php');
            }
        });

        $this->app->bind(\Iivannov\Gauge\Contracts\LogDriver::class, function () use ($settings) {

            switch ($settings['driver']) {

                case 'runtime' :
                    return new RuntimeDriver($this->app->make(\Illuminate\Http\Request::class));

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

                    if($settings['drivers']['file']['disk']) {
                        return new JsonFileDriver($request, $filesystem->disk($settings['drivers']['file']['disk']));
                    }

                    return new JsonFileDriver($request, $filesystem->disk());

                case 'http' :

                    if(!isset($settings['drivers']['http']['url']) || !isset($settings['drivers']['http']['token']) || !$settings['drivers']['http']['url'] || !$settings['drivers']['http']['token']) {
                        throw new \RuntimeException('Missing Gauge Authentication configuration in config/gauge.php');
                    }

                    /** @var  \Illuminate\Http\Request $request */
                    $request = $this->app->make(\Illuminate\Http\Request::class);

                    return new HttpDriver($request, new Client(), $settings['drivers']['http']['url'], $settings['drivers']['http']['token']);

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


    private function bootInHttpMode(\Illuminate\Database\Connection $connection, \Illuminate\Contracts\Events\Dispatcher $dispatcher)
    {
        // Enable the query log for the currently used DB connection
        $connection->enableQueryLog();

        // Wait for kernel to handle the request and listen for the event
        $dispatcher->listen(\Illuminate\Foundation\Http\Events\RequestHandled::class, \Iivannov\Gauge\Listeners\HandleQueryLog::class);
    }


    private function bootInConsoleMode()
    {
        $this->commands([
            \Iivannov\Gauge\Commands\Toggle::class,
        ]);
    }


    private function publish()
    {
        $this->publishes([__DIR__ . '/../../config/laravel.php' => config_path('gauge.php')], 'gauge');
    }
}
