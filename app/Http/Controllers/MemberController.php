<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;


class MemberController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();   
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'          => 'required|string',
            'alamat'        => 'required|string',
            'jenis_kelamin' => 'required|string',
            'no_telfon'     => 'required|string',
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }
        
        $member = new Member();
        $member->nama           = $request->nama;
        $member->alamat         = $request->alamat;
        $member->jenis_kelamin  = $request->jenis_kelamin;
        $member->no_telfon      = $request->no_telfon;
        
        $member->save();
        
        $data = Member::where('id_member', '=', $member->id_member)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data member berhasil ditambahkan',
            'data' => $data
        ]);
    }
    
    public function getAll()
    {
        $data['count'] = Member::count();  
        
        $data['member'] = Member::get();  

        return response()->json(['data' => $data]);
    }
    
    public function getById($id)
    {
        $data['member'] = Member::where('id_member', '=', $id)->get();

        return response()->json(['data' => $data]);
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'no_telfon' => 'required',
        ]);
    
        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $member = Member::where('id_member', '=', $id)->first();
        $member->nama          = $request -> nama;
        $member->alamat        = $request -> alamat;
        $member->jenis_kelamin = $request -> jenis_kelamin;
        $member->no_telfon     = $request -> no_telfon;
        
        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Data member berhasil diubah'
        ]);        
    }

    public function delete($id)
    {
        $delete = Member::where('id', '=', $id)->delete();

        if($delete) {
            return response()->json([
                'success' => true,
                'message' => 'Data member berhasil dihapus'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data member gagal dihapus'
            ]);            
        }
    }
}