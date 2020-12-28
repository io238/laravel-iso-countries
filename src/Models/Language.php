<?php

namespace Io238\ISOCountries\Models;

class Language extends IsoBaseModel {

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }

}
