<?php

namespace App\Services;

use App\Interfaces\ProfileServiceInterface;
use App\Models\User;

class ProfileService implements ProfileServiceInterface
{
    public function getProfile(User $user): array
    {
        return $user->toArray();
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }
}