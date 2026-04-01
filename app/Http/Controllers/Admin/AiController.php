<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\AiConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiController extends BaseController
{
    // ── Bağlantı Ayarları ───────────────────────────────────────
    public function settings()
    {
        $config = AiConfig::instance();
        return view('admin.ai.settings', compact('config'));
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $request->validate([
            'provider'    => ['required', 'in:openai,anthropic'],
            'api_key'     => ['nullable', 'string', 'max:500'],
            'model'       => ['required', 'string', 'max:100'],
            'temperature' => ['required', 'numeric', 'min:0', 'max:2'],
            'max_tokens'  => ['required', 'integer', 'min:100', 'max:8000'],
            'ai_enabled'  => ['boolean'],
            'daily_message_limit' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        $config = AiConfig::instance();

        $data = $request->only([
            'provider', 'model', 'temperature', 'max_tokens', 'daily_message_limit',
        ]);
        $data['ai_enabled'] = $request->boolean('ai_enabled');

        // API key: boş gönderilirse mevcut saklanır
        if ($request->filled('api_key')) {
            $data['api_key'] = $request->input('api_key');
        }

        $config->update($data);

        return $this->success('AI bağlantı ayarları kaydedildi.');
    }

    // ── Prompt Ayarları ─────────────────────────────────────────
    public function prompt()
    {
        $config = AiConfig::instance();
        return view('admin.ai.prompt', compact('config'));
    }

    public function updatePrompt(Request $request): JsonResponse
    {
        $request->validate([
            'system_prompt'   => ['nullable', 'string', 'max:8000'],
            'welcome_message' => ['nullable', 'string', 'max:500'],
        ]);

        AiConfig::instance()->update($request->only(['system_prompt', 'welcome_message']));

        return $this->success('Prompt ayarları kaydedildi.');
    }
}
