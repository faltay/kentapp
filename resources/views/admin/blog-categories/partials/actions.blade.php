<div class="dropdown">
    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
        {{ __('common.actions') }}
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a href="{{ route('admin.blog-categories.edit', $category) }}" class="dropdown-item">
            <i class="ti ti-edit icon me-2"></i>{{ __('common.edit') }}
        </a>
        <button type="button"
                class="dropdown-item toggle-active-btn"
                data-url="{{ route('admin.blog-categories.toggle-active', $category) }}">
            <i class="ti ti-{{ $category->is_active ? 'eye-off' : 'eye' }} icon me-2"></i>{{ $category->is_active ? __('admin.blog_categories.deactivate') : __('admin.blog_categories.activate') }}
        </button>
        <div class="dropdown-divider"></div>
        <button type="button"
                class="dropdown-item text-red delete-btn"
                data-url="{{ route('admin.blog-categories.destroy', $category) }}"
                data-confirm="{{ __('admin.blog_categories.confirm_delete') }}">
            <i class="ti ti-trash icon me-2"></i>{{ __('common.delete') }}
        </button>
    </div>
</div>
