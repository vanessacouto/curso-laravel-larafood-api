<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Product;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    /**
     * Error get all Products
     *
     * @return void
     */
    public function testErrorGetAllProducts()
    {
        // ocorre erro pois não está passand o valor do 'token_company'
        $response = $this->getJson('/api/v1/products');

        $response->assertStatus(422);
    }

    /**
     * Get all Products
     *
     * @return void
     */
    public function testGetAllProducts()
    {
        $tenant = Tenant::factory()->create();
        
        $response = $this->getJson("/api/v1/products?token_company={$tenant->uuid}");

        $response->assertStatus(200);
    }

    /**
     * Not found Product
     *
     * @return void
     */
    public function testNotFoundProduct()
    {
        $tenant = Tenant::factory()->create();
        $product = 'fake_value';
        
        $response = $this->getJson("/api/v1/products/{$product}?token_company={$tenant->uuid}");

        $response->assertStatus(404);
    }

    /**
     * Get Product by Identify
     *
     * @return void
     */
    public function testGetProductByIdentify()
    {
        $tenant = Tenant::factory()->create();
        $product = Product::factory()->create();
        
        $response = $this->getJson("/api/v1/products/{$product->uuid}?token_company={$tenant->uuid}");

        $response->assertStatus(200);
    }
}
