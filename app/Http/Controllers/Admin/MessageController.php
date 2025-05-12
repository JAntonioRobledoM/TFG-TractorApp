<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get all messages where the user is either sender or receiver
        $messages = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Group by conversation partner
        $conversations = [];
        foreach ($messages as $message) {
            $partnerId = ($message->sender_id == $user->id) ? $message->receiver_id : $message->sender_id;
            
            if (!isset($conversations[$partnerId])) {
                $partnerUser = ($message->sender_id == $user->id) ? $message->receiver : $message->sender;
                $conversations[$partnerId] = [
                    'partner' => $partnerUser,
                    'latest_message' => $message,
                    'unread_count' => ($message->receiver_id == $user->id && !$message->is_read) ? 1 : 0
                ];
            } else {
                // Update unread count
                if ($message->receiver_id == $user->id && !$message->is_read) {
                    $conversations[$partnerId]['unread_count']++;
                }
                
                // Update latest message if this one is newer
                if ($message->created_at > $conversations[$partnerId]['latest_message']->created_at) {
                    $conversations[$partnerId]['latest_message'] = $message;
                }
            }
        }
        
        return Inertia::render('Admin/Messages/Index', [
            'conversations' => array_values($conversations)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'content' => ['required', 'string'],
            'related_id' => ['nullable', 'numeric'],
            'related_type' => ['nullable', 'string', 'max:50'],
        ]);
        
        $validated['sender_id'] = $request->user()->id;
        $validated['is_read'] = false;
        
        $message = Message::create($validated);
        
        // Create notification for the receiver
        Notification::create([
            'user_id' => $validated['receiver_id'],
            'related_id' => $message->id,
            'related_type' => 'message',
            'type' => 'new_message',
            'message' => 'Nuevo mensaje de ' . $request->user()->first_name . ' ' . $request->user()->last_name,
            'is_read' => false
        ]);
        
        return redirect()->back()
            ->with('message', 'Mensaje enviado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $partnerId)
    {
        $user = $request->user();
        $partner = User::findOrFail($partnerId);
        
        // Get all messages between the two users
        $messages = Message::where(function($query) use ($user, $partner) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $partner->id);
            })
            ->orWhere(function($query) use ($user, $partner) {
                $query->where('sender_id', $partner->id)
                    ->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Mark all received messages as read
        Message::where('sender_id', $partner->id)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return Inertia::render('Admin/Messages/Show', [
            'partner' => $partner,
            'messages' => $messages
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $message->delete();

        return redirect()->back()
            ->with('message', 'Mensaje eliminado correctamente.');
    }
    
    /**
     * Get unread messages count.
     */
    public function unreadCount(Request $request)
    {
        $user = $request->user();
        $count = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
            
        return response()->json(['count' => $count]);
    }
    
    /**
     * Mark all messages from a specific sender as read.
     */
    public function markAsRead(Request $request, $senderId)
    {
        $user = $request->user();
        
        Message::where('sender_id', $senderId)
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return redirect()->back()
            ->with('message', 'Mensajes marcados como leídos.');
    }
    
    /**
     * Get contacts list (users who have exchanged messages with the current user).
     */
    public function contacts(Request $request)
    {
        $user = $request->user();
        
        // Get all unique users who have sent or received messages from the current user
        $senderIds = Message::where('receiver_id', $user->id)
            ->select('sender_id')
            ->distinct()
            ->pluck('sender_id');
            
        $receiverIds = Message::where('sender_id', $user->id)
            ->select('receiver_id')
            ->distinct()
            ->pluck('receiver_id');
            
        $contactIds = $senderIds->merge($receiverIds)->unique();
        $contacts = User::whereIn('id', $contactIds)->get();
        
        return response()->json(['contacts' => $contacts]);
    }
}