<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents_financiers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type_document', ['FACTURE', 'RECU', 'RAPPORT']);
            $table->unsignedBigInteger('reference_id');
            $table->text('file_path');
            $table->string('original_name', 255)->nullable();
            $table->string('mime_type', 120)->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents_financiers');
    }
};
