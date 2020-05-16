<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class BoardController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user =  Auth::user()->username;
        $data = DB::table('board')
            ->where('username', $user)
            ->get();
        return response()->json(['message' => 'success', 'data' => $data], 200);
    }

    public function show($id)
    {
        $Board = new Board();
        $where = array(
            'id' => $id
        );
        $board = $Board->findData('table', $where);
        if ($board->username == Auth::user()->username) {
            $data =  DB::table('board')
                ->where($where)
                ->get();
            return response()->json(['message' => 'success', 'data' => $data], 200);
        } else {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    public function store(Request $request)
    {

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $new = array(
            'board_id' => "B-" . substr(str_shuffle($permitted_chars), 0, 7),
            'board_name' => $request->board_name,
            'username' => Auth::user()->username,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        if (DB::table('board')->insert($new)) {
            return response()->json(['message' => 'success'], 200);
        } else {
            return response()->json(['message' => 'error'], 500);
        }
    }

    public function update(Request $req, $boardID)
    {
        $Board = new Board();
        $where = array(
            'id' => $boardID,
        );
        $board = $Board->findData('board',$where);
        if ($board->username == Auth::user()->username) {
            $new = array(
                'board_name' => $req->board_name
            );
            $where2 = array(
                'id' => $boardID,
                'username' => Auth::user()->username
            );
            DB::table('board')->where($where2)->update($new);
            $board = $Board->findData('table',$where);
            return response()->json(['message' => 'success', 'board' => $board], 200);
        } else {
            return response()->json(['message' => 'Forbidden access'], 403);
        }
    }

    public function delete($id)
    {
        $Board = new Board();
        $where = array(
            'id' => $id,
        );
        $board = $Board->findData('table',$where);
        if ($board->username != Auth::user()->username) {
            return response()->json(['message' => 'Forbidden access !'], 403);
        }else{
            DB::table('board')->where($where)->delete();
            return response()->json(['message' => 'Board deleted'], 200);
        }
    }
}
