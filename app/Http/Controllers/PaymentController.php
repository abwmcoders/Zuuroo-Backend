<?php

namespace App\Http\Controllers;

use App\Models\UserCardDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Repositories\PaystackRepository;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    protected $paystackRepository;

    public function __construct(PaystackRepository $paystackRepository)
    {
        $this->paystackRepository = $paystackRepository;
    }

    public function initializePayment(Request $request)
    {
        $defaultAmount = 50 * 100;
        $amount = $request->input('amount') ?? $defaultAmount;
        $reference = 'TXN_' . uniqid();

        $paymentDetails = [
            'email' => Auth::user()->email,
            'amount' => $amount,
            'reference' => $reference,
            'callback_url' => URL::to('/api/payment/verify'),
            'trxref' => ''
        ];

        $response = $this->paystackRepository->processPayment($paymentDetails);

        if ($response['status'] === true) {
            $paymentUrl = $response['data']['authorization_url'];
            return response()->json([
                'status' => 'success',
                'message' => 'Payment initialized successfully',
                'payment_url' => $paymentUrl,
                'reference' => $reference
            ], 200);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Something went wrong, payment could not be processed'
            ], 500);
        }
    }

    public function verifyTransaction(Request $request)
    {
        $reference = $request->input('reference');
        $url = "https://api.paystack.co/transaction/verify/{$reference}";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ',
        ])->get($url);

        if ($response->successful() && $response['data']['status'] === 'success') {
            return response()->json(['message' => 'Transaction verified successfully'], 200);
        } else {
            return response()->json(['message' => 'Transaction verification failed'], 400);
        }
    }
}
