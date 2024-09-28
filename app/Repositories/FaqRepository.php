<?php
namespace App\Repositories;

use App\Interfaces\FaqRepositoryInterface;
use App\Models\Faq;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

class FaqRepository
{
    public function getAllFaqs()
    {
        return Faq::all();
    }

    public function createFaq(array $FaqDetails)
    {
        return Faq::create($FaqDetails);
    }

    public function updateFaq($FaqId, array $FaqDetails)
    {
        return Faq::whereId($FaqId)->update($FaqDetails);
    }

    public function deleteFaq($FaqId)
    {
        Faq::destroy($FaqId);
    }

}