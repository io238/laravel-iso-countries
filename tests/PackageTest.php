<?php

namespace Io238\ISOCountries\Tests;

//it('loads to package config correctly')->expect(config('iso-countries'))->not->toBeNull();

it('it seeds data country data correctly')->assertDatabaseCount('countries', 250);

it('it seeds data language data correctly')->assertDatabaseCount('languages', 184);

it('it seeds data currency data correctly')->assertDatabaseCount('currencies', 139);
