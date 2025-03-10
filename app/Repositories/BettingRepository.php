<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class BettingRepository
{
    public function fetchBillers()
    {
        try {
            $apiUrl = 'https://giftbills.com/api/v1/betting'; 
            $apiKey = 'AZGK9SK82FRXFI4XMCW6CYGCQWECPK1';// env('I3QHMRI8EB2LZZGBLGSRVO5SR6XMNXJ'); 
            $merchantId = 'Zuuroo';//env('Themade'); 

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
            $apiUrl = 'https://giftbills.com/api/v1/betting/validate';
            $apiKey = 'AZGK9SK82FRXFI4XMCW6CYGCQWECPK1'; //env('API_KEY'); 
            $merchantId = 'Zuuroo'; //env('MERCHANT_ID');

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
            $apiUrl = 'https://giftbills.com/api/v1/betting/topup'; 
            $apiKey = 'AZGK9SK82FRXFI4XMCW6CYGCQWECPK1'; //env('API_KEY'); 
            $merchantId = 'Zuuroo'; //env('MERCHANT_ID');
            $encryptionKey = 'X05P6KB0QE7M1735588849'; //env('ENCRYPTION_KEY');

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
