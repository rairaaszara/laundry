<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Models\Paket;


class PaketController extends Controller
{
    public $user;
    public function __construct(){
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'harga' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
		}

        $paket = new Paket();
        $paket->jenis = $request -> jenis;
        $paket->harga = $request -> harga;
        $paket -> save();

        $data = Paket::where('id_paket', '=', $paket->id_paket)->first();
        return response()->json([
            'success' => true,
            'message' => 'Data paket berhasil ditambahkan',
            'data' => $data
        ]);
    }

    public function getAll()
    {
        $data['count'] = Paket::count();

        $data['paket'] = Paket::get();

        return response()->json(['data' => $data]);
    }

    public function getById($id)
    {
        $data['paket'] = Paket::where('id_paket', '=', $id)->first();
        
        return response()->json(['data' => $data]);
        
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required',
            'harga' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $paket = Paket::where('id_paket', '=', $id)->first();
        $paket->jenis = $request -> jenis;
        $paket->harga = $request -> harga;

        $paket->save();

        return response()->json([
            'success' => true,
            'message' => 'Data paket berhasil diubah'
        ]);
    }

    public function delete($id)
    {
        $delete = Paket::where('id_paket', '=', $id)->delete();

        if($delete) {
            return response()->json([
                'success' => true,
                'message' => "Data outlet berhasil dihapus"
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Data outlet gagal dihapus"
            ]);
        }
    }
}