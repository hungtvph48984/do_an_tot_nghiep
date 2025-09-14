<?php

namespace Database\Factories;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'user_id' => rand(1, 10),
        'address' => $this->faker->address,
        'phone' => $this->faker->phoneNumber,
        'email' => $this->faker->safeEmail,
        'status' => $this->faker->randomElement(['pending', 'processing', 'completed']),
        'payment' => $this->faker->randomElement(['cod', 'banking', 'momo']),
        'total' => $this->faker->numberBetween(50000, 500000),
        'vorcher_code' => Str::upper(Str::random(6)),
        'sale_price' => $this->faker->numberBetween(10000, 50000),
        'pay_amount' => function (array $attributes) {
            return $attributes['total'] - $attributes['sale_price'];
        },
        'created_at' => now(),
        'updated_at' => now(),
        ];
    }
}
