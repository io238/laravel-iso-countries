<?php

namespace Io238\ISOCountries\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Str;


class Currency extends Model {

    use HasTranslations;


    protected $fillable = [];

    protected $appends = ['slug'];

    public $incrementing = false;

    public $timestamps = false;

    public $translatable = ['name'];


    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }


    public function countries()
    {
        return $this->belongsToMany('App\Models\Country');
    }

}
