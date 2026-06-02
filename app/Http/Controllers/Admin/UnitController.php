<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Booking;
use App\Models\BookingAttachment;
use App\Models\BookingLog;
use App\Models\Position;
use App\Models\Room;
use App\Models\Unit;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowRequirement;
use App\Models\WorkflowStep;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $userUnit = $user->unit;

        // Logika menampilkan unit berdasarkan level dan role
        if ($user->role->name === 'Administrator Utama') {
            // SuperAdmin - tampilkan semua unit
            $units = Unit::with('parent', 'children')
                ->orderBy('level')
                ->orderBy('unit_name')
                ->get();
        } elseif ($userUnit) {
            // Admin_Unit - tampilkan berdasarkan level
            if ($userUnit->level === 'Jurusan') {
                // Level 2 (Jurusan): tampilkan unit sendiri + semua anak (level 3)
                $units = Unit::with('parent', 'children')
                    ->where('id', $userUnit->id)  // Unit sendiri
                    ->orWhere('parent_id', $userUnit->id)  // Anak unit (level 3)
                    ->orderBy('level')
                    ->orderBy('unit_name')
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

        if ($user->role->name !== 'Administrator Utama') {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'unit_name' => 'required|string|max:255|unique:units,unit_name',
            'level' => 'required|in:Pusat,Jurusan,Organisasi',
            'parent_id' => 'nullable|integer|exists:units,id',
            'description' => 'nullable|string|max:255',
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

        // Strict 4-level nesting limits: prevent Level 5 units by checking if the chosen parent is already a Sub-Organization (Level 4)
        if ($request->level === 'Organisasi' && $request->filled('parent_id')) {
            $parent = Unit::find($request->parent_id);
            if ($parent && $parent->level === 'Organisasi') {
                $grandparent = Unit::find($parent->parent_id);
                if ($grandparent && $grandparent->level === 'Organisasi') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Struktur organisasi maksimal hanya diperbolehkan sampai tingkatan Sub-Organisasi (Level 4).',
                    ], 422);
                }
            }
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
        if ($user->role->name !== 'Administrator Utama') {
            $userUnit = $user->unit;

            if (! $userUnit) {
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
            'unit_name' => 'sometimes|required|string|max:255|unique:units,unit_name,'.$id,
            'level' => 'sometimes|required|in:Pusat,Jurusan,Organisasi',
            'parent_id' => 'nullable|integer|exists:units,id',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Strict 4-level nesting limits: prevent Level 5 units by checking if the chosen parent is already a Sub-Organization (Level 4)
        $targetLevel = $request->input('level', $unit->level);
        $targetParentId = $request->input('parent_id', $unit->parent_id);

        if ($targetLevel === 'Organisasi' && $targetParentId) {
            $parent = Unit::find($targetParentId);
            if ($parent && $parent->level === 'Organisasi') {
                $grandparent = Unit::find($parent->parent_id);
                if ($grandparent && $grandparent->level === 'Organisasi') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Struktur organisasi maksimal hanya diperbolehkan sampai tingkatan Sub-Organisasi (Level 4).',
                    ], 422);
                }
            }
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

        if ($user->role->name !== 'Administrator Utama') {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $unit = Unit::withCount(['children'])->find($id);

        if (! $unit) {
            return response()->json(['success' => false, 'message' => 'Unit tidak ditemukan.'], 404);
        }

        if ($unit->children_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Unit tidak bisa dihapus karena masih memiliki sub-unit.',
            ], 422);
        }

        try {
            DB::transaction(function () use ($unit) {
                // 1. Ambil semua room milik unit ini
                $roomIds = Room::where('unit_id', $unit->id)->pluck('id')->toArray();
                if (! empty($roomIds)) {
                    // Cari booking pada room ini
                    $bookingIds = Booking::whereIn('room_id', $roomIds)->pluck('id')->toArray();
                    if (! empty($bookingIds)) {
                        Approval::whereIn('booking_id', $bookingIds)->delete();
                        BookingAttachment::whereIn('booking_id', $bookingIds)->delete();
                        BookingLog::whereIn('booking_id', $bookingIds)->delete();
                        Booking::whereIn('id', $bookingIds)->delete();
                    }
                    Room::whereIn('id', $roomIds)->delete();
                }

                // 2. Ambil semua user milik unit ini
                $userIds = User::where('unit_id', $unit->id)->pluck('id')->toArray();
                if (! empty($userIds)) {
                    // Cari booking yang diajukan user ini
                    $userBookingIds = Booking::whereIn('user_id', $userIds)->pluck('id')->toArray();
                    if (! empty($userBookingIds)) {
                        Approval::whereIn('booking_id', $userBookingIds)->delete();
                        BookingAttachment::whereIn('booking_id', $userBookingIds)->delete();
                        BookingLog::whereIn('booking_id', $userBookingIds)->delete();
                        Booking::whereIn('id', $userBookingIds)->delete();
                    }

                    // Hapus approval yang disetujui user ini
                    Approval::whereIn('approver_id', $userIds)->delete();
                    BookingLog::whereIn('actor_id', $userIds)->delete();
                    User::whereIn('id', $userIds)->delete();
                }

                // 3. Hapus posisi terkait
                Position::where('unit_id', $unit->id)->delete();

                // 4. Hapus workflow terkait
                $workflowIds = Workflow::where('unit_id', $unit->id)->pluck('id')->toArray();
                if (! empty($workflowIds)) {
                    WorkflowStep::whereIn('workflow_id', $workflowIds)->delete();
                    WorkflowRequirement::whereIn('workflow_id', $workflowIds)->delete();
                    Workflow::whereIn('id', $workflowIds)->delete();
                }

                // 5. Terakhir hapus unitnya
                $unit->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Unit beserta semua data terkait (user, posisi, ruangan, alur kerja) berhasil dihapus secara bersih.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus unit: '.$e->getMessage(),
            ], 500);
        }
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
