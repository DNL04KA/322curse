<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Restaurant>
 */
class RestaurantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company() . ' Ресторан',
            'description' => fake()->sentence(10),
            'address' => fake()->streetAddress() . ', Минск',
            'phone' => '+375' . fake()->numerify('29#######'),
            'image' => 'https://via.placeholder.com/400x300?text=Restaurant',
            'is_active' => true,
        ];
    }
}
