<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'workflow_id',
        'event_name',
        'event_description',
        'event_scope',
        'booking_date',
        'booking_end_date',
        'start_time',
        'end_time',
        'current_step',
        'status',
        'revision_count',
        'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'booking_end_date' => 'date',
            'start_time' => 'datetime:H:i:s',
            'end_time' => 'datetime:H:i:s',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Booking $booking) {
            if (empty($booking->booking_end_date)) {
                $booking->booking_end_date = $booking->booking_date;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(BookingAttachment::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(BookingLog::class);
    }

    public function bookingSteps(): HasMany
    {
        return $this->hasMany(BookingStep::class)->orderBy('step_order', 'asc');
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->status === 'Approved') {
            return 100;
        }

        if ($this->status === 'Rejected' || $this->status === 'Cancelled') {
            return 0;
        }

        // Prefer instantiated booking_steps chain; fallback to workflow template steps.
        $totalSteps = $this->bookingSteps->count();
        if ($totalSteps === 0) {
            $totalSteps = $this->workflow?->steps?->count() ?? 0;
        }

        if ($totalSteps === 0) {
            return 0;
        }

        // current_step starts at 1. If it's at step 1, 0% is done.
        $completedSteps = $this->current_step - 1;

        return (int) round(($completedSteps / $totalSteps) * 100);
    }

    public function getDurationString(): string
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        if ($end->lt($start)) {
            $end->addDay();
        }

        $totalMinutes = $start->diffInMinutes($end, true);

        if ($totalMinutes < 60) {
            return $totalMinutes.' Menit';
        }

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        if ($minutes > 0) {
            return $hours.' Jam '.$minutes.' Menit';
        }

        return $hours.' Jam';
    }

    public function getFormattedDateRange(bool $showDayOfWeek = true): string
    {
        $startDate = Carbon::parse($this->booking_date);
        $endDate = Carbon::parse($this->booking_end_date);

        if ($startDate->format('Y-m-d') === $endDate->format('Y-m-d')) {
            return $showDayOfWeek
                ? $startDate->translatedFormat('l, d M Y')
                : $startDate->translatedFormat('d M Y');
        }

        return $startDate->translatedFormat('d M Y').' - '.$endDate->translatedFormat('d M Y');
    }
}
