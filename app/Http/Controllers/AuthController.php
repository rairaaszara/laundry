<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        try {
            if(! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token', 500]);
        }

        $user = JWTAuth::user();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => $user
        ]);

        $data = [
            'token' => $token,
            'user' => JWTAuth::user()
        ];
        

        return response()->json([
            'message' => 'Login Sukses',
            // 'data' => $data
        ]);
    }

    public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			
			'name' => 'required',
			'username' => 'required',
			'password' => 'required|string|min:6',
			'role' => 'required',
			 
		]);

		if($validator->fails()){
            return response()->json($validator->errors());
		}

		$user = new User();
		$user->name 	= $request->name;
		$user->username = $request->username;
		$user->password = Hash::make($request->password);
		$user->role 	= $request->role;

		$user->save();

		$token = JWTAuth::fromUser($user);

        $data = User::where('username','=', $request->username)->first();
        return response()->json([
			'message' => 'Berhasil menambah user',
			'data' => $data
		]);
	}

	//public function getAll()
	//{
		//$data = DB::table('users')->join('outlet', 'users.id_outlet', '=', 'outlet.id')
					//			  ->select('users.*', 'outlet.nama')
					//			  ->get();
		
		//return response()->json($data);
	//}

	public function getById($id)
	{
		$user = User::where('id', '=', $id)->first();

		return response()->json($user);
	}

	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
			'role' => 'required',
			'name' => 'required',
			'id_outlet' => 'required'
		]);
		
		$user = User::where('id', '=', $id)->first();
		
		$user->name = $request->name;
		$user->username = $request->username;
		$user->role = $request->role;
		$user->id_outlet = $request->id_outlet;
		if($request->password != null) {
			$user->password = Hash::make($request->password);
		}

		$user->save();

		return response()->json([
			'success' => true,
			'message' => 'Data user berhasil diubah'
		]);

	}

	public function delete($id)
	{
		$user = User::where('id', '=', $id)->delete();

		if($user) {
			return response()->json([
				'success' => true,
				'message' => 'Data user berhasil dihapus'
			]);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Data user gagal dihapus'
			]);
		}
	}


    public function loginCheck()
    {
        try {
            if(! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['message' => 'Invalid Token']);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'Token Expired']);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'Invalid Token']);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'Token absent']);
        }

        return response()->json(['message' => 'Authentication Success!']);
    }

   
    public function logout(Request $request)
    {
        if(JWTAuth::invalidate(JWTAuth::getToken())) {
            return response()->json(['message' => 'Anda sudah logout']);
        } else {
            return response()->json(['message' => 'Gagal logout']);
        }
    }

}