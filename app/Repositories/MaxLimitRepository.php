<?php

namespace App\Repositories;

use App\Models\MaxLimit;
use App\Models\TopupLimit;

class MaxLimitRepository
{
    public function getAll()
    {
        return MaxLimit::all();
    }

    public function findById($id)
    {
        return MaxLimit::find($id);
    }

    public function create(array $data)
    {
        return MaxLimit::create($data);
    }

    public function update($id, array $data)
    {
        $topupLimit = MaxLimit::find($id);
        if ($topupLimit) {
            $topupLimit->update($data);
            return $topupLimit;
        }
        return null;
    }

    public function delete($id)
    {
        $topupLimit = MaxLimit::find($id);
        if ($topupLimit) {
            $topupLimit->delete();
            return true;
        }
        return false;
    }
}
