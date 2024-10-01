<?php

namespace App\Repositories;

use App\Models\Faq;

class FaqRepository
{
    // Get all FAQs
    public function getAllFaqs()
    {
        return Faq::all();
    }

    // Find a specific FAQ by ID
    public function findFaqById($id)
    {
        return Faq::find($id);
    }

    // Create a new FAQ
    public function createFaq(array $data)
    {
        return Faq::create($data);
    }

    // Update an existing FAQ
    public function updateFaq($id, array $data)
    {
        $faq = Faq::find($id);

        if ($faq) {
            $faq->update($data);
            return $faq;
        }

        return null;
    }

    // Delete a FAQ
    public function deleteFaq($id)
    {
        $faq = Faq::find($id);

        if ($faq) {
            $faq->delete();
            return true;
        }

        return false;
    }
}





// namespace App\Repositories;

// use App\Interfaces\FaqRepositoryInterface;
// use App\Models\Faq;
// use Illuminate\Support\Facades\Http;
// use Illuminate\Http\Client\Response;
// use Illuminate\Http\Client\RequestException;

// class FaqRepository
// {
//     public function getAllFaqs()
//     {
//         return Faq::all();
//     }

//     public function createFaq(array $FaqDetails)
//     {
//         return Faq::create($FaqDetails);
//     }

//     public function updateFaq($FaqId, array $FaqDetails)
//     {
//         return Faq::whereId($FaqId)->update($FaqDetails);
//     }

//     public function deleteFaq($FaqId)
//     {
//         Faq::destroy($FaqId);
//     }

// }