<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Table;
use App\Models\Client;
use App\Models\Tenant;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    /**
     * Validation Create New Order
     *
     * @return void
     */
    public function testValidationCreateNewOrder()
    {
        $response = $this->postJson('/api/v1/orders');

        $response->assertStatus(422)
            ->assertJsonPath(
                'errors.token_company', [
                trans('validation.required', ['attribute' => 'token company'])
                ]
            )
            ->assertJsonPath(
                'errors.products', [
                trans('validation.required', ['attribute' => 'products'])
                ]
            );
    }

    /**
     * Create New Order
     *
     * @return void
     */
    public function testCreateNewOrder()
    {
        $tenant = Tenant::factory()->create();
        
        $payload = [
            'token_company' => $tenant->uuid,
            'products' => []
        ];
        
        $products = Product::factory()->count(2)->create();
        
        foreach ($products as $product)
        {
            array_push(
                $payload['products'], [
                'identify' => $product->uuid,
                'qty' => 2
                ]
            );
        }

        $response = $this->postJson('/api/v1/orders', $payload);

        $response->assertStatus(201);
    }

    /**
     * Test total order
     *
     * @return void
     */
    public function testTotalOrder()
    {
        $tenant = Tenant::factory()->create();
        
        $payload = [
            'token_company' => $tenant->uuid,
            'products' => []
        ];
        
        $products = Product::factory()->count(2)->create();
        
        foreach ($products as $product) {
            array_push(
                $payload['products'], [
                'identify' => $product->uuid,
                'qty' => 1
                ]
            );
        }

        $response = $this->postJson('/api/v1/orders', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.total', 25.8);
    }

    /**
     * Test order not found
     *
     * @return void
     */
    public function testOrderNotFound()
    {
        $order = 'fake_value';

        $response = $this->getJson("/api/v1/orders/{$order}");
        
        $response->assertStatus(404);
    }

    /**
     * Test get order
     *
     * @return void
     */
    public function testGetOrder()
    {
        $order = Order::factory()->create();

        $response = $this->getJson("/api/v1/orders/{$order->identify}");
        
        $response->assertStatus(200);
    }

     /**
      * Create new order authenticated
      *
      * @return void
      */
    public function testCreateNewOrderAuthenticated()
    {
        $client = Client::factory()->create();
        $token = $client->createToken(Str::random(10))->plainTextToken;
        $tenant = Tenant::factory()->create();
        
        $payload = [
            'token_company' => $tenant->uuid,
            'products' => []
        ];
        
        $products = Product::factory()->count(2)->create();
        
        foreach ($products as $product) {
            array_push(
                $payload['products'], [
                'identify' => $product->uuid,
                'qty' => 1
                ]
            );
        }

        $response = $this->postJson(
            '/api/auth/v1/orders', $payload, [
            'Authorization' => "Bearer {$token}",
            ]
        );

        $response->assertStatus(201);
    }

    /**
     * Create new order with table
     *
     * @return void
     */
    public function testCreateNewOrderWithTable()
    {
        $table = Table::factory()->create();
        $tenant = Tenant::factory()->create();
          
        $payload = [
            'token_company' => $tenant->uuid,
            'table' => $table->uuid,
            'products' => []
        ];
          
        $products = Product::factory()->count(2)->create();
          
        foreach ($products as $product) {
            array_push(
                $payload['products'], [
                'identify' => $product->uuid,
                'qty' => 1
                ]
            );
        }
  
        $response = $this->postJson('/api/v1/orders', $payload);
  
        $response->assertStatus(201);
    }

    /**
     * Get my orders
     *
     * @return void
     */
    public function testGetMyOrders()
    {
        $client = Client::factory()->create();
        $token = $client->createToken(Str::random(10))->plainTextToken;
  
        //cria um Pedido
        Order::factory()->create();

        $response = $this->getJson(
            '/api/auth/v1/my-orders', [
            'Authorization' => "Bearer {$token}",
            ]
        );
  
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data'); // deve retornar 1 pedido
    }
}
