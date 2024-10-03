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

// use Validator;

class BillPayment extends Controller
{
    //
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
    }
    
    public function verify_iucNo(Request $request)
    {
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
    }

    // Getting Cable TV Plans
    public function getCablePlan(Request $request)
    {
        $cableId = $request->route('id');
        $response = $this->BillPaymentRepository->getCablePlan($cableId) ;
        return $response;
    }
    
    // payElectricity

    public function payElectricity(Request $request)
    // : JsonResponse
    {
        
        date_default_timezone_set("Africa/Lagos");
        // echo date_default_timezone_get();
        $requestID = date('YmdHi').rand(99, 9999999);

        $uid = $request->userID;//Auth::user()->id;
        $req_Account_process = $this->WalletRepository->getWalletBalance($uid);
        $req_bal_process = $req_Account_process->balance;
        $user = $this->UserRepository->getUserById($uid);
       
        $request->validate([
            'billerName'            => 'required|string',
            'meterType'             => 'required|string',
            'meterNumber'           => 'required|numeric',
            'amount'                => 'required|numeric',
            'customerName'          => 'required',
            'customerPhoneNumber'   => 'required|numeric'
        ]);

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

    }
    
    // PayCable TV
    public function payCableTV(Request $request)
    {
        
        $request->validate([
            'cableName'             => 'required|string',
            'cablePlan'             => 'required|string',
            'cableNumber'           => 'required|numeric',
            'customerName'          => 'required|string',
            'customerPhoneNumber'   => 'required|numeric'
        ]);
        

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

    }
    
    // public function waec_recharge(Request $request)
    // {
    //     date_default_timezone_set("Africa/Lagos");
    //     // echo date_default_timezone_get();
    //     $requestID = date('YmdHi').rand(99, 9999999);

    //     $uid = Auth::user()->id;
    //     $req_Account_process = $this->WalletRepository->getWalletBalance($uid);
    //     $req_bal_process = $req_Account_process->balance;
    //     $user = $this->UserRepository->getUserById($uid);
        
    //     $request->validate([
    //         'pin_no'=> 'required'
    //     ]);

    //     // Validate Account Verification
    //     if($user->email_verified_at !="" && $user->create_pin != 0)
    //     {
    //         if(Hash::check($request->pin, $user->create_pin)){
                
    //             if($req_bal_process < $request->amount){
    //                 Alert::warning('Insufficient fund');
    //                 return back();//->with('fail', 'Insufficient fund');
                    
    //             }else{

    //                 // Debit User Account Before Proceeding For Transaction .....
    //                 $new_bal_process = $req_bal_process - $request->amount;
    //                 $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
    //                 $this->WalletRepository->updateWallet($uid, $walletDetails);

    //                 $billDetails = [
    //                     'request_id'        => $requestID,
    //                     'serviceID'         => 'waec',
    //                     'variation_code'    => $this->getWaecVariation(),
    //                     'quantity'          => $request->pin_no,
    //                     'phone'             => Auth::user()->mobile
    //                 ];
    //                 $response = $this->ResulCheckerRepository->buyWaec($billDetails);
    //                 // return $this->getWaecVariation();
    //                 // return $response;
    //                 if($response['code'] == 000)
    //                 {
    //                     foreach($response['cards'] as $card){
    //                         $Card = implode(" ", $card);
    //                         $desc = $response['response_description'];
    //                     }
                        
    //                     $HistoryDetails = [
    //                         'user_id'               => $uid,
    //                         'purchase'              => 'education',
    //                         'network'               =>  'waec',
    //                         'api_mode'              => 'Vtpass',
    //                         'plan'                  => $response['content']['transactions']['product_name'],
    //                         'product_code'          => $response['purchased_code'],
    //                         'transfer_ref'          => $response['content']['transactions']['transactionId'],
    //                         'phone_number'          => $response['content']['transactions']['phone'],
    //                         'distribe_ref'          => $response['requestId'],
    //                         'selling_price'         => $response['content']['transactions']['amount'],
    //                         'description'           => $desc,
    //                         'deviceNo'              => $Card,
    //                         'commission_applied'    => $response['content']['transactions']['commission'],
    //                         'processing_state'      => $response['content']['transactions']['status'],
    //                         'send_value'            => $response['content']['transactions']['total_amount'],  
    //                     ]; //1111111111111
    //                     $createHistory = $this->HistoryRepository->createHistory( $HistoryDetails );
    //                     if($createHistory){

    //                         // Getting Referer Details ..........................................
    //                         $myReferral_process = Referral::where('referree_id', $uid)->first();
    //                         if( $myReferral_process )
    //                         {
    //                             $my_refferal = $myReferral_process->referral;
    //                             $referral_Account_process = ReferralBonus::where('user_id', $my_refferal);
    //                             $referral_bal = $referral_Account_process->amount;
    //                             $perc_bonus = (1/100) * $request->amount;
    //                             $referral_bonus = $perc_bonus + $referral_bal;
    //                             ReferralBonus::where('user_id', $my_refferal)->update([ 'amount'=>$referral_bonus , 'balance_before'=>$referral_bal, 'updated_at'=> NOW() ]);
    //                         }        
    //                         // ............................................................................//                            
    //                         Alert::success('Congrats', 'You\'ve Successfully Purchased, Details Are: '.$Card);
    //                         return back();
    //                         // return back()->with('success', 'You have Successfully Purchased');
    //                     }else{
    //                         // return 'Fail';
    //                         Alert::warning('Oops', 'Internal Server Error');
    //                         return back();
    //                     }
    //                 }else{
    //                     // Failed Transaction Auto Refund User Wallet
    //                     $new_bal_process = $req_bal_process + $request->amount;
    //                     $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
    //                     $this->WalletRepository->updateWallet($uid, $walletDetails);

    //                     Alert::warning('Failed, your account has been auto-refunded');
    //                     return back();
    //                     // return back()->with('fail', 'Error Occured, try later');
    //                 }
    //             }
                
                
    //         }else{
    //             Alert::error('Invalif PIN !!!');
    //             return back();//->with('fail', 'Invalid PIN !!!');
    //         }
    //     }



    // }

    // public function neco_recharge(Request $request)
    // {
    //     date_default_timezone_set("Africa/Lagos");
    //     // echo date_default_timezone_get();
    //     $requestID = date('YmdHi').rand(99, 9999999);

    //     $uid = Auth::user()->id;
    //     $req_Account_process = $this->WalletRepository->getWalletBalance($uid);
    //     $req_bal_process = $req_Account_process->balance;
    //     $user = $this->UserRepository->getUserById($uid);
       
    //     $request->validate([
    //         'pin_no'=> 'required'
    //     ]);
    //     // dd($request->pin_no);
    //     // Validate Account Verification
    //     if($user->email_verified_at !="" && $user->create_pin != 0)
    //     {
    //         if(Hash::check($request->pin, $user->create_pin)){
               
    //             if($req_bal_process < $request->amount){
                    
    //                 Alert::warning('Insufficient fund');
    //                 return back();//->with('fail', 'Insufficient fund');
                    
    //             }else{
                    
    //                 // Debit User Account Before Proceeding For Transaction .....
    //                 $new_bal_process = $req_bal_process - $request->amount;
    //                 $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
    //                 $this->WalletRepository->updateWallet($uid, $walletDetails);
                    
    //                 $billDetails = [
    //                     'no_of_pins' => $request->pin_no,
    //                 ];
    //                 $response = $this->ResulCheckerRepository->buyNeco($billDetails);
    //                 // return 'Money dey';
    //                 return $response;
    //                 if($response['success'] == true)
    //                 {

    //                     $HistoryDetails = [
    //                         'user_id'               => $uid,
    //                         'purchase'              => 'education',
    //                         'network'               =>  'waec',
    //                         'plan'                  => $response['content']['transactions']['product_name'],
    //                         'product_code'          => $response['purchased_code'],
    //                         'transfer_ref'          => $response['content']['transactions']['transactionId'],
    //                         'phone_number'          => $response['content']['transactions']['phone'],
    //                         'distribe_ref'          => $response['requestId'],
    //                         'selling_price'         => $response['content']['transactions']['amount'],
    //                         'description'           => '78887',
    //                         'deviceNo'              => '76575',
    //                         'commission_applied'    => $response['content']['transactions']['commission'],
    //                         'processing_state'      => $response['content']['transactions']['status'],
    //                         'send_value'            => $response['content']['transactions']['total_amount'],  
    //                     ]; //1111111111111
    //                     $createHistory = $this->HistoryRepository->createHistory( $HistoryDetails );
    //                     if($createHistory){
    //                         Alert::success('Congrats', 'You\'ve Successfully Purchased');
    //                         return back();
    //                         // return back()->with('success', 'You have Successfully Purchased');
    //                     }else{
    //                         // return 'Fail';
    //                         Alert::warning('Oops', 'Internal Server Error');
    //                         return back();
    //                     }
    //                 }else{
    //                     // Failed Transaction Auto Refund User Wallet
    //                     $new_bal_process = $req_bal_process + $request->amount;
    //                     $walletDetails = [ 'balance' => $new_bal_process, 'updated_at'=> NOW() ];
    //                     $this->WalletRepository->updateWallet($uid, $walletDetails);

    //                     Alert::warning('Failed, your account has been auto-refunded');
    //                     return back();
    //                     // return back()->with('fail', 'Error Occured, try later');
    //                 }
    //             }
                
                
    //         }else{
    //             Alert::error('Invalif PIN !!!');
    //             return back();
    //         }
    //     }else{
    //         Alert::error('Complete Account Verification!!!');
    //         return back();
    //     }

    // }
    
}
