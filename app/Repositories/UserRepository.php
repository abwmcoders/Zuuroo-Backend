<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Models\userBankDetail;
use App\Models\TermCondition;
use App\Models\Pricing;

class UserRepository
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($UserId)
    {
        return User::findOrFail($UserId);
    }

    public function deleteUser($UserId)
    {
        User::destroy($UserId);
    }

    public function createUser(array $UserDetails)
    {
        return User::create($UserDetails);
    }

    public function updateUser($UserId, array $newDetails)
    {
        return User::whereId($UserId)->update($newDetails);
    }

    public function getActiveUser()
    {
        return User::where('email_verifies_at', true);
    }
    
    public function getAllUsersWithWalletBal()
    {
        return User::join('wallets', 'wallets.user_id', 'users.id')
                    ->orderBy('users.id', 'DESC')
                    ->where('users.status', 1)
                    ->get();
    }


    public function createUserAccountDetails(array $bankDetails){
        return userBankDetail::create($bankDetails);
    }

    public function getTermsCondition()
    {
        return TermCondition::first();
    }

    public function getPricing($provider)
    {
        return Pricing::where('provider', $provider)->get();
    }

}