<?php

namespace App\Http\Controllers;

use App\Interfaces\AirtimeRepositoryInterface;
use App\Interfaces\HistoryRepositoryInterface;
use App\Interfaces\LoanHistoryRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\WalletRepositoryInterface;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Models\RecurringCharge;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\DataWallet;
use App\Models\Payment;
use App\Models\Country;
use App\Models\Kyc;
use App\Repositories\AirtimeRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\LoanHistoryRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Traits\ApiResponseTrait;

class AirtimeController extends Controller
{

    use ApiResponseTrait; 

    private AirtimeRepository $AirtimeRepository;
    private HistoryRepository $HistoryRepository;
    private LoanHistoryRepository $LoanHistoryRepository;
    private UserRepository $UserRepository;
    private WalletRepository $WalletRepository;

    public function __construct(AirtimeRepository $AirtimeRepository, HistoryRepository $HistoryRepository, LoanHistoryRepository $LoanHistoryRepository, UserRepository $UserRepository, WalletRepository $WalletRepository,)
    {
        $this->AirtimeRepository = $AirtimeRepository;
        $this->HistoryRepository = $HistoryRepository;
        $this->LoanHistoryRepository = $LoanHistoryRepository;
        $this->UserRepository = $UserRepository;
        $this->WalletRepository = $WalletRepository;
    }

