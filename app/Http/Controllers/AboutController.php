<?php

namespace App\Http\Controllers;

use App\Repositories\AboutRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    use ApiResponseTrait;
    protected $itemRepository;

    public function __construct(AboutRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function index()
    {
        return $this->successResponse($this->itemRepository->all(),);
    }

    public function show($id)
    {
        return response()->json($this->itemRepository->find($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);
        $item = $this->itemRepository->create($validated);
        return response()->json($item, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:active,inactive',
        ]);
        $item = $this->itemRepository->update($id, $validated);
        return response()->json($item);
    }

    public function destroy($id)
    {
        $this->itemRepository->delete($id);
        return response()->json(null, 204);
    }
}
