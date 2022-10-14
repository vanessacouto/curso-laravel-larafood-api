<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EvaluationTest extends TestCase
{
    /**
     * Error create evaluation
     *
     * @return void
     */
    public function testErrorCreateNewEvaluation()
    {
        $order = 'fake_value';

        $response = $this->postJson("/auth/v1/orders/{$order}/evaluations");

        $response->assertStatus(404);
    }

    /**
     * Create evaluation
     *
     * @return void
     */
    // public function testCreateNewEvaluation()
    // {
    //     $client = Client::factory()->create();
    //     $token = $client->createToken(Str::random(10))->plainTextToken;

    //     // cria um novo pedido relacionado com o Client
    //     $order = $client->orders()->save(Order::factory()->make());

    //     $payload = [
    //         'stars' => 5
    //     ];

    //     $headers = [
    //         'Authorization' => "Bearer {$token}"
    //     ];

    //     $response = $this->postJson(
    //         "/auth/v1/orders/{$order}/evaluations",
    //         $payload,
    //         $headers
    //     );

    //     $response->dump();

    //     $response->assertStatus(201);
    // }
}
