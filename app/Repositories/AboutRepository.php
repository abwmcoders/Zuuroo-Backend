<?php

namespace App\Repositories;

use App\Models\About;

class AboutRepository
{
    public function all()
    {
        return About::all();
    }

    public function find($id)
    {
        return About::findOrFail($id);
    }

    public function create(array $data)
    {
        return About::create($data);
    }

    public function update($id, array $data)
    {
        $item = $this->find($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        $item = $this->find($id);
        $item->delete();
        return $item;
    }
}