<div class="btn-list flex-nowrap">
    <button class="btn btn-sm btn-ghost-primary btn-toggle-active"
            data-url="{{ route('admin.languages.toggle-active', $language) }}"
            title="{{ $language->is_active ? __('common.inactive') : __('common.active') }}">
        <i class="ti ti-{{ $language->is_active ? 'eye-off' : 'eye' }} icon"></i>
    </button>
    <a href="{{ route('admin.languages.edit', $language) }}" class="btn btn-sm btn-ghost-primary" title="{{ __('common.edit') }}">
        <i class="ti ti-edit icon"></i>
    </a>
    @unless($language->is_default)
    <button class="btn btn-sm btn-ghost-danger btn-delete"
            data-url="{{ route('admin.languages.destroy', $language) }}"
            title="{{ __('common.delete') }}">
        <i class="ti ti-trash icon"></i>
    </button>
    @endunless
</div>
