<?php

namespace App\Http\Controllers;

use App\Interfaces\ProductCategoryRepositoryInterface;
use App\Repositories\ProductCategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductCategoryController extends Controller
{
    private ProductCategoryRepository $ProductCategoryRepository;

    public function __construct(ProductCategoryRepository $ProductCategoryRepository)
    {
        $this->ProductCategoryRepository = $ProductCategoryRepository;
    }

    public function index()
    : JsonResponse
    {
        return response()->json([
            'data' => $this->ProductCategoryRepository->getAllProductCategories()
        ]);
    }

    public function store(Request $request)
    : JsonResponse
    {
        $ProductCategoryDetails = $request->only([
            'operator_code',
            'category_name',
            'category_code',
            'status'
        ]);

        return response()->json(
            [
                'data' => $this->ProductCategoryRepository->createProductCategory($ProductCategoryDetails)
            ],
            Response::HTTP_CREATED
        );
    }


    public function show(Request $request)
    : JsonResponse
    {
        $ProductCategoryId = $request->route('id');

        return response()->json([
            'data' => $this->ProductCategoryRepository->getProductCategoryById($ProductCategoryId)
        ]);
    }
    
    
    public function update(Request $request)
    : JsonResponse
    {
        $ProductCategoryId = $request->route('id');

        $ProductCategoryDetails = $request->only([
            'operator_code',
            'category_name',
            'category_code',
            'status'
        ]);

        return response()->json([
            'data' => $this->ProductCategoryRepository->updateProductCategory($ProductCategoryId, $ProductCategoryDetails)
        ]);
    }

    public function destroy(Request $request)
    : JsonResponse
    {
        $ProductCategoryId = $request->route('id');

        $this->ProductCategoryRepository->deleteProductCategory($ProductCategoryId);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    
    public function ProductCategoryStatus(Request $request)
    : JsonResponse
    {
        $ProductCategoryId = $request->route('id');
        return response()->json([
            'data' => $this->ProductCategoryRepository->getProductCategoryByStatus($ProductCategoryId)
        ]);
    }

    public function ProductCategoryByOperator(Request $request)
    : JsonResponse
    {
        $OperatorId = $request->route('id');
        return response()->json([
            'data' => $this->ProductCategoryRepository->getProductCategoryByOperator($OperatorId)
        ]);
    }



}
