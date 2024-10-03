<?php

namespace App\Repositories;


class BillPaymentRepository
{
    public function verifyMeterNumber(array $billDetails)
    {
        //:TODO MAke Request to api call
        $response = [
            'status' => 'success',
            'message' => 'Meter number verified successfully',
            'content' => [
                'meterNumber' => $billDetails['billersCode'],
                'customerName' => 'John Doe',
                'meterType' => $billDetails['type']
            ]
        ];

        return json_encode($response);
    }

    public function verifyIUCNumber(array $billDetails)
    {
        //:TODO MAke Request to api call

        $response = [
            'status' => 'success',
            'message' => 'IUC number verified successfully',
            'content' => [
                'iucNumber'    => $billDetails['billersCode'],
                'customerName' => 'Jane Doe',
                'serviceID'    => $billDetails['serviceID']
            ]
        ];

        return json_encode($response);
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

    public function payElectricity(array $billDetails)
    {
        //:TODO MAke Request to api call


        $response = [
            'code' => '016',
            'content' => [
                'transactions' => [
                    'type' => 'Electricity Payment',
                    'product_name' => 'EKO Electric',
                    'phone' => $billDetails['phone'],
                    'transactionId' => '123456789',
                    'amount' => $billDetails['amount'],
                    'commission' => '₦50',
                    'status' => 'Delivered',
                    'total_amount' => $billDetails['amount'] + 50
                ]
            ],
            'requestId' => $billDetails['request_id']
        ];

        return $response;
    }

    public function payCableTV(array $billDetails)
    {
        //:TODO MAke Request to api call


        $response = [
            'code' => 016,
            'content' => [
                'transactions' => [
                    'type' => 'Cable TV Payment',
                    'product_name' => 'DSTV Compact Plus',
                    'phone' => $billDetails['phone'],
                    'transactionId' => '123456789',
                    'amount' => $billDetails['amount'],
                    'commission' => '₦50',
                    'status' => 'Delivered',
                    'total_amount' => $billDetails['amount'] + 50
                ]
            ],
            'requestId' => $billDetails['request_id'],
            'response_description' => 'Transaction successful'
        ];

        return $response;
    }

    public function ResulCheckerRepository(){}

}
