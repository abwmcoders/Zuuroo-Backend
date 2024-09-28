<?php

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Http\Request;

interface UserServiceInterface
{
    public function register(array $data): User;
    public function login(array $data): string;
    public function verifyOtp(array $data): User;
    public function requestPasswordReset(array $data): User;
    public function resetPassword(array $data): User;
}
