<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\BookingLog;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer(['layouts.header', 'layouts.app'], function ($view) {
            if (auth()->check()) {
                $user = auth()->user();
                $role = $user->role?->name;

                if ($role === 'Penyetuju') {
                    // For Approver: Bookings waiting for their approval (Meja Kerja logic)
                    $notifications = Booking::where('status', 'Pending')
                        ->whereHas('workflow.steps', function ($q) use ($user) {
                            $q->where('position_id', $user->position_id)
                                ->whereColumn('step_order', 'bookings.current_step');
                        })
                        ->with(['room.building', 'user'])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get()
                        ->map(function ($b) {
                            return (object) [
                                'id' => $b->id,
                                'booking_id' => $b->id,
                                'action' => 'PENDING REQUEST',
                                'notes' => "Peminjaman baru dari {$b->user->name}",
                                'created_at' => $b->created_at,
                                'booking' => $b,
                            ];
                        });
                } else {
                    // For User (Peminjam): Updates on their own bookings
                    $notifications = BookingLog::whereHas('booking', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                        ->with(['booking.room', 'actor'])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                }

                $view->with('globalNotifications', $notifications);
            }
        });
    }
}
