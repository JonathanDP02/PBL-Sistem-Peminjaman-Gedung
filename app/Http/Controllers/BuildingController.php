<?php

namespace App\Http\Controllers;

use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BuildingController extends Controller
{
    public function index()
    {
        $this->authorizeSuperAdmin();

        return response()->json(Building::all());
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'building_name' => 'required',
            'location' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('buildings', 'public');
        }

        Building::create($validated);

        return redirect()->back()->with('success', 'Gedung berhasil ditambahkan.');
    }

    public function update(Request $request, Building $building)
    {
        $this->authorizeSuperAdmin();

        $validated = $request->validate([
            'building_name' => 'required',
            'location' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($building->image) {
                Storage::disk('public')->delete($building->image);
            }
            $validated['image'] = $request->file('image')->store('buildings', 'public');
        }

        $building->update($validated);

        return redirect()->back()->with('success', 'Data Gedung berhasil diperbarui.');
    }

    public function destroy(Building $building)
    {
        $this->authorizeSuperAdmin();

        if ($building->rooms()->count() > 0) {
            return response()->json(['message' => 'Tidak dapat menghapus gedung karena masih memiliki ruangan.'], 422);
        }

        if ($building->image) {
            Storage::disk('public')->delete($building->image);
        }

        $building->delete();

        return response()->json(['message' => 'Gedung berhasil dihapus.']);
    }

    private function authorizeSuperAdmin()
    {
        if (Auth::user()->role->name !== 'Administrator Utama') {
            abort(403, 'Unauthorized');
        }
    }
}
