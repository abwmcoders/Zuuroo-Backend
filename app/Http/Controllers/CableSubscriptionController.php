<?php

namespace App\Http\Controllers;

use App\Models\CableSubscription;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CableSubscriptionController extends Controller
{

    use ApiResponseTrait;

    public function index()
    {
        return response()->json(CableSubscription::all(), 200);
    }

    // Create a new cable subscription
    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider_name' => 'required|string|max:255',
            'provider_code' => 'required|string|max:255|unique:cable_subscriptions',
            'country_code' => 'required|string|max:3',
            'status' => 'required|string|in:active,inactive',
        ]);

        $cableSubscription = CableSubscription::create($validated);
        return response()->json($cableSubscription, 201);
    }

    // Get a specific cable subscription by ID
    public function show($id)
    {
        $cableSubscription = CableSubscription::findOrFail($id);
        return response()->json($cableSubscription, 200);
    }

    // Update an existing cable subscription
    public function update(Request $request, $id)
    {
        $cableSubscription = CableSubscription::findOrFail($id);

        $validated = $request->validate([
            'provider_name' => 'sometimes|string|max:255',
            'provider_code' => 'sometimes|string|max:255|unique:cable_subscriptions,provider_code,' . $id,
            'country_code' => 'sometimes|string|max:3',
            'status' => 'sometimes|string|in:active,inactive',
        ]);

        $cableSubscription->update($validated);
        return response()->json($cableSubscription, 200);
    }

    // Delete a cable subscription
    public function destroy($id)
    {
        $cableSubscription = CableSubscription::findOrFail($id);
        $cableSubscription->delete();

        return response()->json(['message' => 'Cable subscription deleted successfully'], 200);
    }

    // Get all cable subscriptions by status
    public function getByStatus($status)
    {
        $cableSubscriptions = CableSubscription::where('status', $status)->get();
        return $this->successResponse(data: $cableSubscriptions);
    }
}
