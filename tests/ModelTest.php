<?php

use Illuminate\Support\Facades\DB;
use Io238\ISOCountries\Models\Country;
use Io238\ISOCountries\Models\Currency;
use Io238\ISOCountries\Models\Language;


it('can create a new country', function () {
    Country::unguard();

    Country::unsetEventDispatcher();

    Country::query()->create([
        'id'      => 'XX',
        'alpha3'  => 'XXX',
        'numeric' => 999,
        'name'    => 'Test Country',
    ]);

    $this->assertDatabaseHas('countries', ['id' => 'XX']);

    DB::table('countries')->where('id', 'XX')->delete();
});

it('can create a new language', function () {
    Language::unguard();

    Language::unsetEventDispatcher();

    Language::query()->create([
        'id'          => 'xx',
        'iso639_2'    => 'xxx',
        'name'        => 'Test Language',
        'native_name' => 'Test Language',
    ]);

    $this->assertDatabaseHas('languages', ['id' => 'xx']);

    DB::table('languages')->where('id', 'xx')->delete();
});

it('can create a new currency', function () {
    Currency::unguard();

    Currency::unsetEventDispatcher();

    Currency::query()->create([
        'id'            => 'XXX',
        'name'          => 'Test Currency',
        'name_plural'   => 'Test Currencies',
        'symbol'        => '✗',
        'symbol_native' => '✗',
    ]);

    $this->assertDatabaseHas('currencies', ['id' => 'XXX']);

    DB::table('currencies')->where('id', 'XXX')->delete();
});

it('prevents updates to ISO data via Eloquent methods', function () {
    Country::unguard();

    $original = Country::find('US')->toArray();

    // update() method
    Country::find('US')->update(['is_independent' => ! $original['is_independent']]);

    expect(Country::find('US')->toArray())->toBe($original);

    // save() method
    $country = Country::find('US');
    $country->is_independent = ! $original['is_independent'];
    $country->save();

    expect(Country::find('US')->toArray())->toBe($original);
});

it('prevents deletion of ISO data via Eloquent methods', function () {
    Country::find('US')->delete();

    expect(Country::find('US'))->not->toBeNull();
});

it('can eager load relations', function () {
    $country = Country::find('MX');

    expect($country->getRelations())->toBeEmpty();

    $country->load([
        'currencies',
        'languages',
        'neighbours',
    ]);

    expect($country->getRelations()['currencies']->count())->toBeGreaterThan(0);
    expect($country->getRelations()['languages']->count())->toBeGreaterThan(0);
    expect($country->getRelations()['neighbours']->count())->toBeGreaterThan(0);
});
