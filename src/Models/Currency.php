<?php

namespace Io238\ISOCountries\Models;

class Currency extends IsoBaseModel {

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }

}
