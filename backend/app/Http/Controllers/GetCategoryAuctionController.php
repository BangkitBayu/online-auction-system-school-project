<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetCategoryAuctionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        $categories = KategoriBarang::select(['id_kategori_barang', 'nama_kategori_barang'])->get();

        return response()->json(['message' => 'Successfully retrieved categories', 'data' => $categories], 200);
    }
}
