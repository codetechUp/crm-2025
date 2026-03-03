@props([
    'name'        => null,
    'controlName' => '',
])

@php
    $fieldName = $name ?? $controlName;
@endphp

@if($fieldName && $errors->has($fieldName))
    <p {{ $attributes->merge(['class' => 'mt-1 text-xs italic text-red-600']) }}>
        {{ $errors->first($fieldName) }}
    </p>
@else
    <v-error-message
        {{ $attributes }}
        name="{{ $fieldName }}"
        v-slot="{ message }"
    >
        <p
            {{ $attributes->merge(['class' => 'mt-1 text-xs italic text-red-600']) }}
            v-text="message"
        >
        </p>
    </v-error-message>
@endif
