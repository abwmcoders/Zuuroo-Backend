<?php

namespace App\Repositories;

use App\Interfaces\SmsDebtorRepositoryInterface;
use App\Models\SmsDebtor;

class SmsDebtorRepository
{
    public function getAllSmsDebtors()
    {
        return SmsDebtor::all();
    }

    public function getSmsDebtorById($SmsDebtorId)
    {
        return SmsDebtor::findOrFail($SmsDebtorId);
    }

    public function deleteSmsDebtor($SmsDebtorId)
    {
        SmsDebtor::destroy($SmsDebtorId);
    }

    public function createSmsDebtor(array $SmsDebtorDetails)
    {
        return SmsDebtor::create($SmsDebtorDetails);
    }

    public function updateSmsDebtor($SmsDebtorId, array $newDetails)
    {
        return SmsDebtor::whereId($SmsDebtorId)->update($newDetails);
    }

    public function getFulfilledSmsDebtors()
    {
        return SmsDebtor::where('is_fulfilled', true);
    }

}