<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUsers extends Command
{
    protected $signature = 'check:users';
    protected $description = 'Check existing users in the database';

    public function handle()
    {
        $this->info('Checking users in the database...');
        
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->error('No users found in the database!');
            return Command::FAILURE;
        }
        
        $this->info('Found ' . $users->count() . ' users:');
        
        $headers = ['ID', 'Username', 'Email', 'First Name', 'Last Name'];
        $rows = [];
        
        foreach ($users as $user) {
            $rows[] = [
                $user->id,
                $user->username ?? 'N/A',
                $user->email ?? 'N/A',
                $user->first_name ?? 'N/A',
                $user->last_name ?? 'N/A'
            ];
        }
        
        $this->table($headers, $rows);
        
        return Command::SUCCESS;
    }
}