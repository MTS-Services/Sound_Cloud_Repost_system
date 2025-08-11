<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Order;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::routes();


// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });


// For presence channels (optional)
// Broadcast::channel('notifications', function ($user) {
//     return ['id' => $user->id, 'name' => $user->name];
// });


// Broadcast::channel('users', function ($user) {
//     return ['id' => $user->id, 'name' => $user->name];
// });

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
}, ['guards' => ['web']]);
