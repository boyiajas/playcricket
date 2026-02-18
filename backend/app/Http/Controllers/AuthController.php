<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'role' => 'required|string',
        ]);

        $user = collect(config('appdata.users'))->firstWhere('role', $data['role']);

        if (!$user) {
            return response()->json(['message' => 'Invalid role'], 422);
        }

        // Stubbed authentication. Replace with Laravel Sanctum/Passport as needed.
        return response()->json($user);
    }
}
