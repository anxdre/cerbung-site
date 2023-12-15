<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFollowing extends Model
{
    use HasFactory;

    protected $table = 'user_following';

    function userFollow(){
        return $this->hasMany(Cerbung::class,'id','cerbung_id');
    }
}
