<?php

namespace Database\Factories;

use App\Models\Definition;
use App\Models\User;
use App\Models\Word;
use Illuminate\Database\Eloquent\Factories\Factory;

class DefinitionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Definition::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::pluck('id')->toArray();
        $words = Word::pluck('id')->toArray();

        return [
            'text' => $this->faker->name,
            'exemple' => $this->faker->name,
            'user_id' => $this->faker->randomElement($users),
            'word_id' => $this->faker->randomElement($words)
        ];
    }
}