    public function createAirtime(Request $request)
    //: JsonResponse
    {
        try{
            date_default_timezone_set("Africa/Lagos");

            $uid        = Auth::user()->id;
            $uemail     = Auth::user()->email;
            $today      = date('Y-m-d');
            $daysToAdd  = $request->loan_term;
            $repayment  = date("Y-m-d", strtotime("+" . $daysToAdd . " days"));

            //date('Y-m-d', strtotime(date('Y-m-d'). ' +'. $dueDate));

            $requestID  = date('YmdHi').rand(99, 9999999);
            $req_Account_process    = $this->WalletRepository->getWalletBalance($uid);
            $req_bal_process        = $req_Account_process->balance;
            $req_loanBal_process    = $req_Account_process->loan_balance;
            $user                   = $this->UserRepository->getUserById($uid);
            $LoanCountry            = Country::where('is_loan', true)->where('country_code', $request->country)->get();

            // GLNG | MTNG | ZANG | ETNG

            // Validate Account Verification
            $amount         = strip_tags($request->total_price);
            $network        = strip_tags($request->network_operator);
            $customer_ref   = 'ZR_'.rand(99, 999999);
            $actAmt         = strip_tags($request->amount);

            // -------------------------------- KYC --------------------------------------------------------------------------------------
            $Kyc = Kyc::where('user_id', $uid)->first();
            // ---------------------------------------------------------------------------------------------------------------------------

            // -------------------------------- CHECK CARD --------------------------------------------------------------------------------
            $CheckCard = RecurringCharge::where('user_id', $uid)->where('status', 1)->first();

            $checkPaymentRc = Payment::where('user_id', $uid)->get();

            // ----------------------------------------------------------------------------------------------------------------------------


            if ($actAmt < 1000)
            {
                if ( !is_null($checkPaymentRc) ){

                    if($user->email_verified_at !="" && $user->number_verify_at != "")
                    {
                        if(Hash::check($request->pin, $user->create_pin)){

                            if($request->top_up == 1){

                                $request->validate([
                                    'top_up'            =>  'required',
                                    'country'           =>  'required',
                                    'phoneNumber'       =>  'required',
                                    'network_operator'  =>  'required',
                                    'amount'            =>  'required'
                                ]);


                                // Processing Nigeria Data
                                if($request->country == 'NG'){
                                    if($req_bal_process < $amount){
                                        return $this->errorResponse(message: 'Insufficient fund !!!',);
                                    }else{

                                        // $new_bal_process = $req_bal_process - $amount;
                                        // $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
                                        // $this->WalletRepository->updateWallet($uid, $walletDetails);

                                        //  // Update Wallet History ....................................................
                                        // DataWallet::create([
                                        //     'transfer_ref'  => $network .' '. $amount,
                                        //     'mobile_recharge'=> 'Airtime',
                                        //     'user_id'       => $uid,
                                        //     'balance_bfo'   => $req_bal_process,
                                        //     'balance_after' => $new_bal_process,
                                        //     'amount_debt'   => $amount
                                        // ]);
                                        // // ...........................................................................


                                        $phoneNumber = str_replace('234', '0', strip_tags($request->phoneNumber));

                                        $DataDetails = [
                                            'request_id'        => $requestID,
                                            'serviceID'         => $network,
                                            'phone'             => $phoneNumber,
                                            'amount'            => $actAmt,

                                        ];

                                        // send request to get service
                                    try {
                                        $createNigData = json_decode( $this->AirtimeRepository->createAlhAirtime($DataDetails) ); //Log::error(['err' => $createNigData]);
                                        // return response()->json([
                                        //     'success'       => false,
                                        //     'statusCode'    => 500,
                                        //     'message'       => 'Message'
                                        // ]);

                                        if( $createNigData ){

                                            // update the wallet if purchase is successful
                                            $new_bal_process = $req_bal_process - $amount;
                                            $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
                                            $this->WalletRepository->updateWallet($uid, $walletDetails);

                                            // Update Wallet History ....................................................
                                            DataWallet::create([
                                                'transfer_ref'  => $network .' '. $amount,
                                                'mobile_recharge'=> 'Airtime',
                                                'user_id'       => $uid,
                                                'balance_bfo'   => $req_bal_process,
                                                'balance_after' => $new_bal_process,
                                                'amount_debt'   => $amount
                                            ]);
                                            // ...........................................................................
                                            $HistoryDetails = [
                                                'user_id'               =>  $uid,
                                                'plan'                  =>  $createNigData->plan_name,
                                                'purchase'              =>  'Airtime',
                                                'country_code'          =>  $request->country,
                                                'operator_code'         =>  $network,
                                                'product_code'          =>  'VTU',
                                                'transfer_ref'          =>  $createNigData->ident,
                                                'phone_number'          =>  $createNigData->mobile_numbert,
                                                'distribe_ref'          =>  $customer_ref,
                                                'selling_price'         =>  $amount,
                                                'cost_price'            =>  $actAmt,
                                                'receive_value'         =>  $amount,
                                                'send_value'            =>  $actAmt,
                                                'receive_currency'      =>  'NGN',
                                                'commission_applied'    =>  0,
                                                'startedUtc'            =>  NOW(),
                                                'completedUtc'          =>  $createNigData->create_date,
                                                'processing_state'      =>  $createNigData->Status,
                                            ];
                                            $query = $this->HistoryRepository->createHistory($HistoryDetails);
                                            if($query){
                                                return $this->successResponse(message: 'You\'ve Purchase ' . $phoneNumber . ' With ' . number_format($actAmt) . ' NGN Airtime',);
                                            }else{
                                                return $this->errorResponse(message: 'Transaction Failed !!!',);
                                            }

                                        } else {

                                            $new_bal_process = $req_bal_process + $amount;
                                            $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
                                            $this->WalletRepository->updateWallet($uid, $walletDetails);

                                            return response()->json([
                                                'success'       => false,
                                                'statusCode'    => 500,
                                                'Error'         => $createNigData,
                                                'message'       => $createNigData->content->transactions->status,
                                            ]);
                                        }
                                    }catch (\Exception $e) {

                                        // Log the exception
                                            Log::error('Exception: ' . $e->getMessage());
                                            // Return an error response
                                            return $this->errorResponse(message: 'An error occurred: ' . $e->getMessage(),);
                                    }

                                    }

                                }
                                // Processing Other Countries Data
                                else{
                                    // Check wallet balance
                                    if($req_bal_process < $amount){
                                        return $this->errorResponse(message: 'Insufficient fund !!!',);
                                    }else{

                                        $new_bal_process = (float) $req_bal_process - (float)$amount;
                                        $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];

                                        if($this->WalletRepository->updateWallet($uid, $walletDetails) )
                                        {
                                            // Update Wallet History ....................................................
                                            DataWallet::create([
                                                'transfer_ref'  => $network .' '. $actAmt,
                                                'mobile_recharge'=> 'Airtime',
                                                'user_id'       => $uid,
                                                'balance_bfo'   => $req_bal_process,
                                                'balance_after' => $new_bal_process,
                                                'amount_debt'   => $amount
                                            ]);
                                            // ...........................................................................

                                            // Data Api Arrays
                                            $DataDetails = [
                                                'SkuCode'           => $network,
                                                'SendValue'         => $actAmt,
                                                'SendCurrencyIso'   => 'USD',
                                                'AccountNumber'     => $request->phoneNumber,
                                                'DistributorRef'    => $request->DistributorRef,
                                                'ValidateOnly'      => false,
                                                'RegionCode'        => $network
                                            ];
                                            $response = $this->AirtimeRepository->createIntAirtime($DataDetails);
                                            if($response['ResultCode'] ==1){
                                                $HistoryDetails = [
                                                    'user_id'               =>  $uid,
                                                    'plan'                  =>  $actAmt,
                                                    'purchase'              =>  'data',
                                                    'country_code'          =>  $request->country,
                                                    'operator_code'         =>  $network,
                                                    'product_code'          =>  $network,
                                                    'transfer_ref'          =>  $response['TransferRecord']['TransferId']['TransferRef'],
                                                    'phone_number'          =>  $request->phoneNumber,
                                                    'distribe_ref'          =>  $response['TransferRecord']['TransferId']['DistributorRef'],
                                                    'selling_price'         =>  '',
                                                    'receive_value'         =>  $response['TransferRecord']['Price']['ReceiveValue'],
                                                    'send_value'            =>  $response['TransferRecord']['Price']['SendValue'],
                                                    'receive_currency'      =>  $response['TransferRecord']['Price']['SendCurrencyIso'],
                                                    'commission_applied'    =>  $response['TransferRecord']['CommissionApplied'],
                                                    'startedUtc'            =>  $response['TransferRecord']['StartedUtc'],
                                                    'completedUtc'          =>  $response['TransferRecord']['CompletedUtc'],
                                                    'processing_state'      =>  $response['TransferRecord']['ProcessingState'],
                                                ];
                                                $query = $this->HistoryRepository->createHistory($HistoryDetails);

                                                if($query){
                                                    return $this->successResponse(message: 'Successful !!!',);
                                                }else{
                                                    return $this->errorResponse(message: 'Transaction Failed !!!',);
                                                }

                                            }else{
                                                $new_bal_process = $req_bal_process + $amount;
                                                $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
                                                $this->WalletRepository->updateWallet($uid, $walletDetails);

                                                return $this->errorResponse(message: 'Error Occured, try later !!!',);
                                            }
                                        }else{
                                            return $this->errorResponse(message: 'Internal Server Error, Please Retry !!!',);
                                        }
                                    }
                                    // return $response;
                                }
                            }elseif($request->top_up ==2){
                                // Check User Add Card or Not ------------------------------------------------------------------------------
                                if (!empty($CheckCard)) {
                                    // Check if loan record exist ============================================================+++
                                    $isLoan = $this->LoanHistoryRepository->getUserLoan($uid);
                                    if ( !empty($isLoan) ) {
                                        return $this->errorResponse(message: 'You have an outstanding debt !!!', code: 500,);
                                    } else {
                                        // check if user complete Kyc---------------------------------------------------------------------------
                                        if($Kyc){
                                            if($Kyc->verificationStatus == 1)
                                            {
                                                if($LoanCountry){
                                                    if($req_loanBal_process >= 100){
                                                        return $this->errorResponse(message: 'Your Balance Is Still High, You Cannot Loan At This Time !!!',);
                                                    }else{
                                                        // Processing Loan Nigeria Data
                                                        if($request->country == 'NG'){
                                                            // $new_loanBal_process = $req_loanBal_process + $amount;
                                                            // $walletDetails = [ 'loan_balance' => $new_loanBal_process, 'updated_at'=> NOW() ];
                                                            // $this->WalletRepository->updateWallet($uid, $walletDetails);
                                                            $phoneNumber = str_replace('234', '0', $request->phoneNumber);

                                                            // dd($amount);
                                                            $DataDetails = [
                                                                'request_id'        => $requestID,
                                                                'serviceID'         => $network,
                                                                'amount'            => $actAmt,
                                                                'phone'             => $phoneNumber,
                                                            ];
                                                            // dd($requestID);
                                                            // Store returned data in DB
                                                        try {
                                                            $createNigData = json_decode( $this->AirtimeRepository->createVTPassAirtime($DataDetails) );
                                                                //  return $createNigData;

                                                                if ($createNigData->code == '000') {
                                                                    //update loan amount
                                                                    $new_loanBal_process = $req_loanBal_process + $amount;
                                                                    $walletDetails = [ 'loan_balance' => $new_loanBal_process, 'updated_at'=> NOW() ];
                                                                    $this->WalletRepository->updateWallet($uid, $walletDetails);

                                                                    // Store returned data in DB
                                                                    $HistoryDetails = [
                                                                        'user_id'               =>  $uid,
                                                                        'plan'                  =>  $createNigData->content->transactions->product_name,
                                                                        'purchase'              =>  'Airtime',
                                                                        'country_code'          =>  $request->country,
                                                                        'operator_code'         =>  $network,
                                                                        'product_code'          =>  'VTU',
                                                                        'transfer_ref'          =>  $createNigData->content->transactions->transactionId,
                                                                        'phone_number'          =>  $createNigData->content->transactions->unique_element,
                                                                        'distribe_ref'          =>  $createNigData->requestId,
                                                                        'selling_price'         =>  $amount,
                                                                        'receive_value'         =>  $createNigData->amount,
                                                                        'send_value'            =>  $actAmt,
                                                                        'receive_currency'      =>  'NGN',
                                                                        'commission_applied'    =>  $createNigData->content->transactions->commission,
                                                                        'startedUtc'            =>  NOW(),
                                                                        'completedUtc'          =>  NOW(),
                                                                        'processing_state'      =>  $createNigData->content->transactions->status,
                                                                        'loan_amount'           =>  $amount,
                                                                        'repayment'             =>  $repayment,
                                                                        'payment_status'        =>  'pending',
                                                                        'due_date'              =>  $request->loan_term
                                                                    ];
                                                                    $query = $this->LoanHistoryRepository->createLoanHistory($HistoryDetails);

                                                                    if($query){
                                                                        return $this->successResponse(message: 'You Loan ' . $phoneNumber . ' With ' . number_format($actAmt) . ' NGN Airtime',);
                                                                    }else{
                                                                        return $this->errorResponse(message: 'Transaction Failed !!!',);
                                                                    }

                                                                } else if( $createNigData->code == '016' ){

                                                                    // $new_bal_process = $req_bal_process + $amount;
                                                                    // $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
                                                                    // $this->WalletRepository->updateWallet($uid, $walletDetails);

                                                                    return $this->errorResponse(message: 'Transaction Failed, Please Try Later !!!',);
                                                                } else {
                                                                    return response()->json([
                                                                        'success'       => false,
                                                                        'statusCode'    => 500,
                                                                        'Error'         => $createNigData,
                                                                        // 'message'       => 'Transaction Failed, Unknown Error Occurered, Try Later'
                                                                        'message'       => $createNigData->content->transactions->status,
                                                                    ]);
                                                                }
                                                        }catch (\Exception $e) {

                                                            // Log the exception
                                                                Log::error('Exception: ' . $e->getMessage());

                                                                // Optionally, you can re-throw the exception to propagate it
                                                                // throw $e;

                                                                // Return an error response
                                                                return $this->errorResponse(message: 'An error occurred: ' . $e->getMessage(),);
                                                        }
                                                        }
                                                        //other countries
                                                        else{
                                                            $DataDetails = [
                                                                'SkuCode'           => $network,
                                                                'SendValue'         => $actAmt,
                                                                'SendCurrencyIso'   => 'USD',
                                                                'AccountNumber'     => $request->phoneNumber,
                                                                'DistributorRef'    => $request->DistributorRef,
                                                                'ValidateOnly'      => false,
                                                                'RegionCode'        => $network
                                                            ];
                                                            $response = $this->AirtimeRepository->createIntAirtime($DataDetails);
                                                            // return $response;
                                                            if($response['ResultCode'] ==1){
                                                                $HistoryDetails = [
                                                                    'user_id'               =>  $uid,
                                                                    'plan'                  =>  $actAmt,
                                                                    'purchase'              =>  'Airtime',
                                                                    'country_code'          =>  $request->country,
                                                                    'operator_code'         =>  $network,
                                                                    'product_code'          =>  $network,//$skuCode
                                                                    'transfer_ref'          =>  $response['TransferRecord']['TransferId']['TransferRef'],
                                                                    'phone_number'          =>  $request->phoneNumber,
                                                                    'distribe_ref'          =>  $response['TransferRecord']['TransferId']['DistributorRef'],
                                                                    'selling_price'         =>  '',
                                                                    'receive_value'         =>  $response['TransferRecord']['Price']['ReceiveValue'],
                                                                    'send_value'            =>  $response['TransferRecord']['Price']['SendValue'],
                                                                    'receive_currency'      =>  $response['TransferRecord']['Price']['SendCurrencyIso'],
                                                                    'commission_applied'    =>  $response['TransferRecord']['CommissionApplied'],
                                                                    'startedUtc'            =>  $response['TransferRecord']['StartedUtc'],
                                                                    'completedUtc'          =>  $response['TransferRecord']['CompletedUtc'],
                                                                    'processing_state'      =>  $response['TransferRecord']['ProcessingState'],
                                                                    'loan_amount'           =>  $amount,
                                                                    'repayment'             =>  $repayment,
                                                                    'payment_status'        =>  'pending',
                                                                    'due_date'              =>  $request->loan_term
                                                                ];
                                                                $query = $this->LoanHistoryRepository->createLoanHistory($HistoryDetails);
                                                                if($query){
                                                                    return $this->successResponse(message: 'Succeeded !!!',);
                                                                }else{
                                                                    return $this->errorResponse(message: 'Transaction Failed !!!',);
                                                                }

                                                            } else {
                                                                return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
                                                            }
                                                        }
                                                    }
                                                }else{
                                                    return $this->errorResponse(message: 'Sorry, loan is not available in the selected country !!!',);
                                                }
                                            }
                                            else
                                            {
                                                return $this->errorResponse(message: 'Verification Status Is Still Pending !!!',);
                                            }
                                        } else {
                                            return $this->errorResponse(message: 'Kindly proceed to kyc page !!!', );
                                        }

                                    }
                                    // =====================================================================================+++

                                } else {
                                    return $this->errorResponse(message: 'Please Add Card To Continue !!!', code: 500,);
                                }

                            }else{
                                return $this->errorResponse(message: 'Invalid Selection, Please Make a Choice !!!', code: 500,);
                            }

                        }else{
                            return $this->errorResponse(message: 'Incorrect PIN !!!', code: 500,);
                        }
                    }
                    else
                    {
                        return $this->errorResponse(message: 'Complete Account Verification !!!', code: 500,);
                    }
                    // PIN Validation

                } else
                {

                    return $this->errorResponse(message: 'No payment record found !!!', code: 500,);
                }
            } else {

                Log::debug(['suspected fruad' => $uid]);
                return $this->errorResponse(message: "Suspected fraud !!! $actAmt", code: 500,);
            }
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }
}
