<?php

namespace Io238\ISOCountries\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Io238\ISOCountries\Models\Language as LanguageModel;


class Language implements CastsAttributes {

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
        return LanguageModel::find(strtolower($value));
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
        return strtolower($value instanceof LanguageModel ? $value->id : $value);
    }

}