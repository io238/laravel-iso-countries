<?php

namespace Io238\ISOCountries\Tests;

use Io238\ISOCountries\Models\Country;


class ModelTest extends TestCase {

    /** @test */
    public function can_create_a_country_model()
    {
        Country::create([
            'id'   => 'XX',
            'name' => 'Test Country',
        ]);

        $this->assertDatabaseHas('countries', [
            'id'   => 'XX',
        ]);
    }

}
