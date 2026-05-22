<?php

namespace Database\Factories;

use App\Models\Conversations;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Conversations>
 */
class ConversationsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Conversations::class;
    public function definition(): array
    {
        $senderId = User::inRandomOrder()->first()?->id ?? User::factory();
        $receiverId = User::where('id', '!=', $senderId)->inRandomOrder()->first()?->id ?? User::factory();
        return [
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'last_message_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
