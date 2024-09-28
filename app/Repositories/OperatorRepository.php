<?php

namespace App\Repositories;

use App\Interfaces\OperatorRepositoryInterface;
use App\Models\Operator;

class OperatorRepository
{
    public function getAllOperators()
    {
        return Operator::all();
    }

    public function getAllOperatorsInfo()
    {
        return Operator::join('countries', 'operators.country_code', '=', 'countries.country_code')
                         ->orderBy('operators.id', 'DESC')
                         ->get();
    }

    public function getOperatorById($OperatorId)
    {
        return Operator::findOrFail($OperatorId);
    }

    public function deleteOperator($OperatorId)
    {
        Operator::destroy($OperatorId);
    }

    public function createOperator(array $OperatorDetails)
    {
        return Operator::create($OperatorDetails);
    }

    public function updateOperator($OperatorId, array $newDetails)
    {
        return Operator::whereId($OperatorId)->update($newDetails);
    }

    public function getOperatorByStatus($OperatorId)
    {
        return Operator::where('status', true)->get();
    }

    public function getOperatorByCountry($CountryIso)
    {
        return Operator::where('country_code', $CountryIso)
                        ->groupBy('operator_code')
                        ->get();
    }

}