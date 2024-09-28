<?php

namespace App\Repositories;

use App\Interfaces\KycRepositoryInterface;
use App\Models\Kyc;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

class KycRepository
{
    public static function getToken(){
        $DataDetails = [
            'client_id'=> '919c366c-4645-46f8-80cc-35c77040014b',
            'client_secret' => '71apN0bg3CXO7ACVWe9mjjaibZu6sd4uC0VA2rH10GI=',
            'grant_type' => 'client_credentials'
        ];
        $response = Http::asForm()->post('https://idp.ding.com/connect/token', $DataDetails);
        return $response['access_token'];
    }
    
    public function getMonnifyToken(){
        $monnify_baseUrl = "https://api.monnify.com";
        $monnify_apiKey = "MK_PROD_0JNWWV5ZY6";
        $monnify_secretKey = "A263P7DAA0TJ6BJQ5B37PU50Y9ZXWVJA";
        
        $response = Http::withBasicAuth($monnify_apiKey, $monnify_secretKey)->post($monnify_baseUrl.'/api/v1/auth/login');
        //$token = json_decode($response)->responseBody->accessToken;
        return json_decode($response)->responseBody->accessToken;  
    }

    public function getAllKycs()
    {
        return Kyc::all();
        // return 'Hello Only';
    }

    public function getKycById($KycId)
    {
        return Kyc::findOrFail($KycId);
    }
    
    public function getKycByUserId($UserId)
    {
        return Kyc::join('users', 'users.id', 'kycs.user_id')->where('kycs.user_id', $UserId)->first();
    }

    public function deleteKyc($KycId)
    {
        Kyc::destroy($KycId);
    }

    public function createKyc(array $KycDetails)
    {
        return Kyc::create($KycDetails);
    }

    public function updateKyc($KycId, array $newDetails)
    {
        return Kyc::whereId($KycId)->update($newDetails);
    }

    public function getFulfilledKycs()
    {
        return Kyc::where('is_fulfilled', true);
    }
    
    public function verifyBvn(array $newDetails)
    {
        $endPoint = "https://api.fincra.com/core/bvn-verification"; // Test: https://sandboxapi.fincra.com Live: https://api.fincra.com/core/bvn-verification
        $response = Http::withHeaders([
            'api-key'       => 'FjWfL1Mar0BC3cOwKAIWpwvmgr0UcIpT',
            'content-type'  => 'application/json',
            'accept'        => 'application/json',
            
        ])->post($endPoint, $newDetails);
        return $response;
    }
    
    public function verifyBvnMonnify(array $newDetails)
    {
        $endPoint = "https://api.monnify.com/api/v1/vas/bvn-details-match"; 
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. $this->getMonnifyToken(),
                'Content-Type' => 'application/json'
        ])->post($endPoint, $newDetails);
        return $response;
    }

    // public function verifyBvn(array $newDetails)
    // {
    //     $endPoint = "https://api.ufitpay.com/v1/identity";
    //     $response = Http::withHeaders([
    //         'Api-Key'        => 'TSVLWCB5TO5RfJFCNkwSX8z0F8ZwD91',//aJaQEQ9igipCQ2FVQSRi
    //         'content-type'  => 'application/x-www-form-urlencoded',
    //         'Api-Token'      => 'TS66uEwtekJEcWiwzC1gDdiDzKcJFk1'//'1677103220637'
            
    //     ])->post($endPoint, $newDetails);
    //     return $response;
    // }

    public function getBvnDetailByUser($UserId, array $newDetails)
    {

    }

    public function getVerifiedUser()
    {

    }

}