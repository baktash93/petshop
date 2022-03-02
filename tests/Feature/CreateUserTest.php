<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions;
    /**
     * Create user with correct data.
     *
     * @return void
     */
    public function test_create_user()
    {
        $password = $this->faker->words(3, true);
        $response = $this->post('/api/v1/user/create', [
            'email' => $this->faker->safeEmail(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'password' => $password,
            'confirm_password' => $password,
            'address' => $this->faker->text(5),
            'phone_number' => $this->faker->phoneNumber()
        ]);
        $response->assertStatus(201);
    }

    public function test_create_user_unmatching_password()
    {
        $response = $this->post('/api/v1/user/create', [
            'email' => $this->faker->safeEmail(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'password' => $this->faker->words(3, true),
            'confirm_password' => $this->faker->words(2, true),
            'address' => $this->faker->text(5),
            'phone_number' => $this->faker->phoneNumber()
        ]);
        $response->assertStatus(422);
    }

    public function test_create_user_missing_required_fields()
    {
        $password = $this->faker->words(3, true);
        $response = $this->post('/api/v1/user/create', [
            'email' => $this->faker->safeEmail(),
            'password' => $password,
            'confirm_password' => $password
        ]);
        $response->assertStatus(500);
    }
}
