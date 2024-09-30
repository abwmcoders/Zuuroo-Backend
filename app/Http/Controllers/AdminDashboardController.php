<?php

namespace App\Http\Controllers;


use App\Models\Kyc;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\History;
use App\Models\LoanHistory;
use App\Models\MaxLimit;
use App\Models\OfferService;
use App\Models\LoanLimit;
use App\Models\Operator;
use App\Models\OtherProduct;
use App\Models\Payment;
use App\Models\TermCondition;
use App\Models\DataWallet;
use App\Models\User;
use App\Models\Fund;
use App\Models\Popup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

use App\Repositories\ActivityRepository;
use App\Repositories\AirtimeProductRepository;
use App\Repositories\CountryRepository;
use App\Repositories\DataRepository;
use App\Repositories\HistoryRepository;

use App\Repositories\LoanHistoryRepository;
use App\Repositories\OperatorRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\PaystackRepository;
use App\Repositories\ProductCategoryRepository;

use App\Repositories\ProductRepository;
use App\Repositories\SupportRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Repositories\AdminRepository;

use App\Repositories\KycRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\FaqRepository;
use App\Repositories\AirtimeRepository;
use App\Repositories\SmsDebtorRepository;
use App\Repositories\TermConditionRepository;
use App\Repositories\PaymentGatewayRepository;


class AdminDashboardController extends Controller
{

    private ActivityRepository  $ActivityRepository;
    private AirtimeProductRepository $AirtimeProductRepository;
    private CountryRepository $CountryRepository;
    private DataRepository $DataRepository;
    private HistoryRepository $HistoryRepository;
    private LoanHistoryRepository $LoanHistoryRepository;
    private OperatorRepository $OperatorRepository;
    private PaymentRepository $PaymentRepository;
    private PaystackRepository $PaystackRepository;
    private ProductCategoryRepository $ProductCategoryRepository;
    private ProductRepository $ProductRepository;
    private SupportRepository  $SupportRepository;

    private UserRepository $UserRepository;
    private WalletRepository $WalletRepository;
    private AdminRepository $AdminRepository;
    private KycRepository $KycRepository;
    private NotificationRepository $NotificationRepository;
    private FaqRepository $FaqRepository;
    private AirtimeRepository $AirtimeRepository;
    private SmsDebtorRepository $SmsDebtorRepository;
    private TermConditionRepository $TermConditionRepository;
    private $PaymentGatewayRepository;

    public function __construct(ActivityRepository  $ActivityRepository,
                                AirtimeProductRepository $AirtimeProductRepository,
                                CountryRepository $CountryRepository,
                                DataRepository $DataRepository,
                                HistoryRepository $HistoryRepository,
                                LoanHistoryRepository $LoanHistoryRepository,
                                OperatorRepository $OperatorRepository,
                                PaymentRepository $PaymentRepository,
                                PaystackRepository $PaystackRepository,
                                ProductCategoryRepository $ProductCategoryRepository,
                                ProductRepository $ProductRepository,
                                SupportRepository  $SupportRepository,
                                UserRepository $UserRepository,
                                WalletRepository $WalletRepository,
                                AdminRepository $AdminRepository,
                                KycRepository $KycRepository,
                                NotificationRepository $NotificationRepository,
                                FaqRepository $FaqRepository,
                                AirtimeRepository $AirtimeRepository,
                                SmsDebtorRepository $SmsDebtorRepository,
                                TermConditionRepository $TermConditionRepository,
                                PaymentGatewayRepository $PaymentGatewayRepository
    ){
        $this->middleware(['isLoggedAdmin']);
        // middleware('isLoggedAdmin')

        $this->ActivityRepository = $ActivityRepository;
        $this->AirtimeProductRepository = $AirtimeProductRepository;
        $this->CountryRepository = $CountryRepository;
        $this->DataRepository = $DataRepository;
        $this->HistoryRepository = $HistoryRepository;
        $this->LoanHistoryRepository = $LoanHistoryRepository;
        $this->OperatorRepository = $OperatorRepository;
        $this->PaymentRepository = $PaymentRepository;
        $this->PaystackRepository = $PaystackRepository;
        $this->ProductCategoryRepository = $ProductCategoryRepository;
        $this->ProductRepository = $ProductRepository;
        $this->SupportRepository = $SupportRepository;

        $this->UserRepository= $UserRepository;
        $this->WalletRepository = $WalletRepository;
        $this->AdminRepository = $AdminRepository;
        $this->KycRepository = $KycRepository;
        $this->NotificationRepository = $NotificationRepository;
        $this->FaqRepository = $FaqRepository;
        $this->AirtimeRepository = $AirtimeRepository;
        $this->SmsDebtorRepository = $SmsDebtorRepository;
        $this->TermConditionRepository = $TermConditionRepository;
        $this->PaymentGatewayRepository = $PaymentGatewayRepository;
    }

