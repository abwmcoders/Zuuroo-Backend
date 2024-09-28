<?php

namespace App\Repositories;

use App\Interfaces\TermConditionRepositoryInterface;
use App\Models\TermCondition;

class TermConditionRepository
{
    public function getAllTermConditions()
    {
        return TermCondition::all();
        // return 'Hello Only';
    }

    public function getTermConditionById($TermConditionId)
    {
        return TermCondition::findOrFail($TermConditionId);
    }

    public function deleteTermCondition($TermConditionId)
    {
        TermCondition::destroy($TermConditionId);
    }

    public function createTermCondition(array $TermConditionDetails)
    {
        return TermCondition::create($TermConditionDetails);
    }

    public function updateTermCondition($TermConditionId, array $newDetails)
    {
        return TermCondition::whereId($TermConditionId)->update($newDetails);
    }

    public function getFulfilledTermConditions()
    {
        return TermCondition::where('is_fulfilled', true);
    }

}