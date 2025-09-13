<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Animal;
use App\Models\Enclosure;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'species' => null, // This will be set via `state()` always
            'is_predator' => null, // This will also be set via `state()`
            'born_at' => $this->faker->dateTimeBetween('-10 years', 'now'),
            'image_path' => $this->faker->imageUrl(640, 480, 'animals'),
            'enclosure_id' => null,
        ];
    }


}
