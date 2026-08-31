<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuctionResourceV1;
use App\Models\Barang;
use App\Models\Lelang;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $auctions = DB::table('tb_lelang as lelang')
            ->leftJoin('tb_barang as barang', 'lelang.id_barang', '=', 'barang.id_barang')
            ->where('lelang.status', '=', 'dibuka')
            ->select([
                'lelang.id_lelang',
                'lelang.tgl_mulai_lelang',
                'lelang.tgl_akhir_lelang',
                'lelang.status as status_lelang',
                'barang.id_barang',
                'barang.nama_barang',
                'barang.thumbnail as thumbnail_url',
                'barang.deskripsi_barang',
                'barang.harga_awal'
            ])
            ->get();

        return response()->json([
            'message' => 'Successfully retrieved auctions data',
            'data' => AuctionResourceV1::collection($auctions)
        ], 200);
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
    public function show(string $id): JsonResponse
    {
        if (Lelang::where('id_lelang', '=', $id)->exists()) {

            $auction = DB::table('tb_lelang as lelang')
                ->where('lelang.id_lelang', '=', $id)
                ->leftJoin('tb_barang as barang', 'barang.id_barang', '=', 'lelang.id_barang')
                ->select([
                    /** Barang */
                    'barang.id_barang as id_barang',
                    'barang.nama_barang',
                    'barang.deskripsi_barang',
                    'barang.thumbnail as thumbnail_url',
                    'barang.harga_awal',

                    /** Lelang */
                    'lelang.id_lelang as id_lelang',
                    'lelang.tgl_mulai_lelang',
                    'lelang.tgl_akhir_lelang',
                ])
                ->first();
            // dd($auction);

            return response()->json([
                'message' => 'Successfully retrieved auction data with ID ' . $id,
                'data' => new AuctionResourceV1($auction)
            ]);
        }

        return response()->json(['message' => 'Auction with ID ' . $id . ' not found'], 404);
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
