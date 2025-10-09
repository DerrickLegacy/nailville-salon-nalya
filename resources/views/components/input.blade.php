@props(['disabled' => false])
{{-- <?php var_dump($attributes); ?> --}}

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-input w-full']) !!}>
