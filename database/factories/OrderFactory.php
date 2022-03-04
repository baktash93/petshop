<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\OrderStatus;
use App\Models\Payment;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $statuses = OrderStatus::pluck('id')->toArray();
        $statusId = $this->faker->randomElement($statuses);
        $orderStatusTitle = OrderStatus::where('id', $statusId)->value('title');
        return [
            'user_id' => $this->faker->uuid(),
            'payment_id' => $orderStatusTitle === 'paid' || $orderStatusTitle === 'shipped' ? 
                Payment::factory()->create() : null,
            'order_status_id' => $statusId,
            'uuid' => $this->faker->uuid(),
            'products' => [
                [
                    'product' => $this->faker->uuid(),
                    'quantity' => $this->faker->randomNumber(2, false)
                ]
            ],
            'address' => [
                'billing' => $this->faker->text(10),
                'shipping' => $this->faker->text(10)  
            ],
            'delivery_fee' => $this->faker->randomNumber(2, true),
            'amount' => $this->faker->randomNumber(3, false)
        ];
    }
}
