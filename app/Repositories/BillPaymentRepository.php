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

    public function ResulCheckerRepository(){}

}
