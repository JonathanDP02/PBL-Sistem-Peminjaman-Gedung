<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role->name === 'Penyetuju') {
            return app(ApprovalController::class)->dashboard($request);
        }

        $stats = ['approved' => 0, 'pending' => 0, 'rejected' => 0];
        $recentBookings = collect();
        $notifications = collect();

        if ($user->role->name === 'Peminjam') {
            // Statistik
            $stats['approved'] = Booking::where('user_id', $user->id)->where('status', 'Approved')->count();
            $stats['pending'] = Booking::where('user_id', $user->id)->where('status', 'Pending')->count();
            $stats['rejected'] = Booking::where('user_id', $user->id)->whereIn('status', ['Rejected', 'Revising'])->count();

            // Ambil 5 booking terbaru
            $recentBookings = Booking::with('room')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Ambil 5 notifikasi terbaru (dari log booking milik user)
            $notifications = BookingLog::whereHas('booking', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        }

        $view = match ($user->role->name) {
            'Administrator Utama' => 'user.superadmin.dashboard',
            'Administrator Unit' => 'user.admin_unit.dashboard',
            'Peminjam' => 'user.peminjam.dashboard',
            default => 'user.peminjam.dashboard',
        };

        return view($view, compact('stats', 'recentBookings', 'notifications'));
    }
}
