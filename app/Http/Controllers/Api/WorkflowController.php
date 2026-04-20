<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workflow;

class WorkflowController extends Controller
{
    public function requirements(string $id)
    {
        $workflow = Workflow::with('requirements')->findOrFail($id);

        $requirements = $workflow->requirements->map(function ($req) {
            return [
                'document_name' => $req->document_name,
                'is_mandatory' => (bool)$req->is_mandatory,
            ];
        });

        return response()->json($requirements);
    }
}
