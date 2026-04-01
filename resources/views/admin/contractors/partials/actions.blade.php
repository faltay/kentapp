<div class="d-flex gap-1">
    <a href="{{ route('admin.contractors.show', $contractor) }}" class="btn btn-sm btn-ghost-secondary" title="{{ __('common.view') }}">
        <i class="ti ti-eye icon"></i>
    </a>
    <a href="{{ route('admin.contractors.edit', $contractor) }}" class="btn btn-sm btn-ghost-primary" title="{{ __('common.edit') }}">
        <i class="ti ti-pencil icon"></i>
    </a>
    <button type="button" class="btn btn-sm btn-ghost-danger btn-delete"
            data-url="{{ route('admin.contractors.destroy', $contractor) }}"
            data-name="{{ $contractor->name }}"
            title="{{ __('common.delete') }}">
        <i class="ti ti-trash icon"></i>
    </button>
</div>
