<?php

namespace Io238\ISOCountries\Models;

class Language extends BaseModel {

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return parent::resolveRouteBinding(strtolower($value), $field);
    }

}
