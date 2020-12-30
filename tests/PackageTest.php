<?php

namespace Io238\ISOCountries\Tests;

use Io238\ISOCountries\Models\Country;
use Io238\ISOCountries\Models\Currency;
use Io238\ISOCountries\Models\Language;


class PackageTest extends TestCase {

    public function can_load_config()
    {
        $this->assertNotNull(config('iso-countries'));
    }


    public function database_was_seeded_with_iso_data()
    {
        $this->assertNotNull(Country::first());
        $this->assertNotNull(Language::first());
        $this->assertNotNull(Currency::first());
    }

}
