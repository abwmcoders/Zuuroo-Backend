<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\RegistrationResource;
use App\Interfaces\ProfileServiceInterface;
use App\Models\LoanHistory;
use App\Models\User;
use App\Repositories\FaqRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\SupportRepository;
use App\Repositories\UserBankDetailsRepository;
use App\Repositories\UserRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    use ApiResponseTrait;

    private PaymentRepository $PaymentRepository;
    private HistoryRepository $HistoryRepository;
    private FaqRepository $FaqRepository;
    private SupportRepository $SupportRepository;
    private NotificationRepository $NotificationRepository;
    private UserRepository $UserRepository;
    private $UserBankDetailsRepository;

    protected $profileService;

    public function __construct(
        PaymentRepository $PaymentRepository,
        HistoryRepository $HistoryRepository,
        FaqRepository $FaqRepository,
        SupportRepository $SupportRepository,
        NotificationRepository $NotificationRepository,
        UserRepository $UserRepository,
        UserBankDetailsRepository $UserBankDetailsRepository,
        ProfileServiceInterface $profileService
    ) {
        $this->PaymentRepository = $PaymentRepository;
        $this->HistoryRepository = $HistoryRepository;
        $this->FaqRepository = $FaqRepository;
        $this->SupportRepository = $SupportRepository;
        $this->NotificationRepository = $NotificationRepository;
        $this->UserRepository = $UserRepository;
        $this->UserBankDetailsRepository = $UserBankDetailsRepository;
        $this->profileService = $profileService;
    }

    //!---- Get Token ------
    public static function getToken()
    {
        $DataDetails = [
            'client_id' => '919c366c-4645-46f8-80cc-35c77040014b',
            'client_secret' => '71apN0bg3CXO7ACVWe9mjjaibZu6sd4uC0VA2rH10GI=',
            'grant_type' => 'client_credentials'
        ];
        $response = Http::asForm()->post('https://idp.ding.com/connect/token', $DataDetails);
        return $response['access_token'];
    }

    //!---- Get Faqs ------
    public function faqs()
    {
        $faqs = $this->FaqRepository->getAllFaqs();
        $data = [
            'dt' => 0,
            'records' => $faqs
        ];

        return $this->successResponse(data: $data,);
    }

    //!---- Loans ------
    public function loans()
    {
        $uid = Auth::id();

        $loanInfo = LoanHistory::where('user_id', $uid)
        ->whereIn('processing_state', ['successful', 'delivered'])
        ->where('payment_status', 'paid')
        ->latest()
        ->get();

        $outLoan = LoanHistory::where('user_id', $uid)
        ->whereIn('payment_status', ['pending', 'partially'])
        ->whereIn('processing_state', ['successful', 'delivered'])
        ->latest()
        ->get();

        $data = [
            'loanInfo' => $loanInfo,
            'outLoan' => $outLoan,
        ];


        return $this->successResponse(data: $data,);
    }

    //!---- Out Loans ------
    public function outLoans()
    {
        $uid = Auth::id();

        $outLoan = LoanHistory::where('user_id', $uid)
            ->whereIn('payment_status', ['pending', 'partially'])
            ->get();

        return $this->successResponse(data: $outLoan,);
    }

    //!---- Loan Receipt ------
    public function userLoanReceipt($id)
    {
        $loanInfo = LoanHistory::join('users', 'users.id', '=', 'loan_histories.user_id')
        ->join('countries', 'countries.country_code', '=', 'loan_histories.country_code')
        ->select('loan_histories.*', 'countries.country_name')
        ->where('loan_histories.transfer_ref', $id)
        ->first();

        if ($loanInfo) {
            return $this->successResponse(data: $loanInfo,);
        } else {
            return $this->errorResponse(message:'Loan receipt not found',code: 404,);
        }
    }

    //!---- Support ------
    public function supports()
    {
        $supports = $this->SupportRepository->getAllSupports();
        return $this->successResponse(data: $supports,);
    }

    //!---- Profile ------
    public function userProfile()
    {
        $user = Auth::user();

        if (!$user) {
            return $this->errorResponse(message: "User not found");
        }
        return $this->successResponse(data: new RegistrationResource($user),);
    }

    //!-- Update Password ----
    public function updatePassword(Request $request)
    {
        $uid = Auth::user()->id;
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        $userDetails = [
            'password' => Hash::make($request->password)
        ];

        $this->UserRepository->updateUser($uid, $userDetails);
        return $this->successResponse(message:'Password updated successfully.');
    }

    //!-- Update Pin ----
    public function updatePin(Request $request)
    {
        $uid = Auth::user()->id;
        $data = $request->validate([
            'pin' => ['required', 'string', 'max:4', 'confirmed']
        ]);

        $userDetails = [
            'create_pin' => Hash::make($request->pin)
        ];

        $this->UserRepository->updateUser($uid, $userDetails);
        return $this->successResponse(message: 'Pin updated successfully.',);
    }

    //!-- Verify Pin ----
    public function verifyPin(Request $request)
    {

        $request->validate([
            'pin' => 'required|string|min:4|max:4',
        ]);

        $uid = Auth::user()->id;

        $user = $this->UserRepository->verifyPin($uid);

        $inputPin = Hash::make($request->pin);

        if($user) {
            // Check if the input PIN matches the hashed pin in the user's record
            if (Hash::check($request->pin, $user->create_pin)) {
                return $this->successResponse(message: 'PIN verification successful');
            } else {
                return $this->errorResponse(message: 'PIN does not match', code: 401,);
            }
        } else {
            return $this->errorResponse(message: 'Unauthorized !!!', code: 401,);
        }


        
    }

    //!-- Update Phone Number ----
    public function updatePhoneNumber(Request $request)
    {
        $uid = Auth::user()->id;
        $data = $request->validate([
            'phoneNumber' => ['required', 'numeric']
        ]);

        $userDetails = [
            'mobile' => $request->phoneNumber,
            'number_verify_at' => now()
        ];

        $this->UserRepository->updateUser($uid, $userDetails);
        return $this->successResponse(message: 'Phone number updated successfully.',);
    }


    //!---- Update Profile ------
    public function updateProfile(UpdateProfileRequest $request)
    {
        try {
            // Get the authenticated user ID
            $userId = Auth::id(); // Simplified method to get user ID

            // Validate the incoming request data
            $validatedData = $request->validated();

            // Prepare user details for the update
            $userDetails = [
                'name'     => $validatedData['name'] ?? null,
                'email'    => $validatedData['email'] ?? null,
                'mobile'   => $validatedData['mobile'] ?? null,
                'username' => $validatedData['username'] ?? null,
                'address'  => $validatedData['address'] ?? null,
                'dob'      => $validatedData['dob'] ?? null,
                'gender'   => $validatedData['gender'] ?? null,
            ];

            // Perform the update using UserRepository
            $updatedUser = $this->UserRepository->updateUser($userId, $userDetails);

            return response()->json([
                'status'  => true,
                'message' => 'Profile successfully updated',
                'data'    => new RegistrationResource($updatedUser),
            ], 200);
        } catch (\Exception $e) {
            // Handle any errors or exceptions
            return response()->json([
                'status'  => false,
                'message' => 'Failed to update profile',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // public function updateProfile(UpdateProfileRequest $request)
    // {
    //     $uid = Auth::user()->id;

    //     $validatedData = $request->validated();

    //     // if (!$user) {
    //     //     return response()->json(['message' => 'Unauthorized'], 401);
    //     // }
        
    //     $userDetails = [
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'mobile' => $request->mobile,
    //         'username' => $request->username,
    //         'address' => $request->address,
    //         'dob' => $request->dob,
    //         'gender' => $request->gender,
    //     ];

    //     //$updatedUser = $this->profileService->updateProfile($user, $validatedData);
    //     $updatedUser = $this->UserRepository->updateUser($uid, $userDetails);
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Profile successfully updated',
    //         'data' => new RegistrationResource($updatedUser)
    //     ]);
    // }


}
