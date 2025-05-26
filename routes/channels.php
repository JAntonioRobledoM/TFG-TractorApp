<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use App\Models\Conversation;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// Canal para los mensajes de chat
Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    try {
        // Comprobar si el usuario es participante de la conversación
        $isParticipant = Conversation::find($conversationId)
            ->participants()
            ->where('user_id', $user->id)
            ->exists();
        
        Log::info('Autenticación de canal de chat', [
            'user_id' => $user->id,
            'conversation_id' => $conversationId,
            'authorized' => $isParticipant
        ]);
        
        return $isParticipant;
    } catch (\Exception $e) {
        Log::error('Error en autenticación de canal', [
            'user_id' => $user->id,
            'conversation_id' => $conversationId,
            'error' => $e->getMessage()
        ]);
        
        return false;
    }
});