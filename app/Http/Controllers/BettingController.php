<?php

namespace App\Http\Controllers;

use App\Models\Kyc;
use App\Models\RecurringCharge;
use App\Repositories\HistoryRepository;
use App\Repositories\LoanHistoryRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\BettingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class BettingController extends Controller
{
    protected $bettingService;
    private $UserRepository;
    use ApiResponseTrait;
    private LoanHistoryRepository $LoanHistoryRepository;
    private $WalletRepository;
    private $HistoryRepository;
    

    public function __construct(BettingService $bettingService, UserRepository $UserRepository, LoanHistoryRepository $LoanHistoryRepository, WalletRepository $WalletRepository, HistoryRepository $HistoryRepository,)
    {
        $this->bettingService = $bettingService;
        $this->UserRepository = $UserRepository;
        $this->LoanHistoryRepository = $LoanHistoryRepository;
        $this->WalletRepository = $WalletRepository;
    }

    public function getBillers()
    {
        return response()->json($this->bettingService->getBillers());
    }

    public function validateCustomerId(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'customerId' => 'required|string',
        ]);

        return response()->json($this->bettingService->validateCustomerId($request->provider, $request->customerId));
    }

    public function purchaseBet(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'reference' => 'required|string',
            'customerId' => 'required|string',
            'amount' => 'required|string',
        ]);
        $uid                = Auth::user()->id;
        $user               = $this->UserRepository->getUserById($uid);
        $CheckCard          = RecurringCharge::where('user_id', $uid)->where('status', 1)->first();
        $Kyc                = Kyc::where('user_id', $uid)->first();
        $req_Account_process = $this->WalletRepository->getWalletBalance($uid);
        $req_bal_process    = $req_Account_process->balance;
        $req_loanBal_process = $req_Account_process->loan_balance;

        if ($user->email_verified_at != "" && $user->create_pin != 0){
            if (Hash::check($request->pin, $user->create_pin)){
                if ($request->top_up == 1){
                    if ($req_bal_process < $request->amount) {
                        return response()->json([
                            'success'       => false,
                            'statusCode'    => 500,
                            'message'       => 'Error, Insufficient fund !!!'
                        ]);
                    } else {
                        // Debit User Account Before Proceeding For Transaction .....
                        $new_bal_process = $req_bal_process - $request->amount;
                        $walletDetails = ['balance' => $new_bal_process, 'updated_at' => NOW()];
                        $this->WalletRepository->updateWallet($uid, $walletDetails);
                        $response = json_decode($this->bettingService->purchaseBet($request->all()));
                        if (property_exists($response, 'success') && $response->success == true) {

                            $HistoryDetails = [
                                'user_id'               => $uid,
                                'plan'                  =>  $request->provider,
                                'purchase'              => "Betting",
                                'country_code'          => "NG",
                                'operator_code'         => $request->provider,
                                'product_code'          => "VTU",
                                'transfer_ref'          => $response->data->reference,
                                'phone_number'          => $user->mobile,
                                'distribe_ref'          => $response->data->orderNo,
                                'selling_price'         => $response->amount,
                                // 'description'           => 'Delivered',
                                //'deviceNo'              => $request->meterNumber,
                                'commission_applied'    => 0,
                                'send_value'            => $request->amount,
                                'receive_value'         => $request->amount,
                                'receive_currency'      =>  'NGN',
                                'cost_price'            => $request->amount,
                                'startedUtc'            => NOW(),
                                'completedUtc'          => $response->create_date,
                                'processing_state'      => $response->data->status,
                            ];
                            $createHistory = $this->HistoryRepository->createHistory($HistoryDetails);
                            if ($createHistory) {
                                return response()->json([
                                    'success'       => true,
                                    'statusCode'    => 200,
                                    'message'       => 'You\'ve Successfully Placed A Bet'
                                ]);
                            } else {

                                return response()->json([
                                    'success'       => false,
                                    'statusCode'    => 500,
                                    'message'       => 'Transaction failed, try later !!!'
                                ]);
                            }
                        } else {

                            // Failed Transaction Auto Refund User Wallet
                            $new_bal_process = $req_bal_process + $request->amount;
                            $walletDetails = ['balance' => $new_bal_process, 'updated_at' => NOW()];
                            $this->WalletRepository->updateWallet($uid, $walletDetails);
                            Log::error(['err' => $request, "Details" => $request->all()]);
                            return response()->json([
                                'success'       => false,
                                'statusCode'    => 500,
                                'message'       => 'Error, your account has been auto-refunded !!!'
                            ]);
                        }
                        return $response;
                    }
                }else if($request->top_up == 2) {
                    if (!empty($CheckCard)) {
                        $isLoan = $this->LoanHistoryRepository->getUserLoan($uid);
                        if (!empty($isLoan)) {
                            return $this->errorResponse(message: 'You have an outstanding debt !!!', code: 500,);
                        }else {
                            if ($Kyc){
                                if ($Kyc->verificationStatus == 1){
                                    if ($req_loanBal_process >= 100) {
                                        return $this->errorResponse(message: 'Your Balance Is Still High, You Cannot Loan At This Time !!!',);
                                    }else{
if ($req_bal_process < $request->amount) {
                                            return response()->json([
                                                'success'       => false,
                                                'statusCode'    => 500,
                                                'message'       => 'Error, Insufficient fund !!!'
                                            ]);
                                        } else {
                                            // Debit User Account Before Proceeding For Transaction .....
                                            $new_bal_process = $req_bal_process - $request->amount;
                                            $walletDetails = ['balance' => $new_bal_process, 'updated_at' => NOW()];
                                            $this->WalletRepository->updateWallet($uid, $walletDetails);
                                            $response = json_decode($this->bettingService->purchaseBet($request->all()));
                                            if(property_exists($response, 'success') && $response->success == true){

                                                $HistoryDetails = [
                                                    'user_id'               => $uid,
                                                    'plan'                  =>  $request->provider,
                                                    'purchase'              => "Betting",
                                                    'country_code'          => "NG",
                                                    'operator_code'         => $request->provider,
                                                    'product_code'          => "VTU",
                                                    'transfer_ref'          => $response->data->reference,
                                                    'phone_number'          => $user->mobile,
                                                    'distribe_ref'          => $response->data->orderNo,
                                                    'selling_price'         => $response->amount,
                                                    // 'description'           => 'Delivered',
                                                    //'deviceNo'              => $request->meterNumber,
                                                    'commission_applied'    => 0,
                                                    'send_value'            => $request->amount,
                                                    'receive_value'         => $request->amount,
                                                    'receive_currency'      =>  'NGN',
                                                    'cost_price'            => $response->amount,
                                                    'startedUtc'            => NOW(),
                                                    'completedUtc'          => $response->create_date,
                                                    'processing_state'      => $response->data->status,
                                                ];
                                                $createHistory = $this->HistoryRepository->createHistory($HistoryDetails);
                                                if ($createHistory) {

                                                    return response()->json([
                                                        'success'       => true,
                                                        'statusCode'    => 200,
                                                        'message'       => 'You\'ve Successfully Placed A Bet'
                                                    ]);
                                                } else {

                                                    return response()->json([
                                                        'success'       => false,
                                                        'statusCode'    => 500,
                                                        'message'       => 'Transaction failed, try later !!!'
                                                    ]);
                                                }
                                            }else{
                                               
                                                        // Failed Transaction Auto Refund User Wallet
                                                        $new_bal_process = $req_bal_process + $request->amount;
                                                        $walletDetails = ['balance' => $new_bal_process, 'updated_at' => NOW()];
                                                        $this->WalletRepository->updateWallet($uid, $walletDetails);
                                                        Log::error(['err' => $request, "Details" => $request->all()]);
                                                        return response()->json([
                                                            'success'       => false,
                                                            'statusCode'    => 500,
                                                            'message'       => 'Error, your account has been auto-refunded !!!'
                                                        ]);
                                                    
                                            }
                                            return $response;
                                        }
                                    }
                                }else {
                                    return $this->errorResponse(message: 'Verification Status Is Still Pending !!!',);
                                }
                            }else{
                                return $this->errorResponse(message: 'Kindly proceed to kyc page !!!',);
                            }
                        }
                    }else {
                        return $this->errorResponse(message: 'Please Add Card To Continue !!!', code: 500,);
                    }
                } else {
                    return $this->errorResponse(message: 'Invalid Selection, Please Make a Choice !!!', code: 500,);
                }
            } else{
                return response()->json([
                    'success'       => false,
                    'statusCode'    => 500,
                    'message'       => 'Error, Invalid PIN !!'
                ]);
            }
        }else {
            return response()->json([
                'success'       => false,
                'statusCode'    => 500,
                'message'       => 'Unverified, Please verify your account to continue !!!'
            ]);
        }

        
    }
}
