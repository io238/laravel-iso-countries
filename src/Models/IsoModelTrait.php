<?php

namespace Io238\ISOCountries\Models;

use Spatie\Translatable\HasTranslations;


trait IsoModelTrait {

    use HasTranslations;


    public $incrementing = false;

    public $timestamps = false;

    public $translatable = ['name'];

    protected $appends = ['slug'];

    protected $guarded = [];

}
