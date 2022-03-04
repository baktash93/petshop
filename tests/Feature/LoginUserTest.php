<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;

class LoginUserTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;
    /**
     *
     * @return void
     */
    public function test_login_user()
    {
        $user = User::factory()->state([
            'password' => bcrypt($password = '12345')
        ])->create();
        $response = $this->post('/api/v1/user/login', [
            'email' => $user->email,
            'password' => $password
        ]);

        $response->assertStatus(200);
    }

    public function test_login_user_incorrect_email() {
        $response = $this->post('/api/v1/user/login', [
            'email' => $this->faker->email(),
            'password' => $this->faker->words(2, true)
        ]);
        $response->assertStatus(404);
    }

    public function test_login_nonexistent_email () {
        User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(3, true))
        ])->create();
        $response = $this->post('/api/v1/user/login', [
            'email' => $this->faker->safeEmail(),
            'password' => $password
        ]);
        $response->assertStatus(404);
    }
    
    public function test_login_incorrect_password () {
        User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($this->faker->words(3, true))
        ])->create();
        $response = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $this->faker->words(2, true)
        ]);
        $response->assertStatus(401);
    }
    
    public function test_logout () {
        User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(3, true))
        ])->create();
        $token = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $password
        ])->getOriginalContent();
        $response = $this->get('/api/v1/user/logout', [
            'authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
    }

    
    public function test_logout_confirm_resource_inaccessable () {
        User::factory()->state([
            'email' => $email = $this->faker->safeEmail(),
            'password' => bcrypt($password = $this->faker->words(3, true))
        ])->create();
        $token = $this->post('/api/v1/user/login', [
            'email' => $email,
            'password' => $password
        ])->getOriginalContent();
        $this->get('/api/v1/user/logout', [
            'authorization' => 'Bearer ' . $token
        ]);
        $response = $this->get('/api/v1/payments', ['authorization' => 'Bearer ' . $token]);
        $response->assertStatus(401);
    }
}
