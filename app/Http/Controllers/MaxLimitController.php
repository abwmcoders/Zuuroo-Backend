<?php

namespace App\Http\Controllers;

use App\Repositories\MaxLimitRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class MaxLimitController extends Controller
{
    use ApiResponseTrait;
    protected $repository;

    public function __construct(MaxLimitRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return $this->successResponse(data: $this->repository->getAll(),);
    }

    public function show($id)
    {
        $topupLimit = $this->repository->findById($id);
        if ($topupLimit) {
            return $this->successResponse(data: $topupLimit);
        }
        return $this->errorResponse(message: 'Not Found' , code: 404);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'topup' => 'required|string',
            'limit_value' => 'required|integer',
            'admin' => 'required|string',
        ]);

        $topupLimit = $this->repository->create($validated);
        return $this->successResponse(data: $topupLimit, code: 201,);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'topup' => 'sometimes|string',
            'limit_value' => 'sometimes|integer',
            'admin' => 'sometimes|string',
        ]);

        $topupLimit = $this->repository->update($id, $validated);
        if ($topupLimit) {
            return
            $this->successResponse(data: $topupLimit);
        }
        return $this->errorResponse(message: 'Not Found', code: 404,);
    }

    public function destroy($id)
    {
        if ($this->repository->delete($id)) {
            return
            $this->successResponse(message: 'Deleted');
        }
        return $this->errorResponse(message: "Not Found", code: 404,);
    }
}
