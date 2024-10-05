<?php

namespace App\Repositories;

use App\Interfaces\DataRepositoryInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

class DataRepository
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

    public function createNgData(array $DataDetails)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token 8f68d6c81f1dcb34f6e8ddbeb33bde8044359182', //'Token 7449197381ad06f36b660461759a4f4d9c3ead05',
            'Content-Type' => 'application/json'
        ])->post('https://alrahuzdata.com.ng/api/data/', $DataDetails);
        return $response;
    }

    public function createIntData(array $DataDetails)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. $this->getToken(),
            'Content-Type' => 'application/json'
        ])->post('https://api.dingconnect.com/api/V1/SendTransfer', $DataDetails);
        return $response;
    }

    public function findUser()
    {
        //$response = Http::withToken('Token 7449197381ad06f36b660461759a4f4d9c3ead05')->get('https://tentendata.com.ng/api/user');
        $response = Http::withHeaders([
            'Authorization' => 'Token 7449197381ad06f36b660461759a4f4d9c3ead05',
            'Content-Type' => 'application/json'
        ])->get('https://tentendata.com.ng/api/user');

        return $response;
    }

}
