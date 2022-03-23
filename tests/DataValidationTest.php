<?php

// Check existence of certain countries and their relations

it('seeds correct data for United States', function () {

    $attributes = [
        'id'       => 'US',
        'alpha3'   => 'USA',
        'name->en' => 'United States',
    ];

    $this->assertDatabaseHas('countries', $attributes);

    $model = \Io238\ISOCountries\Models\Country::query()->where($attributes)->first();

    expect($model->currencies->pluck('id')->toArray())->toBe(['USD']);
    expect($model->languages->pluck('id')->toArray())->toBe(['en']);
    expect($model->neighbours->pluck('id')->toArray())->toBe(['CA', 'MX']);

});

it('seeds correct data for Hong Kong', function () {

    $attributes = [
        'id'       => 'HK',
        'alpha3'   => 'HKG',
        'name->en' => 'Hong Kong',
    ];

    $this->assertDatabaseHas('countries', $attributes);

    $model = \Io238\ISOCountries\Models\Country::query()->where($attributes)->first();

    expect($model->currencies->pluck('id')->toArray())->toBe(['HKD']);
    expect($model->languages->pluck('id')->toArray())->toBe(['en', 'zh']);
    expect($model->neighbours->pluck('id')->toArray())->toBe(['CN']);

});

it('seeds correct data for UK', function () {

    $attributes = [
        'id'       => 'GB',
        'alpha3'   => 'GBR',
        'name->en' => 'United Kingdom',
    ];

    $this->assertDatabaseHas('countries', $attributes);

    $model = \Io238\ISOCountries\Models\Country::query()->where($attributes)->first();

    expect($model->currencies->pluck('id')->toArray())->toBe(['GBP']);
    expect($model->languages->pluck('id')->toArray())->toBe(['en']);
    expect($model->neighbours->pluck('id')->toArray())->toBe(['IE']);

});