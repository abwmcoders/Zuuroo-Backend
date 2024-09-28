<?php

namespace App\Repositories;

use App\Interfaces\FundRepositoryInterface;
use App\Models\Fund;

class FundRepository
{
    public function getAllFunds()
    {
        return Fund::all();
        // return 'Hello Only';
    }

    public function getFundById($FundId)
    {
        return Fund::findOrFail($FundId);
    }

    public function deleteFund($FundId)
    {
        Fund::destroy($FundId);
    }

    public function createFund(array $FundDetails)
    {
        return Fund::create($FundDetails);
    }

    public function updateFund($FundId, array $newDetails)
    {
        return Fund::whereId($FundId)->update($newDetails);
    }

    public function getFulfilledFunds()
    {
        return Fund::where('is_fulfilled', true);
    }

}