<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuildingController extends Controller
{
    public function index()
    {
        $this->authorizeSuperAdmin();

        return Building::all();
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'building_name' => 'required',
            'location' => 'required',
        ]);

        return Building::create($validated);
    }

    public function update(Request $request, Building $building)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'building_name' => 'required',
            'location' => 'required',
        ]);

        $building->update($validated);

        return $building;
    }

    public function destroy(Building $building)
    {
        $this->authorizeSuperAdmin();
        $building->delete();
    }

    private function authorizeSuperAdmin()
    {
        if (Auth::user()->role->name !== 'SuperAdmin') {
            abort(403, 'Unauthorized');
        }
    }
}
