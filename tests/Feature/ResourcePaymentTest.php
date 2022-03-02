<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResourcePaymentTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_payment_list()
    {
        User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(4, true))
        ])->has(Order::factory()->state([
                'payment_id' => Payment::factory()->create()->id
            ]))
        ->create();
        $token = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $password
        ])->getOriginalContent();
        $response = $this->get('/api/v1/payments', ['authorization' => 'Bearer ' . $token]);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                [
                    'type',
                    'details',
                    'uuid'
                ]
            ]);
    }
}
