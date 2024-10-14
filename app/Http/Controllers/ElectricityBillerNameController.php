<?php

namespace App\Http\Controllers;

use App\Models\ElectricityBillerName;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ElectricityBillerNameController extends Controller
{

    use ApiResponseTrait;

    public function getAll()
    {
        $billers = ElectricityBillerName::all();
        return $this->successResponse(data: $billers);
    }

    // Get electricity billers by status
    public function getByStatus($status)
    {
        $billers = ElectricityBillerName::where('status', $status)->get();
        return $this->successResponse(data: $billers,);
    }
}
