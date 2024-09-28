<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegistrationResource;
use Illuminate\Http\Request;
use App\Interfaces\ProfileServiceInterface;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileServiceInterface $profileService)
    {
        $this->profileService = $profileService;
    }

    public function show()
    {
        $user = Auth::user();
        $profile = $this->profileService->getProfile($user);
        return response()->json([
            'status' => true,
            'message' => 'Successfully fetched user profile',
            'data' => new RegistrationResource($user)
        ], 200);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255|min:3',
            'last_name' => 'required|string|max:255|min:3',
            'username' => 'required|string|max:255|min:3',
            'address' => 'required|string|max:255|',
            'referral_code' => 'nullable|string|max:10',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|min:8',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $updatedUser = $this->profileService->updateProfile($user, $validatedData);
        return response()->json([
            'status' => true,
            'message' => 'Profile successfully updated',
            'data'=> new RegistrationResource($updatedUser)
        ]);
    }
}
