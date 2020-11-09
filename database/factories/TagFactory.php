<?php

namespace Database\Factories;

use App\Models\Tag;
use App\Models\Word;
use Illuminate\Database\Eloquent\Factories\Factory;

class TagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tag::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $words = Word::pluck('id')->toArray();
        return [
            'text' => $this->faker->name
        ];
    }
//     // ONE TO ONE relationship (with Users already created)
// $factory->define(App\Profile::class, function (Faker\Generator $faker) {
//     return [
//         'user_id' => $faker->unique()->numberBetween(1, App\User::count()),
//         // Rest of attributes...
//     ];
// });

// // ONE TO MANY relationship (with Users already created)
// $factory->define(App\Posts::class, function (Faker\Generator $faker) {
//     $users = App\User::pluck('id')->toArray();
//     return [
//         'user_id' => $faker->randomElement($users),
//         // Rest of attributes...
//     ];
// });
}
