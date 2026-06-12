<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Unit;
use App\Models\Workflow;
use Illuminate\Support\Facades\Auth;

class AdminUnitController extends Controller
{
    public function manajemenRuangan()
    {
        $user = Auth::user();
        $unitIds = [$user->unit_id];

        // Dapatkan seluruh child unit (descendants)
        if ($user->unit) {
            $childIds = Unit::where('parent_id', $user->unit_id)->pluck('id')->toArray();
            $unitIds = array_merge($unitIds, $childIds);
            if (! empty($childIds)) {
                $grandchildIds = Unit::whereIn('parent_id', $childIds)->pluck('id')->toArray();
                $unitIds = array_merge($unitIds, $grandchildIds);
            }
        }

        $unitIds = array_unique($unitIds);

        $rooms = Room::whereIn('unit_id', $unitIds)->with(['building', 'workflows'])->get();
        $workflows = Workflow::where('unit_id', $user->unit_id)->get();

        return view('user.admin_unit.manajemenRuangan', compact('rooms', 'workflows'));
    }

    public function pemblokiranRuangan()
    {
        $user = Auth::user();
        $unitIds = [$user->unit_id];

        // Dapatkan seluruh child unit (descendants)
        if ($user->unit) {
            $childIds = Unit::where('parent_id', $user->unit_id)->pluck('id')->toArray();
            $unitIds = array_merge($unitIds, $childIds);
            if (! empty($childIds)) {
                $grandchildIds = Unit::whereIn('parent_id', $childIds)->pluck('id')->toArray();
                $unitIds = array_merge($unitIds, $grandchildIds);
            }
        }

        $unitIds = array_unique($unitIds);

        $rooms = Room::whereIn('unit_id', $unitIds)->with('building')->get();

        // Mengambil jadwal maintenance mendatang dan mengelompokkannya
        $rawBlockings = Booking::with('room')
            ->whereHas('room', fn ($q) => $q->whereIn('unit_id', $unitIds))
            ->where('event_name', 'LIKE', '[MAINTENANCE HARD-LOCK]%')
            ->whereDate('booking_date', '>=', now())
            ->orderBy('booking_date', 'asc')
            ->get();

        $activeBlockings = $rawBlockings->groupBy('event_name')->map(function ($group) {
            $first = $group->first();

            return (object) [
                'id' => $first->id,
                'event_name' => $first->event_name,
                'room' => $first->room,
                'event_description' => $first->event_description,
                'start_date' => $group->min('booking_date'),
                'end_date' => $group->max('booking_date'),
                'is_range' => $group->count() > 1,
                'count' => $group->count(),
            ];
        })->take(5);

        return view('user.admin_unit.pemblokiranRuangan', compact('rooms', 'activeBlockings'));
    }
}
