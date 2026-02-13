<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_conversations', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('message_conversations')->cascadeOnDelete();
            $table->foreignId('sender_id')->constrained('users');
            $table->string('subject');
            $table->enum('message_type', ['information', 'important', 'urgence', 'rappel'])->default('information');
            $table->text('content');
            $table->boolean('is_draft')->default(false);
            $table->boolean('internal_channel')->default(true);
            $table->boolean('email_channel')->default(false);
            $table->boolean('requires_read_receipt')->default(false);
            $table->string('source_module', 60)->default('messagerie');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('message_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->cascadeOnDelete();
            $table->string('recipient_type', 30);
            $table->unsignedBigInteger('recipient_id')->nullable();
            $table->string('recipient_name');
            $table->string('recipient_email')->nullable();
            $table->enum('internal_status', ['non_lu', 'lu'])->default('non_lu');
            $table->enum('email_status', ['non_demande', 'en_attente', 'envoye', 'echec', 'email_manquant'])->default('non_demande');
            $table->text('email_error')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['recipient_type', 'recipient_id']);
            $table->index('email_status');
        });

        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->cascadeOnDelete();
            $table->string('disk', 50)->default('public');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type', 120)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_attachments');
        Schema::dropIfExists('message_recipients');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('message_conversations');
    }
};
