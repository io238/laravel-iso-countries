<?php

namespace Io238\ISOCountries\Tests;

use CreateLaravelIsoCountriesTables;
use Illuminate\Support\Facades\Artisan;
use Io238\ISOCountries\Database\Seeders\IsoSeeder;
use Io238\ISOCountries\ISOCountriesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;


abstract class TestCase extends Orchestra {

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('countries:build');
    }


    protected function getPackageProviders($app)
    {
        return [
            ISOCountriesServiceProvider::class,
        ];
    }


    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'iso-countries');
    }

}
