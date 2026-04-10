<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowStepController extends Controller
{
    /**
     * Store a newly created workflow step.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role->name !== 'Admin_Unit') {
            abort(403, 'Hanya Admin Unit yang dapat menambah workflow step');
        }

        $workflow = Workflow::findOrFail($request->workflow_id);

        // Pastikan workflow milik unit user
        if ($workflow->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak memiliki akses ke workflow unit lain');
        }

        $validated = $request->validate([
            'workflow_id' => 'required|exists:workflows,id',
            'position_id' => 'required|exists:positions,id',
            'step_order' => 'required|integer|min:1',
            'requires_attachment' => 'nullable|boolean',
        ]);

        $validated['requires_attachment'] = (bool) $request->input('requires_attachment', false);

        return WorkflowStep::create($validated);
    }

    /**
     * Update a workflow step.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        if ($user->role->name !== 'Admin_Unit') {
            abort(403, 'Hanya Admin Unit yang dapat mengubah workflow step');
        }

        $step = WorkflowStep::findOrFail($id);

        // Check authorization
        if ($step->workflow->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak memiliki akses ke workflow unit lain');
        }

        $validated = $request->validate([
            'position_id' => 'required|exists:positions,id',
            'step_order' => 'required|integer|min:1',
            'requires_attachment' => 'nullable|boolean',
        ]);

        $validated['requires_attachment'] = (bool) $request->input('requires_attachment', false);

        $step->update($validated);

        return $step;
    }

    /**
     * Remove a workflow step.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();

        if ($user->role->name !== 'Admin_Unit') {
            abort(403, 'Hanya Admin Unit yang dapat menghapus workflow step');
        }

        $step = WorkflowStep::findOrFail($id);

        // Check authorization
        if ($step->workflow->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak memiliki akses ke workflow unit lain');
        }

        $step->delete();

        return response()->json(['message' => 'Workflow step berhasil dihapus']);
    }
}
