<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('ja_JP');
        return [
            'category_id' => Category::inRandomOrder()->first()->id,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'gender' => $faker->randomElement([1, 2]),
            'email' => $faker->safeEmail,
            'tel' => preg_replace('/[^0-9]/', '', $faker->phoneNumber),
            'address' => $faker->address,
            'building' => $faker->secondaryAddress,
            'detail' => $faker->realText(100),
        ];
    }
}
