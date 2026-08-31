<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Lelang;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $auction = Lelang::where('status', '=', 'dibuka')->select(['id_lelang', 'tgl_mulai_lelang', 'tgl_akhir_lelang', 'status', 'id_barang'])->withCount('history_lelangs')->withMax('history_lelangs', 'penawaran_harga')->with('barang:id_barang,nama_barang')->paginate(5);

        return response()->json(['message' => 'Successfully retrieved live auctions data', 'data' => $auction], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id_lelang): JsonResponse
    {
        $count_bid_sub = DB::table('history_lelang as h_sub')->selectRaw('count(*)')->whereColumn('h_sub.id_lelang', '=', 'lelang.id_lelang');
        $higher_bidder = DB::table('history_lelang as history')
            ->select(['user.nama_lengkap', 'user.username', 'user.telp', 'history.penawaran_harga'])
            ->leftJoin('tb_masyarakat as user', 'user.id_user',  '=', 'history.id_user')
            ->where('history.id_lelang', '=', $id_lelang)
            ->orderByDesc('history.penawaran_harga')
            ->first();
        $log_bids = DB::table('history_lelang as history')
            ->select(
                'user.id_user',
                'user.username',
                'history.penawaran_harga',
                'history.created_at',
            )
            ->leftJoin('tb_masyarakat as user', 'history.id_user', '=', 'user.id_user')->orderBy('history.penawaran_harga', 'desc')
            ->where('history.id_lelang', '=', $id_lelang)
            ->limit(10)
            ->get();

        $auction = DB::table('tb_lelang as lelang')->where('lelang.id_lelang', '=', $id_lelang)
            ->leftJoin('tb_barang as barang', 'lelang.id_barang', '=', 'barang.id_barang')
            ->leftJoin('tb_petugas as petugas',  'lelang.id_petugas', '=', 'petugas.id_petugas')
            ->select([
                'lelang.id_lelang',

                'barang.id_barang',
                'barang.nama_barang',
                'barang.thumbnail as thumbnail_url',
                'barang.harga_awal',

                'petugas.id_petugas',
                'petugas.username as username_petugas',
            ])
            ->selectSub($count_bid_sub, 'count_bid')
            ->first();

        $dto = [
            'id_lelang' => $auction->id_lelang,
            'asset' => [
                'id' => $auction->id_barang,
                'nama' => $auction->nama_barang,
                'thumbnail_url' => asset('storage/' . $auction->thumbnail_url),
                'harga_awal' => $auction->harga_awal
            ],
            'detail_highest_bidder' => [
                'nama_lengkap' => $higher_bidder->nama_lengkap ?? null,
                'telp' => $higher_bidder->telp ?? null,
            ],
            'current_bidder' => [
                'higher_price' => $higher_bidder->penawaran_harga ?? 0,
                'by' => $higher_bidder->username ?? null,
                'count_bidder' => $auction->count_bid
            ],
            'log_bids' => $log_bids
        ];

        if ($auction) {
            return response()->json(['message' => 'Auction with ID ' . $id_lelang . ' successfully retrieved', 'data' => $dto], 200);
        } else {

            return response()->json(['message' => 'Auction with ID ' . $id_lelang . ' not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
