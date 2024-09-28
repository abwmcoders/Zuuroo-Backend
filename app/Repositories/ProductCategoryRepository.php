<?php

namespace App\Repositories;

use App\Models\ProductCategory;

class ProductCategoryRepository
{
    public function getAllProductCategories()
    {
        return ProductCategory::all();
    }
    
    public function getAllProductCategoriesWithOperator()
    {
        return ProductCategory::join('operators', 'operators.operator_code', 'product_categories.operator_code')
                                ->select('operators.logo_url', 'product_categories.status', 'product_categories.category_name', 'product_categories.category_code')
                                ->get();
    }

    public function getAllProductsCatInfo()
    {
        return ProductCategory::join('operators', 'product_categories.operator_code', '=', 'operators.operator_code')
                                ->orderBy('product_categories.id', 'DESC')
                                ->get();
    }

    public function getProductCategoryById($ProductCategoryId)
    {
        return ProductCategory::findOrFail($ProductCategoryId);
    }

    public function deleteProductCategory($ProductCategoryId)
    {
        ProductCategory::destroy($ProductCategoryId);
    }

    public function createProductCategory(array $ProductCategoryDetails)
    {
        return ProductCategory::create($ProductCategoryDetails);
    }

    public function updateProductCategory($ProductCategoryId, array $newDetails)
    {
        return ProductCategory::whereId($ProductCategoryId)->update($newDetails);
    }

    public function getProductCategoryByStatus($ProductCategoryId)
    {
        return ProductCategory::where('status', true)->get();
    }

    public function getProductCategoryByOperator($OperatorId)
    {
        return ProductCategory::where('operator_code', $OperatorId)->where('status', true)->get();
    }

}