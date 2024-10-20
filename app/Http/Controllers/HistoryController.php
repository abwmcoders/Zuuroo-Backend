<?php

namespace App\Http\Controllers;

use App\Repositories\HistoryRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{

    use ApiResponseTrait;
    private $historyRepository;

    public function __construct(HistoryRepository $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }

    // 1. Get All Histories
    public function index(): JsonResponse
    {
        $histories = $this->historyRepository->getAllHistories();
        return $this->successResponse(data: $histories);
    }

    // 2. Get Single History by ID
    public function show(int $id): JsonResponse
    {
        $history = $this->historyRepository->getHistoryById($id);
        return response()->json($history);
    }

    // 3. Create a New History
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'plan' => 'required|string',
            'purchase' => 'required|string',
            'country_code' => 'required|string',
            'operator_code' => 'required|string',
            'transfer_ref' => 'required|string|unique:histories',
            'phone_number' => 'required|string',
            'selling_price' => 'required|numeric',
            'receive_value' => 'required|numeric',
            'receive_currency' => 'required|string',
            'commission_applied' => 'required|string',
            'processing_state' => 'required|boolean',
            'created_at' => 'required|date',
        ]);

        $history = $this->historyRepository->createHistory($data);
        return response()->json($history, 201);
    }

    // 4. Update an Existing History
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'plan' => 'string',
            'purchase' => 'string',
            'phone_number' => 'string',
            'selling_price' => 'numeric',
            'receive_value' => 'numeric',
            'receive_currency' => 'string',
            'commission_applied' => 'string',
            'processing_state' => 'boolean',
        ]);

        $updated = $this->historyRepository->updateHistory($id, $data);

        if (!$updated) {
            return response()->json(['message' => 'History not found'], 404);
        }

        return response()->json(['message' => 'History updated successfully']);
    }

    // 5. Delete a History
    public function destroy(int $id): JsonResponse
    {
        $this->historyRepository->deleteHistory($id);
        return response()->json(['message' => 'History deleted successfully']);
    }

    // 6. Get Data Purchase Histories
    public function getDataHistories(): JsonResponse
    {
        $dataHistories = $this->historyRepository->getAllDataHistories();
        return response()->json($dataHistories);
    }

    // 7. Get Airtime Purchase Histories
    public function getAirtimeHistories(): JsonResponse
    {
        $airtimeHistories = $this->historyRepository->getAllAirtimeHistories();
        return response()->json($airtimeHistories);
    }

    // 8. Get User-Specific History
    public function getUserHistory(): JsonResponse
    {
        $id = Auth::user()->id;
        //$userHistories = $this->historyRepository->getAllHistoryByUser($id);
        $userHistories = $this->historyRepository->getHistoryByUser($id);
        return $this->successResponse(data: $userHistories);
    }

    // 9. Get User-Specific Purchase History
    public function getUserPurchaseHistory(string $purchase): JsonResponse
    {
        $id = Auth::user()->id;
        $purchaseHistories = $this->historyRepository->getPurchaseHistoryByUser($purchase, $id);
        return $this->successResponse(data: $purchaseHistories); 
        
    }

    // 9. Get User-Specific Purchase History
    public function getUserProcessingStateHistory(string $state): JsonResponse
    {
        $id = Auth::user()->id;
        $purchaseHistories = $this->historyRepository->getProcessingStateHistoryByUser($state, $id);
        return $this->successResponse(data: $purchaseHistories); 
        
    }

    public function getByPurchase(Request $request)
    {
        $purchase = $request->input('purchase');

        $histories = $this->historyRepository->getByPurchase($purchase);

        return $this->successResponse(data: $histories,);
    }

    public function getByProcessingState(Request $request)
    {
        $state = $request->input('state');

        $histories = $this->historyRepository->getByProcessingState($state);

        return $this->successResponse(data: $histories,);
    }
}
