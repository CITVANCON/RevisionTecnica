@props([
    'model' => null,
    'placeholder' => 'Seleccionar fecha',
])

<div x-data
     x-init="flatpickr($refs.input, { dateFormat: 'Y-m-d' })"
     class="w-full">
    <input
        x-ref="input"
        type="text"
        {{ $attributes->merge(['class' => 'form-input rounded-md w-full']) }}
        placeholder="{{ $placeholder }}"
        wire:model="{{ $model }}"
    >
</div>
