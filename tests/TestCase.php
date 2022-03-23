<?php

namespace Io238\ISOCountries\Tests;

use CreateLaravelIsoCountriesTables;
use Io238\ISOCountries\Database\Seeders\IsoSeeder;
use Io238\ISOCountries\ISOCountriesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;


abstract class TestCase extends Orchestra {

    protected function setUp(): void
    {
        parent::setUp();

        include_once realpath(__DIR__ . '/../database/migrations/create_laravel_iso_countries_tables.php');
        (new CreateLaravelIsoCountriesTables())->up();

        $this->seed(IsoSeeder::class);
    }


    protected function getPackageProviders($app)
    {
        return [
            ISOCountriesServiceProvider::class,
        ];
    }


    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }

}
