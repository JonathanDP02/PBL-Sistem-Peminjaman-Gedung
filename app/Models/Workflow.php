<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    protected $fillable = [
        'unit_id',
        'name',
        'description'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function steps()
    {
        return $this->hasMany(WorkflowStep::class);
    }

    public function requirements()
    {
        return $this->hasMany(WorkflowRequirement::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
