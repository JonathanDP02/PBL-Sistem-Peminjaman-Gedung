<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'name',
        'category',
        'quantity',
        'status'
    ];

    // Relasi: 1 Fasilitas dimiliki oleh 1 Ruangan
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}