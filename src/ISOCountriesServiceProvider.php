<?php

namespace Io238\ISOCountries;

use Illuminate\Support\ServiceProvider;


class ISOCountriesServiceProvider extends ServiceProvider {

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                realpath(__DIR__ . '/../config/iso-countries.php') => config_path('iso-countries.php'),
            ], 'config');

            $migrationFileName = 'create_laravel_iso_countries_tables.php';
            if ( ! $this->migrationFileExists($migrationFileName)) {
                $this->publishes([
                    realpath(__DIR__ . "/../database/migrations/{$migrationFileName}.stub") => database_path('migrations/' . date('Y_m_d_His', time()) . '_' . $migrationFileName),
                ], 'migrations');
            }
        }
    }


    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/iso-countries.php', 'iso-countries');
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
