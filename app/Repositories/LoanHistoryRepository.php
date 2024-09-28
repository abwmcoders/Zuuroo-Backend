<?php

namespace App\Repositories;

use App\Models\LoanHistory;
use Illuminate\Support\Facades\DB;

class LoanHistoryRepository
{
    public function getAllLoanHistories()
    {
        return LoanHistory::join('users', 'users.id', '=','loan_histories.user_id')->join('operators', 'loan_histories.operator_code', '=', 'operators.operator_code')
                            ->join('countries', 'loan_histories.country_code', '=', 'countries.country_code')
                            ->select('users.name', 'loan_histories.selling_price', 'loan_histories.payment_status', 'loan_histories.plan', 'loan_histories.phone_number', 'loan_histories.selling_price', 'loan_histories.due_date', 'loan_histories.repayment', 'loan_histories.receive_currency', 'loan_histories.purchase', 'loan_histories.transfer_ref', 'loan_histories.selling_price', 'loan_histories.loan_amount', 'loan_histories.receive_value', 'loan_histories.commission_applied', 'loan_histories.processing_state', 'loan_histories.created_at', 'operators.operator_name', 'countries.country_name')
                            ->orderBy('loan_histories.id', 'DESC')
                            ->distinct()
                            ->get();
        // DB::table('users')->where('loan_record.repayment', '<=', $today)
        // ->join('loan_record', 'users.id', '=', 'loan_record.user_id')
        // ->join('transactions', 'loan_record.referrence_id', '=', 'transactions.TransferRef')
        // ->orderBy('transactions.id', 'DESC')
        // ->get(),
    }

    public function getDebtors()
    {
        

// $query = DB::table('loan_histories')
//     ->select(
//         'loan_histories.plan',
//         'loan_histories.user_id',
//         'loan_histories.phone_number',
//         'loan_histories.selling_price',
//         'loan_histories.due_date',
//         'loan_histories.repayment',
//         'loan_histories.receive_currency',
//         'loan_histories.purchase',
//         'loan_histories.transfer_ref',
//         'loan_histories.selling_price',
//         'loan_histories.receive_value',
//         'loan_histories.commission_applied',
//         'loan_histories.processing_state',
//         'loan_histories.created_at',
//         'users.name',
//         'users.mobile',
//         'users.email'
//     )
//     ->join('users', 'loan_histories.user_id', '=', 'users.id')
//     ->where('loan_histories.payment_status', 'pending')
//     ->orWhere('loan_histories.payment_status', 'partially')
//     ->groupBy('users.id')
//     ->orderBy('loan_histories.id', 'DESC');

//  return $results = $query->get();

        
     return LoanHistory::select(
        'loan_histories.plan',
        'loan_histories.user_id',
        'loan_histories.phone_number',
        'loan_histories.selling_price',
        'loan_histories.due_date',
        'loan_histories.repayment',
        'loan_histories.receive_currency',
        'loan_histories.purchase',
        'loan_histories.transfer_ref',
        'loan_histories.selling_price',
        'loan_histories.receive_value',
        'loan_histories.commission_applied',
        'loan_histories.processing_state',
        'loan_histories.created_at',
        'users.name',
        'users.mobile',
        'users.email'
    )
    ->join('users', 'loan_histories.user_id', '=', 'users.id')
    ->where(function ($query) {
        $query->where('loan_histories.payment_status', 'pending')
            ->orWhere('loan_histories.payment_status', 'partially');
    })
    ->where(function ($query) {
      $query->where('loan_histories.processing_state', 'successful')
          ->orWhere('loan_histories.processing_state', 'delivered');
    })
    ->groupBy('users.id')
    ->orderByDesc('loan_histories.id')
    ->get();
    
    
    
    // ->where('loan_histories.payment_status', 'pending')
    // ->orWhere('loan_histories.payment_status', 'partially')
    
    
    
    
        
        // LoanHistory::join('users', 'loan_histories.user_id', '=', 'users.id')
        //                     ->where('loan_histories.payment_status', 'pending')
        //                     // ->where('repayment', '<=', date('Y-m-d'))
        //                     ->select('loan_histories.plan', 'loan_histories.user_id', 'loan_histories.phone_number', 'loan_histories.selling_price', 'loan_histories.due_date', 'loan_histories.repayment', 'loan_histories.receive_currency', 'loan_histories.purchase', 'loan_histories.transfer_ref', 'loan_histories.selling_price', 'loan_histories.receive_value', 'loan_histories.commission_applied', 'loan_histories.processing_state', 'loan_histories.created_at', 'users.name', 'users.mobile', 'users.email')
        //                     ->groupBy('users.id')
        //                     ->orderBy('loan_histories.id', 'DESC')
        //                     ->distinct()
        //                     ->get();
                            
                            
        
        
        
        
        // $latestLoanHistories = LoanHistory::join('users', 'loan_histories.user_id', '=', 'users.id')
        //                     ->where('loan_histories.payment_status', 'pending')
        //                     ->select('loan_histories.plan', 'loan_histories.user_id', 'loan_histories.phone_number', 'loan_histories.selling_price', 'loan_histories.due_date', 'loan_histories.repayment', 'loan_histories.receive_currency', 'loan_histories.purchase', 'loan_histories.transfer_ref', 'loan_histories.selling_price', 'loan_histories.receive_value', 'loan_histories.commission_applied', 'loan_histories.processing_state', 'loan_histories.created_at', 'users.name', 'users.mobile', 'users.email')
        //                     ->groupBy('users.id')
        //                     ->orderBy('loan_histories.id', 'DESC')
        //                     ->distinct();
                        
        //                     // Subquery to get the latest created_at for each user
        //                     $latestLoanHistories = $latestLoanHistories->whereIn('loan_histories.id', function ($query) {
        //                         $query->select(DB::raw('MAX(id)'))
        //                               ->from('loan_histories')
        //                               ->groupBy('user_id');
        //                     });
                            
        //                     // Subquery to get the latest repayment for each user
        //                     $latestLoanHistories = $latestLoanHistories->whereIn('loan_histories.repayment', function ($query) {
        //                         $query->select(DB::raw('MAX(repayment)'))
        //                               ->from('loan_histories')
        //                               ->groupBy('user_id');
        //                     });
                            
        //                   return $latestLoanHistories = $latestLoanHistories->get();

        
        
        
        
        
        
        
    }
    
