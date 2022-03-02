<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResourceUserTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_current_user()
    {
        $user = User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(4, true))
        ])->create();
        $token = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $password
        ])->getOriginalContent();
        $response = $this->get('/api/v1/user', ['authorization' => 'Bearer ' . $token]);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'email',
                'first_name',
                'last_name',
                'is_marketing',
                'phone_number'
            ])
            ->assertJsonPath('uuid', $user->uuid)
            ->assertJsonPath('email', $user->email)
            ->assertJsonPath('first_name', $user->first_name)
            ->assertJsonPath('last_name', $user->last_name)
            ->assertJsonPath('phone_number', $user->phone_number);
    }


    public function test_delete_current_user() {
        User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(4, true))
        ])->create();
        $token = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $password
        ])->getOriginalContent();
        $response = $this->delete('/api/v1/user', [], ['authorization' => 'Bearer ' . $token]);
        $response->assertStatus(204);
        $response = $this->delete('/api/v1/user', [], ['authorization' => 'Bearer ' . $token]);
        $response->assertStatus(404);
    }

    public function test_edit_current_user() {
        User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(4, true))
        ])->create();
        $token = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $password
        ])->getOriginalContent();
        $response = $this->put('/api/v1/user/edit', [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'is_marketing' => true,
            'phone_number' => $this->faker->phoneNumber()
        ], ['authorization' => 'Bearer ' . $token]);
        $response->assertStatus(204);
    }
}
