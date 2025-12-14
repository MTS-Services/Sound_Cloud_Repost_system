<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\FaqCategory;
use App\Models\Faq as FaqModel;
use App\Services\SoundCloud\SoundCloudService;

class Faq extends Component
{
    public $faqCategories;
    public $selectedCategory = 'all';
    public $categoryCount;
    public $faqCount;

    protected SoundCloudService $soundCloudService;

    public function boot(SoundCloudService $soundCloudService)
    {
        $this->soundCloudService = $soundCloudService;
    }


    public function mount()
    {
        $this->loadFaqs();
    }

    public function updated()
    {
        $this->soundCloudService->refreshUserTokenIfNeeded(user());
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
        return view('livewire.user.faq');
    }
}
