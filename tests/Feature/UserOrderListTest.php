<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserOrderListTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list_current_user_orders()
    {
        $user = User::factory()
            ->state([
                'password' => bcrypt($password = '12345')
            ])
            ->has(Order::factory()->count(15))
            ->create();
        $token = $this->post('/api/v1/user/login', [
            'email' => $user->email,
            'password' => $password
        ])->getOriginalContent();
        $response = $this->get('/api/v1/user/orders', ['authorization' => 'Bearer ' . $token]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
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
}
