<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Booking::with(['room.building', 'user'])
            ->orderBy('created_at', 'desc');

        if ($user->role->name === 'Administrator Utama') {
            // SuperAdmin can see all bookings
            $bookings = $query->get();
        } elseif ($user->role->name === 'Administrator Unit') {
            // Admin_Unit can see bookings belonging to their unit's rooms or child's rooms
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

            $bookings = $query->whereHas('room', function ($q) use ($unitIds) {
                $q->whereIn('unit_id', $unitIds);
            })->get();
        } else {
            $bookings = collect();
        }

        // Hitung statistik untuk sidebar
        $statusCounts = [
            'Approved' => $bookings->where('status', 'Approved')->count(),
            'Pending' => $bookings->where('status', 'Pending')->count(),
            'Rejected' => $bookings->whereIn('status', ['Rejected', 'Cancelled', 'Revising'])->count(),
        ];

        return view('user.admin.laporan_peminjaman', compact('bookings', 'statusCounts'));
    }
}
