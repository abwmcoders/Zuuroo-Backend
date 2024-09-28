<?php
namespace App\Repositories;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

class PaymentRepository
{
    public function getAllPayments()
    {
        return Payment::all();
    }

    public function getTodayPayments()
    {
        $today = date('d');
        return Payment::whereDay('created_at', $today)->get();
    }

    public function getMonthPayments()
    {
        $thisMonth = date('m');
        return Payment::whereMonth('created_at', $thisMonth)->get();
    }

    // PayStack Total Accounts
    public function getAllPPayments()
    {
        return Payment::where('payment_mode', 'Paystack')->get();
    }

    public function getTodayPPayments()
    {
        $today = date('d');
        return Payment::whereDay('created_at', $today)->where('payment_mode', 'Paystack')->get();
    }

    public function getMonthPPayments()
    {
        $thisMonth = date('m');
        return Payment::whereMonth('created_at', $thisMonth)->where('payment_mode', 'Paystack')->get();
    }
    // ------------------------------

    // Monnify Total Accounts
    public function getAllMPayments()
    {
        return Payment::where('payment_mode', 'Monnify')->get();
    }

    public function getTodayMPayments()
    {
        $today = date('d');
        return Payment::whereDay('created_at', $today)->where('payment_mode', 'Monnify')->get();
    }

    public function getMonthMPayments()
    {
        $thisMonth = date('m');
        return Payment::whereMonth('created_at', $thisMonth)->where('payment_mode', 'Monnify')->get();
    }

    public function getPaystackPayments()
    {
        return Payment::join('users', 'users.id', '=', 'payments.user_id')->where('payment_mode', 'Paystack')->get();
    }

    public function getMonnifyPayments()
    {
        return Payment::join('users', 'users.id', '=', 'payments.user_id')->where('payment_mode', 'Monnify')->get();
    }

    public function getPaymentsById($UserId)
    {
        return Payment::where('user_id', $UserId)->orderBy('id', 'DESC')->get();
    }

    public function getPaymentByStatus($status)
    {
        $response = Payment::where('status', $status)->first();
        return $response;
    }

    public function getPaymentByRef($PaymentRef)
    {
        // return Paystack::findOrFail($PaystackId);
        $response = Payment::where('reference', $PaymentRef)->first();
        return $response;
    }

    public function createPayment(array $PaymentDetails)
    {
        return Payment::create($PaymentDetails);
    }

    public function deletePaymentRecord($PaymentRef)
    {
        Payment::destroy($PaymentRef);
    }

    public function updatePayment($UserId, array $PaymentDetails)
    {
        return Payment::whereId($UserId)->update($PaymentDetails);
    }
    
}
