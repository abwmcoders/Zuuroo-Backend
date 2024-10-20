<?php

namespace App\Http\Controllers;

use App\Repositories\FaqRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    use ApiResponseTrait;
    private $faqRepository;

    public function __construct(FaqRepository $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    // Get all FAQs
    public function index()
    {
        $faqs = $this->faqRepository->getAllFaqs();
        return $this->successResponse(data: $faqs);
    }

    // Get a specific FAQ by ID
    public function show($id)
    {
        $faq = $this->faqRepository->findFaqById($id);

        if (!$faq) {
            return response()->json(['message' => 'FAQ not found'], 404);
        }

        return response()->json($faq, 200);
    }

    // Create a new FAQ
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|max:255',
            'answer' => 'required'
        ]);

        $faqData = $request->only(['question', 'answer']);
        $faq = $this->faqRepository->createFaq($faqData);

        return response()->json($faq, 201);
    }

    // Update an existing FAQ
    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'sometimes|required|max:255',
            'answer' => 'sometimes|required'
        ]);

        $faqData = $request->only(['question', 'answer']);
        $faq = $this->faqRepository->updateFaq($id, $faqData);

        if (!$faq) {
            return response()->json(['message' => 'FAQ not found'], 404);
        }

        return response()->json($faq, 200);
    }

    // Delete a FAQ
    public function destroy($id)
    {
        $deleted = $this->faqRepository->deleteFaq($id);

        if (!$deleted) {
            return response()->json(['message' => 'FAQ not found'], 404);
        }

        return response()->json(['message' => 'FAQ deleted successfully'], 200);
    }
}
