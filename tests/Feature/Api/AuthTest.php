<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $route = '/api/login';

    /** @test */
    public function can_retrieve_token_with_correct_credentials()
    {
        $credentials = [
            'email' => 'test@test.ru',
            'password' => '123456',
        ];
        User::factory()->create(array_merge(
            $credentials,
            ['password' => Hash::make($credentials['password'])]
        ));

        $response = $this->json('POST', $this->route, $credentials);

        $response->assertStatus(200);
        $this->assertNotEmpty($response->json('token'));
    }

    /** @test */
    public function cannot_retrieve_token_with_incorrect_email()
    {
        $credentials = [
            'email' => 'test@test.ru',
            'password' => '12345612',
        ];
        User::factory()->create(array_merge(
            $credentials,
            ['password' => Hash::make($credentials['password'])]
        ));

        $response = $this->json('POST', $this->route, array_merge($credentials, ['email' => 'test2@test.ru']));

        $response->assertStatus(401);
    }

    /** @test */
    public function cannot_retrieve_token_with_incorrect_password()
    {
        $credentials = [
            'email' => 'test@test.ru',
            'password' => '12345612',
        ];
        User::factory()->create(array_merge(
            $credentials,
            ['password' => Hash::make($credentials['password'])]
        ));

        $response = $this->json('POST', $this->route, array_merge($credentials, ['password' => '123']));

        $response->assertStatus(401);
    }
}
