<?php

namespace Io238\ISOCountries\Models\Traits;

use Illuminate\Database\Eloquent\Model;


trait ReadOnlyModel {

    public static function bootReadOnlyModel()
    {
        static::deleting(function (Model $model) {
            return false;
        });

        static::saving(function (Model $model) {
            return false;
        });
    }

}