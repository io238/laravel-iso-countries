<?php

namespace Io238\ISOCountries\Models;

class Currency extends BaseModel {

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return parent::resolveRouteBinding(strtoupper($value), $field);
    }

}
