<?php

namespace Io238\ISOCountries;

use Illuminate\Support\ServiceProvider;
use Io238\ISOCountries\Commands\Build;


class ISOCountriesServiceProvider extends ServiceProvider {

    public function boot()
    {
        $databaseFile = __DIR__ . '/../data/iso-countries.sqlite';

        if ( ! file_exists($databaseFile)) {
            file_put_contents($databaseFile, '');
        }

        $this->app['config']->set('database.connections.iso', [
            'driver'   => 'sqlite',
            'database' => realpath($databaseFile),
        ]);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                realpath(__DIR__ . '/../config/iso-countries.php') => config_path('iso-countries.php'),
            ], 'config');
        }
    }


    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/iso-countries.php', 'iso-countries');

        $this->commands([
            Build::class,
        ]);
    }

}
