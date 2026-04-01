<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subject')</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f5f7fa; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
        .wrapper { width: 100%; background-color: #f5f7fa; padding: 32px 16px; }
        .card { max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        .header { background-color: #206bc4; padding: 28px 40px; text-align: center; }
        .header-logo { color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: -0.3px; text-decoration: none; }
        .body { padding: 36px 40px; color: #354052; font-size: 15px; line-height: 1.6; }
        .body h1 { margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #1a2332; }
        .body p { margin: 0 0 16px; }
        .body ul { margin: 0 0 16px; padding-left: 20px; }
        .body ul li { margin-bottom: 6px; }
        .btn { display: inline-block; padding: 11px 28px; background-color: #206bc4; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600; margin-top: 8px; }
        .divider { border: none; border-top: 1px solid #e8ecf0; margin: 24px 0; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-danger  { background-color: #fee2e2; color: #991b1b; }
        .meta { background-color: #f8fafc; border-radius: 6px; padding: 16px 20px; margin: 20px 0; }
        .meta-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .meta-row:last-child { margin-bottom: 0; }
        .meta-label { color: #6b7280; }
        .meta-value { font-weight: 600; color: #1a2332; }
        .footer { padding: 20px 40px; background-color: #f8fafc; border-top: 1px solid #e8ecf0; text-align: center; color: #9ca3af; font-size: 12px; }
        .footer a { color: #6b7280; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">
        <div class="header">
            <span class="header-logo">{{ config('app.name') }}</span>
        </div>
        <div class="body">
            @yield('content')
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
        </div>
    </div>
</div>
</body>
</html>
