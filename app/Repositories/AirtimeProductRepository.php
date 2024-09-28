<?php

namespace App\Repositories;

use App\Interfaces\AirtimeProductRepositoryInterface;
use App\Models\AirtimeProduct;

class AirtimeProductRepository
{
    public function getAllAirtimeProducts()
    {
        return AirtimeProduct::all();
    }

    public function getAirtimeProductById($ProductId)
    {
        return AirtimeProduct::findOrFail($ProductId);
    }

    public function deleteAirtimeProduct($ProductId)
    {
        AirtimeProduct::destroy($ProductId);
    }

    public function createAirtimeProduct(array $ProductDetails)
    {
        return AirtimeProduct::create($ProductDetails);
    }

    public function updateAirtimeProduct($ProductId, array $newDetails)
    {
        return AirtimeProduct::whereId($ProductId)->update($newDetails);
    }

    public function getAirtimeProductByStatus($ProductId)
    {
        return AirtimeProduct::where('status', true)->get();
    }

    public function getAirtimeProductByOperator($OperatorId)
    {
        return AirtimeProduct::where('operator_code', $OperatorId)->where('status', true)->get();
    }

    public function getAirtimeProductByCategory($CategoryId)
    {
        return AirtimeProduct::where('category_code', $CategoryId)->where('status', true)->get();
    }

}