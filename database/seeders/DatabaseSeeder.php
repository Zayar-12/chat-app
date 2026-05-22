<?php

namespace Database\Seeders;

use App\Models\Conversations;
use App\Models\Messages;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      

       User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), 
        ]);

       
        User::factory(20)->create();

       
        Conversations::factory(15)->create();

       
        Messages::factory(150)->create();
        
      
        foreach (Conversations::all() as $conversation) {
            $latestMessage = $conversation->messages()->latest()->first();
            if ($latestMessage) {
                $conversation->update([
                    'last_message_at' => $latestMessage->created_at
                ]);
            }
    }
}
}