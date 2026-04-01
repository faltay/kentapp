<div class="dropdown">
    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
        {{ __('common.actions') }}
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a href="{{ route('admin.posts.edit', $post) }}" class="dropdown-item">
            <i class="ti ti-edit icon me-2"></i>{{ __('common.edit') }}
        </a>
        <button type="button"
                class="dropdown-item toggle-publish-btn"
                data-url="{{ route('admin.posts.toggle-publish', $post) }}"
                data-published="{{ $post->is_published ? '1' : '0' }}">
            <i class="ti ti-{{ $post->is_published ? 'eye-off' : 'eye' }} icon me-2"></i>{{ $post->is_published ? __('admin.posts.unpublish') : __('admin.posts.publish') }}
        </button>
        <div class="dropdown-divider"></div>
        <button type="button"
                class="dropdown-item text-red delete-btn"
                data-url="{{ route('admin.posts.destroy', $post) }}"
                data-confirm="{{ __('admin.posts.confirm_delete') }}">
            <i class="ti ti-trash icon me-2"></i>{{ __('common.delete') }}
        </button>
    </div>
</div>
