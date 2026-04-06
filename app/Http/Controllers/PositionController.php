<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role->name === 'Admin_Unit') {
            return Position::where('unit_id', $user->unit_id)->get();
        }

        return Position::all();
    }
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required'
        ]);

        $validated['unit_id'] = $user->unit_id;

        return Position::create($validated);
    }
    public function update(Request $request, Position $position)
    {
        $user = auth()->user();

        if ($position->unit_id != $user->unit_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required'
        ]);

        $position->update($validated);
        return $position;
    }

    public function destroy(Position $position)
    {
        if ($position->unit_id != auth()->user()->unit_id) {
            abort(403);
        }

        $position->delete();
    }
}
