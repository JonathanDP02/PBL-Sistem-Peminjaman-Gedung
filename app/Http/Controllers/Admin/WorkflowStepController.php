<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkflowStepController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Workflow::query()->withCount(['steps', 'requirements']);

        if ($user->role->name === 'Admin_Unit') {
            $query->where('unit_id', $user->unit_id);
        }

        return response()->json($query->get());
    }

    /**
     * Store a newly created workflow step.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        ]);

        $validated['unit_id'] = Auth::user()->unit_id;
        
        $workflow = Workflow::create($validated);
        
        return response()->json($workflow, 201);
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
