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

    protected $keyType = 'string';

    protected $fillable = [];

    protected $hidden = ['pivot'];

    public $translatable = ['name'];


    public function getSlugAttribute()
    {
        return Str::slug($this->name);
    }

}
