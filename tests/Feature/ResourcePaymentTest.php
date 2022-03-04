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

    public function test_get_payment()
    {
        $payment = Payment::factory()->create();
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
        $response = $this->get('/api/v1/payment/' . $payment->uuid, ['authorization' => 'Bearer ' . $token]);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'type',
                'details',
                'uuid'
            ])
            ->assertJsonPath('type', $payment->type)
            ->assertJsonPath('details', $payment->details)
            ->assertJsonPath('uuid', $payment->uuid);
    }

    public function test_edit_payment()
    {
        $payment = Payment::factory()->state([
            'type' => 'credit_card'
        ])->create();
        User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(4, true))
        ])->create();
        $update = [
            'type' => 'cash_on_delivery'
        ];
        $token = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $password
        ])->getOriginalContent();
        $response = $this->put(
            '/api/v1/payment/' . $payment->uuid,
            $update,
            ['authorization' => 'Bearer ' . $token]
        );
        $response
            ->assertStatus(204);
    }
    
    public function test_delete_payment()
    {
        $payment = Payment::factory()->state([
            'type' => 'credit_card'
        ])->create();
        User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(4, true))
        ])->create();
        $token = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $password
        ])->getOriginalContent();
        $response = $this->delete('/api/v1/payment/' . $payment->uuid, [], ['authorization' => 'Bearer ' . $token]);
        $response->assertStatus(204);
        $response = $this->delete('/api/v1/payment/' . $payment->uuid, [], ['authorization' => 'Bearer ' . $token]);
        $response->assertStatus(404);
    }
}
