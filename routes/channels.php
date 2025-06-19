<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// CAO notifications channel (only for cao users)
Broadcast::channel('cao-notifications', function ($user) {
    return $user->role === 'cao';
});

// Admin notifications channel (only for admin users)
Broadcast::channel('admin-notifications', function ($user) {
    return $user->role === 'admin';
});

// User-specific channel
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
