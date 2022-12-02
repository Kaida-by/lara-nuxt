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

Broadcast::channel('articles', function ($user) {
    return 'cccchhhaaannnneeellll';
});

Broadcast::channel('private-user.{user_id}', function ($user, $user_id) {
    return true;
//    return $user->id === \App\Models\Article::find($articleId)->author_id;
//    return $user->name;
//    return (int) $user->id === (int) $id;
});
