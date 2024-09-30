<?php

namespace App\Http\Controllers;

use App\Models\OfferService;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\ProductCategory;
use Illuminate\Http\Request;


class ServicesController extends Controller
{
    //
    public function addService(Request $request)
    {
        $request->validate([
            'operator'      => ['required', 'numeric'],
            'categoryName'  => ['required', 'string'],
            'categoryCode'  => ['required', 'string'],
        ]);

        $check = ProductCategory::where('category_name', $request->categoryName)->first();

        if( $check )
        {
            Alert::warning('Sorry', 'Details Already Exit !!!');
            return back();
        }
        else
        {
            $sql = ProductCategory::create([
                'operator_code' => $request->operator,
                'category_name' => $request->categoryName,
                'category_code' => $request->categoryCode
            ]);

            if( $sql )
            {
                Alert::success('Success', 'DONE ..');
                return back();
            }
            else
            {
                Alert::error('Oops', 'Operation Failed, Try Later !!!');
                return back();
            }
        }
    }

    // Activate
    public function activateService($id)
    {
        # code...
        $sql = ProductCategory::where('category_code', $id)->update([
            'status'    => 1,
        ]);

        if( $sql )
        {
            Alert::success('Success', 'DONE ... ');
            return back();
        }
        else
        {
            Alert::error('Oops', 'Operation Failed, Try Later !!!');
            return back();
        }

    }

    // Deactivate
    public function deactivateService($id)
    {
        # code...
        $sql = ProductCategory::where('category_code', $id)->update([
            'status'    => 0,
        ]);

        if( $sql )
        {
            Alert::success('Success', 'DONE ... ');
            return back();
        }
        else
        {
            Alert::error('Oops', 'Operation Failed, Try Later !!!');
            return back();
        }
    }



    // Activate Loan
    public function activateLService($id)
    {
        # code...
        $sql = OfferService::where('service_code', $id)->update([
            'service_state'    => 1,
        ]);

        if( $sql )
        {
            Alert::success('Success', 'DONE ... ');
            return back();
        }
        else
        {
            Alert::error('Oops', 'Operation Failed, Try Later !!!');
            return back();
        }

    }

    // Deactivate Loan
    public function deactivateLService($id)
    {
        # code...
        $sql = OfferService::where('service_code', $id)->update([
            'service_state'    => 0,
        ]);

        if( $sql )
        {
            Alert::success('Success', 'DONE ... ');
            return back();
        }
        else
        {
            Alert::error('Oops', 'Operation Failed, Try Later !!!');
            return back();
        }
    }


}
