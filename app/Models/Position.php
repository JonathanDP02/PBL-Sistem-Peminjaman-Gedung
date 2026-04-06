<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'unit_id',
        'name'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function workflowSteps()
    {
        return $this->hasMany(WorkflowStep::class);
    }
}
