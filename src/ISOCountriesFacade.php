<?php

namespace Io238\ISOCountries;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Io238\ISOCountries\ISOCountries
 */
class ISOCountriesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'iso-countries';
    }
}
