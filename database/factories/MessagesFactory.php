<?php

namespace Database\Factories;

use App\Models\Conversations;
use App\Models\Messages;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Messages>
 */
class MessagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

        protected $model = Messages::class;

    public function definition(): array
    {
$conversation = Conversations::inRandomOrder()->first() ?? Conversations::factory()->create();
$authorId = $this->faker->randomElement([$conversation->sender_id, $conversation->receiver_id]);
        return [
           'conversation_id' => $conversation->id,
            'user_id' => $authorId,
            'body' => $this->faker->sentence(mt_rand(3, 15)), 
            'file_path' => null, 
            'read_at' => $this->faker->randomElement([null, now()]), 
            'created_at' => $this->faker->dateTimeBetween($conversation->created_at, 'now'),
        ];
    }
}
