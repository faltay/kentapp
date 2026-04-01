<div class="dropdown">
    <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
        {{ __('common.actions') }}
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <a href="{{ route('admin.users.show', $user) }}" class="dropdown-item">
            <i class="ti ti-eye icon me-2"></i>{{ __('common.view') }}
        </a>
        <a href="{{ route('admin.users.edit', $user) }}" class="dropdown-item">
            <i class="ti ti-edit icon me-2"></i>{{ __('common.edit') }}
        </a>
        @if(auth()->id() !== $user->id && !$user->hasRole('super_admin'))
        <div class="dropdown-divider"></div>
        <form action="{{ route('admin.users.impersonate', $user) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="dropdown-item">
                <i class="ti ti-switch-horizontal icon me-2"></i>{{ __('admin.impersonate.login_as') }}
            </button>
        </form>
        @endif
        @if(auth()->id() !== $user->id)
        <div class="dropdown-divider"></div>
        <button type="button"
                class="dropdown-item text-red btn-delete-user"
                data-url="{{ route('admin.users.destroy', $user) }}">
            <i class="ti ti-trash icon me-2"></i>{{ __('common.delete') }}
        </button>
        @endif
    </div>
</div>
