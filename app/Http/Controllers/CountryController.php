<?php

namespace App\Http\Controllers;

use App\Interfaces\CountryRepositoryInterface;
use App\Repositories\CountryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class CountryController extends Controller
{
    private CountryRepository $CountryRepository;

    public function __construct(CountryRepository $CountryRepository)
    {
        $this->CountryRepository = $CountryRepository;
    }

    public function index()
    : JsonResponse
    {
        return response()->json([
            'data' => $this->CountryRepository->getAllCountries()
        ]);
    }

    public function store(Request $request)
    : JsonResponse
    {
        $CountryDetails = $request->only([
            'country_name', 
            'country_code',
            'is_loan' ,
            'phone_code',
            'status'
        ]);

        return response()->json(
            [
                'data' => $this->CountryRepository->createCountry($CountryDetails)
            ],
            Response::HTTP_CREATED
        );
    }

    public function show(Request $request)
    : JsonResponse
    {
        $CountryId = $request->route('id');

        return response()->json([
            'data' => $this->CountryRepository->getCountryById($CountryId)
        ]);
    }

    public function update(Request $request)
    : JsonResponse
    {
        $CountryId = $request->route('id');

        $CountryDetails = $request->only([
            'country_name', 
            'country_code',
            'is_loan' ,
            'phone_code',
            'status'
        ]);

        return response()->json([
            'data' => $this->CountryRepository->updateCountry($CountryId, $CountryDetails)
        ]);
    }

    public function destroy(Request $request)
    : JsonResponse
    {
        $CountryId = $request->route('id');

        $this->CountryRepository->deleteCountry($CountryId);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function isloan()
    : JsonResponse
    {
        return response()->json([
            'data' => $this->CountryRepository->getLoanCountries()
        ]);
    }

    public function phoneCode(Request $request)
    : JsonResponse
    {
        $countryIso = $request->route('id');
        return response()->json([
            'data' => $this->CountryRepository->getPhoneCode($countryIso)
        ]);
    }

    public function CountryByStatus()
    : JsonResponse
    {
        return response()->json([
            'data'=> $this->CountryRepository->CountryByStatus()
        ]);
    }

}