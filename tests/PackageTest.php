<?php

namespace Io238\ISOCountries\Tests;

use Illuminate\Support\Facades\Artisan;
use Io238\ISOCountries\Models\Country;
use Io238\ISOCountries\Models\Currency;
use Io238\ISOCountries\Models\Language;


class PackageTest extends TestCase {

    /** @test */
    public function can_load_config()
    {
        $this->assertNotNull(config('iso-countries'));
    }


    /** @test */
    public function can_seed_database()
    {
        Artisan::call('migrate');

        Artisan::call('countries:seed');

        $this->assertNotNull(Country::first());
        $this->assertNotNull(Language::first());
        $this->assertNotNull(Currency::first());
    }

}
