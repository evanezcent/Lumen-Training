<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class CardController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getListCards($boardID, $listID)
    {
        $Board = new Board();
        $user =  Auth::user()->username;
        $board = $Board->findData('board', ['board_id' => $boardID]);

        if ($user == $board->username) {
            $data = DB::table('card')
                ->join('list_board', 'list_board.list_id', '=', 'card.list_id')
                ->where('list_id', $listID)
                ->get();
            if ($data) {
                return response()->json(['status' => 'success', 'data' => $data], 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => "Not found"], 404);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => "Unauthorized"], 401);
        }
    }

    public function getCard($boardID, $listID, $cardID)
    {
        $Board = new Board();
        $user =  Auth::user()->username;
        $board = $Board->findData('board', ['board_id' => $boardID]);
        if ($user == $board->username) {
            $where = array(
                'card_ID' => $cardID,
                'list_id' => $listID
            );
            $data = DB::table('card')
                ->where($where)
                ->get();
            if ($data) {
                return response()->json(['status' => 'success', 'data' => $data], 200);
            } else {
                return response()->json(['status' => 'failed', 'message' => "Not Found"], 404);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => "Unauthorized"], 401);
        }
    }

    public function addCard(Request $req, $listID, $boardID)
    {
        $this->validate($req, ['name' => 'required']);
        $Board = new Board();
        $user =  Auth::user()->username;
        $board = $Board->findData('board', ['board_id' => $boardID]);

        if ($user != $board->username) {
            return response()->json(['status' => 'failed', 'message' => "Unauthorized"], 401);
        }

        $list = $Board->findData('list_board', ['list_id' => $listID]);
        if ($list) {
            $text = "";
            if ($req->data) {
                $text = $req->data;
            }
            $new = array(
                'card_id' => "C-" . uniqid(),
                'list_id' => $listID,
                'nama' => $req->nama,
                'data' => $text,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            DB::table('card')->insert($new);
            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'failed', 'message' => "Not Found"], 404);
        }
    }

    // Update a list in a bord
    public function updateCard(Request $req, $boardID, $listID, $cardID)
    {
        $this->validate($req, ['name' => 'required']);
        $Board = new Board();
        $user =  Auth::user()->username;
        $board = $Board->findData('board', ['board_id' => $boardID]);

        if ($user != $board->username) {
            return response()->json(['status' => 'failed', 'message' => "Unauthorized"], 401);
        }
        $list = $Board->findData('list_board', ['list_id' => $listID]);
        if ($list) {
            $text = "";
            if ($req->data) {
                $text = $req->data;
            }
            $new = array(
                'list_id' => $req->list_id,
                'name' => $req->name,
                'data' => $text,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            DB::table('card')->where(['card_id' => $cardID])->update($new);

            $data = DB::table('card')
                ->join('board', 'board.board_id', '=', 'list_board.board_id')
                ->join('list_board', 'list_board.list_id', '=', 'card.list_id')
                ->where('list_board.board_id', $boardID)
                ->get();
            return response()->json(['status' => 'success', 'data' => $data], 200);
        }
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

    public function deleteCard($boardID, $listID, $cardID)
    {
        $Board = new Board();
        $user =  Auth::user()->username;
        $board = $Board->findData('board', ['board_id' => $boardID]);

        if ($user != $board->username) {
            return response()->json(['status' => 'failed', 'message' => "Unauthorized"], 401);
        }
        DB::table('list_board')->where(['list_id' => $listID, 'card_id' => $cardID])->delete();

        $data = DB::table('card')
            ->join('board', 'board.board_id', '=', 'list_board.board_id')
            ->join('list_board', 'list_board.list_id', '=', 'card.list_id')
            ->where('list_board.board_id', $boardID)
            ->get();
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
}
