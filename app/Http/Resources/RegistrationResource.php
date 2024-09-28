<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationResource extends JsonResource
{

    public static $wrap = false;
    
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'date_of_birth' => $this->dob,
            'gender' => $this->gender,
            'phone_number' => $this->mobile,
            'country' => $this->country,
        ];
    }
}
