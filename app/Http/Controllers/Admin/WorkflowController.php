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
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role->name === 'Admin_Unit') {
            return Workflow::where('unit_id', $user->unit_id)
                ->with('steps.position', 'requirements')
                ->withCount('steps')
                ->get();
        }

        return Workflow::with('steps.position', 'requirements')
            ->withCount('steps')
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role->name !== 'Admin_Unit') {
            abort(403, 'Hanya Admin Unit yang dapat membuat workflow');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['unit_id'] = $user->unit_id;

        return Workflow::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $workflow = Workflow::with('steps.position', 'requirements')->findOrFail($id);

        $this->authorizeUnit($workflow);

        return $workflow;
    }

    /**
     * Update the specified resource in storage.
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

        return $workflow;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $workflow = Workflow::findOrFail($id);

        $this->authorizeUnit($workflow);

        $workflow->delete();

        return response()->json(['message' => 'Workflow berhasil dihapus']);
    }

    /**
     * Get workflow requirements (API endpoint untuk AJAX).
     */
    public function showRequirements(string $id)
    {
        $workflow = Workflow::findOrFail($id);

        $this->authorizeUnit($workflow);

        return response()->json($workflow->requirements);
    }

    /**
     * Authorize Admin_Unit untuk mengakses workflow unit mereka.
     */
    private function authorizeUnit(Workflow $workflow): void
    {
        $user = Auth::user();

        if ($user->role->name === 'Admin_Unit' && $workflow->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak memiliki akses ke workflow unit lain');
        }
    }

    /**
     * Mengambil daftar jabatan untuk dropdown di Frontend.
     */
    public function getPositions()
    {
        // Jika ada filter spesifik unit, bisa ditambahkan di sini.
        // Untuk sekarang, kita ambil semua jabatan aktif.
        $positions = Position::select('id', 'name')->orderBy('name', 'asc')->get();

        return response()->json(['data' => $positions]);
    }

    /**
     * Menyimpan urutan langkah dan syarat dokumen secara bersamaan (Atomic Transaction).
     */
    public function syncDetails(Request $request, $id)
    {
        $user = Auth::user();
        $workflow = Workflow::query()
            ->when($user->role->name === 'Admin_Unit', fn ($query) => $query->where('unit_id', $user->unit_id))
            ->findOrFail($id);

        $validated = $request->validate([
            'steps' => 'array',
            'steps.*.position_id' => 'required_with:steps|integer|exists:positions,id|distinct',
            'steps.*.step_order' => 'required_with:steps|integer|min:1',
            'steps.*.requires_attachment' => 'nullable|boolean',
            'requirements' => 'array',
            'requirements.*.document_name' => 'required_with:requirements|string|max:150',
            'requirements.*.is_mandatory' => 'nullable|boolean',
        ], [
            'steps.*.position_id.distinct' => 'Pejabat/Approver tidak boleh dipilih lebih dari satu kali dalam satu workflow.',
        ]);

        try {
            DB::transaction(function () use ($workflow, $validated) {
                $workflow->steps()->delete();
                $workflow->requirements()->delete();

                if (! empty($validated['steps'])) {
                    $positionIds = collect($validated['steps'])->pluck('position_id');
                    $positions = Position::with('unit')->whereIn('id', $positionIds)->get()->keyBy('id');

                    $levelScore = [
                        'Organisasi' => 1,
                        'Jurusan' => 2,
                        'Pusat' => 3,
                    ];

                    // Refactoring: Urutkan data workflow mengikuti level terbawah sampai teratas untuk approvernya
                    $sortedSteps = collect($validated['steps'])->sortBy(function ($step) use ($positions, $levelScore) {
                        $unitLevel = $positions[$step['position_id']]->unit->level ?? 'Organisasi';

                        return $levelScore[$unitLevel] ?? 1;
                    })->values();

                    // Reassign step_order
                    foreach ($sortedSteps as $index => $step) {
                        $workflow->steps()->create([
                            'position_id' => $step['position_id'],
                            'step_order' => $index + 1,
                            'requires_attachment' => (bool) ($step['requires_attachment'] ?? false),
                        ]);
                    }
                }

                foreach ($validated['requirements'] ?? [] as $requirement) {
                    $workflow->requirements()->create([
                        'document_name' => $requirement['document_name'],
                        'is_mandatory' => (bool) ($requirement['is_mandatory'] ?? true),
                    ]);
                }
            });

            return response()->json([
                'message' => 'Konfigurasi Workflow berhasil disimpan.',
                'workflow' => $workflow->load('steps', 'requirements'),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan internal saat menyimpan data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
