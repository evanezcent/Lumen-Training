<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Auth\Authorizable;

class Card extends Model{
    // use Authenticatable, Authorizable;

    protected $fillable = ['data', 'card_id', 'listd_id'];

}