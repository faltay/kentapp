<?php

namespace App\Services;

use App\Models\AiConfig;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    private AiConfig $config;

    public function __construct()
    {
        $this->config = AiConfig::instance();
    }

    /**
     * Verilen mesaj geçmişini kullanarak AI'dan yanıt al.
     *
     * @param  array  $history  [['role' => 'user'|'assistant', 'content' => '...'], ...]
     * @return string|null  AI yanıtı veya hata durumunda null
     */
    public function getReply(array $history): ?string
    {
        if (!$this->config->api_key) {
            return null;
        }

        try {
            return match ($this->config->provider) {
                'anthropic' => $this->callAnthropic($history),
                default     => $this->callOpenAi($history),
            };
        } catch (\Throwable $e) {
            Log::error('AiService error', [
                'provider' => $this->config->provider,
                'message'  => $e->getMessage(),
            ]);
            return null;
        }
    }

    private function callOpenAi(array $history): ?string
    {
        $messages = [];

        if ($this->config->system_prompt) {
            $messages[] = ['role' => 'system', 'content' => $this->config->system_prompt];
        }

        foreach ($history as $msg) {
            $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
        }

        $response = Http::withToken($this->config->api_key)
            ->timeout(30)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model'       => $this->config->model,
                'messages'    => $messages,
                'temperature' => $this->config->temperature,
                'max_tokens'  => $this->config->max_tokens,
            ]);

        if (!$response->successful()) {
            Log::error('OpenAI API error', ['body' => $response->body()]);
            return null;
        }

        return $response->json('choices.0.message.content');
    }

    private function callAnthropic(array $history): ?string
    {
        // Anthropic: mesajlar user/assistant arasında dönüşümlü olmalı
        $messages = $this->normalizeForAnthropic($history);

        $payload = [
            'model'      => $this->config->model,
            'max_tokens' => $this->config->max_tokens,
            'messages'   => $messages,
        ];

        if ($this->config->system_prompt) {
            $payload['system'] = $this->config->system_prompt;
        }

        $response = Http::withHeaders([
            'x-api-key'         => $this->config->api_key,
            'anthropic-version' => '2023-06-01',
        ])->timeout(30)->post('https://api.anthropic.com/v1/messages', $payload);

        if (!$response->successful()) {
            Log::error('Anthropic API error', ['body' => $response->body()]);
            return null;
        }

        return $response->json('content.0.text');
    }

    /**
     * Anthropic ardışık aynı role mesajlara izin vermez.
     * Aynı role'dan art arda gelenler birleştirilir.
     */
    private function normalizeForAnthropic(array $history): array
    {
        // Sadece user/assistant rol mesajlarını al
        $filtered = array_filter($history, fn($m) => in_array($m['role'], ['user', 'assistant']));
        $filtered = array_values($filtered);

        $normalized = [];
        foreach ($filtered as $msg) {
            if (!empty($normalized) && end($normalized)['role'] === $msg['role']) {
                $normalized[count($normalized) - 1]['content'] .= "\n" . $msg['content'];
            } else {
                $normalized[] = $msg;
            }
        }

        // Anthropic ilk mesajın user olmasını zorunlu kılar
        if (!empty($normalized) && $normalized[0]['role'] !== 'user') {
            array_shift($normalized);
        }

        return $normalized;
    }

    public function isEnabled(): bool
    {
        return $this->config->ai_enabled && !empty($this->config->api_key);
    }

    public function getDailyLimit(): int
    {
        return $this->config->daily_message_limit ?? 20;
    }

    public function getWelcomeMessage(): ?string
    {
        return $this->config->welcome_message ?: null;
    }
}
