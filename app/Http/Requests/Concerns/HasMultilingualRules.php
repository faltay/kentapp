<?php

namespace App\Http\Requests\Concerns;

trait HasMultilingualRules
{
    /**
     * Aktif dillere göre çok dilli alan validation kuralları üretir.
     *
     * @param  string  $field        Alan adı (name, description, title vb.)
     * @param  string  $baseRule     Ana alan kuralı (örn: 'required|array')
     * @param  string  $defaultRule  Default dil kuralı (örn: 'required|string|max:255')
     * @param  string  $otherRule    Diğer diller kuralı (örn: 'nullable|string|max:255')
     */
    protected function translatableRules(
        string $field,
        string $baseRule = 'required|array',
        string $defaultRule = 'required|string|max:255',
        string $otherRule = 'nullable|string|max:255'
    ): array {
        $defaultCode = default_language_code();
        $languages = active_language_codes();

        $rules = [
            $field => $baseRule,
        ];

        foreach ($languages as $code) {
            $rules["{$field}.{$code}"] = ($code === $defaultCode) ? $defaultRule : $otherRule;
        }

        // Wildcard — DB'de kayıtlı olmayan diller için de esneklik
        $rules["{$field}.*"] = $otherRule;

        return $rules;
    }
}
