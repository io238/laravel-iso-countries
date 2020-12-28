<?php

namespace Io238\ISOCountries;

use Illuminate\Support\ServiceProvider;
use Io238\ISOCountries\Commands\ISOCountriesCommand;

class ISOCountriesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-iso-countries.php' => config_path('laravel-iso-countries.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../resources/views' => base_path('resources/views/vendor/laravel-iso-countries'),
            ], 'views');

            $migrationFileName = 'create_laravel_iso_countries_table.php';
            if (! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }

            $this->commands([
                ISOCountriesCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-iso-countries');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-iso-countries.php', 'laravel-iso-countries');
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
