<?php

use App\Models\Language;
use App\Models\Setting;

if (! function_exists('active_languages')) {
    function active_languages(): \Illuminate\Support\Collection
    {
        return Language::getActiveLanguages();
    }
}

if (! function_exists('default_language_code')) {
    function default_language_code(): string
    {
        return Language::getDefaultCode();
    }
}

if (! function_exists('datatables_language')) {
    function datatables_language(): array
    {
        return [
            'info' => __('common.datatables.info'),
            'infoEmpty' => __('common.datatables.info_empty'),
            'infoFiltered' => __('common.datatables.info_filtered'),
            'lengthMenu' => __('common.datatables.length_menu'),
            'loadingRecords' => __('common.datatables.loading'),
            'processing' => __('common.datatables.processing'),
            'search' => __('common.datatables.search'),
            'zeroRecords' => __('common.datatables.zero_records'),
            'emptyTable' => __('common.datatables.empty_table'),
            'paginate' => [
                'first' => __('common.datatables.first'),
                'last' => __('common.datatables.last'),
                'next' => __('common.datatables.next'),
                'previous' => __('common.datatables.previous'),
            ],
        ];
    }
}

if (! function_exists('active_language_codes')) {
    function active_language_codes(): array
    {
        return Language::getActiveCodes();
    }
}

if (! function_exists('app_setting')) {
    function app_setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}
