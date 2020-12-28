<?php

namespace Io238\ISOCountries\Commands;

use Io238\ISOCountries\Database\Seeders\IsoSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;


class ISOCountriesCommand extends Command {

    public $signature = 'countries:seed';

    public $description = 'Seeds (or updates) all country, language, and currency info to the DB.';


    public function handle()
    {
        Artisan::call('db:seed', [
            '--force' => true,
            '--class' => IsoSeeder::class,
        ]);

        $this->comment('All done');
    }

}
