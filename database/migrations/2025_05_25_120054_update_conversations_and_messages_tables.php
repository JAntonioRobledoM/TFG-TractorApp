<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modificar la tabla de conversaciones para asociarla con solicitudes
        Schema::table('conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('conversations', 'request_id')) {
                $table->foreignId('request_id')->nullable()->constrained('requests')->onDelete('cascade');
            }
        });

        // Asegurar que la tabla de mensajes tiene la estructura correcta
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'conversation_id')) {
                $table->foreignId('conversation_id')->nullable()->constrained('conversations')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('conversations', function (Blueprint $table) {
            if (Schema::hasColumn('conversations', 'request_id')) {
                $table->dropForeign(['request_id']);
                $table->dropColumn('request_id');
            }
        });

        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'conversation_id')) {
                $table->dropForeign(['conversation_id']);
                $table->dropColumn('conversation_id');
            }
        });
    }
};