<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    /**
     * Error get Categories by Tenant
     *
     * @return void
     */
    public function testGetCategoryTenantError()
    {
        // se não passar o 'token_company' tem que dar erro
        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(422);
    }

    /**
     * Get all Categories by Tenant
     *
     * @return void
     */
    public function testGetAllCategoriesByTenant()
    {
        $tenant = Tenant::factory()->create();

        $response = $this->getJson("/api/v1/categories?token_company={$tenant->uuid}");

        $response->assertStatus(200);
    }

     /**
      * Error get Category by Tenant
      *
      * @return void
      */
    public function testErrorGetCategoryByTenant()
    {
        $category = 'fake_value';
        $tenant = Tenant::factory()->create();

        $response = $this->getJson("/api/v1/categories/{$category}?token_company={$tenant->uuid}");

        $response->assertStatus(404);
    }

     /**
      * Get Category by Tenant
      *
      * @return void
      */
      public function testGetCategoryByTenant()
      {
          $category = Category::factory()->create();
          $tenant = Tenant::factory()->create();
  
          $response = $this->getJson("/api/v1/categories/{$category->uuid}?token_company={$tenant->uuid}");
  
          $response->assertStatus(200);
      }
}
