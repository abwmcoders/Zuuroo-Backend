<?php

namespace App\Services;

use App\Models\TermCondition;
use Illuminate\Http\Request;

class TermConditionService
{
    // Fetch all TermConditions
    public function getAllTerms()
    {
        return TermCondition::all();
    }

    // Fetch a specific TermCondition by ID
    public function getTermById($id)
    {
        return TermCondition::findOrFail($id);
    }

    // Create a new TermCondition
    public function createTerm(Request $request)
    {
        $request->validate([
            'write_up' => 'required',
            'admin'    => 'required|max:255',
        ]);

        return TermCondition::create($request->all());
    }

    // Update an existing TermCondition
    public function updateTerm(Request $request, $id)
    {
        $request->validate([
            'write_up' => 'required|max:255',
            'admin'    => 'required|max:255',
        ]);

        $termCondition = TermCondition::findOrFail($id);
        $termCondition->update($request->all());

        return $termCondition;
    }

    // Delete a TermCondition by ID
    public function deleteTerm($id)
    {
        $termCondition = TermCondition::findOrFail($id);
        $termCondition->delete();
        return true;
    }
}
