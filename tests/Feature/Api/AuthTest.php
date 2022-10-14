<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    /**
     * Validation auth.
     *
     * @return void
     */
    public function testValidationAuth()
    {
        $response = $this->postJson('/api/auth/token');

        $response->assertStatus(422);
    }

     /**
      * Auth with fake client.
      *
      * @return void
      */
    public function testAuthClientFake()
    {
        $payload = [
            'email' => 'fake@email.com',
            'password' => '123456',
            'device_name' => Str::random(10)
        ];

        $response = $this->postJson('/api/auth/token', $payload);

        $response->assertStatus(404)
            ->assertExactJson(
                [
                'message' => trans('messages.invalid_credentials')
                ]
            );
    }

     /**
      * Success Auth.
      *
      * @return void
      */
    public function testAuthSuccess()
    {
        $client = Client::factory()->create();

        $payload = [
          'email' => $client->email,
          'password' => 'password',
          'device_name' => Str::random(10)
        ];
  
        $response = $this->postJson('/api/auth/token', $payload);
  
        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }
}
