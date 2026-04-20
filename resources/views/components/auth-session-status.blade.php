@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-cyan-400 bg-gray-800 border border-gray-700 rounded-lg p-3 mb-4']) }}>
        {{ $status }}
    </div>
@endif
