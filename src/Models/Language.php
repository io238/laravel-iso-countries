<?php

namespace Io238\ISOCountries\Models;

use Illuminate\Support\Str;


class Language extends IsoBaseModel {

    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }


    public function countries()
    {
        return $this->belongsToMany('Io238\ISOCountries\Country');
    }

}
