<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseResponseResource;
use App\Models\Cerbung;
use App\Models\CerbungStory;
use App\Models\Dolanan;
use App\Models\Jadwal;
use App\Models\UserParty;
use Dentro\Yalr\Attributes\Get;
use Dentro\Yalr\Attributes\Name;
use Dentro\Yalr\Attributes\Post;
use Dentro\Yalr\Attributes\Prefix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

#[Prefix('cerbung'),Name('.cerbung')]
class CerbungApi extends Controller
{
    #[Get('/', '.all')]
    function getAll()
    {
        $data = Cerbung::all();
        return new BaseResponseResource(true, 'listOfCerbung', $data);
    }

    #[Post('/add', '.add')]
    function newCerbung(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'desc' => 'required',
            'thumbnail' => 'required',
            'genre' => 'required',
            'visibility' => 'required',
            'first_paragraph' => 'required',
            'users_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(new BaseResponseResource(false, $validator->errors()->first(), null), 422);
        }

        $cerbung = new Cerbung();
        $cerbung->title = $request->title;
        $cerbung->user_id = $request->users_id;
        $cerbung->desc = $request->desc;
        $cerbung->thumbnail = $request->thumbnail;
        $cerbung->genre = $request->genre;
        $cerbung->visibility = $request->visibility;
        $cerbung->save();

        $cerbungStory = new CerbungStory();
        $cerbungStory->cerbung_id = $cerbung->id;
        $cerbungStory->user_id = $request->users_id;
        $cerbungStory->desc = $request->first_paragraph;
        $cerbungStory->save();


        return response()->json(new BaseResponseResource(true, "Success Add Cerbung", $cerbung->loadMissing(['cerbungStory'])));
    }

    #[Get('{id}', '.cerbung-by-id')]
    public function getCerbungById(string $id)
    {
        if ($id == null) {
            return response()->json(new BaseResponseResource(false, 'Cerbung id is required', null), 422);
        }

        $cerbung = Cerbung::query()->find($id)->loadMissing(['cerbungStory']);
        if ($cerbung == null) {
            return response()->json(new BaseResponseResource(false, 'Cerbung not found', null), 404);
        }
        return response()->json(new BaseResponseResource(true, "Success", $cerbung));
    }
}
