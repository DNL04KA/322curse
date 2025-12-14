<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'confirmed', 'preparing', 'delivering', 'delivered', 'cancelled'];
        
        return [
            'user_id' => \App\Models\User::factory(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->optional()->email(),
            'customer_phone' => '+375' . fake()->numerify('29#######'),
            'delivery_address' => fake()->streetAddress() . ', Минск',
            'total_amount' => fake()->randomFloat(2, 10, 100),
            'status' => fake()->randomElement($statuses),
            'delivery_time' => fake()->optional()->dateTimeBetween('now', '+2 hours'),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
