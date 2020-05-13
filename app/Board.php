<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;

class Board extends Model{
    // use Authenticatable, Authorizable;

    protected $fillable = [];

    public function findBoard($where)
    {
        return DB::table('board')
            ->where($where)
            ->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}