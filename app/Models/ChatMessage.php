<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = ['conversation_id', 'role', 'content', 'is_read'];

    protected $casts = ['is_read' => 'boolean'];

    public const ROLE_USER      = 'user';
    public const ROLE_ASSISTANT = 'assistant';
    public const ROLE_ADMIN     = 'admin';

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }
}
