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
        return $this->belongsToMany('Io238\ISOCountries\Language');
    }


    public function currencies()
    {
        return $this->belongsToMany('Io238\ISOCountries\Currency');
    }


    public function neighbours()
    {
        return $this->belongsToMany('Io238\ISOCountries\Country', 'country_country', 'country_id', 'neighbour_id');
    }

}
