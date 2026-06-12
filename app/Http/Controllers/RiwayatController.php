<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role->name === 'Penyetuju') {
            return app(ApprovalController::class)->history($request);
        }

        if (in_array($user->role->name, ['Administrator Utama', 'Administrator Unit'])) {
            return redirect()->route('laporan');
        }

        $sort = $request->query('sort', 'latest');
        $query = Booking::with(['room.building', 'user'])
            ->where('user_id', $user->id);

        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'status') {
            $query->orderBy('status', 'asc')->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $bookings = $query->get();

        // Hitung statistik untuk sidebar
        $statusCounts = [
            'Approved' => $bookings->where('status', 'Approved')->count(),
            'Pending' => $bookings->where('status', 'Pending')->count(),
            'Rejected' => $bookings->whereIn('status', ['Rejected', 'Cancelled', 'Revising'])->count(),
        ];

        return view('user.peminjam.riwayat', compact('bookings', 'statusCounts'));
    }
}
