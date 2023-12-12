<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cerbung extends Model
{
    use HasFactory;

    protected $table = "cerbungs";

    function user(){
        return $this->hasOne(User::class,'id');
    }

    function cerbungStory(){
        return $this->hasMany(CerbungStory::class,'cerbung_id');
    }
}
