<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Idea>
 */
class IdeaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Izveido jaunu lietotāju un piešķir tā ID kā user_id
            'title' => fake()->sentence(), // Ģenerē nejaušu virsrakstu
            'description' => fake()->paragraph(), // Ģenerē nejaušu aprakstu
            'links' => [fake()->url()], // Ģenerē masīvu ar nejaušām saitēm
        ];
    }
}
