<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        return response()->json(config('appdata.campaigns'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'channel' => 'required|string',
            'status' => 'required|string',
            'stats' => 'array',
        ]);

        $campaign = array_merge([
            'id' => uniqid('camp'),
            'createdAt' => now()->toDateString(),
        ], $data);

        return response()->json($campaign, 201);
    }
}
