<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Order;
use App\Models\User;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


// For presence channels (optional)
Broadcast::channel('notifications', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});
