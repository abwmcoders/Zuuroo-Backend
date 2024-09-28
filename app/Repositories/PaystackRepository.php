<?php

namespace App\Repositories;

use App\Models\PaymentHistory;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;


class PaystackRepository 
{

    public function getToken(){
        return "sk_live_7570700bb6e02b66cb43cc0040b5dcb9fc72985d"; //"sk_test_52fdad00c5f938381b29d16a6e4c516bea328ff5"; //""; //
        // pk_live_a6b19f77b9d8c4098d2102a95f1d07d60bdb56a0
    }


    public function verifyPayment($PaymentRef)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. $this->getToken(),
            'Content-Type' => 'application/json'
        ])->get('https://api.paystack.co/transaction/verify/'.$PaymentRef);
        return $response;
    }


    public function processPayment(array $PaymentDetails)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. $this->getToken(),
            'Content-Type' => 'application/json'
        ])->post('https://api.paystack.co/transaction/initialize', $PaymentDetails);
        return $response;
    }

    public function verifyWebhookPaystack(array $PaymentDetails)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. $this->getToken(),
            'Content-Type' => 'application/json'
        ])->post('https://api.paystack.co/transaction/initialize', $PaymentDetails);
        return $response;
    }

    public function initialize_recurring(array $Details)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. $this->getToken(),
            'Content-Type' => 'application/json'
        ])->post('https://api.paystack.co/transaction/initialize', $Details);
        return $response;
    }

    public function charge_recurring(array $Details)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. $this->getToken(),
            'Content-Type' => 'application/json'
        ])->post('https://api.paystack.co/transaction/charge_authorization', $Details);
        return $response;
    }

}