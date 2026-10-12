@props(['type' => 'info'])

@php
    $styles = [
        'success' => 'border-green-300 bg-green-50 text-green-800',
        'danger'  => 'border-red-300 bg-red-50 text-red-800',
        'warning' => 'border-amber-300 bg-amber-50 text-amber-800',
        'info'    => 'border-sky-300 bg-sky-50 text-sky-800',
    ];
@endphp

<div role="alert" {{ $attributes->merge(['class' => 'mb-4 rounded-lg border px-4 py-3 text-sm '.($styles[$type] ?? $styles['info'])]) }}>
    {{ $slot }}
</div>
