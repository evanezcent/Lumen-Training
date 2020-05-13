<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;

class List_board extends Model{
    // use Authenticatable, Authorizable;

    protected $fillable = ['data', 'board_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}