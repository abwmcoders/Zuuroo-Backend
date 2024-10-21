<?php

namespace App\Http\Controllers;

use App\Models\Kyc;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKycRequest;
use App\Http\Requests\UpdateKycRequest;
use App\Interfaces\KycRepositoryInterface;
use App\Interfaces\DataRepositoryInterface;
use App\Interfaces\HistoryRepositoryInterface;
use App\Interfaces\LoanHistoryRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Alert;
use App\Repositories\DataRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\KycRepository;
use App\Repositories\LoanHistoryRepository;
use Illuminate\Support\Str;

class KycController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private KycRepository $KycRepository;
    private DataRepository $DataRepository;
    private HistoryRepository $HistoryRepository;
    private LoanHistoryRepository $LoanHistoryRepository;

    public function __construct(DataRepository $DataRepository, 
                                HistoryRepository $HistoryRepository, 
                                LoanHistoryRepository $LoanHistoryRepository,
                                KycRepository $KycRepository)
    {
        $this->DataRepository = $DataRepository;
        $this->HistoryRepository = $HistoryRepository;
        $this->LoanHistoryRepository = $LoanHistoryRepository;
        $this->KycRepository = $KycRepository;
    }

    public function verify_bvn(Request $request)
    {
        
        $request->validate([
            'firstname'    => 'required',
            'middlename'   => 'required',
            'lastname'     => 'required',
            'bvn'          => 'required',
            'dd'           => 'required',
            'mm'           => 'required',
            'yy'           => 'required',
            //'Phone_Number' => 'required',
        ]);

        $id = Auth::user()->id;
        $country = Auth::user()->country;
        $number = Auth::user()->mobile;
        // dd($request->all() );
        $bvnNumber          = $request->bvn;
        $firstName          = $request->firstname;
        $lastName           = $request->lastname;
        $middleName         = $request->middlename;

        $references = [];
        for ($i = 0; $i < 10; $i++) {
            $references[] = Str::upper(Str::random(12)); // Example: A1B2C3D4
        }
        
        
        $fullName           = strtoupper($lastName .' '. $firstName .' '. $middleName);
        $phoneNumber        = $number; //request->Phone_Number;
        $dateOfBirth        = $request->dd .'-'. $request->mm .'-'. $request->yy;
        $uid                = $id;//$request->user_id;
        $ucc                = $country; //$request->user_country;
        $transaction_ref    = $references; //request->transaction_ref;
        
        // CHeck existig KYC -------------------------------------------------------------------------------------------------
        $Kyc = Kyc::where('user_id', $uid )->first();
        // -------------------------------------------------------------------------------------------------------------------
        
        if( $Kyc == null )
        {

            $newDetails = [
                "bvn"           => $request->bvn,
                "name"          => $fullName,
                "dateOfBirth"   => $dateOfBirth, //"27-Apr-1993",
                "mobileNo"      => $phoneNumber
            ];
    
            $request = $this->KycRepository->verifyBvnMonnify($newDetails);
            $response = json_decode( $request );
            // return $response;
            // requestSuccessful
            if( $response->requestSuccessful == true ){
                
                $KycDetails = [
                    'user_id'           => $uid,
                    'countryC_code'     => $ucc ,
                    'first_name'        => $firstName,
                    'last_name'         => $lastName,
                    'transaction_ref'   => $transaction_ref,
                    'id_number'         => $bvnNumber,
                    'id_type'           => 'bvn',
                    'date_of_birth'     => $dateOfBirth,
                    'verify_status'     => "1",
                ];
                
                if($response->responseBody->name->matchStatus == "FULL_MATCH" )
                {
                    
                    $create_kyc = $this->KycRepository->createKyc($KycDetails);
                    // return $create_kyc;
                    if($create_kyc){
                        
                        return response()->json([
                            'success'       => true,
                            'statusCode'    => 200,
                            'message'       => 'You\'ve Successfully Submitted Your Kyc, You Are All Set'
                        ]);
                                    
                    }else{
                        
                        return response()->json([
                            'success'       => false,
                            'statusCode'    => 500,
                            'message'       => 'An Error Occured While Trying To Verify Your Identity, Please Try Later  !!!'
                        ]);
                        
                    }
                    
                }
                else
                {
                     return response()->json([
                        'success'       => false,
                        'statusCode'    => 500,
                        'message'       => 'The provided details does not match !!!'
                    ]);
                }
                
            }else{
                
                return response()->json([
                        'success'       => false,
                        'statusCode'    => 500,
                        'message'       => 'Error! There is a problem validating your BVN, try later !!!'
                    ]);
            }
        }
        else{
                
                return response()->json([
                    'success'       => false,
                    'statusCode'    => 500,
                    'user_id'       => $uid,
                    'message'       => 'KYC Already Exist !!!'
                ]);
            }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreKycRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kyc  $kyc
     * @return \Illuminate\Http\Response
     */
    public function show(Kyc $kyc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kyc  $kyc
     * @return \Illuminate\Http\Response
     */
    public function edit(Kyc $kyc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateKycRequest  $request
     * @param  \App\Models\Kyc  $kyc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kyc $kyc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kyc  $kyc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kyc $kyc)
    {
        //
    }
}
