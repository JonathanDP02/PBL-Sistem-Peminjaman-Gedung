@if(Auth::check())
    <x-app-layout title="Validasi Surat Izin">
        @include('booking.validate-content')
    </x-app-layout>
@else
    <x-guest-layout>
        <div class="pt-24 max-w-4xl mx-auto py-8 px-4">
            @include('booking.validate-content')
        </div>
    </x-guest-layout>
@endif
