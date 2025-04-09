<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $recurrent = fake()->boolean();

        return [
            'name' => fake()->words(3, true),
            'value' => fake()->numberBetween(1000, 999999),
            'status' => fake()->randomElement(['AWAITING_PAYMENT', 'PAID', 'EXPIRED']),

            'due_date' => fake()->date(),
            'category' => fake()->word(),
            'payment_method' => fake()->randomElement(['Pix', 'Cartão de Crédito', 'Boleto']),
            'payment_source' => fake()->randomElement(['Sicredi', 'Banco do Brasil']),

            'recurrent' => $recurrent,
            'repeat_for' => $recurrent ? fake()->randomElement([fake()->numberBetween(1, 12), -1]) : -1,

            'auto_pay' => fake()->boolean()
        ];
    }
}
