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
        return response()->json([
            'data' => $this->OperatorRepository->getAllOperators()
        ]);
    }

    public function store(Request $request)
    : JsonResponse
    {
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
    }


    public function show(Request $request)
    : JsonResponse
    {
        $OperatorId = $request->route('id');

        return response()->json([
            'data' => $this->OperatorRepository->getOperatorById($OperatorId)
        ]);
    }
    
    
    public function update(Request $request)
    : JsonResponse
    {
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
    }

    public function destroy(Request $request)
    : JsonResponse
    {
        $OperatorId = $request->route('id');

        $this->OperatorRepository->deleteOperator($OperatorId);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    
    public function operatorStatus(Request $request)
    : JsonResponse
    {
        $OperatorId = $request->route('id');
        return response()->json([
            'data' => $this->OperatorRepository->getOperatorByStatus($OperatorId)
        ]);
    }

    public function operatorsByCountry(Request $request)
    : JsonResponse
    {
        $CountryIso = $request->route('id');
        return response()->json([
            'data' => $this->OperatorRepository->getOperatorByCountry($CountryIso)
        ]);
    }


}
