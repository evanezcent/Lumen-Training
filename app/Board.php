<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;

class Board extends Model{
    // use Authenticatable, Authorizable;

    protected $fillable = [];

    public function findData($table, $where)
    {
        return DB::table($table)
            ->where($where)
            ->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}