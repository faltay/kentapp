@props([
    'name',
    'label'    => null,
    'options'  => [],
    'selected' => null,
    'required' => false,
    'empty'    => null,
    'hint'     => null,
])

<div class="mb-3">
    @if($label)
        <label class="form-label {{ $required ? 'required' : '' }}" for="{{ $name }}">
            {{ $label }}
        </label>
    @endif

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-select' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    >
        @if($empty !== null)
            <option value="">{{ $empty }}</option>
        @endif

        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

    @if($hint && !$errors->has($name))
        <small class="form-hint">{{ $hint }}</small>
    @endif

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
