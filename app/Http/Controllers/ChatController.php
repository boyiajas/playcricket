<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $memberId = $request->query('memberId');
        $messages = collect(config('appdata.chats'));

        if ($memberId) {
            $messages = $messages->where('memberId', $memberId);
        }

        return response()->json($messages->values());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'memberId' => 'required|string',
            'sender' => 'required|string',
            'content' => 'required|string',
        ]);

        $message = array_merge([
            'id' => uniqid('msg'),
            'timestamp' => now()->toIso8601String(),
        ], $data);

        return response()->json($message, 201);
    }
}
