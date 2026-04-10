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
        $userUnit = $user->unit;

        // Logika menampilkan unit berdasarkan level dan role
        if ($user->role->name === 'SuperAdmin') {
            // SuperAdmin - tampilkan semua unit
            $units = Unit::with('parent', 'children')
                ->orderBy('level')
                ->orderBy('name')
                ->get();
        } elseif ($userUnit) {
            // Admin_Unit - tampilkan berdasarkan level
            if ($userUnit->level === 'Jurusan') {
                // Level 2 (Jurusan): tampilkan unit sendiri + semua anak (level 3)
                $units = Unit::with('parent', 'children')
                    ->where('id', $userUnit->id)  // Unit sendiri
                    ->orWhere('parent_id', $userUnit->id)  // Anak unit (level 3)
                    ->orderBy('level')
                    ->orderBy('name')
                    ->get();
            } elseif ($userUnit->level === 'Organisasi') {
                // Level 3 (Organisasi): tampilkan unit sendiri saja
                $units = Unit::with('parent', 'children')
                    ->where('id', $userUnit->id)
                    ->get();
            } else {
                // Level Pusat atau lainnya: tampilkan unit sendiri
                $units = Unit::with('parent', 'children')
                    ->where('id', $userUnit->id)
                    ->get();
            }
        } else {
            // Jika user tidak memiliki unit, return empty
            $units = collect([]);
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

        // Validasi akses berdasarkan role dan level
        if ($user->role->name !== 'SuperAdmin') {
            $userUnit = $user->unit;
            
            if (!$userUnit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengedit unit ini.',
                ], 403);
            }

            // Cek akses berdasarkan level unit user
            if ($userUnit->level === 'Jurusan') {
                // Level 2: bisa edit unit sendiri atau anak (level 3)
                if ($unit->id !== $userUnit->id && $unit->parent_id !== $userUnit->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat mengedit unit Anda sendiri atau bawahan langsung.',
                    ], 403);
                }
            } elseif ($userUnit->level === 'Organisasi') {
                // Level 3: hanya bisa edit unit sendiri
                if ($unit->id !== $userUnit->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat mengelola unit Anda sendiri.',
                    ], 403);
                }
            } else {
                // Level lain: hanya bisa edit diri sendiri
                if ($unit->id !== $userUnit->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda hanya dapat mengelola unit Anda sendiri.',
                    ], 403);
                }
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
