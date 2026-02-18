<?php

namespace App\Http\Controllers;

class AuditController extends Controller
{
    public function index()
    {
        return response()->json(config('appdata.audit'));
    }
}
