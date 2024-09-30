<?php

namespace App\Http\Controllers;

use App\Repositories\FaqRepository;
use App\Repositories\ProductRepository;
use App\Repositories\SupportRepository;
use App\Repositories\TermConditionsRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Country;
use Alert;
use App\Models\User;
use App\Models\MaxLimit;
use App\Models\LoanLimit;
use Illuminate\Support\Facades\Hash;
use App\Models\Operator;
use App\Models\OtherProduct;
use App\Models\Product;
use App\Models\ProductCategory;
use RealRashid\SweetAlert\Facades\Alert as FacadesAlert;

class ProductController extends Controller
{
    private $TermConditionsRepository;
    private $ProductRepository;
    private $SupportRepository;
    private $FaqRepository;

    public function __construct(
                ProductRepository $ProductRepository,
                SupportRepository $SupportRepository,
                TermConditionsRepository $TermConditionsRepository,
                FaqRepository $FaqRepository)
    {
        // $this->middleware(['AlreadyLoggedAdmin']);
        $this->TermConditionsRepository = $TermConditionsRepository;
        $this->ProductRepository = $ProductRepository;
        $this->SupportRepository = $SupportRepository;
        $this->FaqRepository = $FaqRepository;
    }

        //GET FUNCTIONS ========================================================>>>>>>>>>>>>>>>>>>>>>>
    public function isLoan($id) : JsonResponse {
        $isLoan = Country::where('country_code', $id)->first();
        if($isLoan->is_loan==1)
        {
            Country::where('country_code', $id)->update(['is_loan'=>0]);
            return response()->json([
                'message' => 'Operation Succeeded'
            ]);
            // Alert::success('Success', 'Operation succeeded');
            // return back();
        }
        else
        {
            Country::where('country_code', $id)->update(['is_loan'=>1]);
            return response()->json([
                'message' => 'Operation Succeeded'
            ]);
        }
    }

    public function activateRepay($id) : JsonResponse {
        $isLoan = LoanLimit::whereId($id)->first();
        if($isLoan->status==1)
        {
            LoanLimit::whereId($id)->update(['status'=>0]);
            return response()->json([
                'message' => 'Operation Succeeded'
            ]);
            // Alert::success('Success', 'Operation succeeded');
            // return back();
        }
        else
        {
            LoanLimit::where('id', $id)->update(['status'=>1]);
            return response()->json([
                'message' => 'Operation Succeeded'
            ]);
        }
    }

    public function isLoanlimit($id) : JsonResponse {
        $isLoan = LoanLimit::whereId($id)->first();
        if($isLoan->status==true)
        {
            LoanLimit::whereId($id)->update(['status'=>0]);
            return response()->json([
                'message' => 'Operation Succeeded'
            ]);
        }
        else
        {
            LoanLimit::whereId($id)->update(['status'=>1]);
            return response()->json([
                'message' => 'Operation Succeeded'
            ]);
        }
    }


    public function toggle_countryStatus($id) {
        $isLoan = Country::whereId($id)->first();
        if($isLoan->status==1)
        {
            Country::whereId($id)->update(['status'=>0]);
            FacadesAlert::success('Success', 'Operation succeeded');
            return back();
        }
        else
        {
            Country::whereId($id)->update(['status'=>1]);
            FacadesAlert::success('Success', 'Operation succeeded');
            return back();
        }
    }

    public function delete_country($id) {
        $delete = Country::whereId($id)->delete();
        FacadesAlert::success('Success', 'Operation succeeded');
        return back();
    }

    // ----------------------------------NETWORK OPERATORS ----------------------------------

