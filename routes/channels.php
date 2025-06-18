<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// Admin notifications channel (for admin and cao users)
Broadcast::channel('admin-notifications', function ($user) {
    return in_array($user->role, ['admin', 'cao']);
});

// User-specific channel
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
