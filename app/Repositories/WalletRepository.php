<?php
namespace App\Repositories;

use App\Interfaces\WalletRepositoryInterface;
use App\Models\Wallet;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

class WalletRepository
{
    public function getWalletBalance($UserId)
    {
        return Wallet::where('user_id', $UserId)->first();
    }

    public function createWallet(array $WalletDetails)
    {
        return Wallet::create($WalletDetails);
    }

    public function updateWallet($UserId, array $WalletDetails)
    {
        return Wallet::where('user_id', $UserId)->update($WalletDetails);
    }

    public function deleteWalletRecord($WalletRef)
    {
        Wallet::destroy($WalletRef);
    }

}