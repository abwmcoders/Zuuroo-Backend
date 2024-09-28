<?php

namespace App\Repositories;

use App\Interfaces\BulkSmsInterface;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;


class BulkSmsRepository
{

    public function getToken(){
        return "sk_live_7570700bb6e02b66cb43cc0040b5dcb9fc72985d"; //"sk_test_52fdad00c5f938381b29d16a6e4c516bea328ff5"; //
        // pk_live_a6b19f77b9d8c4098d2102a95f1d07d60bdb56a0
    }


    public function processMobileVer(array $smsDetails)
    {
        $response = Http::withHeaders([
            // 'Authorization' => 'Bearer '. $this->getToken(),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json'
        ])->post('https://www.bulksmsnigeria.com/api/v2/sms', $smsDetails);
        return $response;
    }

    public function verifyMobile($MobileRef)
    {
        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json'
        ])->post('https://www.bulksmsnigeria.com/api/v2/sms', $MobileRef);
        return $response;
    }


}
