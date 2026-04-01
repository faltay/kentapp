@props([
    'name',
    'label'       => null,
    'type'        => 'text',
    'value'       => null,
    'required'    => false,
    'placeholder' => '',
    'hint'        => null,
])

<div class="mb-3">
    @if($label)
        <label class="form-label {{ $required ? 'required' : '' }}" for="{{ $name }}">
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    >

    @if($hint && !$errors->has($name))
        <small class="form-hint">{{ $hint }}</small>
    @endif

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
