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
}
