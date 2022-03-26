<?php

namespace Io238\ISOCountries\Models;

class Country extends BaseModel {

    protected $casts = [
        'borders'        => 'array',
        'currency_codes' => 'array',
        'language_codes' => 'array',
        'is_independent' => 'boolean',
        'is_un_member'   => 'boolean',
        'is_eu_member'   => 'boolean',
    ];


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


    public function resolveRouteBinding($value, $field = null)
    {
        return parent::resolveRouteBinding(strtoupper($value), $field);
    }

}
