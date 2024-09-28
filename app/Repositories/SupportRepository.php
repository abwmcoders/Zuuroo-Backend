<?php
namespace App\Repositories;

use App\Interfaces\SupportRepositoryInterface;
use App\Models\Support;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

class SupportRepository
{
    public function getAllSupports()
    {
        return Support::all();
    }

    public function createSupport(array $SupportDetails)
    {
        return Support::create($SupportDetails);
    }

    public function updateSupport($SupportId, array $SupportDetails)
    {
        return Support::whereId($SupportId)->update($SupportDetails);
    }

    public function deleteSupportRecord($SupportId)
    {
        Support::destroy($SupportId);
    }

}