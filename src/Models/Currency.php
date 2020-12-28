<?php

namespace Io238\ISOCountries\Models;

use Illuminate\Support\Str;


class Currency extends IsoBaseModel {

    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }


    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }

}
