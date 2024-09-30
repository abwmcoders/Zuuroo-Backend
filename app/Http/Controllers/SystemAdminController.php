<?php

namespace App\Http\Controllers;


use Alert;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Repositories\FundRepository;
use App\Repositories\UserRepository;
use App\Repositories\AdminRepository;
use App\Repositories\WalletRepository;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert as FacadesAlert;

class SystemAdminController extends Controller
{
    //
    private $UserRepository;
    private $AdminRepository;
    private $WalletRepository;
    private $FundRepository;

    public function __construct(UserRepository $UserRepository, AdminRepository $AdminRepository, WalletRepository $WalletRepository, FundRepository $FundRepository)
    {
        $this->UserRepository = $UserRepository;
        $this->AdminRepository = $AdminRepository;
        $this->WalletRepository = $WalletRepository;
        $this->FundRepository = $FundRepository;
    }

    public function index()
    {
        $data = [
            'i' => 0,
            'UserInfo'=>$this->AdminRepository->getAllAdmins()
        ];
        return view('app.admin.manage_admins_page', $data);
    }
    
    public function add_admin()
    {
        return view('app.admin.add_admin');
    }
    
    public function change_admin_password(Request $request)
    {
        # code...
        // dd( $request->all() );
        $request->validate([
            'user_id'   => ['required', 'numeric'],
            'password'  => ['required', 'string', 'max:200']
        ]);
       
        $id = $request->user_id;
        $password = Hash::make($request->password);
        $make_admin = Admin::whereId($id)->update([ 'password'=>$password ]);
        if($make_admin)
        {
            FacadesAlert::success("Success!", "Admin Password Successfully Changed To :".$request->password);
            return back();
        }
    }

    public function make_admin_page($id)
    {
        # code...
        $disable_admin = Admin::whereId($id)->update([ 'role'=>2 ]);
        if($disable_admin)
        {
            FacadesAlert::success("Success!", "User Has Been Assigned A Role Of Admin ...");
            return back();
        }
    }

    public function remove_admin_page($id)
    {
        # code...
        // $disable_admin = Admin::whereId($id)->update([ 'role'=>0 ]);
        $disable_admin = Admin::whereId($id)->destroy();
        if($disable_admin)
        {
            FacadesAlert::success("Success!", "Admin Has Been Remove From The List Of Admininstratives, You Can Always Add Them Back ...");
            return back();
        }
    }
    
    public function create_admin(Request $request)
    {
        $request->validate([
            'name'      =>  ['required'],
            'email'     =>  ['required'],
            'phone'     =>  ['required'],
            'gender'    =>  ['required'],
            'password'  =>  ['required'],
            'address'   =>  ['required'],
            'country'   =>  ['required'],
        ]);

        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $gender = $request->gender;
        $password = Hash::make($request->password);
        $address = $request->address;
        $country = $request->country;
        $active = 1;

        $sql = Admin::where('email', $email)->first();

        if($sql !=null)
        {
            FacadesAlert::warning('Oops!', 'Admin Already Exist');
            return back();
        }
        else
        {
            Admin::create([
                'name'      =>  $name,
                'email'     =>  $email,
                'telephone' =>  $phone,
                'gender'    =>  $gender,
                'password'  =>  $password,
                'country'   =>  $country,
                'address'   =>  $address,
                'role'      =>  2,
                'status'    =>  $active
            ]);

            FacadesAlert::success('Success!', 'Admin Successfully Created');
            return back();
        }
    }
    
    // Disable admin
    public function disable_admin($id){
        
         $query = Admin::where('id', $id)->update([ 'status' =>0 ]);
         if($query){
            return back()->with('success', 'Admin Successfully Deactivated');
         }else{
            return back()->with('fail', 'Operation failed, try later :)');
         }
    }
    
    // activate admin
    public function activate_admin($id){
        
         $query = Admin::where('id', $id)->update([ 'status' =>1 ]);
         if($query){
            return back()->with('success', 'Admin Successfully Activated');
         }else{
            return back()->with('fail', 'Operation failed, try later :)');
         }
    }
    
    public function add_user_fund (Request $request)
    {
        $request->validate([
            'user_id'   => ['required', 'numeric'],
            'amount'    => ['required', 'numeric']
        ]);
        // $request->validate([
        //     'message'   => ['required'],
        //     'user_id'   => ['required']
        // ]);

        $userID = $request->user_id;
        $fund_by = $request->fund_by;
        $getWalletBal = $this->WalletRepository->getWalletBalance($userID);
        if($getWalletBal)
        {
            $newBal = $request->amount + $getWalletBal->balance;
            $newWalletDetais = [
                'balance'   => $newBal
            ];
            $FundDetails = [ 'user_id'=>$userID, 'amount'=>$request->amount, 'fund_by'=>$fund_by ];
            $updateBal = $this->WalletRepository->updateWallet( $userID, $newWalletDetais );
            if($updateBal)
            {
                $this->FundRepository->createFund($FundDetails);
                FacadesAlert::success('Success', 'User gifted a sum of '. $request->amount);
                return back();
            }
            else
            {
                FacadesAlert::error('Oops', 'An Error Occured While Performing Your Request');
                return back();
            }
        }
        else
        {
            FacadesAlert::error('Oops', 'Internal Server Error !!!');
            return back();
        }

    }



}
