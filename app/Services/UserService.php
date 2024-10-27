<?php

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Mail\OtpMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService implements UserServiceInterface {

    public function register(array $data) : User {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'mobile' => $data['telephone'],
            'dob' => $data['dob'],
            'username' => $data['username'],
            'gender' => $data['gender'],
            'address' => $data['address'],
            'country' => $data['country'],
        ]);

        //$user->sendEmailVerificationNotification();

        return $user;
    }

    public function login(array $data)
    {
        $loginField = filter_var($data['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Prepare the credentials array for authentication
        $authCredentials = [
            $loginField => $data['email'],
            'password' => $data['password'],
        ];

        if (Auth::attempt($authCredentials)) {
            $user = Auth::user();

            /** @var User $user */

            return $user->createToken('auth_token')->plainTextToken;
        }

        // Throw an exception if authentication fails
        throw new Exception('Invalid login credentials.');
    }

    // public function login(array $data) : string {
    //     if (!Auth::attempt($data)) {
    //         throw new \Exception('Invalid credentials');
    //     }
    //     /** @var User $user */
    //     $user = Auth::user();
    //     if (!$user->hasVerifiedEmail()) {
    //         throw new \Exception('Email not verified.');
    //     }
    //     /** @var User $user */
    //     $user = Auth::user();
    //     return $user->createToken('authToken')->plainTextToken;
    // }
    
    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function verifyOtp(array $data) : User
    {
        $user = User::where('email', $data['email'])
        ->where('otp', $data['otp'])
        ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        return $user;
    }

    public function requestPasswordReset(array $data) : User
    {
        $user = User::where('email', $data['email'])->first();
        return $user;
    }

    public function resetPassword(array $data) : ?User
    {
        $user = User::where('email', $data['email'])
        ->where('otp', $data['otp'])
        ->where('otp_expires_at', '>', Carbon::now())
            ->first();

        return $user;
    }

}