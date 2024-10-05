<?php

namespace App\Http\Controllers;

use App\Repositories\OperatorRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private OperatorRepository $OperatorRepository;

    public function __construct(OperatorRepository $OperatorRepository)
    {
        $this->OperatorRepository = $OperatorRepository;
    }

    public function index()
    : JsonResponse
    {
        try{
            return response()->json([
                'data' => $this->OperatorRepository->getAllOperators()
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function store(Request $request)
    : JsonResponse
    {
        try{
            $OperatorDetails = $request->only([
                'country_code',
                'operator_name',
                'operator_code' ,
                'status'
            ]);
            return response()->json(
                [
                    'data' => $this->OperatorRepository->createOperator($OperatorDetails)
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
            $OperatorId = $request->route('id');
            return response()->json([
                'data' => $this->OperatorRepository->getOperatorById($OperatorId)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }


    public function update(Request $request)
    : JsonResponse
    {
        try{
            $OperatorId = $request->route('id');
            $OperatorDetails = $request->only([
                'country_code',
                'operator_name',
                'operator_code' ,
                'status'
            ]);
            return response()->json([
                'data' => $this->OperatorRepository->updateOperator($OperatorId, $OperatorDetails)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }


    public function destroy(Request $request)
    : JsonResponse
    {
        try{
            $OperatorId = $request->route('id');
            $this->OperatorRepository->deleteOperator($OperatorId);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }


    public function operatorStatus(Request $request)
    : JsonResponse
    {
        try{
            $OperatorId = $request->route('id');
            return response()->json([
                'data' => $this->OperatorRepository->getOperatorByStatus($OperatorId)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function operatorsByCountry(Request $request)
    : JsonResponse
    {
        try{
            $CountryIso = $request->route('id');
            return response()->json([
                'data' => $this->OperatorRepository->getOperatorByCountry($CountryIso)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }


}
