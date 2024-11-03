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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('CompanyUpdated.{companyName}.{phoneNumber}', function ($user, $companyName, $phoneNumber) {
    // You can add your logic to authorize the user here
    // For example, you might want to check if the user belongs to the company
    return true; // Allow access for now (adjust as necessary)
});