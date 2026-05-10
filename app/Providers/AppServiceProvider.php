<?php

namespace App\Providers;

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
                $notifications = \App\Models\BookingLog::whereHas('booking', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->with(['booking.room', 'actor'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

                $view->with('globalNotifications', $notifications);
            }
        });
    }
}
