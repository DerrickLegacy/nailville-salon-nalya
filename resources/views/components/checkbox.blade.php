@props(['name' => null, 'id' => null, 'checked' => false, 'value' => '1'])

<input type="checkbox" name="{{ $name }}" id="{{ $id ?? $name }}" value="{{ $value }}"
    {{ $checked ? 'checked' : '' }}
    {{ $attributes->merge(['class' => 'rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500']) }}>
