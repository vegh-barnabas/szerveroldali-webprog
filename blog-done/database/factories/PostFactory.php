<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => Str::ucfirst($this->faker->words($this->faker->numberBetween(2, 6), true)),
            'text' => $this->faker->paragraphs($this->faker->numberBetween(1, 4), true),
            'description' => $this->faker->boolean() ? $this->faker->text(24) : null
        ];
    }
}