    public function toggle_NetworkStatus($id) {
        $isLoan = Operator::where('operator_code', $id)->first();
        if($isLoan->status==1)
        {
            Operator::whereId($id)->update(['status'=>0]);
            FacadesAlert::success('Success', 'Operation succeeded');
            return back();
        }
        else
        {
            Operator::where('operator_code', $id)->update(['status'=>1]);
            FacadesAlert::success('Success', 'Operation succeeded');
            return back();
        }
    }
    public function make_admin_page($id)
    {
        # code...
        $disable_admin = User::whereId($id)->update([ 'role'=>1 ]);
        if($disable_admin)
        {
            FacadesAlert::success("Success!", "User Has Been Assigned A Role Of Admin ...");
            return back();
        }
    }
    public function delete_network($id) {
        $delete = Operator::where('operator_code', $id)->delete();
        FacadesAlert::success('Success', 'Operation succeeded');
        return back();
    }



    // ------------------------------------ DELETE PRODUCT CATEGORY --------------------------------------------
    public function delete_productCat($id) {
        ProductCategory::where('category_code', $id)->delete();
        FacadesAlert::success('Success', 'Operation succeeded');
        return back();
    }
    // ------------------------------------ DELETE PRODUCT  --------------------------------------------
    public function delete_product($id) {
        Product::where('product_code', $id)->delete();
        FacadesAlert::success('Success', 'Operation succeeded');
        return back();
    }
    public function delete_faq($id)
    {
        $this->FaqRepository->deleteFaq($id);
        FacadesAlert::success('Success', 'FAQ Deleted !!!');
        return back();
    }
    public function deleteSsupportPage($id)
    {
        $delSupp = $this->SupportRepository->deleteSupportRecord($id);

        FacadesAlert::success('Success', 'Selected Support Info Deleted !!!');
        return back();
    }
    public function delete_terms($id)
    {
        $delSupp = $this->TermConditionsRepository->deleteTermCondition($id);

        FacadesAlert::success('Success', 'Selected Support Info Deleted !!!');
        return back();
    }





    // POST FUNCTIONS ======================================================>>>>>>>>>>>>>>>>>>>>

    public function manage_networks(Request $request) {
        // dd($request->all());
        $request->validate([
            'operatorCode'   => ['required', 'string'],
            'productCat'    => ['required', 'string'],
        ]);


        $operatorCode = $request->operatorCode;
        $categoryCode = $operatorCode.'_'.$request->productCat;
        $categoryName = $request->productCat;

        $sql = ProductCategory::where('category_code', $categoryCode)->first();
        if( $sql != null)
        {
            ProductCategory::where('category_code', $categoryCode)->update([ 'operator_code'=>$operatorCode, 'category_code'=>$categoryCode, 'category_name'=>$categoryName ]);
            FacadesAlert::success('Success', 'Operation succeeded');
            return back();
        }
        else
        {
            ProductCategory::create([ 'operator_code'=>$operatorCode, 'category_code'=>$categoryCode, 'category_name'=>$categoryName ]);
            FacadesAlert::success('Success', 'Operation succeeded');
            return back();
        }
    }

    public function add_product(Request $request) {
        dd($request->all());
        $request->validate([
            'countryName' => ['required', 'string'],
            'operator'    => ['required', 'string'],
            'productCat'  => ['required', 'string'],
            'product'     => ['required', 'string'],
            'productCode' => ['required', 'string'],
            'price'       => ['required', 'string'],
            'loanprice'   => ['required', 'string'],
        ]);



        $countryName    = $request->countryName;
        $operator       = $request->operator;
        $productCat     = $request->productCat;
        $product        = $request->product;
        $productCode    = $request->productCode;
        $price          = $request->price;
        $loanprice      = $request->loanprice;

        $sql = Product::where('product_code', $productCode)->first();
        if( $sql != null)
        {
            ProductCategory::where('product_code', $productCode)
                            ->update([
                                'category_code'=>$productCat,
                                'country_code'=>$countryName,
                                'operator_name'=>$operator,
                                'operator_code'=>$productCode,
                                'product_code'=>$productCode,
                                'product_name'=>$product,
                                'price'=>$price,
                                'loan_price'=>$loanprice
                            ]);
            FacadesAlert::success('Success', 'Operation succeeded');
                            return back();
        }
        else
        {
            ProductCategory::create([
                'category_code'=>$productCat,
                'country_code'=>$countryName,
                'operator_name'=>$operator,
                'operator_code'=>$productCode,
                'product_code'=>$productCode,
                'product_name'=>$product,
                'price'=>$price,
                'loan_price'=>$loanprice
            ]);
            FacadesAlert::success('Success', 'Operation succeeded');
            return back();
        }
    }

