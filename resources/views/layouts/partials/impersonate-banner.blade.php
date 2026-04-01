@if(session('impersonating_from'))
<div style="background:#d63939;color:#fff;padding:8px 16px;text-align:center;font-size:14px;font-weight:600;">
    <i class="ti ti-switch-horizontal" style="margin-right:4px;"></i>
    {{ __('admin.impersonate.banner', ['name' => auth()->user()->name, 'admin' => session('impersonating_name')]) }}
    <a href="{{ route('impersonate.stop') }}"
       style="color:#fff;margin-left:12px;text-decoration:underline;font-weight:700;">
        {{ __('admin.impersonate.stop') }}
    </a>
</div>
@endif
