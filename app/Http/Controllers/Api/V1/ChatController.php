<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Services\AiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends ApiController
{
    public function __construct(private AiService $ai) {}

    /**
     * GET /chat
     * Kullanıcının aktif konuşmasını döndürür (yoksa oluşturur).
     * Mesaj geçmişini ve karşılama mesajını içerir.
     */
    public function conversation(Request $request): JsonResponse
    {
        $user = $request->user();

        $conversation = ChatConversation::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'open'],
            ['ai_enabled' => true, 'unread_count' => 0]
        );

        $query = $conversation->messages()->orderBy('created_at');

        if ($afterId = $request->integer('after_id', 0)) {
            $query->where('id', '>', $afterId);
        }

        $messages = $query->get()->map(fn($m) => $this->formatMessage($m));

        return $this->success([
            'conversation_id' => $conversation->id,
            'ai_enabled'      => $conversation->ai_enabled,
            'messages'        => $messages,
            'welcome_message' => (!$afterId && $messages->isEmpty()) ? $this->ai->getWelcomeMessage() : null,
        ]);
    }

    /**
     * POST /chat/message
     * Kullanıcı mesajını kaydeder, AI yanıtı alır.
     */
    public function message(Request $request): JsonResponse
    {
        $request->validate([
            'content'         => ['required', 'string', 'max:2000'],
            'conversation_id' => ['nullable', 'integer'],
        ]);

        $user = $request->user();

        // Konuşmayı bul veya oluştur
        $conversation = $request->conversation_id
            ? ChatConversation::where('id', $request->conversation_id)
                ->where('user_id', $user->id)
                ->first()
            : null;

        if (!$conversation) {
            $conversation = ChatConversation::firstOrCreate(
                ['user_id' => $user->id, 'status' => 'open'],
                ['ai_enabled' => true, 'unread_count' => 0]
            );
        }

        if (!$conversation->isOpen()) {
            return $this->error('Bu konuşma kapalı.', 422);
        }

        // Günlük mesaj limiti kontrolü
        if ($this->ai->isEnabled() && $conversation->ai_enabled) {
            $todayCount = $conversation->messages()
                ->where('role', ChatMessage::ROLE_USER)
                ->whereDate('created_at', today())
                ->count();

            if ($todayCount >= $this->ai->getDailyLimit()) {
                return $this->error('Günlük mesaj limitine ulaştınız.', 429);
            }
        }

        DB::transaction(function () use ($request, $conversation, $user, &$userMsg, &$aiMessage) {
            // Kullanıcı mesajını kaydet
            $userMsg = $conversation->messages()->create([
                'role'    => ChatMessage::ROLE_USER,
                'content' => $request->content,
                'is_read' => false,
            ]);

            $conversation->update([
                'last_message_at' => now(),
                'unread_count'    => DB::raw('unread_count + 1'),
            ]);

            // AI yanıtı al
            if ($this->ai->isEnabled() && $conversation->ai_enabled) {
                $history = $conversation->messages()
                    ->whereIn('role', [ChatMessage::ROLE_USER, ChatMessage::ROLE_ASSISTANT])
                    ->orderBy('created_at')
                    ->get()
                    ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
                    ->toArray();

                $reply = $this->ai->getReply($history);

                if ($reply) {
                    $aiMessage = $conversation->messages()->create([
                        'role'    => ChatMessage::ROLE_ASSISTANT,
                        'content' => $reply,
                        'is_read' => true,
                    ]);

                    $conversation->update(['last_message_at' => now()]);
                }
            }
        });

        return $this->success([
            'conversation_id'   => $conversation->id,
            'user_message_id'   => $userMsg->id,
            'reply'             => isset($aiMessage) ? $this->formatMessage($aiMessage) : null,
            'ai_active'         => $this->ai->isEnabled() && $conversation->ai_enabled,
        ]);
    }

    private function formatMessage(ChatMessage $msg): array
    {
        return [
            'id'         => $msg->id,
            'role'       => $msg->role,
            'content'    => $msg->content,
            'created_at' => $msg->created_at->format('H:i'),
        ];
    }
}
