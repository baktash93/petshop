<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResourceOrderTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;
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
            ])
            ->assertJsonPath('user_id', $order->user_id)
            ->assertJsonPath('uuid', $order->uuid)
            ->assertJsonPath('type', $order->type)
            ->assertJsonPath('products', $order->products)
            ->assertJsonPath('details', $order->details)
            ->assertJsonPath('delivery_fee', $order->delivery_fee)
            ->assertJsonPath('amount', $order->amount);
    }

    // public function test_edit_order() {
    //     $order = order::factory()->state([
    //         'type' => 'credit_card'
    //     ])->create();
    //     User::factory()->state([
    //         'email' => $email = $this->faker->safeEmail(),
    //         'password' => bcrypt($password = $this->faker->words(4, true))
    //     ])->create();
    //     $update = [
    //         'type' => 'cash_on_delivery'
    //     ];
    //     $token = $this->post('/api/v1/user/login', [
    //         'email' => $email,
    //         'password' => $password
    //     ])->getOriginalContent();
    //     $response = $this->put(
    //         '/api/v1/order/' . $order->uuid,
    //         $update,
    //         ['authorization' => 'Bearer ' . $token]
    //     );
    //     $response
    //         ->assertStatus(204);
    // }
    
    // public function test_delete_order() {
    //     $order = order::factory()->state([
    //         'type' => 'credit_card'
    //     ])->create();
    //     User::factory()->state([
    //         'email' => $email = $this->faker->safeEmail(),
    //         'password' => bcrypt($password = $this->faker->words(4, true))
    //     ])->create();
    //     $token = $this->post('/api/v1/user/login', [
    //         'email' => $email,
    //         'password' => $password
    //     ])->getOriginalContent();
    //     $response = $this->delete('/api/v1/order/' . $order->uuid, [], ['authorization' => 'Bearer ' . $token]);
    //     $response->assertStatus(204);
    //     $response = $this->delete('/api/v1/order/' . $order->uuid, [], ['authorization' => 'Bearer ' . $token]);
    //     $response->assertStatus(404);
    // }
}
