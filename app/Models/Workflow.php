<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workflow extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Relasi ke Unit: Mengetahui unit mana yang memiliki workflow ini.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Relasi ke Steps: Mengambil semua tahapan yang ada di dalam alur ini.
     * Diurutkan berdasarkan 'step_order' agar sesuai urutan birokrasi.
     */
    public function steps(): HasMany
    {
        return $this->hasMany(WorkflowStep::class)->orderBy('step_order', 'asc');
    }
}