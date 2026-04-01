<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatConversationController extends BaseController
{
    public function index()
    {
        return view('admin.ai.conversations.index');
    }

    /** Sol panel: konuşma listesi */
    public function list(Request $request): JsonResponse
    {
        $conversations = ChatConversation::with(['user', 'lastMessage'])
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->orderByDesc('last_message_at')
            ->get()
            ->map(fn ($c) => [
                'id'                => $c->id,
                'user_name'         => $c->user?->name ?? 'Kullanıcı #' . $c->user_id,
                'user_email'        => $c->user?->email ?? '',
                'user_avatar'       => 'https://ui-avatars.com/api/?name=' . urlencode($c->user?->name ?? 'U') . '&background=066fd1&color=fff&size=64',
                'last_message'      => $c->lastMessage ? Str::limit($c->lastMessage->content, 55) : '—',
                'last_message_role' => $c->lastMessage?->role,
                'unread_count'      => $c->unread_count,
                'status'            => $c->status,
                'ai_enabled'        => $c->ai_enabled,
                'time'              => $c->last_message_at?->format('H:i') ?? $c->created_at->format('H:i'),
                'date_label'        => $c->last_message_at
                    ? ($c->last_message_at->isToday() ? $c->last_message_at->format('H:i') : $c->last_message_at->format('d.m.Y'))
                    : $c->created_at->format('d.m.Y'),
            ]);

        return response()->json(['data' => $conversations]);
    }

    /** Sağ panel: konuşma detayı */
    public function detail(ChatConversation $conversation): JsonResponse
    {
        $conversation->load(['user', 'messages' => fn ($q) => $q->orderBy('created_at')]);

        $conversation->messages()->where('role', 'user')->where('is_read', false)->update(['is_read' => true]);
        $conversation->update(['unread_count' => 0]);

        return response()->json([
            'data' => [
                'id'             => $conversation->id,
                'status'         => $conversation->status,
                'ai_enabled'     => $conversation->ai_enabled,
                'created_at'     => $conversation->created_at->format('d.m.Y H:i'),
                'last_message_at'=> $conversation->last_message_at?->format('d.m.Y H:i') ?? '—',
                'user'           => $conversation->user ? [
                    'id'    => $conversation->user->id,
                    'name'  => $conversation->user->name,
                    'email' => $conversation->user->email,
                    'type'  => $conversation->user->type,
                    'url'   => route('admin.users.show', $conversation->user),
                ] : null,
                'messages' => $conversation->messages->map(fn ($m) => [
                    'id'         => $m->id,
                    'role'       => $m->role,
                    'content'    => $m->content,
                    'created_at' => $m->created_at->format('H:i'),
                ]),
                'urls' => [
                    'reply'     => route('admin.ai.conversations.reply',     $conversation),
                    'toggle_ai' => route('admin.ai.conversations.toggle-ai', $conversation),
                    'close'     => route('admin.ai.conversations.close',     $conversation),
                    'reopen'    => route('admin.ai.conversations.reopen',    $conversation),
                ],
            ],
        ]);
    }

    public function show(ChatConversation $conversation)
    {
        $conversation->load(['user', 'messages' => fn ($q) => $q->orderBy('created_at')]);
        $conversation->messages()->where('role', 'user')->where('is_read', false)->update(['is_read' => true]);
        $conversation->update(['unread_count' => 0]);

        return view('admin.ai.conversations.show', compact('conversation'));
    }

    public function reply(Request $request, ChatConversation $conversation): JsonResponse
    {
        $request->validate(['content' => ['required', 'string', 'max:2000']]);

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'role'            => ChatMessage::ROLE_ADMIN,
            'content'         => $request->input('content'),
            'is_read'         => true,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return $this->success('Yanıt gönderildi.', [
            'message' => [
                'id'         => $message->id,
                'role'       => $message->role,
                'content'    => $message->content,
                'created_at' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    public function toggleAi(ChatConversation $conversation): JsonResponse
    {
        $conversation->update(['ai_enabled' => !$conversation->ai_enabled]);
        $label = $conversation->ai_enabled ? 'AI yanıtları açıldı.' : 'Konuşma manuel moda alındı.';
        return $this->success($label, ['ai_enabled' => $conversation->ai_enabled]);
    }

    public function close(ChatConversation $conversation): JsonResponse
    {
        $conversation->update(['status' => 'closed']);
        return $this->success('Konuşma kapatıldı.', ['status' => 'closed']);
    }

    public function reopen(ChatConversation $conversation): JsonResponse
    {
        $conversation->update(['status' => 'open']);
        return $this->success('Konuşma yeniden açıldı.', ['status' => 'open']);
    }
}
