<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role->name === 'SuperAdmin') {
            $units = Unit::with('parent', 'children')
                ->orderBy('level')
                ->orderBy('name')
                ->get();
        } else {
            $ownUnitId = $user->unit_id;
            $units = Unit::with('parent', 'children')
                ->where('id', $ownUnitId)
                ->orWhere('parent_id', $ownUnitId)
                ->orderBy('level')
                ->orderBy('name')
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $units,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->role->name !== 'SuperAdmin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:units,name',
            'level' => 'required|in:Pusat,Jurusan,Organisasi',
            'parent_id' => 'nullable|integer|exists:units,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        if ($request->level === 'Pusat' && $request->filled('parent_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Unit level Pusat tidak boleh memiliki parent.',
            ], 422);
        }

        if (in_array($request->level, ['Jurusan', 'Organisasi']) && ! $request->filled('parent_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Unit level Jurusan/Organisasi wajib memiliki parent_id.',
            ], 422);
        }

        $unit = Unit::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil dibuat.',
            'data' => $unit->load('parent'),
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $unit = Unit::find($id);

        if (! $unit) {
            return response()->json(['success' => false, 'message' => 'Unit tidak ditemukan.'], 404);
        }

        $user = $request->user();

        if ($user->role->name !== 'SuperAdmin') {
            $allowed = Unit::where('id', $user->unit_id)
                ->orWhere('parent_id', $user->unit_id)
                ->pluck('id')
                ->toArray();

            if (! in_array($unit->id, $allowed)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengedit unit ini.',
                ], 403);
            }
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:units,name,'.$id,
            'level' => 'sometimes|required|in:Pusat,Jurusan,Organisasi',
            'parent_id' => 'nullable|integer|exists:units,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $unit->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil diperbarui.',
            'data' => $unit->fresh('parent', 'children'),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        if ($user->role->name !== 'SuperAdmin') {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $unit = Unit::withCount(['children', 'users'])->find($id);

        if (! $unit) {
            return response()->json(['success' => false, 'message' => 'Unit tidak ditemukan.'], 404);
        }

        if ($unit->children_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Unit tidak bisa dihapus karena masih memiliki sub-unit.',
            ], 422);
        }

        if ($unit->users_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Unit tidak bisa dihapus karena masih memiliki user terdaftar.',
            ], 422);
        }

        $unit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Unit berhasil dihapus.',
        ]);
    }

    public function positions(int $id): JsonResponse
    {
        $unit = Unit::find($id);

        if (! $unit) {
            return response()->json(['success' => false, 'message' => 'Unit tidak ditemukan.'], 404);
        }

        $positions = Position::where('unit_id', $id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'data' => $positions,
        ]);
    }
}
