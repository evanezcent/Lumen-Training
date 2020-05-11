<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class BoardController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'show']);
    }

    public function index()
    {
        return DB::table('board')->get();
    }

    public function show($id)
    {
        $where = array(
            'id' => $id
        );
        return DB::table('board')
            ->where($where)
            ->get();
    }

    public function store(Request $request)
    {

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $new = array(
            'board_id' => "B-" . substr(str_shuffle($permitted_chars), 0, 7),
            'board_name' => $request->board_name,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        if (DB::table('board')->insert($new)) {
            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json(['message' => 'error'], 500);
        }
    }
}
