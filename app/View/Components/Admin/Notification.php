<?php

namespace App\View\Components\Admin;

use App\Models\CustomNotification;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Notification extends Component
{
    public $notifications;
    public function __construct()
    {
        $this->notifications = CustomNotification::with(['statuses' => function ($query) {
            $query->where('user_id', admin()->id)
                ->where('user_type', get_class(admin()));
        }])
            ->where(function ($query) {
                // Condition one for private messages
                $query->where('receiver_id', admin()->id)
                    ->where('receiver_type', get_class(admin()));
            })
            ->orWhere(function ($query) {
                // Condition two for public messages
                $query->where('receiver_id', null)
                    ->where('type', CustomNotification::TYPE_ADMIN);
            })
            ->latest()
            // ->take(15)
            ->get();
    }

    public function render(): View|Closure|string
    {
        return view('backend.admin.layouts.partials.notification', [
            'notifications' => $this->notifications,
        ]);
    }
}
