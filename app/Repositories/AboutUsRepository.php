<?php

namespace App\Repositories;

use App\Models\AboutUs;

class AboutUsRepository
{
    public function getAllAboutUs()
    {
        return AboutUs::all();
    }

    public function createAboutUs(array $data)
    {
        return AboutUs::create($data);
    }

    public function updateAboutUs($id, array $data)
    {
        $aboutUs = AboutUs::findOrFail($id);
        $aboutUs->update($data);
        return $aboutUs;
    }

    public function deleteAboutUs($id)
    {
        $aboutUs = AboutUs::findOrFail($id);
        return $aboutUs->delete();
    }
}
