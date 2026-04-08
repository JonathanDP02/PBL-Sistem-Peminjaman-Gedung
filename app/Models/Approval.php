<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'approver_id',
        'step_id',
        'approval_status',
        'notes',
        'signature_image',
        'qr_code',
        'approved_at',
        'attempt',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function step(): BelongsTo
    {
        return $this->belongsTo(WorkflowStep::class, 'step_id');
    }
}