    public function admin_dashboard(){
        return view('app.admin.admin_dashboard');
    }
    public function all_transaction_history(){
        $data = array(
            'History' => $this->HistoryRepository->getAllHistories()
        );
        return view('app.admin.all_transaction_history', $data);
    }
    public function data_transaction_history(){
        $data = array(
            'LoanInfo' => $this->HistoryRepository->getAllDataHistories()
        );
        return view('app.admin.data_transaction_history', $data);
    }
    public function airtime_transaction_history(){
        $data = array(
            'LoanInfo' => $this->HistoryRepository->getAllAirtimeHistories()
            );
        return view('app.admin.airtime_transaction_history', $data);
    }
    
    public function wallet_debit() {
        $data = array(
            'Histories' => DataWallet::join('users', 'users.id', 'data_wallets.user_id')
                                       ->select('users.username', 'data_wallets.transfer_ref', 'data_wallets.mobile_recharge', 'data_wallets.balance_bfo', 'data_wallets.balance_after', 'data_wallets.amount_debt', 'data_wallets.created_at')
                                       ->orderBy('data_wallets.created_at', 'DESC')->get()
            );
        return view('app.admin.wallet_debit', $data);
    }
    
    public function transaction_receipt($id){
        $data = array(
            'Histories' => History::join('users', 'users.id', 'histories.user_id')
                                    ->join('operators', 'operators.operator_code', 'histories.operator_code')
                                    ->join('countries', 'countries.country_code', 'histories.country_code')
                                    ->select('users.username', 'users.name', 'users.mobile', 'users.email',
                                    'histories.purchase', 'countries.country_name', 'operators.operator_name',
                                    'histories.product_code', 'histories.phone_number', 'histories.selling_price', 
                                    'histories.created_at', 'histories.plan')
                                    ->where('transfer_ref', $id)->first(),
         );
        return view('app.admin.transaction_receipt', $data);
    }
    public function payment_receipt($id){
        $data = array(
            'Payment' => Payment::join('users', 'users.id', 'payments.user_id')
                                ->where('reference', $id)
                                ->first(),
         );
        return view('app.admin.payment_receipt', $data);
    }
    public function loan_receipt($id){
        $data = array(
            'User'  => User::whereId($id)->first(),
            'Info' => LoanHistory::distinct()
                                ->join('users', 'users.id', 'loan_histories.user_id')
                                ->join('countries', 'countries.country_code', 'loan_histories.country_code')
                                ->where('loan_histories.user_id', $id)
                                // ->where('users.id', 'loan_histories.user_id')
                                // ->where('repayment', '<=', NOW())
                                ->groupBy('loan_histories.transfer_ref')
                                ->get()
         );
        return view('app.admin.loan_receipt', $data);
    }



    public function fund_transaction()
    {
        $data = array(
            'Info' => $this->PaymentRepository->getAllPayments()
            );
        return view('app.admin.fund_transaction_history', $data);
    }
    public function loans_transaction(){
        $data = array(
            'dt' => 0,
            'LoanHistory' => $this->LoanHistoryRepository->getAllLoanHistories()
        );
        return view('app.admin.loans_transaction_histories', $data);
    }
    public function repayment_transaction_history(){
        $data = array(
            'LoanInfo' => DB::table('transactions')
                          ->join('operators', 'transactions.provider_code', '=', 'operators.provider_code')
                          ->orderBy('transactions.id', 'DESC')->get()
        );
        return view('app.admin.repayment_transaction_history', $data);
    }
    public function manage_country(){
        $ctr = DB::table('countries')->get();
        $dt = 0;
                $data = [
                    'CountryInfo'=>$ctr,
                    'dt' => $dt
                ];
        return view('app.admin.manage_country_page', $data);
    }
    public function manage_networks(){
        $dt = 0;
            $data = [
                'NetworkInfo'=> DB::table('operators')
                                ->join('countries', 'operators.country_code', '=', 'countries.country_code')
                                ->orderBy('operators.id', 'DESC')
                                ->get(),
                'Country' => DB::table('countries')->get(),
                'dt' => $dt
            ];
        return view('app.admin.manage_networks_page', $data);
    }

