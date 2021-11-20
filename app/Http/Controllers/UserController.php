<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use JWTAuth;
use Tymon\JWTAuth\Exception\JWTException;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'username' => 'required',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = new User();
        $user->nama = $request->nama;
        $user->username = $request->username;
        $user->password =Hash::make($request->password);
        $user->role = $request->role;

        $user->save();

        return response()->json(['message' => 'Berhasil menambah data user']);
    }

}

