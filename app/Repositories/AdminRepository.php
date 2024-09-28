<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use App\Models\Admin;

class AdminRepository
{
    public static function getToken(){
        $tokenDetails = [
            'client_id'=> '919c366c-4645-46f8-80cc-35c77040014b',
            'client_secret' => '71apN0bg3CXO7ACVWe9mjjaibZu6sd4uC0VA2rH10GI=',
            'grant_type' => 'client_credentials'
        ];
        $response = Http::asForm()->post('https://idp.ding.com/connect/token', $tokenDetails);
        return $response['access_token'];
    }

    public function getAllAdmins()
    {
        return Admin::all();
    }

    public function getAdminById($AdminId)
    {
        return Admin::findOrFail($AdminId);
    }

    public function deleteAdmin($AdminId)
    {
        Admin::destroy($AdminId);
    }

    public function createAdmin(array $AdminDetails)
    {
        return Admin::create($AdminDetails);
    }

    public function AuthAdmin(array $AdminDetails)
    {
        return Admin::create($AdminDetails);
    }

    public function updateAdmin($AdminId, array $newDetails)
    {
        return Admin::whereId($AdminId)->update($newDetails);
    }

    public function getActiveAdmins()
    {
        return Admin::where('status', true);
    }

    public function getExistingAdmin($AdminEmail)
    {
        return Admin::where('email', $AdminEmail)->first();
    }

    public function getSuperAdmin($roleId)
    {
        return Admin::where('role', $roleId)->first();
    }

    public function getDingConnectBal(){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '. $this->getToken(),
            'Content-Type' => 'application/json'
        ])->get('https://api.dingconnect.com/api/V1/GetBalance');
        return $response;
    }
}