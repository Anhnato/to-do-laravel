<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=>$this->faker->sentence(3),
            'description'=>$this->faker->optional()->paragraph(),
            'status'=>$this->faker->randomElement(['pending','completed']),
            'priority'=>$this->faker->randomElement(['high', 'medium', 'low']),
            'due_date'=>$this->faker->optional()->date(),
            'user_id'=>User::factory(),
            'category_id'=>Category::factory(),
        ];
    }
}
