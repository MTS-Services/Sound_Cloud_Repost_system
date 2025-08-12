<?php

namespace App\Livewire\User\Notification;

use Livewire\Component;

class NotificationFilter extends Component
{
    public $filter = 'all';
    public $totalCount = 0;
    public $unreadCount = 0;
    public $readCount = 0;

    public function mount($filter = 'all', $totalCount = 0, $unreadCount = 0, $readCount = 0)
    {
        $this->filter = $filter;
        $this->totalCount = $totalCount;
        $this->unreadCount = $unreadCount;
        $this->readCount = $readCount;
    }

    public function updatedFilter()
    {
        $this->dispatch('filter-changed', $this->filter);
    }


    public function resetFilters()
    {
        $this->filter = 'all';
        
        $this->dispatch('filter-changed', $this->filter);
    }

    public function render()
    {
        return view('livewire.user.notification.notification-filter');
    }
}