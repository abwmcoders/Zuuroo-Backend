<?php

namespace App\Http\Controllers;

use App\Interfaces\CountryRepositoryInterface;
use App\Repositories\CountryRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class CountryController extends Controller
{
    use ApiResponseTrait;
    private CountryRepository $CountryRepository;

    public function __construct(CountryRepository $CountryRepository)
    {
        $this->CountryRepository = $CountryRepository;
    }

    public function index()
    : JsonResponse
    {
        try{
            return $this->successResponse(data: $this->CountryRepository->getAllCountries(),);
            
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function store(Request $request)
    : JsonResponse
    {
        try{
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
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function show(Request $request)
    : JsonResponse
    {
        try{
            $CountryId = $request->route('id');
            return response()->json([
                'data' => $this->CountryRepository->getCountryById($CountryId)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function update(Request $request)
    : JsonResponse
    {
        try{
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
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function destroy(Request $request)
    : JsonResponse
    {
        try{
            $CountryId = $request->route('id');
            $this->CountryRepository->deleteCountry($CountryId);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function isloan()
    : JsonResponse
    {
        try{
            return $this->successResponse(data: $this->CountryRepository->getLoanCountries(),);
            
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function phoneCode(Request $request)
    : JsonResponse
    {
        try{
            $countryIso = $request->route('id');
            return response()->json([
                'data' => $this->CountryRepository->getPhoneCode($countryIso)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function CountryByStatus()
    : JsonResponse
    {
        try{
            return response()->json([
                'data'=> $this->CountryRepository->CountryByStatus()
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

}
