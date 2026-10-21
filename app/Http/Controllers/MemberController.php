<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        return response()->json(config('appdata.members'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'membershipType' => 'required|string',
            'tags' => 'array',
        ]);

        $member = array_merge(['id' => uniqid('m')], $data);
        return response()->json($member, 201);
    }
}
