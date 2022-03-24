<?php

namespace Io238\ISOCountries\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Io238\ISOCountries\Models\Traits\ReadOnlyModel;
use Spatie\Translatable\HasTranslations;


class BaseModel extends Model {

    use ReadOnlyModel;
    use HasTranslations;


    protected $connection = 'iso-countries';

    public $incrementing = false;

    public $timestamps = false;

    public $translatable = ['name'];

    protected $appends = ['slug'];

    protected $hidden = ['pivot'];

    protected $fillable = [];


    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }

}
