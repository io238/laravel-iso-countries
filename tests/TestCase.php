<?php

namespace Io238\ISOCountries\Tests;

use Io238\ISOCountries\ISOCountriesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;


class TestCase extends Orchestra {

    public function setUp(): void
    {
        parent::setUp();
    }


    protected function getPackageProviders($app)
    {
        return [
            ISOCountriesServiceProvider::class,
        ];
    }


    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        include_once realpath(__DIR__ . '/../database/migrations/create_laravel_iso_countries_tables.php.stub');
        (new \CreateLaravelIsoCountriesTables())->up();
    }

}
