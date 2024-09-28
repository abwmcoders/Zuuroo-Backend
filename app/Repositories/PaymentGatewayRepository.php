<?php

namespace App\Repositories;

use App\Models\PaymentGateway;

class PaymentGatewayRepository
{
    public function getAllPaymentGateways()
    {
        return PaymentGateway::all();
    }

    public function getPaymentGatewayById($PaymentGatewayId)
    {
        return PaymentGateway::where('name', $PaymentGatewayId)->first();
    }

    public function deletePaymentGateway($PaymentGatewayId)
    {
        PaymentGateway::destroy($PaymentGatewayId);
    }

    public function createPaymentGateway(array $PaymentGatewayDetails)
    {
        return PaymentGateway::create($PaymentGatewayDetails);
    }

    public function updatePaymentGateway($PaymentGatewayId, array $newDetails)
    {
        return PaymentGateway::whereId($PaymentGatewayId)->update($newDetails);
    }

    public function getFulfilledPaymentGateways()
    {
        return PaymentGateway::where('is_fulfilled', true);
    }

}