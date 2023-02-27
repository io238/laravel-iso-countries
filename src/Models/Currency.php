<?php

namespace Io238\ISOCountries\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Currency extends BaseModel
{
    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return parent::resolveRouteBinding(strtoupper($value), $field);
    }
}
