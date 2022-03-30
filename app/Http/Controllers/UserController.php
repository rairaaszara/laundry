<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use \Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
//use JWTAuth;

class UserController extends Controller
{

	public $user;

    public function __construct()
  {
    $this->user = JWTAuth::parseToken()->authenticate();
    
  }
    public function store(Request $request)
  {
    $validator = Validator::make($request->all(),[
      'name'  => 'required|string',
      'username' => 'required|string',
      'password' => 'required|string',
      'role' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors());
    }

    $user = new User();
    $user->name 	= $request->name;
	$user->username = $request->username;
	$user->password = Hash::make($request->password);
	$user->role 	= $request->role;

    $user->save();
    $data = User::where('id', '=', $user->id)->first();

    return Response()->json([
      'success' => true,
      'message' => 'Data user berhasil ditambahkan',
      'data' => $data
    ]);
  }

  public function getAll()
  {

      $data= User::get();

      return response()->json($data);
  }

  public function getById($id)
  {
      $data['users'] = User::where('id_users', '=', $id)->get();

      return response()->json(['data' => $data]);
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(),[
		'name' => 'required|string',
		'username' => 'required|string',
		'password' => 'required|string',
		'role' => 'required|string',
    ]);

    if ($validator->fails()) {
      return response()->json($validator->errors());
    }

    $user = User::where('id_users', '=', $id)->first();
	$user->name 	= $request->name;
	$user->username = $request->username;
	$user->password = Hash::make($request->password);
	$user->role 	= $request->role;

    $user->save();

    return Response()->json(['message' => 'Data user berhasil diubah',]);
  }

  public function delete($id)
  {
      $delete = User::where('id', '=', $id)->delete();

      if ($delete) {
          return response()->json(['message' => 'Data user berhasil dihapus']);
      }else {
          return response()->json(['message' => 'Data user gagal dihapus']);
      }
  }
}