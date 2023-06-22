<?php

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

Broadcast::channel('user-{id}', function ($user, $id) {
    return (string)$user->id = (string)$id ;
});

Broadcast::channel('data-{action}-{key}-{com}', function ($user,$action,$key,$com) {
        if((string)$user->company_default = (string)$com){
          return ['id' => $user->id ];
        }
});

Broadcast::channel('chat-room-{com}', function ($user,$com) {
        if((string)$user->company_default = (string)$com){
          return ['id' => $user->id , 'name' => $user->username];
        }
});

Broadcast::channel('chat-user', function ($user) {
    return Auth::check();
});
