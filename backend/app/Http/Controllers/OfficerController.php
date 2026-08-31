<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOfficerRequest;
use App\Http\Requests\UpdateOfficerRequest;
use App\Models\Petugas;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OfficerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $officers = Petugas::orderByDesc('created_at')->select(['id_petugas', 'nama_petugas', 'username', 'telp'])->where('id_level', 2)->get();

        return response()->json(['message' => 'Successfully retrieved officers data', 'data' => $officers], 200);
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
    public function store(StoreOfficerRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $hashedPassword = Hash::make($payload['password']);

        $officer = Petugas::create([
            'nama_petugas' => $payload['nama_petugas'],
            'username' => $payload['username'],
            'telp' => $payload['telp'],
            'password' => $hashedPassword,
            'id_level' => 2
        ]);

        return response()->json(['message' => 'New officer with username ' . $officer->username . ' successfully created', 'data' => ['id' => $officer->id_petugas, 'username' => $officer->username, 'role' => $officer->level->level,  'created_at' => $officer->created_at]], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $officer = Petugas::select(['id_petugas', 'nama_petugas', 'username', 'telp'])->where([['id_petugas', $id], ['id_level', 2]])->first();

        if (!$officer) {
            return response()->json(['message' => 'Officer with ID ' . $id . ' not found'], 404);
        }
        return response()->json(['message' => 'Successfully retrieved officer data', 'data' => $officer], 200);
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
    public function update(UpdateOfficerRequest $request, string $id): JsonResponse
    {
        $payload = $request->validated();


        $officer = Petugas::select(['id_petugas', 'nama_petugas', 'username', 'telp'])->where([['id_petugas', $id], ['id_level', 2]])->first();

        if (!$officer) {
            return response()->json(['message' => 'Officer with ID ' . $id . ' not found'], 404);
        }

        $officer->nama_petugas = $payload['nama_petugas'];
        $officer->username = $payload['username'];
        $officer->telp = $payload['telp'];
        $officer->password = Hash::make($payload['password']);

        $officer->save();

        return response()->json([
            'message' => 'Update officer data with ID ' . $id . ' successfully',
            'data' => [
                'id' => $officer->id_petugas,
                'username' => $officer->username,
                'updated_at' => $officer->updated_at
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $officer = Petugas::where([['id_petugas', $id], ['id_level', 2]])->first();

        if (!$officer) {
            return response()->json(['message' => 'Officer with ID ' . $id . ' not found'], 404);
        }

        Petugas::destroy($id);

        return response()->json([
            'message' => 'delete officer with ID ' . $id . ' successfully',
        ], 200);
    }
}
