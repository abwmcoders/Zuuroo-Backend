<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;

class BillPaymentRepository
{

    public function verifyMeterNumber(array $billDetails)
    {
        $url = 'https://alrahuzdata.com.ng/api/validatemeter';
        $headers = [
            'Authorization' => 'Token 8f68d6c81f1dcb34f6e8ddbeb33bde8044359182',
            'Content-Type'  => 'application/json',
        ];

        $queryParams = [
            'meternumber' => $billDetails['billerNumber'],
            'disconame'   => $billDetails['disco'],
            'mtype'       => $billDetails['type'],
        ];
        try {
            $response = Http::withHeaders($headers)->get($url, $queryParams);

            if ($response['invalid'] === false) {
                return [
                    'status' => 'true',
                    'message' => 'Meter number verified successfully',
                    'data' => $response->json(),
                ];
            }

            return [
                'status' => 'false',
                'message' => 'Failed to verify meter number',
                'data' => $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'false',
                'message' => 'An error occurred during the request',
                'data' => $e->getMessage(),
            ];
        }

    }

    public function verifyIUCNumber(array $billDetails)
    {
        $url = 'https://alrahuzdata.com.ng/api/validateiuc';
        $headers = [
            'Authorization' => 'Token 8f68d6c81f1dcb34f6e8ddbeb33bde8044359182',
            'Content-Type'  => 'application/json',
        ];

        $queryParams = [
            'smart_card_number' => $billDetails['iuc'],
            'cablename'   => $billDetails['cable'],
        ];
        try {
            $response = Http::withHeaders($headers)->get($url, $queryParams);

            if ($response['invalid'] === false) {
                return [
                    'status' => 'true',
                    'message' => 'IUC number verified successfully',
                    'data' => $response->json(),
                ];
            }

            return [
                'status' => 'false',
                'message' => 'Failed to verify IUC number',
                'data' => $response->body(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'false',
                'message' => 'An error occurred during the request',
                'data' => $e->getMessage(),
            ];
        }

    }

    public function getCablePlan($cableId)
    {
        //:TODO MAke Request to api call

        $cablePlans = [
            '1' => [
                'plan' => 'Basic',
                'price' => '₦1500',
                'channels' => ['News', 'Sports', 'Movies']
            ],
            '2' => [
                'plan' => 'Premium',
                'price' => '₦3500',
                'channels' => ['News', 'Sports', 'Movies', 'Documentaries', 'Kids']
            ]
        ];

        // Return the cable plan details based on the cableId
        return $cablePlans[$cableId] ?? ['error' => 'Cable Plan not found'];
    }

    public function payElectricity(array $paymentDetails)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token 8f68d6c81f1dcb34f6e8ddbeb33bde8044359182',
            'Content-Type' => 'application/json'
        ])->post('https://alrahuzdata.com.ng/api/billpayment/', $paymentDetails);
        return $response;
    }

    public function payCableTV(array $paymentDetails)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Token 8f68d6c81f1dcb34f6e8ddbeb33bde8044359182',
            'Content-Type' => 'application/json'
        ])->post('https://alrahuzdata.com.ng/api/cablesub/', $paymentDetails);
        return $response;
    }

    public function formatErrorResponse($jsonString)
    {
        $decoded = json_decode($jsonString, true);

        if (isset($decoded['error']) && is_array($decoded['error'])) {
            $errorMessage = implode(', ', $decoded['error']);

            return "error: $errorMessage";
        }
        return "No error found.";
    }

    public function getAllBillers()
    {
        try {
            $apiUrl = 'https://sandbox.giftbills.com/api/v1/electricity';
            $apiKey = 'I3QHMRI8EB2LZZGBLGSRVO5SR6XMNXJ'; // env('I3QHMRI8EB2LZZGBLGSRVO5SR6XMNXJ'); 
            $merchantId = 'Themade'; //env('Themade'); 

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

    public function verify(string $provider, string $number, string $type)
    {
        try {
            $apiUrl = 'https://sandbox.giftbills.com/api/v1/electricity/validate';
            $apiKey = 'I3QHMRI8EB2LZZGBLGSRVO5SR6XMNXJ'; //env('API_KEY'); 
            $merchantId = 'Themade'; //env('MERCHANT_ID');

            $body = [
                'provider' => $provider,
                'number' => $number,
                'type' => $type,
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


    public function purchasePower(array $data)
    {
        try {
            $apiUrl = 'https://sandbox.giftbills.com/api/v1/electricity/recharge';
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

    public function ResulCheckerRepository(){}

}
