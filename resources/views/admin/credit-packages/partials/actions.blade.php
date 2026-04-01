<div class="btn-list flex-nowrap">
    <a href="{{ route('admin.credit-packages.edit', $package) }}" class="btn btn-sm btn-ghost-secondary" title="{{ __('common.edit') }}">
        <i class="ti ti-edit icon"></i>
    </a>
    <button class="btn btn-sm btn-ghost-danger btn-delete-credit-package"
            data-url="{{ route('admin.credit-packages.destroy', $package) }}"
            title="{{ __('common.delete') }}">
        <i class="ti ti-trash icon"></i>
    </button>
</div>
