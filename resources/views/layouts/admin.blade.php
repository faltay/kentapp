<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('common.admin_panel')) — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root { --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; }
        body { font-feature-settings: "cv03","cv04","cv11"; }
    </style>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
    @stack('styles')
</head>
<body class="d-flex flex-column">
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler-theme.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
@include('layouts.partials.impersonate-banner')
<div class="page">

    {{-- Sidebar --}}
    @include('layouts.partials.admin-sidebar')

    <div class="page-wrapper">

        {{-- Top Header --}}
        @include('layouts.partials.admin-header')

        {{-- Page Content --}}
        <div class="page-body">
            <div class="container-xl">

                {{-- Alerts --}}
                @include('layouts.partials.alerts')

                {{-- Page Content --}}
                @yield('content')

            </div>
        </div>

        {{-- Footer --}}
        @include('layouts.partials.footer')

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
{{-- Global translations for JS --}}
<script>
    window.trans = {
        success: @json(__('common.success')),
        error: @json(__('common.error')),
        validation_error: @json(__('common.validation_error')),
        forbidden: @json(__('common.forbidden')),
        not_found: @json(__('common.not_found')),
        confirm_delete_title: @json(__('common.confirm_delete_title')),
        confirm_delete_text: @json(__('common.confirm_delete_text')),
        delete: @json(__('common.delete')),
        cancel: @json(__('common.cancel')),
        saving: @json(__('common.saving')),
        datatables_language: @json(datatables_language()),
    };
</script>
@stack('scripts')
</body>
</html>
