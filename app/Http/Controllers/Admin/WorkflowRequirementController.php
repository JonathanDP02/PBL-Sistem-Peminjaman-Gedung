<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workflow;
use App\Models\WorkflowRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowRequirementController extends Controller
{
    /**
     * Store a newly created workflow requirement.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role->name !== 'Admin_Unit') {
            abort(403, 'Hanya Admin Unit yang dapat menambah syarat dokumen');
        }

        $workflow = Workflow::findOrFail($request->workflow_id);

        // Pastikan workflow milik unit user
        if ($workflow->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak memiliki akses ke workflow unit lain');
        }

        $validated = $request->validate([
            'workflow_id' => 'required|exists:workflows,id',
            'document_name' => 'required|string|max:150',
            'is_mandatory' => 'nullable|boolean',
        ]);

        $validated['is_mandatory'] = (bool) $request->input('is_mandatory', true);

        return WorkflowRequirement::create($validated);
    }

    /**
     * Update a workflow requirement.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        if ($user->role->name !== 'Admin_Unit') {
            abort(403, 'Hanya Admin Unit yang dapat mengubah syarat dokumen');
        }

        $requirement = WorkflowRequirement::findOrFail($id);

        // Check authorization
        if ($requirement->workflow->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak memiliki akses ke workflow unit lain');
        }

        $validated = $request->validate([
            'document_name' => 'required|string|max:150',
            'is_mandatory' => 'nullable|boolean',
        ]);

        $validated['is_mandatory'] = (bool) $request->input('is_mandatory', true);

        $requirement->update($validated);

        return $requirement;
    }

    /**
     * Remove a workflow requirement.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();

        if ($user->role->name !== 'Admin_Unit') {
            abort(403, 'Hanya Admin Unit yang dapat menghapus syarat dokumen');
        }

        $requirement = WorkflowRequirement::findOrFail($id);

        // Check authorization
        if ($requirement->workflow->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak memiliki akses ke workflow unit lain');
        }

        $requirement->delete();

        return response()->json(['message' => 'Syarat dokumen berhasil dihapus']);
    }
}
