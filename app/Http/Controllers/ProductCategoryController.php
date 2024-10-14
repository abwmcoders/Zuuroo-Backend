<?php

namespace App\Http\Controllers;

use App\Interfaces\ProductCategoryRepositoryInterface;
use App\Repositories\ProductCategoryRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductCategoryController extends Controller
{
    use ApiResponseTrait;
    
    private ProductCategoryRepository $ProductCategoryRepository;

    public function __construct(ProductCategoryRepository $ProductCategoryRepository)
    {
        $this->ProductCategoryRepository = $ProductCategoryRepository;
    }

    public function index()
    : JsonResponse
    {
        try{
            return response()->json([
                'data' => $this->ProductCategoryRepository->getAllProductCategories()
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function store(Request $request)
    : JsonResponse
    {
        try{
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
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }


    public function show(Request $request)
    : JsonResponse
    {
        try{
            $ProductCategoryId = $request->route('id');
            return response()->json([
                'data' => $this->ProductCategoryRepository->getProductCategoryById($ProductCategoryId)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }


    public function update(Request $request)
    : JsonResponse
    {
        try{
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
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function destroy(Request $request)
    : JsonResponse
    {
        try{
            $ProductCategoryId = $request->route('id');
            $this->ProductCategoryRepository->deleteProductCategory($ProductCategoryId);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }


    public function ProductCategoryStatus(Request $request)
    : JsonResponse
    {
        try{
            $ProductCategoryId = $request->route('id');
            return response()->json([
                'data' => $this->ProductCategoryRepository->getProductCategoryByStatus($ProductCategoryId)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function ProductCategoryByOperator(Request $request)
    : JsonResponse
    {
        try{
            $OperatorId = $request->route('id');
            return response()->json([
                'data' => $this->ProductCategoryRepository->getProductCategoryByOperator($OperatorId)
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }



}
