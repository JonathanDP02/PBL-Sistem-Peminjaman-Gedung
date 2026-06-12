<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Role;

class AdminApiController extends Controller
{
    public function roles()
    {
        return response()->json([
            'success' => true,
            'data' => Role::all(),
        ]);
    }

    public function positions()
    {
        return response()->json([
            'success' => true,
            'data' => Position::all(),
        ]);
    }
}
