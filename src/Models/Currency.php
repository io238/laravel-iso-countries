<?php

namespace Io238\ISOCountries\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Currency extends Model {

    use IsoModelTrait;


    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }


    public function countries()
    {
        return $this->belongsToMany('Io238\ISOCountries\Country');
    }

}
