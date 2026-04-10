<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = User::with(['role', 'unit', 'position']);

        if ($user->role->name !== 'SuperAdmin') {
            $query->where('unit_id', $user->unit_id);
        }

        $users = $query->orderBy('name')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $user = User::with(['role', 'unit', 'position'])->find($id);

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $authUser = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:roles,id',
            'unit_id' => 'required|integer|exists:units,id',
            'position_id' => 'nullable|integer|exists:positions,id', // opsional
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cek konsistensi position vs unit, hanya jika position_id diisi
        if ($request->filled('position_id')) {
            $position = Position::find($request->position_id);
            if ($position && $position->unit_id !== $request->unit_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Position yang dipilih tidak sesuai dengan unit.',
                ], 422);
            }
        }

        // Scope unit untuk Admin_Unit
        if ($authUser->role->name !== 'SuperAdmin') {
            $allowedUnitIds = Unit::where('id', $authUser->unit_id)
                ->orWhere('parent_id', $authUser->unit_id)
                ->pluck('id')
                ->toArray();

            if (! in_array($request->unit_id, $allowedUnitIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat membuat user untuk unit tersebut.',
                ], 403);
            }

            // Admin_Unit tidak boleh assign role SuperAdmin
            $targetRole = Role::find($request->role_id);
            if ($targetRole && $targetRole->name === 'SuperAdmin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menetapkan role SuperAdmin.',
                ], 403);
            }
        }

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'unit_id' => $request->unit_id,
            'position_id' => $request->position_id ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat.',
            'data' => $newUser->load(['role', 'unit', 'position']),
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $authUser = $request->user();
        $targetUser = User::find($id);

        if (! $targetUser) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        if ($authUser->role->name !== 'SuperAdmin') {
            $allowedUnitIds = Unit::where('id', $authUser->unit_id)
                ->orWhere('parent_id', $authUser->unit_id)
                ->pluck('id')
                ->toArray();

            if (! in_array($targetUser->unit_id, $allowedUnitIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengedit user ini.',
                ], 403);
            }
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,'.$id,
            'password' => 'sometimes|nullable|string|min:8',
            'role_id' => 'sometimes|required|integer|exists:roles,id',
            'unit_id' => 'sometimes|required|integer|exists:units,id',
            'position_id' => 'sometimes|nullable|integer|exists:positions,id', // opsional
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cek konsistensi position vs unit, hanya jika position_id diisi
        if ($request->filled('position_id')) {
            $newUnitId = $request->unit_id ?? $targetUser->unit_id;
            $position = Position::find($request->position_id);
            if ($position && $position->unit_id !== $newUnitId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Position yang dipilih tidak sesuai dengan unit.',
                ], 422);
            }
        }

        if ($request->filled('role_id') && $authUser->role->name !== 'SuperAdmin') {
            $targetRole = Role::find($request->role_id);
            if ($targetRole && $targetRole->name === 'SuperAdmin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menetapkan role SuperAdmin.',
                ], 403);
            }
        }

        $data = $request->only(['name', 'email', 'role_id', 'unit_id', 'position_id']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $targetUser->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui.',
            'data' => $targetUser->fresh(['role', 'unit', 'position']),
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $authUser = $request->user();
        $targetUser = User::find($id);

        if (! $targetUser) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        if ($authUser->id === $targetUser->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri.',
            ], 422);
        }

        if ($authUser->role->name !== 'SuperAdmin') {
            $allowedUnitIds = Unit::where('id', $authUser->unit_id)
                ->orWhere('parent_id', $authUser->unit_id)
                ->pluck('id')
                ->toArray();

            if (! in_array($targetUser->unit_id, $allowedUnitIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus user ini.',
                ], 403);
            }

            if ($targetUser->role->name === 'SuperAdmin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus SuperAdmin.',
                ], 403);
            }
        }

        $targetUser->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.',
        ]);
    }
}
