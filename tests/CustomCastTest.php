<?php

use Io238\ISOCountries\Models\Country;
use Io238\ISOCountries\Models\Currency;
use Io238\ISOCountries\Models\Language;
use Io238\ISOCountries\Tests\Support\Models\TestModel;


it('casts a 2-letter ISO string as Country model', function () {

    $model = TestModel::make([
        'country' => 'LU',
    ]);

    expect($model->country)->toBeInstanceOf(Country::class);

});

it('casts a 2-letter ISO string as Language model', function () {

    $model = TestModel::make([
        'language' => 'en',
    ]);

    expect($model->language)->toBeInstanceOf(Language::class);

});

it('casts a 3-letter ISO string as Currency model', function () {

    $model = TestModel::make([
        'currency' => 'EUR',
    ]);

    expect($model->currency)->toBeInstanceOf(Currency::class);

});
