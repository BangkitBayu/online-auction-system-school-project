<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuctionEndingSoonResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $logged_user = $request->user();

        // Cek apakah user memiliki level, jika tidak maka ini adalah user biasa
        if ($logged_user->id_level === null) {
            $id_user = $logged_user->id_user;

            $joined_auction_count = DB::table('history_lelang as history')
                ->where('history.id_user', '=', $id_user)
                ->count();

            $currently_leading_count = DB::table('history_lelang as history')
                ->where('history.id_user', '=', $id_user)
                ->join('tb_lelang as lelang', 'lelang.id_lelang', 'history.id_lelang')
                ->where('lelang.status', '=', 'dibuka')
                ->orderByDesc('history.penawaran_harga')
                ->count();

            $auction_win_count = DB::table('history_lelang as history')
                ->where('history.id_user', '=', $id_user)
                ->join('tb_lelang as lelang', 'lelang.id_lelang', 'history.id_lelang')
                ->where('lelang.status', '=', 'ditutup')
                ->orderByDesc('history.penawaran_harga')
                ->count();

            $auctions_ending_soon = DB::table('tb_lelang as lelang')
                ->where('lelang.status', '=', 'dibuka')
                ->leftJoin('history_lelang as history', 'history.id_lelang', '=', 'lelang.id_lelang')
                ->join('tb_barang as barang', 'barang.id_barang', '=', 'lelang.id_barang')
                ->orderBy('lelang.tgl_akhir_lelang')
                ->where('history.id_user', '=', $id_user)
                ->select(
                    [
                        'lelang.id_lelang',
                        'barang.nama_barang as nama_lot',
                        'barang.thumbnail as thumbnail_url',
                        'lelang.tgl_akhir_lelang as tgl_selesai'
                    ]
                )
                ->limit(5)
                ->get();

            return response()->json(['message' => 'Successfully retrieved user data', 'data'
            => [
                'total_lelang_diikuti' => $joined_auction_count,
                'total_sedang_unggul' => $currently_leading_count,
                'total_menang' => $auction_win_count,
                'lelang_segera_berakhir' => AuctionEndingSoonResource::collection($auctions_ending_soon)
            ]], 200);
        }
    }
}
