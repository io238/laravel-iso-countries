<?php

namespace Io238\ISOCountries\Models;

use Illuminate\Support\Str;


class Country extends IsoBaseModel {

    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }


    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }


    public function currencies()
    {
        return $this->belongsToMany(Currency::class);
    }


    public function neighbours()
    {
        return $this->belongsToMany(Country::class, 'country_country', 'country_id', 'neighbour_id');
    }

}
