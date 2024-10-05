<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
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
use App\Models\ReferralBonus;
use App\Traits\ApiResponseTrait;
// use Validator;

class BillPayment extends Controller
{
    //
    use ApiResponseTrait;
    private $HistoryRepository;
    private $UserRepository;
    private $BillPaymentRepository;
    private $WalletRepository;
    private $ApiKey;
    private $Secrete_Key;


    public function __construct(
        HistoryRepository $HistoryRepository,
        UserRepository $UserRepository,
       BillPaymentRepository $BillPaymentRepository,
        WalletRepository $WalletRepository,
   )
    {
        $this->BillPaymentRepository = $BillPaymentRepository;
        $this->HistoryRepository = $HistoryRepository;
        $this->WalletRepository = $WalletRepository;
        $this->UserRepository = $UserRepository;
        $this->ApiKey = "bf31dc50bba05e8455154eb725fa13ea";
        $this->Secrete_Key = "SK_236b54e198b86b4ba713e19ebb7deeb4507d1779587";
    }

    public function verify_meterNo(Request $request)
    // : JsonResponse
    {
        try{
            $Validator = Validator::make($request->all(), [
                'billersCode' => ['required'],
                'serviceID'   => ['required'],
                'meterType'   => ['required']
            ]);

            $billersCode =   $request->billersCode;
            $serviceID  =   $request->serviceID;
            $meterType  =   strtolower($request->meterType);

            // Log::debug(['Error:' => $billersCode, $serviceID, $meterType ]);

            if ($Validator->passes()) {
                $billDetails = [
                    'billersCode'   => $billersCode,
                    'serviceID'     => $serviceID,
                    'type'          => $meterType

                ];

                $response = $this->BillPaymentRepository->verifyMeterNumber($billDetails);
                $request = json_decode($response);

                return response()->json([
                    'success'       => false,
                    'statusCode'    => 200,
                    'data'          => $request->content,
                    'message'       => 'Meter Number Verified'
                ]);
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'success'   => false,
                    'message'   => 'Check All Input Fields !!!',
                ]);
            }
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function verify_iucNo(Request $request)
    {
        try{
            $Validator = Validator::make($request->all(), [
                'billersCode' => ['required'],
                'serviceID'   => ['required']
            ]);


            if ($Validator->passes()) {
                $billDetails = [
                    'billersCode'   => $request->billersCode,
                    'serviceID'     => $request->serviceID,
                ];

                $response = $this->BillPaymentRepository->verifyIUCNumber($billDetails);
                $request = json_decode($response);

                return response()->json([
                    'success'       => false,
                    'statusCode'    => 200,
                    'data'          => $request->content,
                    'message'       => 'IUC Number Verified'
                ]);
            } else {
                return response()->json([
                    'statusCode' => 400,
                    'success'   => false,
                    'message'   => 'Check All Input Fields !!!',
                ]);
            }
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    // Getting Cable TV Plans
    public function getCablePlan(Request $request)
    {
        try{
            $cableId = $request->route('id');
            $response = $this->BillPaymentRepository->getCablePlan($cableId) ;
            return $response;
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    public function getElectPlan(Request $request)
    {
        try{
            $cableId = $request->route('id');
            $response = $this->BillPaymentRepository->getCablePlan($cableId) ;
            return $response;
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }
    }

    // payElectricity

    public function payElectricity(Request $request)
    // : JsonResponse
    {

        try{
            date_default_timezone_set("Africa/Lagos");
            // echo date_default_timezone_get();
            $requestID = date('YmdHi').rand(99, 9999999);

            $uid = $request->userID;//Auth::user()->id;
            $req_Account_process = $this->WalletRepository->getWalletBalance($uid);
            $req_bal_process = $req_Account_process->balance;
            $user = $this->UserRepository->getUserById($uid);

            $Validator = Validator::make($request->all(), [
                'billerName'            => 'required|string',
                'meterType'             => 'required|string',
                'meterNumber'           => 'required|numeric',
                'amount'                => 'required|numeric',
                'customerName'          => 'required',
                'customerPhoneNumber'   => 'required|numeric'
            ]);

            if ($Validator->passes()) {

                // Validate Account Verification
                if($user->email_verified_at !="" && $user->create_pin != 0)
                {
                    if(Hash::check($request->pin, $user->create_pin)){
                        // dd('Debugging here');

                        if($req_bal_process < $request->amount){

                            return response()->json([
                                'success'       => false,
                                'statusCode'    => 500,
                                'message'       => 'Insufficient fund !!!'
                            ]);

                        }else{

                            // Debit User Account Before Proceeding For Transaction .....
                            $new_bal_process = $req_bal_process - $request->amount;
                            $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
                            $this->WalletRepository->updateWallet($uid, $walletDetails);

                            $billDetails = [
                                'request_id'        => $requestID,
                                'serviceID'         => $request->billerName,//'eko-electric',
                                'variation_code'    => $request->meterType,//'prepaid',
                                'billersCode'       => $request->meterNumber,
                                'amount'            => $request->amount,
                                'phone'             => $request->customerPhoneNumber
                            ];
                            $response = $this->BillPaymentRepository->payElectricity($billDetails);
                            // return $response;
                            if($response['code'] == "016" ) //000
                            {

                                $HistoryDetails = [
                                    'user_id'               => $request->userID,
                                    'purchase'              => 'electricity',
                                    'api_mode'              => 'Vtpass',
                                    'plan'                  => $response['content']['transactions']['type'],
                                    'product_code'          => $response['content']['transactions']['product_name'],
                                    'transfer_ref'          => $response['requestId'],
                                    'phone_number'          => $response['content']['transactions']['phone'],
                                    'distribe_ref'          => $response['content']['transactions']['transactionId'],
                                    'selling_price'         => $response['content']['transactions']['amount'],
                                    'description'           => 'Delivered',
                                    'deviceNo'              => $request->meterNumber,
                                    'commission_applied'    => $response['content']['transactions']['commission'],
                                    'processing_state'      => $response['content']['transactions']['status'],
                                    'send_value'            => $response['content']['transactions']['total_amount'],
                                ]; //1111111111111

                                $createHistory = $this->HistoryRepository->createHistory( $HistoryDetails );
                                if($createHistory){

                                        return response()->json([
                                            'success'       => true,
                                            'statusCode'    => 200,
                                            'message'       => 'You\'ve Successfully Purchased A Biller'
                                        ]);

                                }else{

                                    return response()->json([
                                        'success'       => false,
                                        'statusCode'    => 500,
                                        'message'       => 'Internal Server Error'
                                    ]);
                                }
                            }else{

                                // Failed Transaction Auto Refund User Wallet
                                $new_bal_process = $req_bal_process + $request->amount;
                                $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
                                $this->WalletRepository->updateWallet($uid, $walletDetails);

                                return response()->json([
                                    'success'       => false,
                                    'statusCode'    => 500,
                                    'message'       => 'But Your wallet has not been debited'
                                ]);

                            }
                        }


                    }else{

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
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }

    }

    // PayCable TV
    public function payCableTV(Request $request)
    {
        try{
            $Validator = Validator::make($request->all(), [
                'cableName'             => 'required|string',
                'cablePlan'             => 'required|string',
                'cableNumber'           => 'required|numeric',
                'customerName'          => 'required|string',
                'customerPhoneNumber'   => 'required|numeric'
            ]);

            if ($Validator->passes()) {

                date_default_timezone_set("Africa/Lagos");
                // echo date_default_timezone_get();
                $requestID          = date('YmdHi').rand(99, 9999999);
                $planId             = $request->cablePlan;
                $uid                = $request->userID;//Auth::user()->id;
                $req_Account_process= $this->WalletRepository->getWalletBalance($uid);
                $req_bal_process    = $req_Account_process->balance;
                $user               = $this->UserRepository->getUserById($uid);

                $selectplanDetail = OtherProduct::where('variation_code', $planId)->first();
                $amount = $selectplanDetail->variation_amount;
                $variationCode = $selectplanDetail->variation_code;


                // foreach($request->cablePlan as $value){
                //     $data_arr = explode(',', $request->cablePlan);
                //     $amount = $data_arr[0];
                //     $variationCode = $data_arr[1];
                //     // return $amount;
                // }

                // Validate Account Verification
                if($user->email_verified_at !="" && $user->create_pin != 0)
                {
                    if(Hash::check($request->pin, $user->create_pin)){

                        if($req_bal_process < $request->amount){
                            // return 'Insufficient fund';

                            return response()->json([
                                'success'       => false,
                                'statusCode'    => 500,
                                'message'       => 'Error, Insufficient fund !!!'
                            ]);


                        }else{

                            // Debit User Account Before Proceeding For Transaction .....
                            $new_bal_process = $req_bal_process - $request->amount;
                            $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
                            $this->WalletRepository->updateWallet($uid, $walletDetails);

                            $billDetails = [
                                'request_id'        => $requestID,
                                'serviceID'         => $request->cableName,//'eko-electric',
                                'billersCode'       => $request->cableNumber,
                                'variation_code'    => $variationCode,//'prepaid',
                                'amount'            => $amount,
                                'phone'             => $request->customerPhoneNumber,
                                'subscription_type' => 'change'
                            ];
                            $response = $this->BillPaymentRepository->payCableTV($billDetails);
                            // return $response;
                            if($response['code'] == 016) //000
                            {

                                $HistoryDetails = [
                                    'user_id'               => $request->userID,
                                    'purchase'              => 'cable',
                                    'api_mode'              => 'Vtpass',
                                    'plan'                  => $response['content']['transactions']['type'],
                                    'product_code'          => $response['content']['transactions']['product_name'],
                                    'transfer_ref'          => $response['requestId'],
                                    'phone_number'          => $response['content']['transactions']['phone'],
                                    'distribe_ref'          => $response['requestId'],
                                    'selling_price'         => $response['content']['transactions']['amount'],
                                    'description'           => $response['response_description'],
                                    'deviceNo'              => $request->cableNumber,
                                    'commission_applied'    => $response['content']['transactions']['commission'],
                                    'processing_state'      => $response['content']['transactions']['status'],
                                    'send_value'            => $response['content']['transactions']['total_amount'],
                                ]; //1111111111111
                                // return $HistoryDetails;
                                $createHistory = $this->HistoryRepository->createHistory( $HistoryDetails );
                                if($createHistory){

                                    return response()->json([
                                        'success'       => true,
                                        'statusCode'    => 200,
                                        'message'       => 'You\'ve Successfully Purchased A Biller'
                                    ]);

                                }else{

                                    return response()->json([
                                        'success'       => false,
                                        'statusCode'    => 500,
                                        'message'       => 'Internal Server Error !!!'
                                    ]);
                                }
                            }else{
                                // Failed Transaction Auto Refund User Wallet
                                $new_bal_process = $req_bal_process + $request->amount;
                                $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
                                $this->WalletRepository->updateWallet($uid, $walletDetails);

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


                    }else{
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
        } catch (\Exception $e) {
            return $this->errorResponse(message: 'Internal Server Error, Try Later !!!',);
        }

    }

}
