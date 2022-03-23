<?php

use Io238\ISOCountries\Models\Country;
use Io238\ISOCountries\Models\Currency;
use Io238\ISOCountries\Models\Language;


it('can create a new country', function () {

    Country::query()->create([
        'id'      => 'XX',
        'alpha3'  => 'XXX',
        'numeric' => 999,
        'name'    => 'Test Country',
    ]);

    $this->assertDatabaseHas('countries', ['id' => 'XX']);
});

it('can create a new language', function () {

    Language::query()->create([
        'id'          => 'xx',
        'iso639_2'    => 'xxx',
        'name'        => 'Test Language',
        'native_name' => 'Test Language',
    ]);

    $this->assertDatabaseHas('languages', ['id' => 'xx']);
});

it('can create a new currency', function () {

    Currency::query()->create([
        'id'            => 'XXX',
        'name'          => 'Test Currency',
        'name_plural'   => 'Test Currencies',
        'symbol'        => '✗',
        'symbol_native' => '✗',
    ]);

    $this->assertDatabaseHas('currencies', ['id' => 'XXX']);
});

it('can use a custom table name', function () {

    // TODO

});