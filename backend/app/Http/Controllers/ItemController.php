<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinBidRequest;
use App\Http\Requests\storeAuctionRequest;
use App\Http\Requests\updateAuctionRequest;
use App\Http\Resources\AuctionHistoryResources;
use App\Models\Barang;
use App\Models\HistoryLelang;
use App\Models\Lelang;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $auctions = Barang::select(['id_barang', 'nama_barang', 'tgl', 'harga_awal', 'id_kategori_barang'])->with('kategori_barang:id_kategori_barang,nama_kategori_barang')->get();
        return response()->json(['message' => 'Successfully retrieved auctions data', 'data' => $auctions], 200);
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
    public function store(storeAuctionRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $imagePath = $payload['thumbnail']->store('images', 'public');

        $auction =  Barang::create([
            'nama_barang' => $payload['nama_barang'],
            'tgl' => $payload['tgl'],
            'harga_awal' => $payload['harga_awal'],
            'deskripsi_barang' => $payload['deskripsi_barang'],
            'thumbnail' => $imagePath,
            'id_kategori_barang' => $payload['id_kategori_barang'],
            'id_petugas' => $request->user()->id_petugas
        ]);

        $auction = Lelang::create([
            'tgl_mulai_lelang' => $payload['tgl_mulai_lelang'],
            'tgl_akhir_lelang' => $payload['tgl_akhir_lelang'],
            'id_petugas' => $request->user()->id_petugas,
            'id_barang' => $auction->id_barang,
            'status' =>  $payload['tgl_akhir_lelang'] <= now() ? 'ditutup' : 'dibuka'
        ]);

        return response()->json([
            'message' => 'New auction with name ' . $auction->nama_barang . ' successfully created',
            'data' =>
            [
                'id' => $auction->id_barang,
                'nama_barang' => $auction->nama_barang,
                'tgl' => $auction->tgl,
                'harga_awal' => $auction->harga_awal,
                'deskripsi_barang' => $auction->deskripsi_barang,
                'thumbnail' => $auction->thumbnail,
                'id_kategori_barang' => $auction->id_kategori_barang,
                'id_petugas' => $auction->id_petugas,
                'created_at' => $auction->created_at
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $auction = Barang::select(['id_barang', 'nama_barang', 'tgl', 'harga_awal', 'deskripsi_barang', 'thumbnail', 'id_kategori_barang', 'id_petugas'])->where('id_barang', $id)->with('kategori_barang:id_kategori_barang,nama_kategori_barang', 'petugas:id_petugas,nama_petugas,id_level', 'petugas.level:id_level,level', 'lelang:id_lelang,id_user,id_barang,status,harga_akhir,tgl_mulai_lelang,tgl_akhir_lelang')->first();
        if (!$auction) {
            return response()->json(['message' => 'Auction with ID ' . $id . ' not found'], 404);
        }

        $imagePath = asset('storage/' . $auction->thumbnail);
        return response()->json([
            'message' => 'Auction with ID ' . $id . ' successfully retrieved',
            'data' => [
                'id_barang' => $auction->id_barang,
                'nama_barang' => $auction->nama_barang,
                'tgl' => $auction->tgl,
                'harga_awal' => $auction->harga_awal,
                'deskripsi_barang' => $auction->deskripsi_barang,
                'thumbnail' => $imagePath,
                'id_kategori_barang' => $auction->id_kategori_barang,
                'id_petugas' => $auction->id_petugas,
                'kategori_barang' => $auction->kategori_barang,
                'petugas' => $auction->petugas,
                'lelang' => $auction->lelang
            ]
        ], 200);
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
    public function update(updateAuctionRequest $request, string $id)
    {
        $payload = $request->validated();

        // 1. Ambil data barang beserta relasi lelangnya
        $barang = Barang::with('lelang')->where('id_barang', $id)->first();

        if (!$barang) {
            return response()->json(['message' => 'Auction with ID ' . $id . ' not found'], 404);
        }

        // Gunakan Transaction agar jika salah satu gagal, semua perubahan di-rollback
        DB::beginTransaction();

        try {
            // 2. Handle upload & hapus gambar thumbnail lama
            if ($request->hasFile('thumbnail')) {
                if ($barang->thumbnail) {
                    Storage::disk('public')->delete($barang->thumbnail);
                }

                $imagePath = $payload['thumbnail']->store('images', 'public');
                $barang->thumbnail = $imagePath;
            }

            // 3. Update data tabel Barang
            $barang->nama_barang        = $payload['nama_barang'];
            $barang->tgl                = $payload['tgl'];
            $barang->harga_awal         = $payload['harga_awal'];
            $barang->deskripsi_barang   = $payload['deskripsi_barang'];
            $barang->id_kategori_barang = $payload['id_kategori_barang'];
            $barang->save();

            // 4. Update data tabel Lelang (Relasi)
            if ($barang->lelang) {
                $barang->lelang->update([
                    'id_petugas' => $request->user()->id_petugas,
                    'tgl_mulai_lelang' => $payload['tgl_mulai_lelang'],
                    'tgl_akhir_lelang' => $payload['tgl_akhir_lelang'],
                    'status' => $payload['tgl_akhir_lelang'] <= now() ? 'ditutup' : 'dibuka'
                ]);
            }

            DB::commit();

            // Refresh data relasi untuk response
            $barang->load('lelang');

            return response()->json([
                'message' => 'Update auction data with ID ' . $id . ' successfully',
                'data' => [
                    'id_barang'          => $barang->id_barang,
                    'nama_barang'        => $barang->nama_barang,
                    'tgl'                => $barang->tgl,
                    'harga_awal'         => $barang->harga_awal,
                    'deskripsi_barang'   => $barang->deskripsi_barang,
                    'thumbnail'          => $barang->thumbnail,
                    'id_kategori_barang' => $barang->id_kategori_barang,
                    'lelang'             => $barang->lelang
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $auction = Barang::select(['thumbnail'])->where('id_barang', $id)->first();
        if (!$auction) {
            return response()->json(['message' => 'Auction with ID ' . $id . ' not found'], 404);
        }

        Storage::disk('public')->delete($auction->thumbnail);
        $auction->destroy($id);

        return response()->json([
            'message' => 'delete auction with ID ' . $id . ' successfully',
        ], 200);
    }

    public function join_bid(string $id, JoinBidRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $logged_user = $request->user();

        HistoryLelang::create([
            'id_barang' => $payload['id_barang'],
            'id_lelang' => $id,
            'id_user' => $logged_user->id_user,
            'penawaran_harga' => $payload['penawaran_harga'],
        ]);

        return response()->json(['message' => 'successfully join bidding on auction ID ' . $id], 201);
    }

    public function get_histories(Request $request): JsonResponse
    {
        $user_id = $request->user()->id_user;

        $auctions_histories = DB::table('history_lelang as history')
            ->where('history.id_user',  '=', $user_id)
            ->join('tb_lelang as lelang', 'lelang.id_lelang', '=', 'history.id_lelang')
            ->join('tb_barang as barang', 'barang.id_barang', '=', 'lelang.id_barang')
            ->latest('history.created_at')
            ->select(
                [
                    // Data Lelang
                    'lelang.id_lelang',
                    'lelang.harga_akhir as penawaran_tertinggi_saat_ini',
                    'lelang.tgl_akhir_lelang as tgl_selesai',
                    'lelang.status as status_lelang',
                    'lelang.id_user as id_pemenang',
                    // Data histori
                    'history.penawaran_harga as penawaran_peserta',

                    // Data barang
                    'barang.nama_barang'
                ]
            )
            ->orderByDesc('lelang.tgl_akhir_lelang')
            ->get();

        return response()->json(['message' => 'Successfully retrieved auctions histories', 'data' => AuctionHistoryResources::collection($auctions_histories)], 200);
    }
}