    public function change_user_password(Request $request)
    {
        # code...
        // dd( $request->all() );
        $request->validate([
            'user_id'   => ['required', 'numeric'],
            'password'  => ['required', 'string', 'max:200']
        ]);

        $id = $request->user_id;
        $password = Hash::make($request->password);
        $make_admin = User::whereId($id)->update([ 'password'=>$password]);
        if($make_admin)
        {
            FacadesAlert::success("Success!", "User Password Successfully Changed To :".$request->password);
            return back();
        }
    }

    public function add_country(Request $request) {
        $request->validate([
            'countryName' => ['required', 'string'],
            'shortcode' => ['required', 'string'],
            'phonecode'    => ['required', 'string'],
            'capital'  => ['required', 'string'],
            'currency'     => ['required', 'string'],
            'currency_name' => ['required', 'string'],
        ]);

        //!TODO:: PRODUCT CODE MISSING
        $productCode    = $request->productCode;


        $countryName = $request->countryName;
        $shortcode = $request->shortcode;
        $phonecode = $request->phonecode;
        $capital = $request->capital;
        $currency = $request->currency;
        $currency_name = $request->currency_name;

        $sql = Country::where('country_code', $shortcode)->first();
        if( $sql != null)
        {
            Country::where('country_code', $productCode)
                            ->update([
                                'country_name'=>$countryName,
                                'country_code'=>$shortcode,
                                'is_loan'=>0,
                                'phone_code'=>$phonecode,
                            ]);
            FacadesAlert::success('Success', 'Operation succeeded');
                            return back();
        }
        else
        {
            Country::create([
                'country_name'=>$countryName,
                'country_code'=>$shortcode,
                'is_loan'=>0,
                'phone_code'=>$phonecode,
            ]);
            FacadesAlert::success('Success', 'Operation succeeded');
            return back();
        }
    }

    public function add_faq(Request $request){
        $request->validate([
            'question'  => ['required', 'string', 'max:255'],
            'answer'    => ['required', 'string', 'max:255'],
        ]);
        $FaqDetails = [
            'question'  => $request->question,
            'answer'    => $request->answer
        ];
        $insFaq = $this->FaqRepository->createFaq($FaqDetails);
        if($insFaq)
        {
            FacadesAlert::success('Success', 'New Faq Added');
            return back();
        }
        else
        {
            FacadesAlert::error('Oops!', 'An Error Occured Whil Processing Your Request');
            return back();
        }
    }

    public function supportPage(Request $request)
    {
        $request->validate([
            'page'          => ['required', 'string', 'max:255'],
            'page_name'    => ['required', 'string', 'max:255'],
            'page_link'    => ['required', 'string', 'max:255'],
            'page_icon'    => ['required', 'string', 'max:255'],
        ]);
        $SupportDetails = [
            'page_type'    => $request->page,
            'page_name'    => $request->page_name,
            'page_link'    => $request->page_link,
            'page_icon'    => $request->page_icon,
        ];
        $insSupp = $this->SupportRepository->createSupport($SupportDetails);
        if($insSupp)
        {
            FacadesAlert::success('Success', 'New Support Added');
            return back();
        }
        else
        {
            FacadesAlert::error('Oops!', 'An Error Occured Whil Processing Your Request');
            return back();
        }
    }

