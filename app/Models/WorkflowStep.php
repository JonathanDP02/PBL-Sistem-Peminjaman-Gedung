<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_id',
        'position_id',
        'step_order',
        'requires_attachment',
    ];

    /**
     * Relasi ke Workflow: Tahapan ini milik alur yang mana.
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * Relasi ke Position: Jabatan mana yang bertugas di tahapan ini (misal: Ketua Jurusan).
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}