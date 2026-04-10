<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role->name === 'Admin_Unit') {
            return Workflow::where('unit_id', $user->unit_id)->with('steps.position', 'requirements')->get();
        }

        return Workflow::with('steps.position', 'requirements')->get();
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
}