    public function terms(Request $request)
    {
        $request->validate([
            'termOfUse'          => ['required', 'string'],
        ]);
        $SupportDetails = [
            'write_up'    => $request->termOfUse,
            'admin'    => session('LoggedAdmin'),
        ];
        $insSupp = $this->TermConditionsRepository->createTermCondition($SupportDetails);
        if($insSupp)
        {
            FacadesAlert::success('Success', 'New Support Added');
            return back();
        }
        else
        {
            FacadesAlert::error('Oops!', 'An Error Occured Whil Processing Your Request');
            return back();
        }
    }

    public function product_price_perc(Request $request)
    {
        # code...
        $request->validate([
            'product_price' => ['required', 'numeric'],
            'product_id'    => ['required', 'numeric', 'max:1'],
            'loan_perc'     => ['required', 'numeric'],
        ]);

        $sql = OtherProduct::where('name', 'airtime')->first();

        if($sql != null)
        {
            $id = $request->product_id;
            $update_price = OtherProduct::whereId($id)->update([ 'variation_amount'=>$request->product_price, 'loan_perc'=>$request->loan_perc  ]);

            if($update_price ){
                FacadesAlert::success('Success', 'Airtime Price Updated');
                return back();
            }else{
                FacadesAlert::error('Success', 'Unable To Add Price');
                return back();
            }
        }
        else{
            $update_price = OtherProduct::create([ 'variation_amount'=>$request->product_price, 'loan_perc'=>$request->loan_perc  ]);
            if($update_price ){
                FacadesAlert::success('Success', 'Airtime Price Updated');
                return back();
            }else{
                FacadesAlert::error('Success', 'Unable To Add Price');
                return back();
            }
        }

    }


    public function product_price_data(Request $request)
    {
        # code...
        // dd($request->all());
        $request->validate([
            'product_price' => ['required', 'numeric'],
            'product_id'    => ['required', 'numeric'],
        ]);

        $sql = OtherProduct::where('name', 'data')->first();

        if($sql != null)
        {
            $id = $request->product_id;
            $update_price = OtherProduct::whereId($id)->update([ 'variation_amount'=>$request->product_price]);

            if($update_price ){
                FacadesAlert::success('Success', 'Data Price Updated');
                return back();
            }else{
                FacadesAlert::error('Success', 'Unable To Add Price');
                return back();
            }
        }
        else{
            $update_price = OtherProduct::create([ 'variation_amount'=>$request->product_price]);
            if($update_price ){
                FacadesAlert::success('Success', 'Data Price Updated');
                return back();
            }else{
                FacadesAlert::error('Success', 'Unable To Add Price');
                return back();
            }
        }

    }

    public function add_loanLimit(Request $request){
        $request->validate([
            'labelName'     => ['required', 'string'],
            'Percentage'    => ['required', 'string']
        ]);

        $labelName = $request->labelName;
        $percentage = $request->Percentage;

        $Loanlimit = LoanLimit::where('labelName', $labelName)->first();

        if( $Loanlimit == null)
        {
            LoanLimit::create([ 'labelName'=> $labelName, 'percentage'=> $percentage, 'status'=>true ]);
            FacadesAlert::success("Success!", "Operation Completed !!!");
            return back();
        }
        else
        {
            LoanLimit::where( 'labelName', $labelName)->update([ 'labelName'=> $labelName, 'percentage'=> $percentage, 'status'=>true ]);
            FacadesAlert::success("Success!", "Operation Completed !!!");
            return back();
        }
    }


    public function set_productLimit(Request $request)
    {
        # code...
        // dd($request->all());
        $request->validate([
            'product_price' => ['required', 'numeric'],
            'product_id'    => ['required', 'numeric'],
        ]);
        $productId = $request->product_id;
        $productPrice = $request->product_price;
        $admin = session('LoggedAdmin');

        $sql = MaxLimit::whereId($productId)->first();

        if($sql != null)
        {
            $update_price = MaxLimit::whereId($productId)->update([ 'limit_value'=>$productPrice, 'admin'=>$admin ]);

            if($update_price ){
                FacadesAlert::success('Success!', 'Operation Successful');
                return back();
            }else{
                FacadesAlert::error('Error!', 'Unable To Process The Request');
                return back();
            }
        }
        else{
            $update_price = OtherProduct::create([ 'limit_value'=>$productPrice, 'admin'=>$admin ]);
            if($update_price ){
                FacadesAlert::success('Success!', 'Operation Successful');
                return back();
            }else{
                FacadesAlert::error('Error!', 'Unable To Process The Request');
                return back();
            }
        }

    }

