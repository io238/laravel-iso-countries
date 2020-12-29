<?php

namespace Io238\ISOCountries\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;


class IsoBaseModel extends Model {

    use HasTranslations;


    public $incrementing = false;

    public $timestamps = false;

    public $translatable = ['name'];

    protected $appends = ['slug'];

    protected $hidden = ['pivot'];

    protected $guarded = [];


    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }

}
