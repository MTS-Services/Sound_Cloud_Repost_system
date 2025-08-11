<?php

namespace App\Livewire\User\Notification;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\CustomNotification;
use App\Models\CustomNotificationStatus;
use Illuminate\Support\Facades\Auth;

class StatsCard extends Component
{
    public $title;
    public $value = 0;
    public $icon;
    public $color;
    public $description;
    public $type; // 'total', 'unread', 'read'
    public $showProgress = true;
    public $currentUserId;
    public $currentUserType;

    public function mount($title, $icon, $color, $description, $type, $showProgress = true)
    {
      
        $this->loadValue();
    }

    #[On('notifications-updated')]
    #[On('notification-updated')]
    #[On('notification-deleted')]
    public function refreshStats()
    {
        $this->loadValue();
    }

    public function loadValue()
    {
        $baseQuery = CustomNotification::where('receiver_id', $this->currentUserId)
            ->where('receiver_type', $this->currentUserType);

        switch ($this->type) {
            case 'total':
                $this->value = $baseQuery->count();
                break;
                
            case 'unread':
                $this->value = $baseQuery->whereDoesntHave('statuses', function($query) {
                    $query->where('user_id', $this->currentUserId)
                          ->where('user_type', $this->currentUserType)
                          ->whereNotNull('read_at');
                })->count();
                break;
                
            case 'read':
                $this->value = $baseQuery->whereHas('statuses', function($query) {
                    $query->where('user_id', $this->currentUserId)
                          ->where('user_type', $this->currentUserType)
                          ->whereNotNull('read_at');
                })->count();
                break;
                
            case 'today':
                $this->value = $baseQuery->whereDate('created_at', today())->count();
                break;
                
            case 'week':
                $this->value = $baseQuery->whereBetween('created_at', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ])->count();
                break;
                
            case 'month':
                $this->value = $baseQuery->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
                break;
        }
    }

    public function getProgressPercentage()
    {
        if (!$this->showProgress) return 0;
        
        $total = CustomNotification::where('receiver_id', $this->currentUserId)
            ->where('receiver_type', $this->currentUserType)
            ->count();
            
        if ($total === 0) return 0;
        
        return ($this->value / $total) * 100;
    }

    public function getColorClasses()
    {
        $colors = [
            'orange' => [
                'bg' => 'bg-gradient-to-br from-orange-500 to-amber-500',
                'text' => 'text-orange-600',
                'progress' => 'bg-gradient-to-r from-orange-500 to-amber-500',
                'glow' => 'shadow-orange-200 dark:shadow-orange-900/50'
            ],
            'red' => [
                'bg' => 'bg-gradient-to-br from-red-500 to-pink-500',
                'text' => 'text-red-500',
                'progress' => 'bg-gradient-to-r from-red-500 to-pink-500',
                'glow' => 'shadow-red-200 dark:shadow-red-900/50'
            ],
            'green' => [
                'bg' => 'bg-gradient-to-br from-green-500 to-emerald-500',
                'text' => 'text-green-500',
                'progress' => 'bg-gradient-to-r from-green-500 to-emerald-500',
                'glow' => 'shadow-green-200 dark:shadow-green-900/50'
            ],
            'blue' => [
                'bg' => 'bg-gradient-to-br from-blue-500 to-cyan-500',
                'text' => 'text-blue-500',
                'progress' => 'bg-gradient-to-r from-blue-500 to-cyan-500',
                'glow' => 'shadow-blue-200 dark:shadow-blue-900/50'
            ],
            'purple' => [
                'bg' => 'bg-gradient-to-br from-purple-500 to-pink-500',
                'text' => 'text-purple-500',
                'progress' => 'bg-gradient-to-r from-purple-500 to-pink-500',
                'glow' => 'shadow-purple-200 dark:shadow-purple-900/50'
            ]
        ];

        return $colors[$this->color] ?? $colors['orange'];
    }

    public function render()
    {
        return view('livewire.user.notification.stats-card');
    }
}