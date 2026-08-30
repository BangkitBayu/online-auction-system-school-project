<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuctionReportsResource;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(): JsonResponse
    {
        $sub_winner_username = DB::table('history_lelang as history')
            ->select(['user.username'])
            ->leftJoin('tb_masyarakat as user', 'user.id_user', '=', 'history.id_user')
            ->whereColumn('history.id_lelang', '=', 'lelang.id_lelang')
            ->orderByDesc('penawaran_harga')
            ->limit(1);

        $sub_last_price = DB::table('history_lelang as history')
            ->select(['history.penawaran_harga'])
            ->whereColumn('history.id_lelang', '=', 'lelang.id_lelang')
            ->orderByDesc('penawaran_harga')
            ->limit(1);


        $auction_reports = DB::table('tb_lelang as lelang')
            ->where('lelang.status', '=', 'ditutup')
            ->leftJoin('tb_barang as barang', 'barang.id_barang', '=', 'lelang.id_barang')
            ->leftJoin('tb_kategori_barang as kategori', 'kategori.id_kategori_barang', '=', 'barang.id_kategori_barang')
            ->select([
                'lelang.id_lelang',
                'barang.nama_barang as nama_lot',
                'kategori.nama_kategori_barang as kategori_lot',
                'lelang.tgl_akhir_lelang as tgl_selesai',
            ])
            ->selectSub($sub_winner_username, 'pemenang')
            ->selectSub($sub_last_price, 'harga_akhir')
            ->paginate(10);


        return response()->json(['message' => 'Successfully retrieved auction reports', 'data' =>  AuctionReportsResource::collection($auction_reports)]);
    }

    public function download_detail_pdf(string $id)
    {
        $history = DB::table('history_lelang as history')
            ->where('history.id_lelang', '=', $id)
            ->leftJoin('tb_masyarakat as user', 'user.id_user', '=', 'history.id_user')
            ->orderByDesc('history.penawaran_harga')
            ->select(
                [
                    'history.id_history',
                    'history.penawaran_harga',

                    'user.nama_lengkap',
                    'user.username',
                    'user.telp',
                    'user.email'
                ]
            )
            ->first();
        $auction = DB::table('tb_lelang as lelang')
            ->where('lelang.id_lelang', '=', $id)
            ->join('tb_barang as barang', 'barang.id_barang', '=', 'lelang.id_barang')
            ->join('tb_kategori_barang as kategori_lot', 'kategori_lot.id_kategori_barang', '=', 'barang.id_kategori_barang')
            ->select(
                [
                    'lelang.id_lelang',
                    'lelang.tgl_mulai_lelang as tgl_mulai',
                    'lelang.tgl_akhir_lelang as tgl_selesai',
                    'lelang.harga_akhir as harga_limit',

                    'barang.id_barang',
                    'barang.nama_barang as nama_lot',
                    'barang.deskripsi_barang as deskripsi_lot',
                    'barang.harga_awal',

                    'kategori_lot.nama_kategori_barang as nama_kategori_lot'
                ]
            )
            ->first();

        $pdf = Pdf::loadView('documents.auction-winner-detail-report-pdf', ['auction' => $auction, 'history' => $history]);
        return $pdf->download('laporan-pemenang-lelang-' . $id . '.pdf');
        // return $pdf->stream('laporan-pemenang-' . $id . '.pdf');
    }
}
