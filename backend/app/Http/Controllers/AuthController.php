<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Level;
use App\Models\Petugas;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $role = $payload['role'];
        $hashedPassword = Hash::make($payload['password']);

        if ($role === "masyarakat") {
            $user =  User::create([
                'nama_lengkap' => $payload['nama_lengkap'],
                'username' => $payload['username'],
                'email' => $payload['email'],
                'telp' => $payload['telp'],
                'password' => $hashedPassword,

            ]);
            return response()->json(['message' => 'New user successfully created', 'data' => ['id' => $user->id_user, 'username' => $user->username, 'created_at' => $user->created_at]], 200);
        }


        $admin = Petugas::create([
            'nama_petugas' => $payload['nama_lengkap'],
            'username' => $payload['username'],
            'telp' => $payload['telp'],
            'password' => $hashedPassword,
            'id_level' => 1
        ]);
        return response()->json(['message' => 'New user successfully created', 'data' => ['id' => $admin->id_petugas, 'username' => $admin->username, 'created_at' => $admin->created_at]], 200);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $role = $request->input('role');
        $isRememberMe = $request->has('isRememberMe');

        if ($role === 'masyarakat') {
            if (Auth::attempt($payload, $isRememberMe)) {
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;


                return response()->json(['message' => 'User login successfully', 'data' => [
                    'id' => $user->id_user,
                    'username' => $user->username,
                    'role' => $role
                ], 'token' => $token], 200);
            }

            return response()->json(['message' => 'Invalid field', 'errors' => ['username' => 'Username atau password salah', 'password' => 'Username atau password salah']], 422);
        }


        $admin = Petugas::where(['username' => $payload['username'], 'id_level' => $role === 'administrator' ? 1 : 2])->first();

        if ($admin && Hash::check($payload['password'], $admin->password)) {
            $token = $admin->createToken('authToken')->plainTextToken;

            Petugas::where('username', $admin->username)->update(['remember_token' => $token]);

            return response()->json(['message' => 'User login successfully', 'data' => [
                'id' => $admin->id_petugas,
                'username' => $admin->username,
                'role' => $admin->level->level
            ], 'token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid field', 'errors' => ['username' => 'Username atau password salah', 'password' => 'Username atau password salah']], 422);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout successfully'], 200);
    }
}
