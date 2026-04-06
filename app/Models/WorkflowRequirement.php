<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowRequirement extends Model
{
    protected $fillable = [
        'workflow_id',
        'document_name',
        'is_mandatory'
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function attachments()
    {
        return $this->hasMany(BookingAttachment::class, 'requirement_id');
    }
}
