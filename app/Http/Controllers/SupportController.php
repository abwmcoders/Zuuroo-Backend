<?php

namespace App\Http\Controllers;

use App\Repositories\SupportRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SupportController extends Controller
{
    protected $supportRepository;
    use ApiResponseTrait;

    public function __construct(SupportRepository $supportRepository)
    {
        $this->supportRepository = $supportRepository;
    }

    public function index()
    {
        $supports = $this->supportRepository->getAllSupports();
        return $this->successResponse(data: $supports);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_type' => 'required|string',
            'page_name' => 'required|string',
            'page_link' => 'required|url',
            'page_icon' => 'nullable|string',
        ]);

        $support = $this->supportRepository->createSupport($validated);
        return response()->json($support, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'page_type' => 'sometimes|string',
            'page_name' => 'sometimes|string',
            'page_link' => 'sometimes|url',
            'page_icon' => 'nullable|string',
        ]);

        $this->supportRepository->updateSupport($id, $validated);
        return response()->json(['message' => 'Support updated successfully.'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->supportRepository->deleteSupportRecord($id);
        return response()->json(['message' => 'Support deleted successfully.'], Response::HTTP_OK);
    }
}