    public function getLateLoanPayment(){
        // return LoanHistory::join('users', 'loan_histories.user_id', '=', 'users.id')
        //                     ->where('loan_histories.payment_status', 'pending')
        //                     ->where('repayment', '<=', NOW())
        //                     ->select('loan_histories.plan', 'loan_histories.user_id', 'loan_histories.phone_number', 'loan_histories.selling_price', 'loan_histories.due_date', 'loan_histories.repayment', 'loan_histories.receive_currency', 'loan_histories.purchase', 'loan_histories.transfer_ref', 'loan_histories.selling_price', 'loan_histories.receive_value', 'loan_histories.commission_applied', 'loan_histories.processing_state', 'loan_histories.created_at', 'users.name', 'users.mobile', 'users.email')
        //                     ->groupBy('users.id')
        //                     ->orderBy('loan_histories.id', 'DESC')
        //                     ->distinct()
        //                     ->get();
        
        
        return LoanHistory::join('users', 'users.id', '=','loan_histories.user_id')->join('operators', 'loan_histories.operator_code', '=', 'operators.operator_code')
                            ->join('countries', 'loan_histories.country_code', '=', 'countries.country_code')
                            ->select('users.name', 'loan_histories.selling_price', 'loan_histories.payment_status', 'loan_histories.plan', 'loan_histories.phone_number', 'loan_histories.selling_price', 'loan_histories.due_date', 'loan_histories.repayment', 'loan_histories.receive_currency', 'loan_histories.purchase', 'loan_histories.transfer_ref', 'loan_histories.selling_price', 'loan_histories.loan_amount', 'loan_histories.receive_value', 'loan_histories.commission_applied', 'loan_histories.processing_state', 'loan_histories.created_at', 'operators.operator_name', 'countries.country_name')
                            ->orderBy('loan_histories.id', 'DESC')
                            ->where(function ($query) {
                              $query->where('loan_histories.processing_state', 'successful')
                                  ->orWhere('loan_histories.processing_state', 'delivered');
                            })
                            ->where(function ($query) {
                              $query->where('loan_histories.payment_status', 'pending')
                                  ->orWhere('loan_histories.payment_status', 'partially');
                            })
                            ->where('loan_histories.repayment', '<=', NOW())
                            ->distinct()
                            ->get();
    }

    public function getPaidLoan()
    {
        return LoanHistory::join('users', 'loan_histories.user_id', '=', 'users.id')
                            ->where('loan_histories.payment_status', 'paid')
                            ->where(function ($query) {
                              $query->where('loan_histories.processing_state', 'successful')
                                  ->orWhere('loan_histories.processing_state', 'delivered');
                            })
                            ->where('loan_histories.repayment', '<=', NOW())
                            ->select('loan_histories.plan', 'loan_histories.phone_number', 'loan_histories.selling_price', 'loan_histories.due_date', 'loan_histories.repayment', 'loan_histories.receive_currency', 'loan_histories.purchase', 'loan_histories.transfer_ref', 'loan_histories.selling_price', 'loan_histories.amount_paid', 'loan_histories.receive_value', 'loan_histories.commission_applied', 'loan_histories.loan_amount', 'loan_histories.processing_state', 'loan_histories.created_at', 'users.name', 'users.mobile', 'users.email')
                            ->groupBy('users.id')
                            ->orderBy('loan_histories.id', 'DESC')
                            // ->distinct()
                            ->get();

    }

    public function TotalLoan()
    {
        return LoanHistory::where('payment_status', 'pending')->sum('loan_amount');
    }

    public function DueLoan()
    {
        return LoanHistory::where('repayment', '<=', NOW())
                            ->where('payment_status', 'pending')
                            ->sum('loan_amount');
    }

    public function TotalPaid()
    {
        return LoanHistory::where('payment_status', 'paid')
                            ->sum('loan_amount');
    }

    public function getLoanHistoryById($HistoryId)
    {
        return LoanHistory::findOrFail($HistoryId);
    }

    public function getUserLoan($UserId)
    {
        return LoanHistory::where('user_id', $UserId)
                            ->where(function ($query) {
                              $query->where('processing_state', 'successful')
                                  ->orWhere('processing_state', 'delivered');
                            })
                            ->where(function ($query) {
                              $query->where('payment_status', 'pending')
                                  ->orWhere('payment_status', 'partially');
                            })->first();
    }

    public function deleteLoanHistory($HistoryId)
    {
        LoanHistory::destroy($HistoryId);
    }

    public function createLoanHistory(array $HistoryDetails)
    {
        return LoanHistory::create($HistoryDetails);
    }

    public function updateLoanHistory($HistoryId, array $newDetails)
    {
        return LoanHistory::whereId($HistoryId)->update($newDetails);
    }

    public function LoanHistoryByStatus()
    {
        return LoanHistory::where('payment_status', true)->get();
    }

}
