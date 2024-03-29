<?php

namespace Database\Factories;

use App\Models\Annonce;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Annonce>
 */
class AnnonceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Annonce::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->dateTime(),
            'location' => $this->faker->address,
            'required_skills' => $this->faker->words(3, true),
            'organizer_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'type_id' => function () {
                return \App\Models\Type::factory()->create()->id;
            },
        ];
    }
}
