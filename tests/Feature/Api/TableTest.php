<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Table;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TableTest extends TestCase
{
   /**
     * Error get Tables by Tenant
     *
     * @return void
     */
    public function testGetAllTablesTenantError()
    {
        // se não passar o 'token_company' tem que dar erro
        $response = $this->getJson('/api/v1/tables');

        $response->assertStatus(422);
    }

    /**
     * Get all Tables by Tenant
     *
     * @return void
     */
    public function testGetAllTablesByTenant()
    {
        $tenant = Tenant::factory()->create();

        $response = $this->getJson("/api/v1/tables?token_company={$tenant->uuid}");

        $response->assertStatus(200);
    }

     /**
      * Error get Table by Tenant
      *
      * @return void
      */
    public function testErrorGetTableByTenant()
    {
        $table = 'fake_value';
        $tenant = Tenant::factory()->create();

        $response = $this->getJson("/api/v1/tables/{$table}?token_company={$tenant->uuid}");

        $response->assertStatus(404);
    }

     /**
      * Get Table by Tenant
      *
      * @return void
      */
      public function testGetTableByTenant()
      {
          $table = Table::factory()->create();
          $tenant = Tenant::factory()->create();
  
          $response = $this->getJson("/api/v1/tables/{$table->uuid}?token_company={$tenant->uuid}");
  
          $response->assertStatus(200);
      }
}
