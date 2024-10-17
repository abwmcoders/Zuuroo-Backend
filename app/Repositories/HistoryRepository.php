<?php

namespace App\Repositories;

use App\Interfaces\HistoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\History;

class HistoryRepository
{
    public function getAllHistories()
    {
        // $histories = History::join('operators', 'histories.operator_code', 'operators.operator_code')
        //                     ->join('countries', 'histories.country_code', 'countries.country_code')
        //                     ->select(
        //                         'histories.id as history_id', // Use an alias to distinguish between IDs
        //                         'histories.plan',
        //                         'histories.phone_number',
        //                         'histories.selling_price',
        //                         'histories.receive_currency',
        //                         'histories.purchase',
        //                         'histories.transfer_ref',
        //                         'histories.selling_price',
        //                         'histories.receive_value',
        //                         'histories.commission_applied',
        //                         'histories.processing_state',
        //                         'histories.created_at',
        //                          DB::raw('CAST(histories.created_at AS DATETIME) as created_at'), // Cast created_at to DATETIME
        //                         'operators.operator_name',
        //                         'countries.country_name'
        //                     )
        //                     ->groupBy('history_id')
        //                     ->distinct()
        //                     ->orderBy('histories.id', 'desc')
        //                     ->get();
        $histories = History::
        //join('operators', 'histories.operator_code', '=', 'operators.operator_code')
       // ->join('countries', 'histories.country_code', '=', 'countries.country_code')
        // ->select(
        //     'histories.id as history_id',
        //     DB::raw('MAX(histories.plan) as plan'),
        //     'histories.phone_number',
        //     'histories.selling_price',
        //     'histories.receive_currency',
        //     'histories.purchase',
        //     'histories.transfer_ref',
        //     'histories.receive_value',
        //     'histories.commission_applied',
        //     'histories.processing_state',
        //     'operators.operator_name',
        //     'countries.country_name'
        // )
        //->groupBy('history_id', 'histories.phone_number', 'operators.operator_name', 'countries.country_name')
        orderBy('histories.id', 'desc')
        ->get();
            return $histories->sortByDesc('created_at');



    //     History::join('operators', 'histories.operator_code', 'operators.operator_code')
    //                     ->join('countries', 'histories.country_code', '=', 'countries.country_code')
    //                     ->select(
    //                         'histories.plan',
    //                         'histories.phone_number',
    //                         'histories.selling_price',
    //                         'histories.receive_currency',
    //                         'histories.purchase',
    //                         'histories.transfer_ref',
    //                         'histories.selling_price',
    //                         'histories.receive_value',
    //                         'histories.commission_applied',
    //                         'histories.processing_state',
    //                         'histories.created_at',
    //                         'operators.operator_name',
    //                         'countries.country_name'
    //                     )
    //                     ->orderBy('histories.created_at', 'DESC')
    //                     ->groupBy('histories.id')
    //                     ->distinct()
    //                     ->get();
    }

    public function getAllDataHistories()
    {
        return History::join('operators', 'histories.operator_code', 'operators.operator_code')
                        ->join('countries', 'histories.country_code', '=', 'countries.country_code')
                        ->select('histories.plan', 'histories.phone_number', 'histories.selling_price', 'histories.receive_currency', 'histories.purchase', 'histories.transfer_ref', 'histories.selling_price', 'histories.receive_value', 'histories.commission_applied', 'histories.processing_state', 'histories.created_at', 'operators.operator_name', 'countries.country_name')
                        ->where('purchase', 'Data')
                        ->orderBy('histories.created_at', 'desc')
                        ->distinct()
                        ->get();
    }

    public function getAllAirtimeHistories()
    {
        return History::join('operators', 'histories.operator_code', 'operators.operator_code')
                        ->join('countries', 'histories.country_code', '=', 'countries.country_code')
                        ->select('histories.plan', 'histories.phone_number', 'histories.selling_price', 'histories.receive_currency', 'histories.purchase', 'histories.transfer_ref', 'histories.selling_price', 'histories.receive_value', 'histories.commission_applied', 'histories.processing_state', 'histories.created_at', 'operators.operator_name', 'countries.country_name')
                        ->where('purchase', 'Airtime')
                        ->orderBy('histories.created_at', 'desc')
                        ->distinct()
                        ->get();
    }

    public function getAllHistoryByUser($UserId)
    {
        return History::join('operators', 'histories.operator_code', 'operators.operator_code')
                        ->where('histories.user_id', $UserId)
                        ->select('histories.transfer_ref', 'histories.purchase', 'histories.plan', 'operators.operator_name', 'histories.phone_number', 'histories.receive_value', 'histories.processing_state', 'histories.country_code', 'histories.created_at')
                        ->groupby('histories.transfer_ref')
                        // ->orderBy('histories.id', 'asc')
                        ->orderBy('histories.created_at', 'desc')
                        ->get();
    }

    public function getPurchaseHistoryByUser($Purchase, $UserId)
    {
        return History::join('operators', 'histories.operator_code', 'operators.operator_code')
                        ->where('user_id', $UserId)
                        ->where('purchase', $Purchase)
                        ->select('histories.plan', 'histories.phone_number', 'histories.selling_price', 'histories.receive_currency', 'histories.purchase', 'histories.transfer_ref', 'histories.selling_price', 'histories.receive_value', 'histories.commission_applied', 'histories.processing_state', 'histories.created_at', 'operators.operator_name', 'operators.country_code')
                        ->groupby('histories.transfer_ref')
                        ->orderBy('histories.created_at', 'desc')
                        ->get();
    }

    public function getHistoryById($HistoryId)
    {
        return History::findOrFail($HistoryId);
    }

    public function getHistoryByPurchase($HistoryId)
    {
        return History::findOrFail($HistoryId);
    }

    public function deleteHistory($HistoryId)
    {
        History::destroy($HistoryId);
    }

    public function createHistory(array $HistoryDetails)
    {
        return History::create($HistoryDetails);
    }

    public function updateHistory($HistoryId, array $newDetails)
    {
        return History::whereId($HistoryId)->update($newDetails);
    }

    public function HistoryByStatus()
    {
        return History::where('processing_state', true)->get();
    }

}