    public function manage_products(){
        $dt = 0;
        $data = [
            'Operator' =>  Operator::where('country_code', 'NG')->get(),
            'ProductInfo' => $this->ProductRepository->getAllProductsInfo(),
            'ProductCat' => $this->OperatorRepository->getAllOperators(),
            'NetworkInfo'=> $this->OperatorRepository->getAllOperatorsInfo(),
            'Country' =>$this->CountryRepository->getAllCountries(),
            'dt' => $dt
        ];
        return view('app.admin.manage_products', $data);
    }
    
    public function editProduct($id)
    {
        $data = [
            'ProductInfo'   => Product::where('products.product_code', $id)->first(),
            'ProductCat'    => ProductCategory::all(),
            'Operator'      => Operator::all(),
            'dt' => 0
        ];
        return view('app.admin.edit_product', $data);
    }

    public function manage_productsCat(){
        $dt = 0;
        $data = [
            'Categories' => $this->ProductCategoryRepository->getAllProductsCatInfo(),
            'Operator'   => Operator::all(),
            'dt' => $dt
        ];
        return view('app.admin.manage_productsCat_page', $data);
    }

    public function manage_airtime()
    {
        # code...
        $data = [
            'Info' => OtherProduct::where('name', 'airtime')->first(),
            'DInfo' => OtherProduct::where('name', 'data')->first(),
        ];
        return view('app.admin.manage_airtime', $data );
    }
    public function manage_data()
    {
        # code...
        $data = [
            'Info' => OtherProduct::where('name', 'data')->first()
        ];
        return view('app.admin.manage_data', $data );
    }
    public function view_services()
    {
        $data = [
            'dt'            => 0,
            'Operator'      => $this->OperatorRepository->getAllOperators(),
            'Categorynfo'   => $this->ProductCategoryRepository->getAllProductCategoriesWithOperator(),
            'PurchaseService'  => OfferService::where('service_category', 'purchase')->get(),
        ];
        return view('app.admin.view_services', $data);
    }
    public function repayment_history(){
        return view('app.admin.repayment_history_page');
    }
    public function manage_users(){
        $data = [
            'i' => 0,
            'UserInfo'=>$this->UserRepository->getAllUsers()
        ];
        return view('app.admin.manage_users_page', $data);
    }
    public function manage_users_funds () {
        $data = [
            'i' => 0,
            'UserInfo'=>$this->UserRepository->getAllUsersWithWalletBal()
        ];
        return view('app.admin.manage_users_funds', $data);
    }
    public function view_users_funds () {
        $data = [
            'i' => 0,
            'UserInfo'  => Fund::join('users', 'users.id', 'funds.user_id')->orderBy('funds.created_at', 'DESC')->get()
        ];
        return view('app.admin.view_user_funds', $data);
    }
    public function view_kyc_info (Request $request) {
        
        $id = $request->route('id');
        $data = [
            'i' => 0,
            'getUserInfo'   => $this->KycRepository->getKycByUserId($id),
            'UserInfo'      => $this->UserRepository->getUserById($id)
        ];
        // dd($id);
        return view('app.admin.view_kyc_info', $data);
    }

    public function manageUserTransaction($id) {
        $data = [
            'i' => 0,
            'UserInfo'=>$this->HistoryRepository->getAllHistoryByUser($id)
        ];
        return view('app.admin.user_transaction_page', $data);
    }

    public function update_data_status($id)
    {
        # code...
        $update_dataRecord = History::where('transfer_ref', $id)->update([ 'processing_state'=>'delivered' ]);
        if($update_dataRecord)
        {
            Alert::success("Success!", "Data Record Updated To Delivered, Kindly Notify User Of The Changes ...");
            return back();
        }
    }


