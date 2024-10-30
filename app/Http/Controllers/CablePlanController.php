<?php

namespace App\Http\Controllers;

use App\Models\CablePlan;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CablePlanController extends Controller
{
    use ApiResponseTrait;
    // Get all plans
    public function index()
    {
        return response()->json(CablePlan::all(), 200);
    }

    public function  plansByProvider($id)
    {
        return response()->json(CablePlan::where('provider_code', $id)->get(), 200);
    }

    // Create a new plan
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'channels' => 'required|array',
            'provider_code' => 'required|string|max:255',
        ]);

        $plan = CablePlan::create($validated);
        return response()->json($plan, 201);
    }

    // Get a specific plan by ID
    public function show($id)
    {
        $plan = CablePlan::findOrFail($id);
        return response()->json($plan, 200);
    }

    // Update an existing plan
    public function update(Request $request, $id)
    {
        $plan = CablePlan::findOrFail($id);

        $validated = $request->validate([
            'plan' => 'sometimes|string|max:255',
            'price' => 'sometimes|string|max:255',
            'channels' => 'sometimes|array',
            'provider_code' => 'sometimes|string|max:255',
        ]);

        $plan->update($validated);
        return response()->json($plan, 200);
    }

    // Delete a plan
    public function destroy($id)
    {
        $plan = CablePlan::findOrFail($id);
        $plan->delete();

        return response()->json(['message' => 'Plan deleted successfully'], 200);
    }

    // Get all plans by provider_code
    public function getByProviderCode($provider_code)
    {
        $plans = CablePlan::where('provider_code', $provider_code)->get();
        return $this->successResponse(data: $plans,);
    }
}
