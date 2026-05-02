<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkflowController extends Controller
{
    /**
     * Menampilkan daftar workflow sesuai unit admin yang login.
     */
    public function index()
    {
        $user = Auth::user();

        // Konsisten: Selalu filter berdasarkan unit_id untuk Admin_Unit
        $query = Workflow::query()->with('steps.position', 'requirements')->withCount('steps');

        if ($user->role->name === 'Admin_Unit') {
            $query->where('unit_id', $user->unit_id);
        }

        return response()->json($query->orderBy('updated_at', 'desc')->get());
    }

    /**
     * Membuat data awal workflow (Nama & Deskripsi).
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role->name !== 'Admin_Unit') {
            return response()->json(['message' => 'Hanya Admin Unit yang dapat membuat workflow'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['unit_id'] = $user->unit_id;

        $workflow = Workflow::create($validated);

        return response()->json($workflow);
    }

    /**
     * Mengambil detail workflow tertentu.
     */
    public function show(string $id)
    {
        $workflow = Workflow::with('steps.position', 'requirements')->findOrFail($id);
        $this->authorizeUnit($workflow);

        return response()->json($workflow);
    }

    /**
     * Update Nama dan Deskripsi workflow.
     */
    public function update(Request $request, string $id)
    {
        $workflow = Workflow::findOrFail($id);
        $this->authorizeUnit($workflow);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $workflow->update($validated);

        return response()->json($workflow);
    }

    /**
     * Menghapus workflow beserta seluruh relasinya.
     */
    public function destroy(string $id)
    {
        $workflow = Workflow::findOrFail($id);
        $this->authorizeUnit($workflow);

        $workflow->delete();

        return response()->json(['message' => 'Workflow berhasil dihapus']);
    }

    /**
     * Sinkronisasi detail Tahapan dan Syarat Dokumen (Atomic Transaction).
     */
    public function syncDetails(Request $request, $id)
    {
        $user = Auth::user();
        $workflow = Workflow::where('unit_id', $user->unit_id)->findOrFail($id);

        $validated = $request->validate([
            'steps' => 'array',
            'steps.*.position_id' => 'required_with:steps|integer|exists:positions,id',
            'steps.*.requires_attachment' => 'nullable|boolean',
            'requirements' => 'array',
            'requirements.*.document_name' => 'required_with:requirements|string|max:150',
            'requirements.*.is_mandatory' => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($workflow, $validated) {
                // 1. Bersihkan data lama
                $workflow->steps()->delete();
                $workflow->requirements()->delete();

                // 2. Simpan Syarat Dokumen
                foreach ($validated['requirements'] ?? [] as $requirement) {
                    $workflow->requirements()->create([
                        'document_name' => $requirement['document_name'],
                        'is_mandatory' => (bool) ($requirement['is_mandatory'] ?? true),
                    ]);
                }

                // 3. Simpan Tahapan (Dengan Logika Hierarki Level Anda)
                if (!empty($validated['steps'])) {
                    $positionIds = collect($validated['steps'])->pluck('position_id');
                    $positions = Position::with('unit')->whereIn('id', $positionIds)->get()->keyBy('id');

                    // Logika skor level Anda: Organisasi paling bawah, Pusat paling atas
                    $levelScore = [
                        'Organisasi' => 1,
                        'Jurusan' => 2,
                        'Pusat' => 3,
                    ];

                    // Urutkan langkah berdasarkan level unit pejabat
                    $sortedSteps = collect($validated['steps'])->sortBy(function ($step) use ($positions, $levelScore) {
                        $unitLevel = $positions[$step['position_id']]->unit->level ?? 'Organisasi';
                        return $levelScore[$unitLevel] ?? 1;
                    })->values();

                    // Masukkan ke database dengan urutan (step_order) yang baru
                    foreach ($sortedSteps as $index => $step) {
                        $workflow->steps()->create([
                            'position_id' => $step['position_id'],
                            'step_order' => $index + 1,
                            'requires_attachment' => (bool) ($step['requires_attachment'] ?? false),
                        ]);
                    }
                }
            });

            return response()->json([
                'message' => 'Konfigurasi Workflow berhasil disimpan.',
                'workflow' => $workflow->load('steps', 'requirements'),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat sinkronisasi data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mengambil Master Data Jabatan untuk dropdown.
     */
    public function getPositions()
    {
        $positions = Position::select('id', 'name')->orderBy('name', 'asc')->get();
        return response()->json(['data' => $positions]);
    }

    /**
     * Helper Keamanan: Memastikan admin hanya bisa akses workflow milik unitnya.
     */
    private function authorizeUnit(Workflow $workflow): void
    {
        $user = Auth::user();
        if ($user->role->name === 'Admin_Unit' && $workflow->unit_id !== $user->unit_id) {
            abort(403, 'Akses ditolak: Workflow ini bukan milik unit Anda.');
        }
    }
}