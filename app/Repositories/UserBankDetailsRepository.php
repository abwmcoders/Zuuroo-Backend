<?php

namespace App\Repositories;

use App\Interfaces\UserBankDetailsRepositoryInterface;
use App\Models\User;
use App\Models\userBankDetail;

class UserBankDetailsRepository
{
    public function getAllUserBankDetails()
    {
        return userBankDetail::all();
    }

    public function getDetailsById($UserId)
    {
        return userBankDetail::where('user_name', $UserId)->get();
    }

    public function deleteUserDetails($UserId)
    {
        userBankDetail::destroy($UserId);
    }

    public function createUserAccountDetails(array $UserDetails)
    {
        return userBankDetail::create($UserDetails);
    }

    public function updateUserDetails($UserId, array $newDetails)
    {
        return userBankDetail::whereId($UserId)->update($newDetails);
    }

    public function getActiveUserBank()
    {
        return userBankDetail::where('email_verifies_at', true)->get();
    }


}