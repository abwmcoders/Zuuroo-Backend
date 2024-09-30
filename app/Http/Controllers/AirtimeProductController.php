<?php

namespace App\Http\Controllers;

use App\Repositories\AirtimeProductRepository as RepositoriesAirtimeProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class AirtimeProductController extends Controller
{
  
    private RepositoriesAirtimeProductRepository $AirtimeProductRepository;

    public function __construct(RepositoriesAirtimeProductRepository $AirtimeProductRepository)
    {
        $this->AirtimeProductRepository = $AirtimeProductRepository;
    }

    public function index()
    : JsonResponse
    {
        return response()->json([
            'data' => $this->AirtimeProductRepository->getAllAirtimeProducts()
        ]);
    }

    public function store(Request $request)
    : JsonResponse
    {
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
    }


    public function show(Request $request)
    : JsonResponse
    {
        $ProductId = $request->route('id');

        return response()->json([
            'data' => $this->AirtimeProductRepository->getAirtimeProductById($ProductId)
        ]);
    }
    
    
    public function update(Request $request)
    : JsonResponse
    {
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
    }

    public function destroy(Request $request)
    : JsonResponse
    {
        $ProductId = $request->route('id');

        $this->AirtimeProductRepository->deleteAirtimeProduct($ProductId);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    
    public function AirtimeProductByStatus(Request $request)
    : JsonResponse
    {
        $ProductId = $request->route('id');
        return response()->json([
            'data' => $this->AirtimeProductRepository->getAirtimeProductByStatus($ProductId)
        ]);
    }

    public function AirtimeProductByOperator(Request $request)
    : JsonResponse
    {
        $OperatorId = $request->route('id');
        return response()->json([
            'data' => $this->AirtimeProductRepository->getAirtimeProductByOperator($OperatorId)
        ]);
    }
    
    public function AirtimeProductByCategory(Request $request)
    : JsonResponse
    {
        $CategoryId = $request->route('id');

        return response()->json([
            'data'  => $this->AirtimeProductRepository->getAirtimeProductByCategory($CategoryId)
        ]);
    }



}