<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $users = User::select(['id_user', 'username'])->get();

        return response()->json(['Successfully retrieved users data', 'data' => $users], 200);
    }
}
