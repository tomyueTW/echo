<?php

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

Broadcast::channel('room.{room}', function ($user, \App\Models\Room $room) {
    $allow = $room->participants->contains($user);
    if ($allow) {
        return ['user' => $user->toArray()];
    }
});
