<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Kyc;
use App\Repositories\BillPaymentRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\WalletRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Stmt\Return_;


use App\Models\Referral;
use App\Models\OtherProduct;
use App\Models\RecurringCharge;
use App\Models\ReferralBonus;
use App\Repositories\LoanHistoryRepository;
use App\Traits\ApiResponseTrait;
// use Validator;

class BillPayment extends Controller
{
    //
    use ApiResponseTrait;
    private $HistoryRepository;
    private $UserRepository;
    private $BillPaymentRepository;
    private LoanHistoryRepository $LoanHistoryRepository;
    private $WalletRepository;
    private $ApiKey;
    private $Secrete_Key;


    public function __construct(
        HistoryRepository $HistoryRepository,
        UserRepository $UserRepository,
        BillPaymentRepository $BillPaymentRepository,
        WalletRepository $WalletRepository,
        LoanHistoryRepository $LoanHistoryRepository,
    ) {
        $this->BillPaymentRepository = $BillPaymentRepository;
        $this->HistoryRepository = $HistoryRepository;
        $this->WalletRepository = $WalletRepository;
        $this->UserRepository = $UserRepository;
        $this->LoanHistoryRepository = $LoanHistoryRepository;
        $this->ApiKey = "bf31dc50bba05e8455154eb725fa13ea";
        $this->Secrete_Key = "SK_236b54e198b86b4ba713e19ebb7deeb4507d1779587";
    }

    public function verify_meterNo(Request $request)
    // : JsonResponse
    {
        $Validator = Validator::make($request->all(), [
            'meterNumber' => ['required'],
            'discoName'   => ['required'],
            'meterType'   => ['required']
        ]);

        $meterNumber =   $request->meterNumber;
        $discoName  =   $request->discoName;
        $meterType  =   strtolower($request->meterType);

        // Log::debug(['Error:' => $billersCode, $serviceID, $meterType ]);

        if ($Validator->passes()) {
            $billDetails = [
                'billerNumber'   => $meterNumber,
                'disco'     => $discoName,
                'type'          => $meterType

            ];

            $response = $this->BillPaymentRepository->verifyMeterNumber($billDetails);
            return response()->json($response);
        } else {
            return response()->json([
                'statusCode' => 400,
                'success'   => false,
                'message'   => 'Check All Input Fields !!!',
            ]);
        }
    }

