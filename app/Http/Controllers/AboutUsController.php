<?php

namespace App\Http\Controllers;

use App\Repositories\AboutUsRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    use ApiResponseTrait;
    protected $aboutUsRepo;

    public function __construct(AboutUsRepository $aboutUsRepo)
    {
        $this->aboutUsRepo = $aboutUsRepo;
    }

    public function index()
    {
        return $this->successResponse(data: $this->aboutUsRepo->getAllAboutUs(),);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'description' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
        ]);

        $aboutUs = $this->aboutUsRepo->createAboutUs($validated);
        return $this->successResponse(data: $aboutUs, code: 201,);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'contact_email' => 'sometimes|email',
            'contact_phone' => 'sometimes|string|max:20',
        ]);

        $aboutUs = $this->aboutUsRepo->updateAboutUs($id, $validated);
        return $this->successResponse(data: $aboutUs,);
    }

    public function destroy($id)
    {
        $this->aboutUsRepo->deleteAboutUs($id);
        return $this->successResponse(message: 'About Us entry deleted successfully.',);
    }
}
