<?php

namespace App\Repositories;

use App\Models\LoanLimit;

class LoanLimitRepository
{
    public function getAll()
    {
        return LoanLimit::all();
    }

    public function findById($id)
    {
        return LoanLimit::find($id);
    }

    public function create(array $data)
    {
        return LoanLimit::create($data);
    }

    public function update($id, array $data)
    {
        $loanLimit = LoanLimit::find($id);
        if ($loanLimit) {
            $loanLimit->update($data);
            return $loanLimit;
        }
        return null;
    }

    public function delete($id)
    {
        $loanLimit = LoanLimit::find($id);
        if ($loanLimit) {
            $loanLimit->delete();
            return true;
        }
        return false;
    }
}
