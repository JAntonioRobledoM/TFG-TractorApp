<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Message;
use App\Events\NewChatMessage;
use App\Models\User;
use App\Models\Conversation;

class TestPusher extends Command
{
    protected $signature = 'test:pusher';
    protected $description = 'Test Pusher broadcasting';

    public function handle()
    {
        $this->info('Testing Pusher broadcast...');

        // Verificar que existe al menos un usuario
        $user = User::first();
        if (!$user) {
            $this->error('No users found in the database. Please create a user first.');
            return Command::FAILURE;
        }

        // Verificar que existe al menos una conversación
        $conversation = Conversation::first();
        if (!$conversation) {
            $this->info('No conversations found. Creating a test conversation...');
            $conversation = Conversation::create(['title' => 'Test Conversation']);
            
            // Añadir el usuario a la conversación
            $conversation->participants()->attach($user->id);
            
            $this->info('Created conversation with ID: ' . $conversation->id);
        }

        // Buscar un mensaje existente o crear uno de prueba
        $message = Message::first();
        
        if (!$message) {
            $this->info('No messages found, creating a test message...');
            // Crear un mensaje de prueba usando el ID de usuario real
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $user->id,
                'content' => 'Test message from console',
                'is_read' => false
            ]);
            
            $this->info('Created message with ID: ' . $message->id);
        }

        $this->info('Broadcasting message with ID: ' . $message->id);
        
        try {
            event(new NewChatMessage($message));
            $this->info('Event broadcast successful!');
        } catch (\Exception $e) {
            $this->error('Error broadcasting event: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }

        return Command::SUCCESS;
    }
}