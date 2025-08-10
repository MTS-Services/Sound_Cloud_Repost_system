<?php 

// namespace App\Http\Controllers\Backend\User\FaqManagement;

// use App\Http\Controllers\Controller;
// use App\Models\Faq;
// use Illuminate\Http\Request;
// use App\Models\FaqCategory;
// use App\Services\Admin\Faq\FaqService as FaqFaqService;
// use App\Services\FaqService;

// class FaqController extends Controller
// {

//    private $faqService;

//     public function __construct(FaqFaqService $faqService)
//     {
//         $this->faqService = $faqService;
//     }
//      public function index()
//     {
//         $data['faqCategories'] = FaqCategory::with('faqs')->withCount('faqs')->active()->orderBy('name', 'asc')->get();

//         return view('backend.user.faq-management.faq', $data);
//     }

