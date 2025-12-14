<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dish>
 */
class DishFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['супы', 'вторые блюда', 'салаты', 'напитки', 'десерты'];
        
        return [
            'restaurant_id' => \App\Models\Restaurant::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(8),
            'price' => fake()->randomFloat(2, 5, 50),
            'category' => fake()->randomElement($categories),
            'image' => 'https://via.placeholder.com/400x300?text=Dish',
            'is_available' => true,
            'calories' => fake()->numberBetween(100, 800),
            'protein' => fake()->numberBetween(5, 50),
            'fat' => fake()->numberBetween(5, 40),
            'carbs' => fake()->numberBetween(10, 80),
        ];
    }
}
