<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class ListController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getBoardsList($boardID)
    {
        $Board = new Board();
        $user =  Auth::user()->username;
        $board = $Board->findData('board', ['id' => $boardID]);

        if ($user == $board->username) {
            $data = DB::table('list_board')
                ->join('board', 'list_board.board_id', '=', 'board.id')
                ->where('board.id', $boardID)
                ->get();
            return response()->json(['status' => 'success', 'data' => $data], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => "Unauthorized"], 401);
        }
    }

    public function getList($boardID, $listID)
    {
        $Board = new Board();
        $user =  Auth::user()->username;
        $board = $Board->findData('board', ['id' => $boardID]);
        if ($user == $board->username) {
            $where = array(
                'board_id' => $boardID,
                'id' => $listID
            );
            $data = DB::table('list_board')
                ->where($where)
                ->get();
            return response()->json(['status' => 'success', 'data' => $data], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => "Unauthorized"], 401);
        }
    }

    public function addList(Request $req, $boardID)
    {
        $this->validate($req, ['data' => 'required']);
        $Board = new Board();
        $user =  Auth::user()->username;
        $board = $Board->findData('board', ['id' => $boardID]);
    
        if ($user != $board->username) {
            return response()->json(['status' => 'failed', 'message' => "Unauthorized"], 401);
        }
        $new = array(
            'list_id' => "L-" . uniqid(),
            'board_id' => $boardID,
            'data' => $req->data,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        DB::table('list_board')->insert($new);
        return response()->json(['status' => 'success'], 200);
    }

    // Update a list in a bord
    public function updateList(Request $req, $boardID, $listID)
    {
        $this->validate($req, ['data' => 'required']);
        $Board = new Board();
        $user =  Auth::user()->username;
        $board = $Board->findData('board', ['id' => $boardID]);

        if ($user != $board->username) {
            return response()->json(['status' => 'failed', 'message' => "Unauthorized"], 401);
        }
        $new = array(
            'data' => $req->data,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        DB::table('list_board')->where(['id' => $listID])->update($new);

        // $data = DB::table('list_board')
        //     ->where('board_id', $boardID)
        //     ->get();
        return response()->json(['status' => 'success'], 200);
    }

    // Maybe it will used to update list position in board
    public function updateBoardList(Request $req, $boardID, $listID)
    {
        $this->validate($req, ['data' => 'required']);
        $Board = new Board();
        $user =  Auth::user()->username;
        $board = $Board->findData('board', ['board_id' => $boardID]);

        if ($user != $board->username) {
            return response()->json(['status' => 'failed', 'message' => "Unauthorized"], 401);
        }
        $new = array(
            'board_id' => $boardID,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        DB::table('list_board')->where(['list_id' => $listID])->update($new);

        $data = DB::table('board')
            ->where('board_id', $boardID)
            ->get();
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function deleteList($boardID, $listID)
    {
        $Board = new Board();
        $user =  Auth::user()->username;
        $board = $Board->findData('board', ['id' => $boardID]);

        if ($user != $board->username) {
            return response()->json(['status' => 'failed', 'message' => "Unauthorized"], 401);
        }
        DB::table('list_board')->where(['id' => $listID])->delete();

        // $data = DB::table('board')
        //     ->where('board_id', $boardID)
        //     ->get();
        return response()->json(['status' => 'success'], 200);
    }
}
