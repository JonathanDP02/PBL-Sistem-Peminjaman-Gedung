<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingAttachment extends Model
{
    protected $fillable = [
        'booking_id',
        'requirement_id',
        'uploader_id',
        'document_type',
        'file_path'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function requirement()
    {
        return $this->belongsTo(WorkflowRequirement::class, 'requirement_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }
}
