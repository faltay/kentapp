<div class="dropdown">
    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
        {{ __('common.actions') }}
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a href="{{ route('admin.pages.edit', $page) }}" class="dropdown-item">
            <i class="ti ti-edit icon me-2"></i>{{ __('common.edit') }}
        </a>
        <div class="dropdown-divider"></div>
        <button type="button"
                class="dropdown-item text-red delete-btn"
                data-url="{{ route('admin.pages.destroy', $page) }}"
                data-confirm="{{ __('admin.pages.confirm_delete') }}">
            <i class="ti ti-trash icon me-2"></i>{{ __('common.delete') }}
        </button>
    </div>
</div>
