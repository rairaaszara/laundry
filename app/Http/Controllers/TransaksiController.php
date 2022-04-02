<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use \Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Carbon\Carbon;


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
            return response()->json($validator->errors());
        }

        $transaksi = new Transaksi();
        $transaksi -> id_member = $request->id_member;
        $transaksi -> tanggal = Carbon::now();
        $transaksi -> batas_waktu = Carbon::now()->addDays(3);
        $transaksi -> tanggal_bayar = Carbon::now();
        $transaksi -> status = 'baru';
        $transaksi -> dibayar = 'belum_dibayar';
        $transaksi -> id = $this->user->id;

        $transaksi->save();

        $data = Transaksi::where('id_transaksi', '=', $transaksi->id_transaksi)->first();

        return response()->json([
            'success' => true,
            'message' => 'Data transaksi berhasil ditambahkan', 
            'data' => $data
        ]);
    }

    public function getAll()
    {
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')
        ->join('users', 'transaksi.id', '=', 'users.id')
                    ->select('transaksi.*', 'member.nama','users.name')
                    ->get();
                    
        return response()->json($data);
    }
    
    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_member' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $transaksi = Transaksi::where('id_transaksi', $id)->first();
        
        $transaksi->id_member = $request->id_member;

        $transaksi->save();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diubah'
        ]);
    }

    public function getById($id)
    {
        $data = Transaksi::where('id_transaksi', '=', $id)->first();  
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member') 
                                    ->join('users', 'transaksi.id', '=', 'users.id')     
                                      ->select('transaksi.*', 'member.nama','users.name')
                                      ->where('transaksi.id_transaksi', '=', $id)
                                      ->first();
        return response()->json($data);
    }

    public function changeStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);
        
        if($validator->fails()) {
            return response()->json($validator->errors());
        }
        
        $transaksi = Transaksi::where('id_transaksi', '=', $id)->first();
        $transaksi->status = $request->status;
        
        $transaksi->save();
        
        return response()->json(['message' => 'Status berhasil diubah']);
    }
    
    public function bayar($id)
    {
        $transaksi = Transaksi::where('id_transaksi', '=', $id)->first();
        $total = DetailTransaksi::where('id_detail_transaksi', $id)->sum('subtotal');

        $transaksi->tanggal = Carbon::now();
        $transaksi->status = "Diambil";
        $transaksi->dibayar = "dibayar";
        $transaksi->total_bayar = $total;        
        
        $transaksi->save();
        
        return response()->json(['message' => 'Pembayaran berhasil']);
    }

    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'bulan' => 'required'
        ]);
        
        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $tahun = $request->tahun;
        $bulan = $request->bulan;
        
        $data = DB::table('transaksi')->join('member', 'transaksi.id_member', '=', 'member.id_member')
                    ->join('users', 'transaksi.id', '=', 'users.id')
                    ->select('transaksi.id','transaksi.tanggal','transaksi.tanggal_bayar','transaksi.total_bayar', 'member.nama','users.name')
                    ->whereYear('tanggal', '=' , $tahun)
                    ->whereMonth('tanggal', '=', $bulan)
                    ->get();

        return response()->json($data);
    }

}