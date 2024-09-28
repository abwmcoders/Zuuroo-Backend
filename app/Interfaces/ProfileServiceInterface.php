<?php

namespace App\Interfaces;

use App\Models\User;

interface ProfileServiceInterface
{
    public function getProfile(User $user): array;
    public function updateProfile(User $user, array $data): User;
}