    public function verify_user($id)
    {
        $verify = User::whereId($id)->update([ 'email_verified_at'=>NOW() ]);
        if($verify)
        {
            return back();
        }
    }

    public function loan_payment(){
        $data = [
            'dt' => 0,
            'PaymentInfo'=>DB::table('payment_method')->get()
        ];
        return view('app.admin.loan_payment_method_page', $data);
    }
    public function manage_debtors(){
        $data = [
            'dt' => 0,
            'LoanInfo' => $this->LoanHistoryRepository->getDebtors()
        ];
        return view('app.admin.manage_debtors', $data);
    }
    
    public function late_loan_payment(){
        $today = NOW(); //date('Y-m-d');
        $data = [
            'dt' => 0,
            'LoanInfo' => $this->LoanHistoryRepository->getLateLoanPayment()
        ];
        return view('app.admin.late_loan_payment', $data);
    }
    // public function late_loan_payment(){
    //     $today = NOW(); //date('Y-m-d');
    //     $data = [
    //         'dt' => 0,
    //         'LoanInfo' => $this->LoanHistoryRepository->getAllLoanHistories()
    //     ];
    //     return view('app.admin.late_loan_payment', $data);
    // }
    
    
    public function loan_record(){
        $today = NOW();
        $data = [
            'TotalLoan' => $this->LoanHistoryRepository->TotalLoan(),
            'DueLoan' => $this->LoanHistoryRepository->DueLoan(),
            'TotalPaid' => $this->LoanHistoryRepository->TotalPaid()
        ];
        return view('app.admin.loan_record', $data);
    }
    public function paid_loan(){
        $today = NOW();
        $data=[
            'dt' => 0,
            'PaidInfo'=> $this->LoanHistoryRepository->getPaidLoan()
        ];
        return view('app.admin.paid_loan', $data);
    }

    // Accounts Record ==========================================>
    public function paystack_record(){
        $today = NOW();
        $data = [
            'i' => 0,
            // 'Info'          =>$this->PaymentGatewayRepository->getPaymentGatewayById('paystack'),
            'TodayIncome'   => $this->PaymentRepository->getTodayPPayments(),
            'MonthlyIncome' => $this->PaymentRepository->getMonthPPayments(),
            'TotalIncome'   => $this->PaymentRepository->getAllPPayments(),
        ];
        // $data = [
        //     'TotalLoan' => DB::table('loan_record')->where('status', 0)->sum('loan_amount'),
        //     'DueLoan' => DB::table('loan_record')->where('repayment', '<=', NOW())->where('status', 0)->sum('loan_amount'),
        //     'TotalPaid' => DB::table('loan_record')->where('status', '=', 1)->sum('loan_amount')
        // ];
        return view('app.admin.paystack_record', $data);
    }
    public function monnify_record () {
        $data = [
            'i' => 0,
            // 'Info'=>$this->PaymentGatewayRepository->getPaymentGatewayById('monnify'),
            'TodayIncome'   => $this->PaymentRepository->getTodayMPayments(),
            'MonthlyIncome' => $this->PaymentRepository->getMonthMPayments(),
            'TotalIncome'   => $this->PaymentRepository->getAllMPayments(),
        ];
        return view('app.admin.monnify_record', $data);
    }
    public function dingconnect_record(){

        $today = NOW();
        $data = [

        ];
        return view('app.admin.dingconnect_record');
    }
    public function direct_carrier_bill(){
        $today = NOW();
        $data = [
            'TotalLoan' => DB::table('loan_record')->where('status', 0)->sum('loan_amount'),
            'DueLoan' => DB::table('loan_record')->where('repayment', '<=', NOW())->where('status', 0)->sum('loan_amount'),
            'TotalPaid' => DB::table('loan_record')->where('status', '=', 1)->sum('loan_amount')
        ];
        return view('app.admin.direct_carrier_bill', $data);
    }
    public function flutterwave_record(){
        $today = NOW();
        $data = [
            'TotalLoan' => DB::table('loan_record')->where('status', 0)->sum('loan_amount'),
            'DueLoan' => DB::table('loan_record')->where('repayment', '<=', NOW())->where('status', 0)->sum('loan_amount'),
            'TotalPaid' => DB::table('loan_record')->where('status', '=', 1)->sum('loan_amount')
        ];
        return view('app.admin.flutterwave_record', $data);
    }
    // Accounts Record Ends Here ================================>
    public function loan_limit(){
        $data = [
            'dt' => 0,
            'LoanInfo' => LoanLimit::all(),
            'MaxLimit'  =>  MaxLimit::where('topup', 'data')->first(),
            'AMaxLimit'  =>  MaxLimit::where('topup', 'airtime')->first(),
        ];
        return view('app.admin.loan_limit_page', $data);
    }
    public function loan_repayment(){
        # code...
        $data = [
            'Info' => OtherProduct::where('name', 'data')->first(),
            'AInfo' => OtherProduct::where('name', 'airtime')->first(),
            'dt' => 0,
            'LoanInfo' => LoanLimit::all(),
        ];
        return view('app.admin.loan_repayment', $data );
    }
    
    
    
