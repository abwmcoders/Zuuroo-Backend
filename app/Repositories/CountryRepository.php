<?php

namespace App\Repositories;

use App\Interfaces\CountryRepositoryInterface;
use App\Models\Country;

class CountryRepository
{
    public function getAllCountries()
    {
        return Country::all();
        // return 'Hello Only';
    }

    public function getCountryById($CountryId)
    {
        return Country::findOrFail($CountryId);
    }

    public function deleteCountry($CountryId)
    {
        Country::destroy($CountryId);
    }

    public function createCountry(array $CountryDetails)
    {
        return Country::create($CountryDetails);
    }

    public function updateCountry($CountryId, array $newDetails)
    {
        return Country::whereId($CountryId)->update($newDetails);
    }

    public function getLoanCountries()
    {
        return Country::where('is_loan', true)->groupBy('country_code')->get();
    }

    public function getPhoneCode($countryIso)
    {
        return Country::where('country_code', $countryIso)->first();
    }

    public function CountryByStatus()
    {
        return Country::orderBy('country_name')->groupBy('country_code')->where('status', true)->get();
        // Item::orderBy('name')->get(); 
    }

}