<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowStep extends Model
{
    protected $fillable = [
        'workflow_id',
        'position_id',
        'step_order',
        'requires_attachment'
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
