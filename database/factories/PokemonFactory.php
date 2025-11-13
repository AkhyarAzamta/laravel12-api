<?php

namespace Database\Factories;

use App\Models\Pokemon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PokemonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pokemon::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'pokeapi_id' => $this->faker->unique()->numberBetween(1, 1000),
            'name' => $this->faker->word,
            'types' => ['grass', 'poison'],
            'abilities' => [
                ['name' => 'overgrow', 'is_hidden' => false],
                ['name' => 'chlorophyll', 'is_hidden' => true]
            ],
            'stats' => [
                ['name' => 'hp', 'value' => 45],
                ['name' => 'attack', 'value' => 49]
            ],
            'sprite' => $this->faker->imageUrl(),
            'height' => $this->faker->numberBetween(1, 100),
            'weight' => $this->faker->numberBetween(1, 1000),
            'is_favorite' => true,
        ];
    }
}