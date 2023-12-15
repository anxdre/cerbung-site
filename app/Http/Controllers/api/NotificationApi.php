<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResponseResource;
use App\Models\Cerbung;
use App\Models\Notification;
use App\Models\User;
use Dentro\Yalr\Attributes\Get;
use Dentro\Yalr\Attributes\Prefix;
use Faker\Guesser\Name;
use Illuminate\Http\Request;

#[Prefix('notification'),Name('notification')]
class NotificationApi extends Controller
{
    #[Get('{id}','notification-by-id-user')]
    function getByIdUser(string $id)
    {
        if ($id == null) {
            return response()->json(new BaseResponseResource(false, 'User id is required', null), 422);
        }
        $user = User::query()->find($id);

        if ($user == null){
            return response()->json(new BaseResponseResource(false, 'User Not Found', null), 422);
        }

        $data = Notification::all()->where('user_id','=',$id);
        return response()->json(new BaseResponseResource(false, "user {$user->name} notification", $data));
    }
}
