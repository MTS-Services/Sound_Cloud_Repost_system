<?php

namespace App\Livewire\User\FaqManagement;

use Livewire\Component;
use App\Models\FaqCategory;

class Faq extends Component
{
    public $faqCategories;
    public $selectedCategory = 'all';

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
