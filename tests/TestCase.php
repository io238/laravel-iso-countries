<?php

namespace Io238\ISOCountries\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Io238\ISOCountries\Database\Seeders\IsoSeeder;
use Io238\ISOCountries\ISOCountriesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;


class TestCase extends Orchestra {

    use DatabaseMigrations;


    public function setUp(): void
    {
        parent::setUp();

        // Load and run migration (stub)
        include_once realpath(__DIR__ . '/../database/migrations/create_laravel_iso_countries_tables.php.stub');
        (new \CreateLaravelIsoCountriesTables())->up();

    }


    protected function getPackageProviders($app)
    {
        return [
            ISOCountriesServiceProvider::class,
        ];
    }


    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }

}
