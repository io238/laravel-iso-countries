<?php

namespace Io238\ISOCountries\Exceptions;

use Exception;

class MissingSqliteExtensionException extends Exception
{
    protected $message = 'The PHP extension pdo_sqlite is not installed or enabled. This package requires the SQLite extension.';
}