    public function edit_popup(){
        # code...
        // $data = [
        //     'Info' => Popup::first(),
        // ];
        return view('app.admin.edit_popup');
    }
    
    
    public function loan_period(){
        return view('app.admin.loan_period_page');
    }
    public function loan_interest(){
        return view('app.admin.loan_interest_page');
    }
    public function sms_debtors(){
        $data = [
            'dt' => 0,
            'DebtorInfo'=>$this->SmsDebtorRepository->getAllSmsDebtors()
        ];
        return view('app.admin.sms_debtors_page', $data);
    }
    public function set_pricing(){
        $data = [
            'NetworkInfo'=> DB::table('operators')->get()
        ];
        return view('app.admin.set_pricing_page', $data);
    }

    // ->select('exam.exm_id as examID', 'exam.school_id as schoolID')
    public function manage_pricing(){
        $data = [
            'PriceInfo'=> DB::table('data_pricing')
                          ->select('data_pricing.id as dataID', 'data_quant', 'display_text', 'data_price', 'duration', 'interest', 'payment_period')
                          ->join('operators', 'data_pricing.network_code', '=', 'operators.id')
                          ->join('countries', 'operators.country_id', '=', 'countries.id')
                          ->orderBy('operators.id', 'DESC')
                          ->get(),
            'i' => 0
        ];
        return view('app.admin.manage_pricing_page', $data);
    }
    public function manage_faq(){
        $data = [
            'dt' => 0,
            'PaymentInfo'=>$this->FaqRepository->getAllFaqs()
        ];
        return view('app.admin.manage_faq', $data);
    }
    public function support_page(){
        $data = [
            'dt' => 0,
            'PageInfo'=>$this->SupportRepository->getAllSupports()
        ];
        return view('app.admin.support_page', $data);
    }
    public function term_conditions(){
        $data = [
            'dt' => 0,
            'PageInfo'=>$this->TermConditionRepository->getAllTermConditions()
        ];
        return view('app.admin.term_conditions', $data);
    }
    public function terms_andConditions() {
        $data = [
            'Info'  => TermCondition::first()
        ];
        return view('app.admin.term_conditions', $data);
    }
    public function admin_profile(){

        $data = array(
            'Profiles' => $this->AdminRepository->getAdminById(session('LoggedAdmin'))
        );
        return view('app.admin.admin_profile', $data);
    }
    public function admin_notification(){
        $data = array(
            'Notifications' => DB::table('notifications')
                                ->JOIN('users', 'users.id', '=', 'notifications.user_id')
                                ->orderBy('notifications.id', 'DESC')
                                ->get()
        );
        return view('app.admin.admin_notification', $data);
    }
    public function user_transaction($id){
        $data = [
            'i' => 0,
            'UserInfo'=> DB::table('users')
                               ->where('users.id', $id)
                               ->join('histories', 'users.id', 'histories.user_id')
                               ->orderBy('histories.id', 'DESC')
                               ->get()
        ];
        return view('app.admin.user_transaction_page', $data);
    }
    public function view_user($id){
        $upd = DB::table('users')
              ->where('id', $id)
              ->first();
            $data = [
                'getUserInfo'=>$upd
            ];
        return view('app.admin.view_user_info', $data);
    }







// ADMIN FUNCTIONALITIES GOES HERE
public function manage_country_script(Request $request){
    $request->validate([
        'countryName' => 'required|max:255',
        'shortcode'   => 'required|max:255',
        'phonecode' => 'required|max:255',
        'capital' => 'required|max:255',
        'currency' => 'required|max:255',
        'currency_name' => 'required|max:255'
    ]);

    $query = DB::table('countries')->insert([
        'name' => $request->countryName,
        'iso3' => $request->shortcode,
        'phonecode' => $request->phonecode,
        'capital' => $request->capital,
        'currency' => $request->currency,
        'currency_name' => $request->currency_name
    ]);

    if($query){
        return back()->with('success', 'Country successfully added');
    }else{
        return back()->with('fail', 'Operation failed, try later ');
    }

    // Nigeria 	NGA 	NG 	234 	Abuja 	NGN 	Nigerian naira
}

public function manage_operators_script(Request $request){
    $request->validate([
        'countryName' => 'required|max:255',
        'operator'   => 'required|max:255',
        'display_text' => 'required|max:255'
    ]);

    $query_net = DB::table('operators')
            ->insert([
                'country_id' => $request->countryName,
                'operator' => $request->operator,
                'display_text' => $request->display_text
            ]);
    if($query_net){
       // Toastr::success('Country successfully added :)', 'Success');
        return redirect()->back();
        // return back()->with('success', 'Country successfully added');
    }else{
        //Toastr::error('Operation failed, try later :)', 'Failed');
        return redirect()->back();
        // return back()->with('fail', 'Operation failed, try later ');
    }
}

// Set pricing
public function set_pricing_script(Request $request){
    $request->validate([
        'data_quat' => 'required|max:255',
        'network'   => 'required|max:255',
        'data_price' => 'required|max:255',
        'plan_valid' => 'required|max:255',
        'interest' => 'required|max:255',
        'plan_period' => 'required|max:255'
    ]);
    $query_pricing = DB::table('data_pricing')
            ->insert([
                'data_quant' => $request->data_quat,
                'network_code' => $request->network,
                'data_price' => $request->data_price,
                'duration' => $request->plan_valid,
                'interest' => $request->interest,
                'payment_period' => $request->plan_valid
            ]);
    if($query_pricing){
        return back()->with('success', 'Pricing successfully set');
    }else{
        return back()->with('fail', 'Operation failed, try later ');
    }

}

// Set payment method
public function payment_method_script(Request $request){
    $request->validate([
        'payment_method' => 'required|max:255',
        'details'   => 'required|max:255'
    ]);
    $query_pay = DB::table('payment_method')
            ->insert([
                'method' => $request->payment_method,
                'details' => $request->details
            ]);
    if($query_pay){
        return back()->with('success', 'Payment method created');
    }else{
        return back()->with('fail', 'Operation failed, try later ');
    }
}

// Set faq question
public function faq_script(Request $request){
    $request->validate([
        'question' => 'required|max:255',
        'answer'   => 'required|max:255'
    ]);
    $query_pay = DB::table('faq')
            ->insert([
                'question' => $request->question,
                'answer' => $request->answer
            ]);
    if($query_pay){
        return back()->with('success', 'Question created');
    }else{
        return back()->with('fail', 'Operation failed, try later ');
    }
}

// Set sms debtors
public function sms_debtors_script(Request $request){
    $request->validate([
        'sender' => 'required|max:255',
        'message'   => 'required|max:255'
    ]);
    $query_pay = DB::table('sms_debtors')
            ->insert([
                'sender' => $request->sender,
                'message' => $request->message
            ]);
    if($query_pay){
        return back()->with('success', 'Message Sent');
    }else{
        return back()->with('fail', 'Operation failed, try later ');
    }
}

// Set support
public function support_script(Request $request){
    $request->validate([
        'page'      => 'required|max:255',
        'page_name' => 'required|max:255',
        'page_link' => 'required|max:255',
        'page_icon' => 'required|max:255'
    ]);
    $query_sup = DB::table('support')
            ->insert([
                'page_type' => $request->page,
                'page_name' => $request->page_name,
                'page_link' => $request->page_link,
                'page_icon' => $request->page_icon
            ]);
    if($query_sup){
        return back()->with('success', 'Page Support Set');
    }else{
        return back()->with('fail', 'Operation failed, try later ');
    }
}

// Set term_conditions
public function term__of_conditions(Request $request){
    $request->validate([
        'termOfUse'      => 'required|mimes:pdf,xlx,csv|max:2048'
    ]);
    $fileName = time().'.'.$request->termOfUse->extension();
    $query_sup = DB::table('term_conditions')
            ->insert([
                'fileName' => $fileName
            ]);

    if($query_sup){
        $request->termOfUse->move(public_path('uploads'), $fileName);
        return back()
            ->with('success','You have successfully upload term of use.')
            ->with('file', $fileName);
    }else{
        return back()->with('fail', 'Operation failed, try later ');
    }



}



// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<


