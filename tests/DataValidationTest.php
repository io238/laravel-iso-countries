<?php

use Io238\ISOCountries\Models\Country;


// Check existence of certain countries and their relations

it('contains correct data for United States', function () {

    $attributes = [
        'id'       => 'US',
        'alpha3'   => 'USA',
        'name->en' => 'United States',
    ];

    $this->assertDatabaseHas('countries', $attributes);

    $model = Country::query()->where($attributes)->first();

    expect($model->currencies->pluck('id')->toArray())->toBe(['USD']);
    expect($model->languages->pluck('id')->toArray())->toBe(['en']);
    expect($model->neighbours->pluck('id')->toArray())->toBe(['CA', 'MX']);

});

it('contains correct data for UK', function () {

    $attributes = [
        'id'       => 'GB',
        'alpha3'   => 'GBR',
        'name->en' => 'United Kingdom',
    ];

    $this->assertDatabaseHas('countries', $attributes);

    $model = Country::query()->where($attributes)->first();

    expect($model->currencies->pluck('id')->toArray())->toBe(['GBP']);
    expect($model->languages->pluck('id')->toArray())->toBe(['en']);
    expect($model->neighbours->pluck('id')->toArray())->toBe(['IE']);

});

it('contains does not have "China" in its common name, if it is not China', function () {

    $countriesWithChinaInItsName = Country::query()
        ->where('name', 'LIKE', '%China%')
        ->where('id', 'NOT LIKE', 'CN')
        ->count();

    expect($countriesWithChinaInItsName)->toBe(0);

})->skip();

it('contains correct model relationships', function () {

    $country = Country::find('LU');

    $this->assertEqualsCanonicalizing($country->languages->pluck('id')->toArray(), ['lb', 'de', 'fr']);
    $this->assertEqualsCanonicalizing($country->currencies->pluck('id')->toArray(), ['EUR']);
    $this->assertEqualsCanonicalizing($country->neighbours->pluck('id')->toArray(), ['DE', 'FR', 'BE']);

});

it('it has the expected amount of countries')->assertDatabaseCount('countries', 250);

it('it has the expected amount of languages')->assertDatabaseCount('languages', 184);

it('it has the expected amount of currencies')->assertDatabaseCount('currencies', 139);