    public function update(Request $request)
    // : JsonResponse
    {
        $ProductId = $request->product_code;
        $request->validate([
            'product_code'  => ['required', 'string', 'max:255'],
            'operator_code' => ['required', 'string', 'max:255'],
            'productCat'    => ['required', 'string', 'max:255'],
            'product_name'  => ['required', 'string', 'max:255'],
            'validity'      => ['required', 'string', 'max:255'],
            'loan_price'    => ['required', 'string', 'max:255'],
            'product_price' => ['required', 'string', 'max:255'],
            'status'        => ['required', 'numeric', 'max:2'],
        ]);

        $ProductDetails = [
            'product_name'      =>  $request->product_name,
            'loan_price'        =>  $request->loan_price,
            'product_price'     =>  $request->product_price,
            'validity'          =>  $request->validity,
            'status'            =>  $request->status,
        ];

        $data = $this->ProductRepository->updateProduct($ProductId, $ProductDetails);
        if($data)
        {

            FacadesAlert::success('Success', 'Record Successfully Updated');
            return back();

        }
        else
        {
            FacadesAlert::error('Oops!', 'An Error Occured While Processing Your Request !!!');
            return back();
        }

    }


    public function delete_loanLimit($id)
    {
        $delSupp = LoanLimit::destroy($id);
        FacadesAlert::success('Success', 'Selected Limit Info Deleted !!!');
        return back();
    }

         // Activate / Deactivate Operator ------------------------------->
     public function activateProduct(Request $request)
     {
         $id = $request->route('id');
         $sql = Product::where('product_code', $id)->update([
             'status'    => 1,
         ]);

         if( $sql )
         {
            return response()->json([
                'success'       => true,
                'statusCode'    => 200,
                'message'       => 'Operation succeeded'
            ]);
         }
         else
         {
            return response()->json([
                'success'       => false,
                'statusCode'    => 500,
                'message'       => 'Operation Failed, Try Later !!!'
            ]);
         }
     }

     public function deactivateProduct(Request $request)
     {
         $id = $request->route('id');
         $sql = Product::where('product_code', $id)->update([ 'status'    => 0, ]);

         if( $sql )
         {
            return response()->json([
                'success'       => true,
                'statusCode'    => 200,
                'message'       => 'Operation succeeded'
            ]);
         }
         else
         {
            return response()->json([
                'success'       => false,
                'statusCode'    => 500,
                'message'       => 'Operation Failed, Try Later !!!'
            ]);
         }
     }

    public function getProductById(Request $request)
    : JsonResponse
    {
        $OperatorId = $request->route('id');
        return response()->json([
            'data' => $this->ProductRepository->getProductByCode($OperatorId)
        ]);
    }

    //
    public function ProductByOperator(Request $request)
    : JsonResponse
    {
        $OperatorId = $request->route('id');
        return response()->json([
            'data' => $this->ProductRepository->ProductByOperator($OperatorId)
        ]);

    // $productResponse = json_decode($response->getBody()->getContents(), true);
    // return $productResponse['Items'];
    }

    public function getProductByPhone(Request $request)
    : JsonResponse
    {
        $OperatorId = $request->route('id');
        return response()->json([
            'data' => $this->ProductRepository->ProductByOperator($OperatorId)
        ]);
    }



    public function ProductByCategory(Request $request)
    : JsonResponse
    {
        $CategoryId = $request->route('id');

        return response()->json([
            'data'  => $this->ProductRepository->getProductByCategory($CategoryId)
        ]);
    }





}