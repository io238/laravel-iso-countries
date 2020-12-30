<?php

namespace Io238\ISOCountries\Tests\Models;

use Io238\ISOCountries\Models\Country;
use Io238\ISOCountries\Tests\TestCase;


class CountryTest extends TestCase {

    /** @test */
    public function it_can_create_a_model()
    {
        Country::create([
            'id'   => 'XX',
            'name' => 'Test Country',
        ]);

        $this->assertDatabaseCount('countries', 1);
    }

}
