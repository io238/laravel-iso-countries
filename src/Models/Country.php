<?php

namespace Io238\ISOCountries\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Str;


class Country extends Model {

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


    public function languages()
    {
        return $this->belongsToMany('App\Models\Language');
    }


    public function currencies()
    {
        return $this->belongsToMany('App\Models\Currency');
    }


    public function neighbours()
    {
        return $this->belongsToMany('App\Models\Country', 'country_country', 'country_alpha_2', 'neighbour_alpha_2');
    }

}
