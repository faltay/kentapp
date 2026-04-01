<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <div class="navbar-nav flex-row ms-auto">

            {{-- Language Selector --}}
            <div class="nav-item dropdown me-2">
                <a href="#" class="nav-link px-0 d-flex align-items-center gap-1 text-reset" data-bs-toggle="dropdown">
                    <i class="ti ti-world icon"></i>
                    <span class="d-none d-sm-inline text-uppercase fw-medium" style="font-size:.75rem">{{ app()->getLocale() }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <a class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() === $localeCode ? 'active' : '' }}"
                           href="{{ route('lang.switch', $localeCode) }}">
                            <span class="text-uppercase fw-medium" style="font-size:.7rem;min-width:1.5rem">{{ $localeCode }}</span>
                            {{ $properties['native'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Notifications --}}
            <div class="nav-item dropdown d-none d-md-flex me-3">
                <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                   aria-label="{{ __('common.notifications') }}">
                    <i class="ti ti-bell icon"></i>
                    <span class="badge bg-red"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card" style="min-width:22rem">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h3 class="card-title">{{ __('common.notifications') }}</h3>
                            <span class="badge bg-blue-lt ms-auto">{{ __('common.coming_soon') }}</span>
                        </div>
                        <div class="card-body text-center py-4 text-secondary">
                            <i class="ti ti-bell-off icon mb-2" style="font-size:2rem;opacity:.3"></i>
                            <p class="mb-0 small">{{ __('common.no_notifications') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- User Dropdown --}}
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown"
                   aria-label="{{ __('common.user_menu') }}">
                    <span class="avatar avatar-sm"
                          style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=066fd1&color=fff&bold=true&size=64)"></span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ auth()->user()->name }}</div>
                        <div class="mt-1 small text-secondary">{{ __('common.super_admin_role') }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <div class="dropdown-header">
                        <div class="fw-medium">{{ auth()->user()->name }}</div>
                        <div class="text-secondary small">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                            <i class="ti ti-logout icon"></i>
                            {{ __('common.logout') }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>
