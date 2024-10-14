<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegistrationResource;
use Illuminate\Http\Request;
use App\Repositories\WalletRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\UserBankDetailsRepository;
use App\Models\RecurringCharge;
use App\Models\LoanHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $WalletRepository;
    private $HistoryRepository;
    private $PaymentRepository;
    private $UserBankDetailsRepository;

    public function __construct(
        WalletRepository $WalletRepository,
        HistoryRepository $HistoryRepository,
        PaymentRepository $PaymentRepository,
        UserBankDetailsRepository $UserBankDetailsRepository
    ) {
        $this->WalletRepository = $WalletRepository;
        $this->HistoryRepository = $HistoryRepository;
        $this->PaymentRepository = $PaymentRepository;
        $this->UserBankDetailsRepository = $UserBankDetailsRepository;
    }

    /**
     * Show the application dashboard (REST API version).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Get the authenticated user information
        $uid = Auth::user()->id;
        $UserId = Auth::user()->email;
        $user = Auth::user();
        $UserStatus = Auth::user()->status;

        // Gather data from various repositories and models
        $data = [
            'wallet' => $this->WalletRepository->getWalletBalance($uid),
            'TotalFund' => $this->PaymentRepository->getPaymentsById($uid),
            'Record' => $this->UserBankDetailsRepository->getDetailsById($UserId),
            'Recurring' => RecurringCharge::where('user_email', $UserId)->get(),
            'OutLoan' => LoanHistory::where(function ($chek) {
                $chek->where('processing_state', 'successful')
                    ->orWhere('processing_state', 'delivered');
            })
                ->where(function ($query) {
                    $query->where('payment_status', 'pending')
                        ->orWhere('payment_status', 'partially');
                })
                ->where('user_id', $uid)
                ->get(),
            'TotalSpend' => DB::table('histories')
                ->where('user_id', $uid)
                ->where('processing_state', '!=', 'failed')
                ->orderBy('id', 'DESC')->get(),
            'user' => new RegistrationResource($user),
        ];

        // Check user status and return appropriate response
        if ($UserStatus == 1) {
            return response()->json([
                'message' => 'Congrats, You\'ve Successfully Registered',
                'data' => $data
            ], 200); // 200 OK
        } else {
            return response()->json([
                'message' => 'Account Suspended, Contact Administrator'
            ], 403); // 403 Forbidden
        }
    }
}
