@if(Auth::check())
    <x-app-layout title="Scanner QR Code">
        @include('booking.scan-content')
    </x-app-layout>
@else
    <x-guest-layout>
        <div class="pt-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-screen">
            @include('booking.scan-content')
        </div>
    </x-guest-layout>
@endif
