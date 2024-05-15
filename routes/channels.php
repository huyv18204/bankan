<?php

use App\Models\Group_user;
use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});
Broadcast::channel('createTask', function ($user) {
    return $user != null;
});
Broadcast::channel('updateTask', function ($user) {
    return $user != null;
});
Broadcast::channel('deleteTask', function ($user) {
    return $user != null;
});
Broadcast::channel('deleteMember.{member}', function ($user,$member) {
    return (int)$user->id == (int)$member;
});
Broadcast::channel('updateStatus', function ($user) {
    return $user != null;
});

Broadcast::channel('displayGroup.{user_joined}', function ($user, $user_joined) {
    return (int)$user->id == (int)$user_joined;

});

Broadcast::channel('joinBoard', function ($user) {
    if ($user != null) {
        return ['id' => $user->id, 'name' => $user->name, 'image' => $user->image];
    }
    return false;
});
