<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'type' => $this->faker->randomElement(['credit_card', 'cash_on_delivery', 'bank_transfer']),
            'details' => $this->faker->randomElement([
                // Type=> credit_card
                [
                    "holder_name" => $this->faker->name(),
                    "number" => $this->faker->randomNumber(5, true),
                    "ccv" => $this->faker->randomNumber(5, true),
                    "expire_date" => $this->faker->dateTime()
                ],
                // Type=> cash_on_delivery
                [
                    "first_name" => $this->faker->firstName(),
                    "last_name" => $this->faker->lastName(),
                    "address" => $this->faker->text(50)
                ],
                // Type=> bank_transfer
                [
                    "swift" => $this->faker->text(15),
                    "iban" => $this->faker->text(15),
                    "name" => $this->faker->company()
                ]
            ])
        ];
    }
}
