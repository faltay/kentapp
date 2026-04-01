@props([
    'name',
    'label'    => null,
    'value'    => null,
    'required' => false,
    'rows'     => 3,
    'hint'     => null,
])

<div class="mb-3">
    @if($label)
        <label class="form-label {{ $required ? 'required' : '' }}" for="{{ $name }}">
            {{ $label }}
        </label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    >{{ old($name, $value) }}</textarea>

    @if($hint && !$errors->has($name))
        <small class="form-hint">{{ $hint }}</small>
    @endif

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
