<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'booking_id',
        'approver_id',
        'step_id',
        'approval_status',
        'notes',
        'signature_image',
        'qr_code',
        'attempt'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function step()
    {
        return $this->belongsTo(WorkflowStep::class, 'step_id');
    }
}
