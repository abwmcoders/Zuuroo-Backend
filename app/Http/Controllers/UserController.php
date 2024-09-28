<?php

namespace App\Http\Controllers;

use App\Repositories\FaqRepository;
use App\Repositories\HistoryRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\SupportRepository;
use App\Repositories\UserBankDetailsRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    private PaymentRepository $PaymentRepository;
    private HistoryRepository $HistoryRepository;
    private FaqRepository $FaqRepository;
    private SupportRepository $SupportRepository;
    private NotificationRepository $NotificationRepository;
    private UserRepository $UserRepository;
    private $UserBankDetailsRepository;

    public function __construct(
        PaymentRepository $PaymentRepository,
        HistoryRepository $HistoryRepository,
        FaqRepository $FaqRepository,
        SupportRepository $SupportRepository,
        NotificationRepository $NotificationRepository,
        UserRepository $UserRepository,
        UserBankDetailsRepository $UserBankDetailsRepository
    ) {
        $this->PaymentRepository = $PaymentRepository;
        $this->HistoryRepository = $HistoryRepository;
        $this->FaqRepository = $FaqRepository;
        $this->SupportRepository = $SupportRepository;
        $this->NotificationRepository = $NotificationRepository;
        $this->UserRepository = $UserRepository;
        $this->UserBankDetailsRepository = $UserBankDetailsRepository;
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


}
