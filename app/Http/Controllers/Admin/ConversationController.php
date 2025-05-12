<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get all conversations for the user
        $conversations = $user->conversations()
            ->with(['users' => function($query) use ($user) {
                $query->where('users.id', '!=', $user->id);
            }])
            ->get();
            
        // For each conversation, get the latest message
        foreach ($conversations as $conversation) {
            // Get the latest message
            $latestMessage = Message::where(function($query) use ($conversation) {
                    $query->whereIn('sender_id', $conversation->users->pluck('id'))
                        ->whereIn('receiver_id', $conversation->users->pluck('id'));
                })
                ->orderBy('created_at', 'desc')
                ->first();
                
            $conversation->latest_message = $latestMessage;
            
            // Count unread messages
            $conversation->unread_count = Message::where('receiver_id', $user->id)
                ->whereIn('sender_id', $conversation->users->pluck('id'))
                ->where('is_read', false)
                ->count();
        }
        
        return Inertia::render('Admin/Conversations/Index', [
            'conversations' => $conversations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        
        return Inertia::render('Admin/Conversations/Create', [
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['exists:users,id'],
        ]);
        
        // Create the conversation
        $conversation = Conversation::create([
            'title' => $validated['title']
        ]);
        
        // Add the current user to the conversation
        $conversation->users()->attach($request->user()->id);
        
        // Add the selected users to the conversation
        $conversation->users()->attach($validated['user_ids']);
        
        return redirect()->route('conversations.show', $conversation)
            ->with('message', 'Conversación creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation, Request $request)
    {
        $user = $request->user();
        
        // Check if the user is part of the conversation
        if (!$conversation->users->contains($user->id)) {
            abort(403, 'No tienes permiso para ver esta conversación.');
        }
        
        $conversation->load(['users']);
        
        // Get all messages for this conversation
        $messages = Message::where(function($query) use ($conversation) {
                $query->whereIn('sender_id', $conversation->users->pluck('id'))
                    ->whereIn('receiver_id', $conversation->users->pluck('id'));
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Mark all received messages as read
        Message::where('receiver_id', $user->id)
            ->whereIn('sender_id', $conversation->users->pluck('id'))
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return Inertia::render('Admin/Conversations/Show', [
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conversation $conversation)
    {
        $conversation->load(['users']);
        $users = User::all();
        
        return Inertia::render('Admin/Conversations/Edit', [
            'conversation' => $conversation,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['exists:users,id'],
        ]);
        
        // Update the conversation title
        $conversation->update([
            'title' => $validated['title']
        ]);
        
        // Sync the users (this will remove users not in the list and add new ones)
        $allUserIds = array_merge([$request->user()->id], $validated['user_ids']);
        $conversation->users()->sync($allUserIds);
        
        return redirect()->route('conversations.show', $conversation)
            ->with('message', 'Conversación actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        $conversation->delete();

        return redirect()->route('conversations.index')
            ->with('message', 'Conversación eliminada correctamente.');
    }
    
    /**
     * Send a message to the conversation.
     */
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        
        // Check if the user is part of the conversation
        if (!$conversation->users->contains($user->id)) {
            abort(403, 'No tienes permiso para enviar mensajes a esta conversación.');
        }
        
        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);
        
        // Send a message to each user in the conversation (except the sender)
        foreach ($conversation->users as $receiver) {
            if ($receiver->id != $user->id) {
                $message = Message::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $receiver->id,
                    'content' => $validated['content'],
                    'is_read' => false,
                ]);
            }
        }
        
        return redirect()->back()
            ->with('message', 'Mensaje enviado correctamente.');
    }
    
    /**
     * Leave the conversation.
     */
    public function leave(Request $request, Conversation $conversation)
    {
        $user = $request->user();
        
        // Remove the user from the conversation
        $conversation->users()->detach($user->id);
        
        return redirect()->route('conversations.index')
            ->with('message', 'Has salido de la conversación.');
    }
    
    /**
     * Add users to the conversation.
     */
    public function addUsers(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['exists:users,id'],
        ]);
        
        // Add the users to the conversation
        $conversation->users()->attach($validated['user_ids']);
        
        return redirect()->back()
            ->with('message', 'Usuarios añadidos a la conversación.');
    }
    
    /**
     * Remove a user from the conversation.
     */
    public function removeUser(Request $request, Conversation $conversation, User $user)
    {
        // Remove the user from the conversation
        $conversation->users()->detach($user->id);
        
        return redirect()->back()
            ->with('message', 'Usuario eliminado de la conversación.');
    }
}