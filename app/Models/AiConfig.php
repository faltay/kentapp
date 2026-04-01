<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiConfig extends Model
{
    protected $fillable = [
        'provider',
        'api_key',
        'model',
        'temperature',
        'max_tokens',
        'ai_enabled',
        'daily_message_limit',
        'welcome_message',
        'system_prompt',
    ];

    protected $casts = [
        'ai_enabled'  => 'boolean',
        'temperature' => 'float',
        'max_tokens'  => 'integer',
        'daily_message_limit' => 'integer',
    ];

    protected $hidden = ['api_key'];

    public const PROVIDERS = [
        'openai'    => 'OpenAI',
        'anthropic' => 'Anthropic (Claude)',
    ];

    public const MODELS = [
        'openai' => [
            'gpt-4o'       => 'GPT-4o',
            'gpt-4o-mini'  => 'GPT-4o Mini',
            'gpt-4-turbo'  => 'GPT-4 Turbo',
        ],
        'anthropic' => [
            'claude-sonnet-4-6'          => 'Claude Sonnet 4.6',
            'claude-haiku-4-5-20251001'  => 'Claude Haiku 4.5',
            'claude-opus-4-6'            => 'Claude Opus 4.6',
        ],
    ];

    /** Tekil config kaydını döndürür, yoksa oluşturur. */
    public static function instance(): self
    {
        return static::firstOrCreate([], [
            'provider'    => 'openai',
            'model'       => 'gpt-4o',
            'temperature' => 0.70,
            'max_tokens'  => 1000,
            'ai_enabled'  => false,
            'daily_message_limit' => 20,
        ]);
    }

    public function getMaskedApiKeyAttribute(): string
    {
        if (!$this->api_key) return '';
        $len = strlen($this->api_key);
        if ($len <= 8) return str_repeat('•', $len);
        return substr($this->api_key, 0, 4) . str_repeat('•', $len - 8) . substr($this->api_key, -4);
    }
}
