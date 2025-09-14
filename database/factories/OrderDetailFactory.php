<?php

namespace Database\Factories;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'product_variant_id' => rand(1, 20), // giả sử có 20 variants
        'order_id' => Order::factory(), // Tạo mới nếu chưa có
        'price' => $this->faker->numberBetween(10000, 200000),
        'quantity' => $this->faker->numberBetween(1, 5),
        'created_at' => now(),
        'updated_at' => now(),
    ];
    }
}
