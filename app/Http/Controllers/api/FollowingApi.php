<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResponseResource;
use App\Models\Cerbung;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserFollowing;
use Dentro\Yalr\Attributes\Get;
use Dentro\Yalr\Attributes\Post;
use Dentro\Yalr\Attributes\Prefix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

#[Prefix('user-following'),Name('.user-following')]
class FollowingApi extends Controller
{
    #[Post('/','.add-remove-following')]
    function addFollow(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'cerbung_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        if ($request->user_id == null) {
            return response()->json(new BaseResponseResource(false, 'User id is required', null), 422);
        }

        $user = User::query()->find($request->user_id);
        if ($user == null){
            return response()->json(new BaseResponseResource(false, 'User Not Found', null), 422);
        }

        $cerbung = Cerbung::query()->find($request->cerbung_id);
        if ($cerbung == null){
            return response()->json(new BaseResponseResource(false, 'Cerbung Not Found', null), 422);
        }

        $data = UserFollowing::all()->where('user_id','=',$user->id);
        if ($data->contains('cerbung_id','=',$cerbung->id)){
            $data = UserFollowing::query()->where('user_id','=',$user->id)->where('cerbung_id','=',$cerbung->id)->delete();
            return response()->json(new BaseResponseResource(false, "{$cerbung->title} has been removed from following", null));
        }

        $data = new UserFollowing();
        $data->user_id = $user->id;
        $data->cerbung_id = (int)$request->cerbung_id;
        $data->save();
        return response()->json(new BaseResponseResource(false, "{$cerbung->title} has been add to following", $data));
    }

    #[Get('{id}','.user-following')]
    function getUserFollowing(string $idUser){
        if ($idUser == null){
            return response()->json(new BaseResponseResource(false, 'forbidden data', null), 403);
        }

        $user = User::query()->find($idUser);
        if ($idUser == null){
            return response()->json(new BaseResponseResource(false, 'User id not found', null), 422);
        }

        $data = UserFollowing::query()->where('user_id','=',$idUser)->get()->loadMissing('userFollow');
        return response()->json(new BaseResponseResource(false, "{$user->name} following", $data));
    }
}
