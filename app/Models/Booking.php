<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'workflow_id',
        'event_name',
        'event_description',
        'booking_date',
        'start_time',
        'end_time',
        'current_step',
        'status',
        'revision_count'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function attachments()
    {
        return $this->hasMany(BookingAttachment::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }
}
