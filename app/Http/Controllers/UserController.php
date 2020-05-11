<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{

    public function index()
    {
        return DB::table('user')->get();
    }

    public function user($id)
    {
        $where = array(
            'id' => $id
        );
        return DB::table('user')
            ->where($where)
            ->get();
    }

    public function register(Request $request)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $data = DB::table('user')->where("username", $request->username)->first();

        if ($data) {
            return response()->json(['message' => 'Username is used'], 500);
        } else {
            if ($request->password == $request->password2) {
                $new = array(
                    'id' => uniqid() . substr(str_shuffle($permitted_chars), 0, 37),
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => md5($request->password),
                    'api_token' => substr(str_shuffle($permitted_chars), 0, 50),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                if (DB::table('user')->insert($new)) {
                    return response()->json(['message' => 'success'], 200);
                } else {
                    return response()->json(['message' => 'error'], 500);
                }
            } else {
                return response()->json(['message' => 'notmatch'], 401);
            }
        }
    }

    public function login(Request $request)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $where = array(
            'username' => $request->username,
            'password' => md5($request->password)
        );

        $data = DB::table('user')->where($where)->first();
        if ($data) {
            $new = array(
                'api_token' => substr(str_shuffle($permitted_chars), 0, 50)
            );
            DB::table('user')->where('username', $request->username)->update($new);

            $data = DB::table('user')->where($where)->first();
            return response()->json([
                'status' => 'success',
                'user' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "Invalid credentials"
            ], 401);
        }
    }

    public function logout(Request $req)
    {
        $api_token = $req->api_token;
        $data = DB::table('user')->where('api_token', $api_token)->first();
        if ($data) {
            // $req->session()->flush();
            $new = array(
                'api_token' => ''
            );
            DB::table('user')->where('api_token', $api_token)->update($new);
            return response()->json([
                'status' => 'success',
                'message' => "Logged out"
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "Not Logged in"
            ], 401);
        }
    }
}
