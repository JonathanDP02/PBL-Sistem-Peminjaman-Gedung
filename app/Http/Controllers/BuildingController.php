<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    use Illuminate\Support\Facades\Auth;

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
