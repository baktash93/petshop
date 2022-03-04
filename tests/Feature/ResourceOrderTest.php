<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResourceOrderTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;
    public function test_get_order_list()
    {
        User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(4, true))
        ])
        ->has(
            Order::factory()->count(10)->state([
                'payment_id' => Payment::factory()->create()->id
            ])
        )->create();
        $token = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $password
        ])->getOriginalContent();
        $response = $this->get('/api/v1/orders', ['authorization' => 'Bearer ' . $token]);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    'user_id',
                    'order_status_id',
                    'payment_id',
                    'uuid',
                    'products',
                    'address',
                    'delivery_fee',
                    'amount',
                    'shipped_at' 
                ]
            ]);
    }

    public function test_get_order() {
        $user = User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(4, true))
        ])->create();
        $order = Order::factory()->state([
            'user_id' => $user->id
        ])->create();
        $token = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $password
        ])->getOriginalContent();
        $response = $this->get('/api/v1/order/' . $order->uuid, ['authorization' => 'Bearer ' . $token]);
        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'user_id',
                'order_status_id',
                'payment_id',
                'uuid',
                'products',
                'address',
                'delivery_fee',
                'amount',
                'shipped_at'
            ]);
    }

    public function test_edit_order() {
        $user = User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(4, true))
            ])->create();
        $order = Order::factory()->state([
            'user_id' => $user->id,
            'order_status_id' => ($orderStatus = OrderStatus::factory()->create())->id
        ])->create();
        $update = [
            'type' => 'cash_on_delivery',
            'amount' => 2000,
            'order_status_uuid' => $orderStatus->uuid
        ];
        $token = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $password
        ])->getOriginalContent();
        $response = $this->put(
            '/api/v1/order/' . $order->uuid,
            $update,
            ['authorization' => 'Bearer ' . $token]
        );
        $response->assertStatus(204);
    }
    
    public function test_delete_order() {
        $user = User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(4, true))
        ])->create();
        $order = Order::factory()->state([
            'user_id' => $user->id
        ])->create();
        $token = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $password
        ])->getOriginalContent();
        $response = $this->delete('/api/v1/order/' . $order->uuid, [], ['authorization' => 'Bearer ' . $token]);
        $response->assertStatus(204);
        $response = $this->delete('/api/v1/order/' . $order->uuid, [], ['authorization' => 'Bearer ' . $token]);
        $response->assertStatus(404);
    }
}
