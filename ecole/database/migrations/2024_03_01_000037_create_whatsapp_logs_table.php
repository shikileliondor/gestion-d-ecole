<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->comment('Logs outgoing WhatsApp messages and delivery statuses.');
            $table->id();
            $table->string('recipient_phone', 30)->index();
            $table->text('message');
            $table->string('status', 50)->default('queued')->index();
            $table->string('provider', 100)->nullable();
            $table->string('provider_reference')->nullable()->index();
            $table->decimal('cost', 10, 2)->nullable();
            $table->timestamp('sent_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
