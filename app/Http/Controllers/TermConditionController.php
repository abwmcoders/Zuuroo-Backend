<?php

namespace App\Http\Controllers;

use App\Services\TermConditionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class TermConditionController extends Controller
{
    use ApiResponseTrait;
    protected $termConditionService;

    public function __construct(TermConditionService $termConditionService)
    {
        $this->termConditionService = $termConditionService;
    }

    // Get all TermConditions
    public function index()
    {
        $terms = $this->termConditionService->getAllTerms();
        return $this->successResponse(data: $terms,);
    }

    // Get a specific TermCondition by ID
    public function show($id)
    {
        $term = $this->termConditionService->getTermById($id);
        return response()->json($term, 200);
    }

    // Create a new TermCondition
    public function store(Request $request)
    {
        $term = $this->termConditionService->createTerm($request);
        return response()->json(['message' => 'Term and Condition created successfully', 'data' => $term], 201);
    }

    // Update a TermCondition
    public function update(Request $request, $id)
    {
        $term = $this->termConditionService->updateTerm($request, $id);
        return response()->json(['message' => 'Term and Condition updated successfully', 'data' => $term], 200);
    }

    // Delete a TermCondition
    public function destroy($id)
    {
        $this->termConditionService->deleteTerm($id);
        return response()->json(['message' => 'Term and Condition deleted successfully'], 200);
    }
}
