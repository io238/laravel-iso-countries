<?php

namespace Io238\ISOCountries\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Io238\ISOCountries\Models\Country as CountryModel;


class Country implements CastsAttributes {

    /**
     * Cast the given value.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param mixed                               $value
     * @param array                               $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return CountryModel::find(strtoupper($value));
    }


    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string                              $key
     * @param array                               $value
     * @param array                               $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        return strtoupper($value instanceof CountryModel ? $value->id : $value);
    }

}