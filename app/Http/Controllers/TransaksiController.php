<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaksi;
use Carbon\Carbon;
use JWTAuth;

class TransaksiController extends Controller
{
    
    public $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_member' => 'required',
        ]);

        if($validator->fails()) {
            return $this->response->errorResponse($validator->errors());
        }

        $transaksi = new Transaksi();
        $transaksi->id_member = $request->id_member;
        $transaksi->tanggal = Carbon::now();
        $transaksi->batas_waktu = Carbon::now()->addDays(3);
        $transaksi->status = 'baru';
        $transaksi->dibayar = 'belum dibayar';
        $transaksi->id_user = $this->user->id;

        $transaksi->save;

        $data = Transaksi::where('id', '=', $transaksi->id)->first();

        return response()->json(['message' => 'data transaksi berhasil ditambahkan', 'data' => $data]);
    }
}
