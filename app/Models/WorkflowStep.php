<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_id',
        'position_id',
        'step_order',
        'requires_attachment',
    ];

    protected function casts(): array
    {
        return [
            'requires_attachment' => 'boolean',
        ];
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'step_id');
    }
}
