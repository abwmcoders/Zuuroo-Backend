<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class BettingRepository
{
    public function fetchBillers()
    {
        try {
            $apiUrl = 'https://sandbox.giftbills.com/api/v1/betting'; 
            $apiKey = 'I3QHMRI8EB2LZZGBLGSRVO5SR6XMNXJ';// env('I3QHMRI8EB2LZZGBLGSRVO5SR6XMNXJ'); 
            $merchantId = 'Themade';//env('Themade'); 

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'MerchantId' => $merchantId,
                'Content-Type' => 'application/json',
            ])->get($apiUrl);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'error' => true,
                'message' => 'Failed to fetch billers',
                'details' => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'An error occurred while fetching billers',
                'details' => $e->getMessage(),
            ];
        }
    }

    public function validateCustomer(string $provider, string $customerId)
    {
        try {
            $apiUrl = 'https://sandbox.giftbills.com/api/v1/betting/validate';
            $apiKey = 'I3QHMRI8EB2LZZGBLGSRVO5SR6XMNXJ'; //env('API_KEY'); 
            $merchantId = 'Themade'; //env('MERCHANT_ID');

            $body = [
                'provider' => $provider,
                'customerId' => $customerId,
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'MerchantId' => $merchantId,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $body);

            if ($response->successful()) {
                return $response->json(); 
            }

            return [
                'error' => true,
                'message' => 'Failed to validate customer ID',
                'details' => $response->json(),
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'An error occurred while validating customer ID',
                'details' => $e->getMessage(),
            ];
        }
    }

    public function purchaseBet(array $data)
    {
        try {
            $apiUrl = 'https://sandbox.giftbills.com/api/v1/betting/topup'; 
            $apiKey = 'I3QHMRI8EB2LZZGBLGSRVO5SR6XMNXJ'; //env('API_KEY'); 
            $merchantId = 'Themade'; //env('MERCHANT_ID');
            $encryptionKey = 'SHAQBOTFHF951736357278'; //env('ENCRYPTION_KEY');

            $signature = hash_hmac('sha512', json_encode($data), $encryptionKey);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'MerchantId' => $merchantId,
                'Encryption' => $signature,
                'Content-Type' => 'application/json',
            ])->post($apiUrl, $data);
            
            return $response;
            // if ($response->successful()) {
            //     return $response->json();
            // }

            // return [
            //     'error' => true,
            //     'message' => 'Failed to purchase bet',
            //     'details' => $response->json(),
            // ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'An error occurred while processing the purchase bet request',
                'details' => $e->getMessage(),
            ];
        }
    }
}
