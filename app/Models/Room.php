<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'building_id',
        'unit_id',
        'room_name',
        'capacity',
        'description'
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
