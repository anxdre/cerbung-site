<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CerbungStory extends Model
{
    use HasFactory;

    protected $table = 'cerbung_story';

    function user(){
        return $this->hasOne(User::class,'id');
    }
}
