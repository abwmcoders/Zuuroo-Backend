<?php

namespace App\Http\Controllers;

use App\Repositories\LoanLimitRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class LoanLimitController extends Controller
{
    use ApiResponseTrait;
    protected $repository;

    public function __construct(LoanLimitRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->successResponse(data: $this->repository->getAll());
    }

    public function show($id)
    {
        $loanLimit = $this->repository->findById($id);
        if ($loanLimit) {
            return response()->json($loanLimit);
        }
        return response()->json(['message' => 'Not Found'], 404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'labelName' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|boolean',
        ]);

        $loanLimit = $this->repository->create($validated);
        return response()->json($loanLimit, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'labelName' => 'sometimes|string|max:255',
            'percentage' => 'sometimes|numeric|min:0|max:100',
            'status' => 'sometimes|boolean',
        ]);

        $loanLimit = $this->repository->update($id, $validated);
        if ($loanLimit) {
            return response()->json($loanLimit);
        }
        return response()->json(['message' => 'Not Found'], 404);
    }

    public function destroy($id)
    {
        if ($this->repository->delete($id)) {
            return response()->json(['message' => 'Deleted']);
        }
        return response()->json(['message' => 'Not Found'], 404);
    }
}
