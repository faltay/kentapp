<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
                aria-controls="sidebar-menu" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Brand --}}
        <div class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <span class="avatar avatar-sm rounded bg-primary text-white">
                    <i class="ti ti-building-community icon"></i>
                </span>
                <span class="fw-bold text-white text-truncate" style="max-width:140px">
                    {{ config('app.name') }}
                </span>
            </a>
        </div>

        {{-- Mobile User --}}
        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown">
                    <span class="avatar avatar-sm"
                          style="background-image: url(https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=066fd1&color=fff&bold=true&size=64)"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">{{ __('common.logout') }}</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Nav Menu --}}
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">

                <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-dashboard icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('common.dashboard') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-users icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.users.title') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.contractors.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.contractors.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-helmet icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.contractors.title') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.agents.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.agents.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-user-star icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.agents.title') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.land-owners.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.land-owners.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-home-2 icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.land_owners.title') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.listings.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.listings.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-building-estate icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.listings.title') }}</span>
                    </a>
                </li>


                <li class="nav-item {{ request()->routeIs('admin.credit-packages.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.credit-packages.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-coins icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.credit_packages.title') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.credit-transactions.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.credit-transactions.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-coin icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.credit_transactions.title') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.payments.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-credit-card icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.payments.title') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.reviews.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-star icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.reviews.title') }}</span>
                    </a>
                </li>

                {{-- Content Section --}}
                <li class="nav-item mt-3 mb-1">
                    <small class="nav-link text-uppercase text-muted fw-bold"
                           style="font-size:.625rem; letter-spacing:.1em; pointer-events:none;">
                        {{ __('common.section_content') }}
                    </small>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.blog-categories.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-category icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.blog_categories.title') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.posts.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-article icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.posts.title') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.pages.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-file-text icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.pages.title') }}</span>
                    </a>
                </li>

                {{-- AI Section --}}
                <li class="nav-item mt-3 mb-1">
                    <small class="nav-link text-uppercase text-muted fw-bold"
                           style="font-size:.625rem; letter-spacing:.1em; pointer-events:none;">
                        AI Asistan
                    </small>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.ai.conversations.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.ai.conversations.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-messages icon"></i>
                        </span>
                        <span class="nav-link-title">Konuşmalar</span>
                        @php $aiUnread = \App\Models\ChatConversation::where('unread_count', '>', 0)->count(); @endphp
                        @if($aiUnread > 0)
                            <span class="badge bg-red ms-auto">{{ $aiUnread }}</span>
                        @endif
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.ai.settings') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.ai.settings') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-robot icon"></i>
                        </span>
                        <span class="nav-link-title">AI Ayarları</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.ai.prompt') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.ai.prompt') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-prompt icon"></i>
                        </span>
                        <span class="nav-link-title">Prompt Ayarları</span>
                    </a>
                </li>

                {{-- System Section --}}
                <li class="nav-item mt-3 mb-1">
                    <small class="nav-link text-uppercase text-muted fw-bold"
                           style="font-size:.625rem; letter-spacing:.1em; pointer-events:none;">
                        {{ __('common.section_system') }}
                    </small>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.languages.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-language icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.languages.title') }}</span>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.settings.edit') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-settings icon"></i>
                        </span>
                        <span class="nav-link-title">{{ __('admin.settings.title') }}</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</aside>
