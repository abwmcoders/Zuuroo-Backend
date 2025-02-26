<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\RegistrationResource;
use App\Mail\OtpMail;
use App\Models\User;
use App\Repositories\ActivityRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\UserService;
use App\Traits\ApiResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    use ApiResponseTrait;

    private WalletRepository $WalletRepository;
    private ActivityRepository $ActivityRepository;
    private UserRepository $UserRepository;

    private $monnify_baseUrl, $monnify_apiKey, $monnify_secretKey, $monnify_accNumber, $monnify_contactCode, $monnify_bvnNumber;

    protected $userService;

    public function __construct(UserService $userService,WalletRepository $WalletRepository, ActivityRepository $ActivityRepository, UserRepository $UserRepository)
    {
        $this->userService = $userService;

        $this->WalletRepository = $WalletRepository;
        $this->ActivityRepository = $ActivityRepository;
        $this->UserRepository = $UserRepository;

        $this->monnify_baseUrl = "https://api.monnify.com";
        $this->monnify_apiKey = "MK_PROD_0JNWWV5ZY6";
        $this->monnify_secretKey = "A263P7DAA0TJ6BJQ5B37PU50Y9ZXWVJA";
        $this->monnify_contactCode = "734720763871";
        $this->monnify_accNumber = "8024437726";
        $this->monnify_bvnNumber = "22318673488";
    }

    public function register(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = $this->create($validatedData);

        if ($user instanceof User) {

            $this->sendOtp($user);

            return $this->successResponse(data: new RegistrationResource($user), message: 'User registered successfully, Please verify your email.', code: 201,);
        } else {
            return $this->errorResponse(message: 'Failed to create account. Please try again.', code: 500,);
        }
       
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $token = $this->userService->login($validatedData);
            return response()->json([
                'status' => true,
                'message' => 'Login successfully',
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'token' => ''
            ], 401);
        }
    }
    
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->successResponse(message: 'Logged out successfully',); 
    }

    public function verifyOtp( Request $request) 
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|string|email|max:255',
                'otp'   => 'required|digits:6',
            ]);
            
            $email  = $request->email;
            $otp    = $request->otp;
            
            $user   = $this->userService->getUserByEmail($email);
            // $user = $this->userService->verifyOtp($validatedData);
            
    
            if ($user->otp != $otp && $user->otp_expires_at != Carbon::now()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP or OTP expired.'
                ], 400);
            }
    
            $user->email_verified_at = now();
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            return $this->successResponse(message: 'Email verified successfully.',);
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error !!!'
            ], 500);
        }
    }

    public function requestPasswordReset(Request $request) {
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

       $user = $this->userService->requestPasswordReset($validatedData);

        Log::info('The found user for reset: ' . $user . ': ');

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.'
            ], 404);
        }

        $this->generateAndSendOtp($user);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent for password reset.'
        ]);
    }

    public function resetPassword(Request $request) {
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
            'otp' => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $this->userService->resetPassword($validatedData);

        Log::info('The found user for reset first: ' . $user . ': ');
        Log::info('request password: ' . $validatedData['password'] . ': ');

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP or OTP expired.'
            ], 400);
        }

        $userDetails = [
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expires_at' => null,
        ];

        User::whereId($user->id)->update($userDetails);

        // $user->password = Hash::make($validatedData['password']);
        // $user->otp = null;
        // $user->otp_expires_at = null;
        // $user->save();

        // Log::info('The found user for reset updated: ' . $user . ': ');

        return response()->json([
            'status' => true,
            'message' => 'Password reset successfully.'
        ]);

    }

    protected function generateAndSendOtp($user)
    {
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        // Send OTP email
        Mail::to($user->email)->send(new OtpMail($otp));
    }

    protected function sendOtp(User $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found.'
            ], 404);
        }

        $this->generateAndSendOtp($user);

        return response()->json([
            'status' => true,
            'message' => 'OTP sent successfully.'
        ]);
    }

    /**
     * Get Monnify token.
     */
    public function getToken()
    {
        $response = Http::withBasicAuth($this->monnify_apiKey, $this->monnify_secretKey)->post($this->monnify_baseUrl . '/api/v1/auth/login');
        return json_decode($response)->responseBody->accessToken;
    }

    /**
     * Create a new user and handle bank account creation for NG users.
     */
    protected function create(array $data)
    {
        if ($data['country'] == 'NG') {
            $ran = $data['telephone'] . rand(99, 999999);
            $details = [
                "accountReference" => $ran,
                "accountName" => $data['name'],
                "currencyCode" => "NGN",
                "contractCode" => $this->monnify_contactCode,
                "customerEmail" => $data['email'],
                "bvn" => $this->monnify_bvnNumber,
                "customerName" => $data['name'],
                "getAllAvailableBanks" => true
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getToken(),
                'Content-Type' => 'application/json'
            ])->post($this->monnify_baseUrl . '/api/v2/bank-transfer/reserved-accounts', $details);

            $return_data = json_decode($response);

            if (isset($return_data->requestSuccessful) && $return_data->requestSuccessful == true) {
                foreach ($return_data->responseBody->accounts as $account) {
                    $bankDetails['res_reference'] = $return_data->responseBody->reservationReference;
                    $bankDetails['user_name'] = $return_data->responseBody->customerEmail;
                    $bankDetails['user_email'] = $return_data->responseBody->customerName;
                    $bankDetails['account_name'] = $account->accountName;
                    $bankDetails['account_number'] = $account->accountNumber;
                    $bankDetails['bank_name'] = $account->bankName;
                    $bankDetails['bank_code'] = $account->bankCode;
                    $bankDetails['account_status'] = $return_data->responseBody->status;

                    $this->UserRepository->createUserAccountDetails($bankDetails);
                }

                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'create_pin' => Hash::make($data['create_pin']),
                    'mobile' => $data['telephone'],
                    'dob' => $data['dob'],
                    'username' => $data['username'],
                    'gender' => $data['gender'],
                    'address' => $data['address'],
                    'country' => $data['country'],
                ]);

                $this->createWalletAndActivity($user, $data);


                return $user;
            } else {
                return response()->json(['error' => 'Bank account creation failed.'], 500);
            }
        } else {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'mobile' => $data['telephone'],
                'dob' => $data['dob'],
                'pin' => $data['pin'],
                'username' => $data['username'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'country' => $data['country'],
            ]);

            $this->createWalletAndActivity($user, $data);
            return $user;
        }
    }

    /**
     * Create wallet and activity log for the user.
     */
    private function createWalletAndActivity($user, $data)
    {
        if ($user) {
            $WalletDetails = [
                'user_id' => $user->id,
                'balance' => 0,
                'email' => $user->email
            ];

            $ActivityDetails = [
                'username' => $data['username'],
                'report'   => 'just registered'
            ];

            $this->WalletRepository->createWallet($WalletDetails);
            $this->ActivityRepository->createActivity($ActivityDetails);
        }
    }

}
