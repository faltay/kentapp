<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // AI yapılandırması — tekil satır
        Schema::create('ai_configs', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->default('openai'); // openai | anthropic
            $table->text('api_key')->nullable();
            $table->string('model')->default('gpt-4o');
            $table->decimal('temperature', 3, 2)->default(0.70);
            $table->unsignedSmallInteger('max_tokens')->default(1000);
            $table->boolean('ai_enabled')->default(false);
            $table->unsignedSmallInteger('daily_message_limit')->default(20);
            $table->text('welcome_message')->nullable();
            $table->longText('system_prompt')->nullable();
            $table->timestamps();
        });

        // Konuşmalar
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->boolean('ai_enabled')->default(true);
            $table->unsignedInteger('unread_count')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('last_message_at');
        });

        // Mesajlar
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')
                  ->constrained('chat_conversations')
                  ->cascadeOnDelete();
            $table->enum('role', ['user', 'assistant', 'admin']);
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_conversations');
        Schema::dropIfExists('ai_configs');
    }
};