        // User Profile
        function userProfile(){

            if(session()->has('LoggedAdmin')){
                $user = DB::table('admins')->where('id', '=', session('LoggedAdmin'))->first();
                $data = [
                    'LoggedAdminInfo'=>$user
                ];
            }
            return view('app.admin.admin_profile', $data);
        }

        // Activate User Account
        // Disable User
        public function activate_user($id){
            $query = DB::table('users')
                 ->where('id', $id)
                 ->update([
                     'status' =>1
                 ]);
                 if($query){
                    return back()->with('success', 'User Activated');
                 }else{
                    return back()->with('fail', 'Operation failed, try later :)');
                 }
        }

        // Disable User
        public function disable_user($id){
            $query = DB::table('users')
                 ->where('id', $id)
                 ->update([
                     'status' =>0
                 ]);
                 if($query){
                    return back()->with('success', 'User Disabled');
                 }else{
                    return back()->with('fail', 'Operation failed, try later :)');
                 }
        }

        // Delete faq
        public function delete_faq($id){
            $query = DB::table('faq')
                 ->where('id', $id)
                 ->delete();
                 if($query){
                    return back()->with('success', 'Deleted');
                 }else{
                    return back()->with('fail', 'Operation failed, try later :)');
                 }
        }

        // Delete sms debtor
        public function delete_sms($id){
            $query = DB::table('sms_debtors')
                 ->where('id', $id)
                 ->delete();
                 if($query){
                    return back()->with('success', 'Deleted');
                 }else{
                    return back()->with('fail', 'Operation failed, try later :)');
                 }
        }

