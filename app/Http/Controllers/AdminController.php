<?php

namespace App\Http\Controllers;

use App\Repositories\AdminRepository;
use App\Repositories\ActivityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    private AdminRepository $AdminRepository;
    private ActivityRepository $ActivityRepository;

    public function __construct(AdminRepository $AdminRepository, ActivityRepository $ActivityRepository)
    {
        $this->middleware(['auth:api'])->except(['login', 'create']);
        $this->AdminRepository = $AdminRepository;
        $this->ActivityRepository = $ActivityRepository;
    }

    // Admin Registration (API)
    public function create(Request $request)
    {
        $request->validate([
            'fullname'  => 'required|max:255',
            'email'     => 'required|email|unique:admins|max:255',
            'number'    => 'required|max:11',
            'gender'    => 'required|max:6',
            'username'  => 'required|max:255',
            'password'  => 'required|confirmed',
            'terms'     => 'required'
        ]);

        $AdminDetails = [
            'name'      => $request->fullname,
            'email'     => $request->email,
            'telephone' => $request->number,
            'gender'    => $request->gender,
            'address'   => $request->username,
            'role'      => Hash::make(2),
            'status'    => Hash::make(0),
            'password'  => Hash::make($request->password),
            'country'   => 'Nigeria',
        ];

        $user = $this->AdminRepository->createAdmin($AdminDetails);

        if ($user) {
            $Activities = [
                'username' => $request->fullname,
                'report'   => 'just registered as an admin'
            ];
            $this->ActivityRepository->createActivity($Activities);

            return response()->json(['message' => 'Registration Completed'], 201);
        } else {
            return response()->json(['message' => 'Error during registration'], 500);
        }
    }

    // Admin Login (API)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = $this->AdminRepository->getExistingAdmin($request->email);
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if ($user->role == 0 || $user->status == 0) {
                    return response()->json(['message' => 'User Account Suspended/Inactive'], 403);
                } else {
                    // Add activity logging
                    $Activities = [
                        'username' => $user->name,
                        'report'   => 'Logged In'
                    ];
                    $this->ActivityRepository->createActivity($Activities);

                    // Return user data as API response (you can use JWT or Passport for token handling)
                    return response()->json(['message' => 'Login Successful', 'user' => $user], 200);
                }
            } else {
                return response()->json(['message' => 'Invalid Login Details'], 401);
            }
        } else {
            return response()->json(['message' => 'No account found for this email'], 404);
        }
    }

    // Logout Admin (API)
    public function signout()
    {
        $Activities = [
            'username' => session('LoggedAdminFullName'),
            'report'   => 'Logged Out'
        ];

        $this->ActivityRepository->createActivity($Activities);

        session()->flush();
        return response()->json(['message' => 'Logout Successful'], 200);
    }

    // Admin Dashboard (API)
    public function admin_dashboard()
    {
        $data = [
            'DingAccount' => $this->AdminRepository->getDingConnectBal(),
        ];

        return response()->json($data, 200);
    }
}























// <?php

//namespace App\Http\Controllers; -->

// use App\Http\Controllers\Controller;
// use App\Repositories\AdminRepository;
// use App\Repositories\ActivityRepository;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;

// class AdminController extends Controller
// {
//     //
//     private AdminRepository $AdminRepository;
//     private ActivityRepository $ActivityRepository;

//     public function __Construct(AdminRepository $AdminRepository, ActivityRepository $ActivityRepository){
//         $this->middleware(['AlreadyLoggedAdmin']);
//         $this->AdminRepository = $AdminRepository;
//         $this->ActivityRepository = $ActivityRepository;
//     }

    // public function admin_login(){
    //     return view('app.admin.admin_login');
    // }

    // public function admin_register(){
    //     return view('app.admin.admin_register');
    // }

    // // Logout script
    // public function signout(){
    //     if(session()->has('LoggedAdmin')){
    //         session()->pull('LoggedAdmin'); 
    //         $Activities = [
    //             'username' => Session('LoggedAdminFullName'),
    //             'report'   => 'Logged Out'
    //         ];
    //         $this->ActivityRepository->createActivity($Activities);
    //         return redirect()->route('admin_login');
    //     }
    // }

    // public function admin_dashboard()
    // {
        
    //     $data = [
    //         'DingAccount' => $this->AdminRepository->getDingConnectBal(),
            
    //     ];
    //     return view('app.admin.admin_dashboard', $data);
    // }

  
    // // Admin Registration 
    // public function create(Request $request){
    //     $request->validate([
    //         'fullname'  => 'required|max:255',
    //         'email'     => 'required|email|unique:admins|max:255',
    //         'number'    => 'required|max:11',
    //         'gender'    => 'required|max:6',
    //         'username'  => 'required|max:255',
    //         'password'  => 'required|confirmed',
    //         'terms'     => 'required'
    //     ]);

    //     $AdminDetails = [
    //         'name'      => $request->fullname,
    //         'email'     => $request->email,
    //         'telephone' => $request->number,
    //         'gender'    => $request->gender,
    //         'address'   => $request->username,
    //         'role'      => Hash::make(2),
    //         'status'    => Hash::make(0),
    //         'password'  => Hash::make($request->password),
    //         'country'   => 'Nigeria',
    //     ];
    //     $user = $this->AdminRepository->createAdmin($AdminDetails);
    //     // 'img/avatars/usr-img.png'
    //     if($user){
    //         $Activities = [
    //             'username' => $request->fullname,
    //             'report'   => 'just registered as an admin'
    //         ];
    //         $this->ActivityRepository->createActivity($Activities);
    //         return redirect('admin_login')->with('success', 'Registration Completed, login to continue ');
    //     }else{
    //         return back()->with('fail', 'Error occur during registration ');
    //     }
        
    // }

    // // Admin Login
    // public function login(Request $request){
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     $user = $this->AdminRepository->getExistingAdmin($request->email);
    //     if($user){
    //         if(Hash::check($request->password, $user->password)){
    //             if( $user->role==0 or $user->status==0 ){
    //                 return back()->with('fail', 'User Account Suspended/Inactive, Contact Super Admin');
    //             }
    //             else{
    //                 $request->session()->put('LoggedAdmin', $user->id);
    //                 $request->session()->put('LoggedAdminRole', $user->role);
    //                 $request->session()->put('LoggedAdminFullName', $user->name);
    //                 $request->session()->put('LoggedAdminEmail', $user->email);
    //                 $request->session()->put('LoggedAdminTelephone', $user->telephone);
    
    //                 $Activities = [
    //                     'username' => Session('LoggedAdminFullName'),
    //                     'report'   => 'Logged In'
    //                 ];
    //                 $this->ActivityRepository->createActivity($Activities);
    //                 return redirect('admin_dashboard');
    //             }
    //         }else{
    //             return back()->with('fail', 'Invalid Login Details');
    //         }
    //     }else{
    //         return back()->with('fail', 'No account found for this email !!!');
    //     }
    // }
    
//}
