<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    /** @use HasFactory<\Database\Factories\UnitFactory> */
    use HasFactory;
    protected $fillable = [
        'parent_id',
        'level',
        'unit_name',
        'description'
    ];

    public function parent()
    {
        return $this->belongsTo(Unit::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Unit::class, 'parent_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }

    public function workflows()
    {
        return $this->hasMany(Workflow::class);
    }
}
