<?php

namespace Io238\ISOCountries\Commands;

use Illuminate\Console\Command;

class ISOCountriesCommand extends Command
{
    public $signature = 'laravel-iso-countries';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
