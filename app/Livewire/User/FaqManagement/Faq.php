<?php

namespace App\Livewire\User\FaqManagement;

use Livewire\Component;
use App\Models\FaqCategory;
use App\Models\Faq as FaqModel;

class Faq extends Component
{
    public $faqCategories;
    public $selectedCategory = 'all';
    public $categoryCount;
    public $faqCount;

    public function mount()
    {
        $this->loadFaqs();
    }

    public function loadFaqs()
    {
        $query = FaqCategory::with('faqs')
            ->withCount('faqs')
            ->active()
            ->orderBy('name', 'asc');



        $this->faqCategories = $query->get();

        $this->categoryCount = FaqCategory::with('faqs')
            ->active()
            ->whereHas('faqs')
            ->count();
        $this->faqCount = FaqModel::active()->count();
    }

    public function selectCategory($slug)
    {
        $this->selectedCategory = $slug;
    }

    public function render()
    {
        return view('livewire.user.faq-management.faq');
    }
}
