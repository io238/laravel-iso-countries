<?php

namespace Io238\ISOCountries\Tests\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Io238\ISOCountries\Casts\Country;
use Io238\ISOCountries\Casts\Currency;
use Io238\ISOCountries\Casts\Language;


class TestModel extends Model {

    protected $guarded = [];

    protected $casts = [
        'country'  => Country::class,
        'currency' => Currency::class,
        'language' => Language::class,
    ];

}
