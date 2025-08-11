<?php

namespace App\Livewire\User\Notification;

use Livewire\Component;

class NotificationFilter extends Component
{
    public $filter = 'all';
    public $search = '';
    public $sort = 'newest';
    public $totalCount = 0;
    public $unreadCount = 0;
    public $readCount = 0;

    public function mount($filter = 'all', $search = '', $sort = 'newest', $totalCount = 0, $unreadCount = 0, $readCount = 0)
    {
        $this->filter = $filter;
        $this->search = $search;
        $this->sort = $sort;
        $this->totalCount = $totalCount;
        $this->unreadCount = $unreadCount;
        $this->readCount = $readCount;
    }

    public function updatedFilter()
    {
        $this->dispatch('filter-changed', $this->filter);
    }

    public function updatedSearch()
    {
        $this->dispatch('search-changed', $this->search);
    }

    public function updatedSort()
    {
        $this->dispatch('sort-changed', $this->sort);
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->dispatch('search-changed', $this->search);
    }

    public function resetFilters()
    {
        $this->filter = 'all';
        $this->search = '';
        $this->sort = 'newest';
        
        $this->dispatch('filter-changed', $this->filter);
        $this->dispatch('search-changed', $this->search);
        $this->dispatch('sort-changed', $this->sort);
    }

    public function render()
    {
        return view('livewire.user.notification.notification-filter');
    }
}