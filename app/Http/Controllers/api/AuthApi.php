<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResponseResource;
use App\Models\User;
use Dentro\Yalr\Attributes\Post;
use Dentro\Yalr\Attributes\Prefix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

#[Prefix('auth'), Name('.auth')]
class AuthApi extends Controller
{
    #[Post('register', '.register')]
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->photoUrl = $request->photo_url;
        $user->password = $request->password;
        $user->save();
        return response()->json($user);
    }

    #[Post('update', '.update')]
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(new BaseResponseResource(false, $validator->errors()->first(), null), 422);
        }

        $user = User::query()->find($request->id);
        if ($user == null){
            return response()->json(new BaseResponseResource(false, 'Jadwal not found', null), 404);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->photoUrl = $request->photo_url;
        $user->password = $request->password;
        $user->save();
        return response()->json($user);
    }

    #[Post('login', '.login')]
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(new BaseResponseResource(false, $validator->errors()->first(), null), 422);
        }
        $credentials = $request->only('email', 'password');
        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password Anda salah'
            ], 401);
        }
        return response()->json([
            'success' => true,
            'user' => auth()->user(),
            'token' => 'dummytoken'
        ], 200);
    }
}
