<?php

namespace App\Http\Controllers;

use App\Repositories\AirtimeProductRepository as RepositoriesAirtimeProductRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class AirtimeProductController extends Controller
{
    use ApiResponseTrait;

    private RepositoriesAirtimeProductRepository $AirtimeProductRepository;

    public function __construct(RepositoriesAirtimeProductRepository $AirtimeProductRepository)
    {
        $this->AirtimeProductRepository = $AirtimeProductRepository;
    }

    public function index()
    : JsonResponse
    {
        try{
            return response()->json([
                'data' => $this->AirtimeProductRepository->getAllAirtimeProducts()
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }

    }

    public function store(Request $request)
    : JsonResponse
    {
        try {
            $ProductDetails = $request->only([
                    'operator_code',
                    'product_code',
                    'category_code',
                    'product_name',
                    'product_price',
                    'validity',
                    'loan_price',
                    'status'
            ]);

            return response()->json(
                [
                    'data' => $this->AirtimeProductRepository->createAirtimeProduct($ProductDetails)
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
            $ProductId = $request->route('id');
            return response()->json([
                'data' => $this->AirtimeProductRepository->getAirtimeProductById($ProductId)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }


    public function update(Request $request)
    : JsonResponse
    {
        try{
            $ProductId = $request->route('id');
            $ProductDetails = $request->only([
                'operator_code',
                'product_code',
                'category_code',
                'product_name',
                'product_price',
                'validity',
                'loan_price',
                'status'
            ]);

            return response()->json([
                'data' => $this->AirtimeProductRepository->updateAirtimeProduct($ProductId, $ProductDetails)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function destroy(Request $request)
    : JsonResponse
    {
        try{
            $ProductId = $request->route('id');
            $this->AirtimeProductRepository->deleteAirtimeProduct($ProductId);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }


    public function AirtimeProductByStatus(Request $request)
    : JsonResponse
    {
        try{
            $ProductId = $request->route('id');
            return response()->json([
                'data' => $this->AirtimeProductRepository->getAirtimeProductByStatus($ProductId)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function AirtimeProductByOperator(Request $request)
    : JsonResponse
    {
        try{
            $OperatorId = $request->route('id');
            return response()->json([
                'data' => $this->AirtimeProductRepository->getAirtimeProductByOperator($OperatorId)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function AirtimeProductByCategory(Request $request)
    : JsonResponse
    {
        try{
            $CategoryId = $request->route('id');
            return response()->json([
                'data'  => $this->AirtimeProductRepository->getAirtimeProductByCategory($CategoryId)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }



}
