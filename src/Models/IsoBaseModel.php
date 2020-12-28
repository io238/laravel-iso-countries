<?php

namespace Io238\ISOCountries\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;


class IsoBaseModel extends Model {

    use HasTranslations;


    public $incrementing = false;

    public $timestamps = false;

    public $translatable = ['name'];

    protected $appends = ['slug'];

    protected $guarded = [];

}