    public function verify_iucNo(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'iucNumber' => ['required'],
            'cableName'   => ['required']
        ]);


        if ($Validator->passes()) {
            $billDetails = [
                'iuc'   => $request->iucNumber,
                'cable'     => $request->cableName,
            ];

            $response = $this->BillPaymentRepository->verifyIUCNumber($billDetails);
            return response()->json($response);
        } else {
            return response()->json([
                'statusCode' => 400,
                'success'   => false,
                'message'   => 'Check All Input Fields !!!',
            ]);
        }
    }

    // Getting Cable TV Plans
    public function getCablePlan(Request $request)
    {
        try {
            $cableId = $request->route('id');
            $response = $this->BillPaymentRepository->getCablePlan($cableId);
            return $response;
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function getElectPlan(Request $request)
    {
        try {
            $cableId = $request->route('id');
            $response = $this->BillPaymentRepository->getCablePlan($cableId);
            return $response;
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    // payElectricity

    public function payElectricity(Request $request)
    {

        // try{
        date_default_timezone_set("Africa/Lagos");
        // echo date_default_timezone_get();
        $requestID = date('YmdHi') . rand(99, 9999999);

        $uid                    = Auth::user()->id;
        $req_Account_process    = $this->WalletRepository->getWalletBalance($uid);
        $req_bal_process        = $req_Account_process->balance;
        $req_loanBal_process    = $req_Account_process->loan_balance;
        $user                   = $this->UserRepository->getUserById($uid);
        $LoanCountry            = Country::where('is_loan', true)->where('country_code', $request->country)->get();
        $amount                 = $request->amount;

        // -------------------------------- KYC --------------------------------------------------------------------------------------
        $Kyc                    = Kyc::where('user_id', $uid)->first();
        // ---------------------------------------------------------------------------------------------------------------------------

        // -------------------------------- CHECK CARD --------------------------------------------------------------------------------
        $CheckCard              = RecurringCharge::where('user_id', $uid)->where('status', 1)->first();

        $Validator = Validator::make($request->all(), [
            'top_up'                => 'required|numeric',
            'billerName'            => 'required|string',
            'meterType'             => 'required|integer|in:1,2',
            'meterNumber'           => 'required|string',
            'amount'                => 'required|string',
            'customerName'          => 'required|string',
            'customerPhoneNumber'   => 'required|string'
        ]);

        $meterType      = (int)$request->meterType;

        if ($Validator->passes()) {

            // Validate Account Verification
            if ($user->email_verified_at != "" && $user->create_pin != 0) {
                if (Hash::check($request->pin, $user->create_pin)) {
                    // dd('Debugging here');

                    if ($request->top_up == 1) {
                        if ($req_bal_process < $request->amount) {

                            return response()->json([
                                'success'       => false,
                                'statusCode'    => 500,
                                'message'       => 'Insufficient fund !!!'
                            ]);
                        } else {
                            $phoneNumber = str_replace('234', '0', $request->customerPhoneNumber);

                            // Debit User Account Before Proceeding For Transaction .....
                            $new_bal_process = $req_bal_process - $request->amount;
                            $walletDetails = ['balance' => $new_bal_process, 'updated_at' => NOW()];
                            $this->WalletRepository->updateWallet($uid, $walletDetails);

                            $billDetails = [
                                "disco_name"        => $request->billerName,
                                "amount"            => $request->amount,
                                "meter_number"      => $request->meterNumber,
                                "MeterType"         => $meterType,
                            ];
                            $response = json_decode($this->BillPaymentRepository->payElectricity($billDetails));


                           // return response()->json($response);
                            // !TODO: STORE TO HISTORY
                            // return $response;
                            if (isset($createNigData->Status) && $createNigData->Status == 'successful') {

                                $HistoryDetails = [
                                    'user_id'               => $request->userID,
                                    'purchase'              => 'electricity',
                                    'country_code'          =>  "NG",
                                    'operator_code'         =>  $request->billerName,
                                    'plan'                  => $response['content']['transactions']['type'],
                                    'product_code'          => $response['content']['transactions']['product_name'],
                                    'transfer_ref'          => $response['requestId'],
                                    'phone_number'          => $response['content']['transactions']['phone'],
                                    'distribe_ref'          => $response['content']['transactions']['transactionId'],
                                    'selling_price'         => $response['content']['transactions']['amount'],
                                    'cost_price'         => $response['content']['transactions']['total_amount'],
                                    'receive_value'         =>  "1 Unit",
                                    'receive_currency'      =>  'NGN',
                                    //'deviceNo'              => $request->meterNumber,
                                    'commission_applied'    => $response['content']['transactions']['commission'],
                                    'processing_state'      => $response['content']['transactions']['status'],
                                    'startedUtc'            =>  NOW(),
                                    'completedUtc'          =>  NOW(),
                                    'send_value'            => $response['content']['transactions']['total_amount'],
                                ];

                                $createHistory = $this->HistoryRepository->createHistory($HistoryDetails);
                                if ($createHistory) {

                                    return response()->json([
                                        'success'       => true,
                                        'statusCode'    => 200,
                                        'message'       => 'You\'ve Successfully Purchased A Biller'
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

                                return response()->json([
                                    'success'       => false,
                                    'statusCode'    => 500,
                                    'message'       => 'But Your wallet has not been debited',
                                    'data'          => $response,
                                ]);
                            }
                        }
                    } else if ($request->top_up == 2) {
                        if (!empty($CheckCard)) {
                            // Check if loan record exist ============================================================+++
                            $isLoan = $this->LoanHistoryRepository->getUserLoan($uid);
                            if (!empty($isLoan)) {
                                return $this->errorResponse(message: 'You have an outstanding debt !!!', code: 500,);
                            } else {
                                if ($Kyc) {
                                    if ($Kyc->verificationStatus == 1) {
                                        if ($LoanCountry) {
                                            if ($req_loanBal_process >= 100) {
                                                return $this->errorResponse(message: 'Your Balance Is Still High, You Cannot Loan At This Time !!!',);
                                            } else {

                                                $billDetails = [
                                                    'disco_name'    => $request->billerName,
                                                    'MeterType'     => $meterType,
                                                    'meter_number'  => $request->meterNumber,
                                                    'amount'        => $request->amount,
                                                ];
                                                $response = json_decode($this->BillPaymentRepository->payElectricity($billDetails));
                                                // return response()->json($response);
                                                //!TODO: STORE TO HISTORY
                                                if (isset($createNigData->Status) && $createNigData->Status == 'successful') {
                                                    //update loan amount
                                                    $new_loanBal_process = $req_loanBal_process + $amount;
                                                    $walletDetails = ['loan_balance' => $new_loanBal_process, 'updated_at' => NOW()];
                                                    $this->WalletRepository->updateWallet($uid, $walletDetails);

                                                    $HistoryDetails = [
                                                        'user_id'               => $request->userID,
                                                        'purchase'              => 'electricity',
                                                        'country_code'          =>  "NG",
                                                        'operator_code'         =>  $request->billerName,
                                                        'plan'                  => $response['content']['transactions']['type'],
                                                        'product_code'          => $response['content']['transactions']['product_name'],
                                                        'transfer_ref'          => $response['requestId'],
                                                        'phone_number'          => $response['content']['transactions']['phone'],
                                                        'distribe_ref'          => $response['content']['transactions']['transactionId'],
                                                        'selling_price'         => $response['content']['transactions']['amount'],
                                                        'cost_price'         => $response['content']['transactions']['total_amount'],
                                                        'receive_value'         =>  "1 Unit",
                                                        'receive_currency'      =>  'NGN',
                                                        //'deviceNo'              => $request->meterNumber,
                                                        'commission_applied'    => $response['content']['transactions']['commission'],
                                                        'processing_state'      => $response['content']['transactions']['status'],
                                                        'startedUtc'            =>  NOW(),
                                                        'completedUtc'          =>  NOW(),
                                                        'send_value'            => $response['content']['transactions']['total_amount'],
                                                    ];

                                                    $createHistory = $this->HistoryRepository->createHistory($HistoryDetails);
                                                    if ($createHistory) {

                                                        return response()->json([
                                                            'success'       => true,
                                                            'statusCode'    => 200,
                                                            'message'       => 'You\'ve Successfully Purchased A Biller'
                                                        ]);
                                                    } else {

                                                        return response()->json([
                                                            'success'       => false,
                                                            'statusCode'    => 500,
                                                            'message'       => 'Transaction failed, try later !!!'
                                                        ]);
                                                    }
                                                } else {

                                                    return response()->json([
                                                        'success'       => false,
                                                        'statusCode'    => 500,
                                                        'message'       => 'But Your wallet has not been debited'
                                                    ]);
                                                }
                                            }
                                        } else {
                                            return $this->errorResponse(message: 'Sorry, loan is not available in the selected country !!!',);
                                        }
                                    } else {
                                        return $this->errorResponse(message: 'Verification Status Is Still Pending !!!',);
                                    }
                                } else {
                                    return $this->errorResponse(message: 'Kindly proceed to kyc page !!!',);
                                }
                            }
                        } else {
                            return $this->errorResponse(message: 'Please Add Card To Continue !!!', code: 500,);
                        }
                    } else {
                        return $this->errorResponse(message: 'Invalid Selection, Please Make a Choice !!!', code: 500,);
                    }
                } else {

                    return response()->json([
                        'success'       => false,
                        'statusCode'    => 500,
                        'message'       => 'Error, Invalid PIN !!'
                    ]);
                }
            }
        } else {
            return response()->json([
                'statusCode' => 400,
                'success'   => false,
                'message'   => 'Check All Input Fields !!!',
            ]);
        }
        // } catch (\Exception $e) {
        //     return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        // }

    }

    // PayCable TV
    public function payCableTV(Request $request)
    {
        // try{
        $Validator = Validator::make($request->all(), [
            'top_up'                => 'required',
            'cableName'             => 'required',
            'cablePlan'             => 'required',
            'cableNumber'           => 'required',
            'customerName'          => 'required|string',
            'customerPhoneNumber'   => 'required|string'
        ]);
        
        $cableName      = (int)$request->cableName;
        $cableNumber    = (int)$request->cableNumber;
        $cablePlan      = (int)$request->cablePlan;

        if ($Validator->passes()) {

            date_default_timezone_set("Africa/Lagos");
            // echo date_default_timezone_get();
            $requestID          = date('YmdHi') . rand(99, 9999999);
            $planId             = $request->cablePlan;
            $uid                = Auth::user()->id;
            $req_Account_process = $this->WalletRepository->getWalletBalance($uid);
            $req_bal_process    = $req_Account_process->balance;
            $req_loanBal_process = $req_Account_process->loan_balance;
            $user               = $this->UserRepository->getUserById($uid);
            $LoanCountry        = Country::where('is_loan', true)->where('country_code', $request->country)->get();

            // -------------------------------- KYC --------------------------------------------------------------------------------------
            $Kyc                = Kyc::where('user_id', $uid)->first();
            // ---------------------------------------------------------------------------------------------------------------------------

            // -------------------------------- CHECK CARD --------------------------------------------------------------------------------
            $CheckCard          = RecurringCharge::where('user_id', $uid)->where('status', 1)->first();

            // Validate Account Verification
            if ($user->email_verified_at != "" && $user->create_pin != 0) {
                if (Hash::check($request->pin, $user->create_pin)) {

                    if ($request->top_up == 1) {
                        if ($req_bal_process < $request->amount) {
                            // return 'Insufficient fund';

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

                            $billDetails = [
                                'cablename'         => $cableName,
                                'cableplan'         => $planId,
                                'smart_card_number' => $cableNumber,
                            ];

                            $response = json_decode($this->BillPaymentRepository->payCableTV($billDetails));
                            Log::error(['err' => $response, "Details" => $billDetails]);
                            // return response()->json($response);

                            //! TODO:
                            if (isset($response->Status) && $response->Status == 'successful') {

                                $HistoryDetails = [
                                    'user_id'               => $request->userID,
                                    'plan'                  =>  $request->cablePlan,
                                    'purchase'              => "cable",
                                    'country_code'          => "NG",
                                    'operator_code'         => $request->cableName,
                                    'product_code'          => "VTU",
                                    'transfer_ref'          => $requestID,
                                    'phone_number'          => $request->customerPhoneNumber,
                                    'distribe_ref'          => $response->ident,
                                    'selling_price'         => $response->paid_amount,
                                    //'description'           => 'Delivered',
                                   // 'deviceNo'              => $request->meterNumber,
                                    'commission_applied'    => 0,
                                    'send_value'            => $request->amount,
                                    'receive_value'         => $request->amount,
                                    'receive_currency'      =>  'NGN',
                                    'cost_price'            => $response->paid_amount,
                                    'startedUtc'            => NOW(),
                                    'completedUtc'          => $response->create_date,
                                    'processing_state'      => $response->Status,
                                ];
                                $createHistory = $this->HistoryRepository->createHistory($HistoryDetails);
                                if ($createHistory) {

                                    return response()->json([
                                        'success'       => true,
                                        'statusCode'    => 200,
                                        'message'       => 'You\'ve Successfully Purchased A Biller'
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
                                Log::error(['err' => $response, "Details" => $billDetails]);
                                return response()->json([
                                    'success'       => false,
                                    'statusCode'    => 500,
                                    'message'       => 'Error, your account has been auto-refunded !!!',
                                    'data'          => $response
                                ]);
                            }
                            // return $user->create_pin;
                        }
                    } else if ($request->top_up == 2) {
                        if (!empty($CheckCard)) {
                            // Check if loan record exist ============================================================+++
                            $isLoan = $this->LoanHistoryRepository->getUserLoan($uid);
                            if (!empty($isLoan)) {
                                return $this->errorResponse(message: 'You have an outstanding debt !!!', code: 500,);
                            } else {
                                if ($Kyc) {
                                    if ($Kyc->verificationStatus == 1) {
                                        if ($LoanCountry) {
                                            if ($req_loanBal_process >= 100) {
                                                return $this->errorResponse(message: 'Your Balance Is Still High, You Cannot Loan At This Time !!!',);
                                            } else {
                                                if ($req_bal_process < $request->amount) {
                                                    // return 'Insufficient fund';

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

                                                    $billDetails = [
                                                        'cablename'         => $cableName,
                                                        'smart_card_number' => $cableNumber,
                                                        'cableplan'         => $planId,
                                                    ];
                                                    $response = json_decode($this->BillPaymentRepository->payCableTV($billDetails));
                                                    // return $response;
                                                    Log::error(['success' => $response, "Details" => $billDetails]);
                                                    if (isset($response->Status) && $response->Status == 'successful') {

                                                        $HistoryDetails = [
                                                            'user_id'               => $request->userID,
                                                            'plan'                  =>  $request->cablePlan, 
                                                            'purchase'              => "cable",
                                                            'country_code'          => "NG",
                                                            'operator_code'         => $request->cableName,
                                                            'product_code'          => "VTU",
                                                            'transfer_ref'          => $requestID,
                                                            'phone_number'          => $request->customerPhoneNumber,
                                                            'distribe_ref'          => $response->ident,
                                                            'selling_price'         => $response->paid_amount,
                                                           // 'description'           => 'Delivered',
                                                            //'deviceNo'              => $request->meterNumber,
                                                            'commission_applied'    => 0,
                                                            'send_value'            => $request->amount,
                                                            'receive_value'         => $request->amount,
                                                            'receive_currency'      =>  'NGN',
                                                            'cost_price'            => $response->paid_amount,
                                                            'startedUtc'            => NOW(),
                                                            'completedUtc'          => $response->create_date,
                                                            'processing_state'      => $response->Status,
                                                        ];
                                                        $createHistory = $this->HistoryRepository->createHistory($HistoryDetails);
                                                        if ($createHistory) {

                                                            return response()->json([
                                                                'success'       => true,
                                                                'statusCode'    => 200,
                                                                'message'       => 'You\'ve Successfully Purchased A Biller'
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
                                                        Log::error(['err' => $response, "Details" => $billDetails]);
                                                        // Alert::error('Oops', 'Internal Server Error');
                                                        // return back();

                                                        return response()->json([
                                                            'success'       => false,
                                                            'statusCode'    => 500,
                                                            'message'       => 'Error, your account has been auto-refunded !!!'
                                                        ]);
                                                    }
                                                    // return $user->create_pin;
                                                }
                                            }
                                        } else {
                                            return $this->errorResponse(message: 'Sorry, loan is not available in the selected country !!!',);
                                        }
                                    } else {
                                        return $this->errorResponse(message: 'Verification Status Is Still Pending !!!',);
                                    }
                                } else {
                                    return $this->errorResponse(message: 'Kindly proceed to kyc page !!!',);
                                }
                            }
                        } else {
                            return $this->errorResponse(message: 'Please Add Card To Continue !!!', code: 500,);
                        }
                    } else {
                        return $this->errorResponse(message: 'Invalid Selection, Please Make a Choice !!!', code: 500,);
                    }
                } else {
                    // return 'Invalif PIN !!!';
                    return response()->json([
                        'success'       => false,
                        'statusCode'    => 500,
                        'message'       => 'Error, Invalid PIN !!'
                    ]);
                }
            }
        } else {
            return response()->json([
                'statusCode' => 400,
                'success'   => false,
                'message'   => 'Check All Input Fields !!!',
            ]);
        }
        // } catch (\Exception $e) {
        //     return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        // }

    }

    //! get billers for electricity
    public function getBillers()
    {
        return response()->json($this->BillPaymentRepository->getAllBillers());
    }

    //! verify meter number
    public function verifyMeter(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'number' => 'required|string',
            'type' => 'required|string',
        ]);

        return response()->json($this->BillPaymentRepository->verify($request->provider, $request->number, $request->type));
    }

    public function buyPower(Request $request)
    {

        // try{
        date_default_timezone_set("Africa/Lagos");
        // echo date_default_timezone_get();
        $requestID = date('YmdHi') . rand(99, 9999999);

        $uid                    = Auth::user()->id;
        $req_Account_process    = $this->WalletRepository->getWalletBalance($uid);
        $req_bal_process        = $req_Account_process->balance;
        $req_loanBal_process    = $req_Account_process->loan_balance;
        $user                   = $this->UserRepository->getUserById($uid);
        $LoanCountry            = Country::where('is_loan', true)->where('country_code', $request->country)->get();
        $amount                 = $request->amount;

        // -------------------------------- KYC --------------------------------------------------------------------------------------
        $Kyc                    = Kyc::where('user_id', $uid)->first();
        // ---------------------------------------------------------------------------------------------------------------------------

        // -------------------------------- CHECK CARD --------------------------------------------------------------------------------
        $CheckCard              = RecurringCharge::where('user_id', $uid)->where('status', 1)->first();

        $Validator = Validator::make($request->all(), [
            'top_up'                => 'required|numeric',
            'provider'            => 'required|string',
            'meterType'             => 'required|string',
            'meterNumber'           => 'required|string',
            'amount'                => 'required|string',
            'reference'               => 'required|string',
            'customerName'          => 'required|string',
            'customerPhoneNumber'   => 'required|string'
        ]);

        // $meterType      = (int)$request->meterType;

        if ($Validator->passes()) {

            // Validate Account Verification
            if ($user->email_verified_at != "" && $user->create_pin != 0) {
                if (!Hash::check($request->pin, $user->create_pin)) {
                    // dd('Debugging here');

                    if ($request->top_up == 1) {
                        if ($req_bal_process > $request->amount) {

                            return response()->json([
                                'success'       => false,
                                'statusCode'    => 500,
                                'message'       => 'Insufficient fund !!!'
                            ]);
                        } else {
                            $phoneNumber = str_replace('234', '0', $request->customerPhoneNumber);

                            // Debit User Account Before Proceeding For Transaction .....
                            $new_bal_process = $req_bal_process - $request->amount;
                            $walletDetails = ['balance' => $new_bal_process, 'updated_at' => NOW()];
                            $this->WalletRepository->updateWallet($uid, $walletDetails);

                            $billDetails = [
                                "provider"        => $request->provider,
                                "amount"            => $request->amount,
                                "number"      => $request->meterNumber,
                                "type"         => $request->meterType,
                                "reference"         => $request->reference,
                            ];
                            $response = json_decode($this->BillPaymentRepository->purchasePower($billDetails));


                            // return response()->json($response);
                            // !TODO: STORE TO HISTORY
                            if ($response->success == true) {

                                $HistoryDetails = [
                                    'user_id'               => $uid,
                                    'purchase'              => 'electricity',
                                    'country_code'          =>  "NG",
                                    'operator_code'         =>  $request->provider,
                                    'plan'                  => $response->data->units,
                                    'product_code'          => $response->data->purchased_code , 
                                    'transfer_ref'          => $response->data->orderNo,
                                    'phone_number'          => $user->mobile,
                                    'distribe_ref'          => $response->data->orderNo,
                                    'selling_price'         => $request->amount,
                                    'cost_price'         => $request->amount,
                                    'receive_value'         =>  $response->data->units,
                                    'receive_currency'      =>  'NGN',
                                    //'deviceNo'              => $request->meterNumber,
                                    'commission_applied'    => 0,
                                    'processing_state'      => $response->data->status,
                                    'startedUtc'            =>  NOW(),
                                    'completedUtc'          =>  NOW(),
                                    'send_value'            => $request->amount,
                                ];

                                $createHistory = $this->HistoryRepository->createHistory($HistoryDetails);
                                if ($createHistory) {

                                    return response()->json([
                                        'success'       => true,
                                        'statusCode'    => 200,
                                        'message'       => 'You\'ve Successfully Purchased A Biller'
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

                                return response()->json([
                                    'success'       => false,
                                    'statusCode'    => 500,
                                    'message'       => 'But Your wallet has not been debited',
                                    'data'          => $response,
                                ]);
                            }
                        }
                    } else if ($request->top_up == 2) {
                        if (!empty($CheckCard)) {
                            // Check if loan record exist ============================================================+++
                            $isLoan = $this->LoanHistoryRepository->getUserLoan($uid);
                            if (!empty($isLoan)) {
                                return $this->errorResponse(message: 'You have an outstanding debt !!!', code: 500,);
                            } else {
                                if ($Kyc) {
                                    if ($Kyc->verificationStatus == 1) {
                                        if ($LoanCountry) {
                                            if ($req_loanBal_process >= 100) {
                                                return $this->errorResponse(message: 'Your Balance Is Still High, You Cannot Loan At This Time !!!',);
                                            } else {

                                                $billDetails = [
                                                    "provider"        => $request->provider,
                                                    "amount"            => $request->amount,
                                                    "number"      => $request->meterNumber,
                                                    "type"         => $request->meterType,
                                                    "reference"         => $request->reference,
                                                ];
                                                $response = json_decode($this->BillPaymentRepository->payElectricity($billDetails), true);
                                                // return response()->json($response);
                                                //!TODO: STORE TO HISTORY
                                                if ($response->success == true) {

                                                    $HistoryDetails = [
                                                            'user_id'               => $uid,
                                                            'purchase'              => 'electricity',
                                                            'country_code'          =>  "NG",
                                                            'operator_code'         =>  $request->provider,
                                                            'plan'                  => $response->data->units,
                                                            'product_code'          => $response->data->purchased_code,
                                                            'transfer_ref'          => $response->data->orderNo,
                                                            'phone_number'          => $user->mobile,
                                                            'distribe_ref'          => $response->data->orderNo,
                                                            'selling_price'         => $request->amount,
                                                            'cost_price'         => $request->amount,
                                                            'receive_value'         =>  $response->data->units,
                                                            'receive_currency'      =>  'NGN',
                                                            //'deviceNo'              => $request->meterNumber,
                                                            'commission_applied'    => 0,
                                                            'processing_state'      => $response->data->status,
                                                            'startedUtc'            =>  NOW(),
                                                            'completedUtc'          =>  NOW(),
                                                            'send_value'            => $request->amount,
                                                        ];

                                                    $createHistory = $this->HistoryRepository->createHistory($HistoryDetails);
                                                    if ($createHistory) {

                                                        return response()->json([
                                                            'success'       => true,
                                                            'statusCode'    => 200,
                                                            'message'       => 'You\'ve Successfully Purchased A Biller'
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

                                                    return response()->json([
                                                        'success'       => false,
                                                        'statusCode'    => 500,
                                                        'message'       => 'But Your wallet has not been debited',
                                                        'data'          => $response,
                                                    ]);
                                                }
                                            }
                                        } else {
                                            return $this->errorResponse(message: 'Sorry, loan is not available in the selected country !!!',);
                                        }
                                    } else {
                                        return $this->errorResponse(message: 'Verification Status Is Still Pending !!!',);
                                    }
                                } else {
                                    return $this->errorResponse(message: 'Kindly proceed to kyc page !!!',);
                                }
                            }
                        } else {
                            return $this->errorResponse(message: 'Please Add Card To Continue !!!', code: 500,);
                        }
                    } else {
                        return $this->errorResponse(message: 'Invalid Selection, Please Make a Choice !!!', code: 500,);
                    }
                } else {

                    return response()->json([
                        'success'       => false,
                        'statusCode'    => 500,
                        'message'       => 'Error, Invalid PIN !!'
                    ]);
                }
            }
        } else {
            return response()->json([
                'statusCode' => 400,
                'success'   => false,
                'message'   => 'Check All Input Fields !!!',
            ]);
        }
        // } catch (\Exception $e) {
        //     return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        // }

    }



}
