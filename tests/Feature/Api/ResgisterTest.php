<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResgisterTest extends TestCase
{
    /**
     * Create new client
     *
     * @return void
     */
    public function testErrorCreateNewClient()
    {
        // ocorre erro pois falta o 'password'
        $payload = [
            'name' => 'Vanessa Teste Cliente',
            'email' => 'nessacoutoa@gmail.com'
        ];

        $response = $this->postJson('api/auth/register', $payload);
        $response->dump();

        $response->assertStatus(422);
            // ->assertExactJson(
            //     [
            //     'message' => 'The given data was invalid.',    
            //     'errors' => [
            //         'password' => ['The password field is required.']
            //     ]
            //     ]
            // );
            // se for usar traducao para outro idioma
            // 'password' => [trans('validation.required', ['attribute' => 'password'])]
    }

    /**
     * Crete new client
     *
     * @return void
     */
    public function testSuccessCreateNewClient()
    {
        // ocorre erro pois falta o 'password'
        $payload = [
            'name' => 'Vanessa Teste Cliente',
            'email' => 'nessacoutoa@gmail.com',
            'password' => '123456'
        ];

        $response = $this->postJson('api/auth/register', $payload);
        $response->dump();

        $response->assertStatus(201)
            ->assertExactJson(
                [
                'data' => [
                    'name' => $payload['name'],
                    'email' => $payload['email'],
                ]
                ]
            );
    }
}