        // Delete support page
        public function delete_support($id){
            $query = DB::table('support')
                 ->where('id', $id)
                 ->delete();
                 if($query){
                    return back()->with('success', 'Deleted');
                 }else{
                    return back()->with('fail', 'Operation failed, try later :)');
                 }
        }

        // Delete term of use page
        public function delete_term($id){
            $query = DB::table('term_conditions')
                 ->where('id', $id)
                 ->delete();
                 if($query){
                    return back()->with('success', 'Deleted');
                 }else{
                    return back()->with('fail', 'Operation failed, try later :)');
                 }
        }

        // Delete pricing
        public function delete_pricing($id){
            $query = DB::table('data_pricing')
                    ->where('id', $id)
                    ->delete();
                 if($query){
                    return back()->with('success', 'Deleted');
                 }else{
                    return back()->with('fail', 'Operation failed, try later :)');
                 }
        }

        // Delete delete_payment_method
        public function delete_payment_method($id){

            $sq = DB::table('payment_method')->where('id', $id)->first();
            if( $sq->status == 0 ){ $st =1; }else{ $st=0; }

            $query = DB::table('payment_method')
                    ->where('id', $id)
                    ->update([
                        'status'=>$st
                    ]);
                 if($query){
                    return back()->with('success', 'Updated');
                 }else{
                    return back()->with('fail', 'Operation failed, try later :)');
                 }
        }


         // Logout script
         public function signout(){
            if(session()->has('LoggedAdmin')){
                session()->pull('LoggedAdmin');
                $act = DB::table('activity')
                           ->insert([
                               'username' => Session('LoggedAdminFullName'),
                               'report'   => 'Logged Out'
                           ]);
                return redirect()->route('admin_login');
            }
        }

}
