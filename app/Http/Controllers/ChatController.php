<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Request as RequestModel;
use App\Events\NewChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    // Obtener o crear una conversación para una solicitud
    public function getOrCreateConversation(RequestModel $request)
    {
        try {
            // Verificar que el usuario es parte de la solicitud
            $userId = Auth::id();
            if ($userId != $request->requester_id && $userId != $request->listing->seller_id) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
            
            // Si la solicitud no está aceptada, no permitir chat
            if ($request->status != 'accepted') {
                return response()->json(['error' => 'La solicitud debe estar aceptada para iniciar un chat'], 400);
            }
            
            // Buscar conversación existente para esta solicitud
            $conversation = Conversation::where('request_id', $request->id)->first();
            
            // Si no existe, crear una nueva conversación
            if (!$conversation) {
                $conversation = Conversation::create([
                    'title' => 'Chat sobre ' . ($request->listing->tractor->brand ?? 'Tractor') . ' ' . ($request->listing->tractor->model ?? ''),
                    'request_id' => $request->id
                ]);
                
                // Añadir participantes (comprador y vendedor)
                $conversation->participants()->attach([
                    $request->requester_id,
                    $request->listing->seller_id
                ]);
                
                Log::info('Nueva conversación creada', ['conversation_id' => $conversation->id, 'request_id' => $request->id]);
            }
            
            // Cargar participantes para devolver la información completa
            $conversation->load('participants');
            
            return response()->json([
                'conversation' => $conversation,
                'success' => true
            ]);
        } catch (\Exception $e) {
            Log::error('Error al crear/obtener conversación', [
                'request_id' => $request->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al procesar la solicitud: ' . $e->getMessage(),
                'success' => false
            ], 500);
        }
    }
    
    // Obtener mensajes de una conversación
    public function getMessages(Conversation $conversation)
    {
        try {
            // Verificar que el usuario es participante
            if (!$conversation->participants()->where('user_id', Auth::id())->exists()) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
            
            // Cargar la solicitud y la información del anuncio/tractor
            $conversation->load(['request.listing.tractor', 'participants']);
            
            // Cargar mensajes con información del remitente
            $messages = $conversation->messages()
                ->with('sender:id,first_name,last_name')
                ->orderBy('created_at', 'asc')
                ->get();
            
            // Marcar mensajes como leídos (los que no son del usuario actual)
            Message::where('conversation_id', $conversation->id)
                ->where('sender_id', '!=', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
            
            return response()->json([
                'messages' => $messages,
                'conversation' => $conversation
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener mensajes', [
                'conversation_id' => $conversation->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Error al obtener mensajes: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Enviar un mensaje
    public function sendMessage(Request $request, Conversation $conversation)
    {
        try {
            // Verificar que el usuario es participante
            if (!$conversation->participants()->where('user_id', Auth::id())->exists()) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
            
            // Validar contenido del mensaje
            $validated = $request->validate([
                'content' => 'required|string|max:1000'
            ]);
            
            // Crear mensaje
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => Auth::id(),
                'content' => $validated['content'],
                'is_read' => false
            ]);
            
            // Cargar información del remitente para la respuesta
            $message->load('sender:id,first_name,last_name');
            
            // Emitir evento para actualizaciones en tiempo real
            broadcast(new NewChatMessage($message))->toOthers();
            
            Log::info('Mensaje enviado', [
                'message_id' => $message->id,
                'conversation_id' => $conversation->id,
                'sender_id' => Auth::id()
            ]);
            
            return response()->json($message);
        } catch (\Exception $e) {
            Log::error('Error al enviar mensaje', [
                'conversation_id' => $conversation->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Error al enviar mensaje: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Obtener conversaciones del usuario actual
    public function getUserConversations()
    {
        try {
            $conversations = Auth::user()->conversations()
                ->with(['participants:id,first_name,last_name', 'request.listing.tractor'])
                ->withCount(['messages as unread_count' => function ($query) {
                    $query->where('sender_id', '!=', Auth::id())
                        ->where('is_read', false);
                }])
                ->latest('updated_at')
                ->get();
            
            return response()->json(['conversations' => $conversations]);
        } catch (\Exception $e) {
            Log::error('Error al obtener conversaciones', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Error al obtener conversaciones: ' . $e->getMessage()
            ], 500);
        }
    }
}