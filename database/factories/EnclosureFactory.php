<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Enclosure;
use App\Models\Animal;
use App\Support\SpeciesMap;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enclosure>
 */
class EnclosureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'limit' => $this->faker->numberBetween(1, 10),
            'feeding_at' => $this->faker->time('H:i:s'),
            'is_predator' => $this->faker->boolean(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Enclosure $enclosure) {
            $count = fake()->numberBetween(1, $enclosure->limit);

            $speciesMap = collect(SpeciesMap::all());

            $matchingSpecies = $speciesMap
                ->filter(fn ($isPredator) => $isPredator === $enclosure->is_predator)
                ->keys()
                ->values()
                ->all();

            if (empty($matchingSpecies)) {
                return; 
            }

            Animal::factory()
                ->count($count)
                ->state(function () use ($enclosure, $matchingSpecies) {
                    $species = fake()->randomElement($matchingSpecies);

                    return [
                        'enclosure_id' => $enclosure->id,
                        'species' => $species,
                        'is_predator' => SpeciesMap::isPredator($species),
                    ];
                })
                ->create();
        });
    }
}
