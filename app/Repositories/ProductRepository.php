<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Http;

class ProductRepository
{

    public static function getToken(){
        $DataDetails = [
            'client_id'=> '8f74ef84-b001-4b03-be6c-5bdedbda8d64',//'919c366c-4645-46f8-80cc-35c77040014b',
            'client_secret' => 'HYpb4WMPLFfOJzXUnZBYxJkiX5VC0xknC4rhp9ju9aU=',//'71apN0bg3CXO7ACVWe9mjjaibZu6sd4uC0VA2rH10GI=',
            'grant_type' => 'client_credentials'
        ];
        $response = Http::asForm()->post('https://idp.ding.com/connect/token', $DataDetails);
        return $response['access_token'];
    }

    public function getAllProducts()
    {
        return Product::all();
    }

    public function getAllProductsInfo()
    {
        return Product::join('countries', 'products.country_code', 'countries.country_code')
                        ->orderBy('products.id', 'DESC')
                        ->get();
    }

    public function getProductById($ProductId)
    {
        return Product::findOrFail($ProductId);
    }

    public function getProductByCode($ProductId)
    {
        return Product::where('product_code', $ProductId)->first();
    }

    public function deleteProduct($ProductId)
    {
        Product::destroy($ProductId);
    }

    public function createProduct(array $ProductDetails)
    {
        return Product::create($ProductDetails);
    }

    public function updateProduct($ProductId, array $newDetails)
    {
        return Product::where('product_code', $ProductId)->update($newDetails);
    }

    public function getProductByStatus($ProductId)
    {
        return Product::where('status', true)->get();
    }

    public function getProductByOperator($OperatorId)
    {
        return Product::where('operator_code', $OperatorId)->where('status', true)->get();
    }

    public function getProductByCategory($CategoryId)
    {
        return Product::where('category_code', $CategoryId)->where('status', true)->get();
    }

    public function ProductByOperator($OperatorId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. $this->getToken(),
            'Content-Type' => 'application/json'
        ])->get("https://api.dingconnect.com/api/V1/GetProducts?accountNumber=$OperatorId&benefits=data");
        // return $this->getToken();
        $return = json_decode($response);
        return $return;
        // Items->Minimum->SendValue

    }

}
