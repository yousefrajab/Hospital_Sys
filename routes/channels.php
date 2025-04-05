<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Doctor;
use App\Models\Patient;

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


Broadcast::channel('create-invoice.{doctor_id}', function ($user, $doctor_id) {
    return $user->id == $doctor_id;
},
['guards' => ['web', 'admin', 'patient', 'doctor', 'ray_employee', 'laboratorie_employee', 'api']]
);

Broadcast::channel('chat.{receiver_id}', function (Doctor $user, $receiver_id) {
     return $user->id == $receiver_id;
 },
     ['guards' => ['web', 'admin', 'patient', 'doctor', 'ray_employee', 'laboratorie_employee', 'api']]
 );


 Broadcast::channel('chat2.{receiver_id}', function (Patient $user, $receiver_id) {
    return $user->id == $receiver_id;
},
    ['guards' => ['web', 'admin', 'patient', 'doctor', 'ray_employee', 'laboratorie_employee', 'api']]
);

