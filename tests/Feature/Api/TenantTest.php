<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantTest extends TestCase
{
    /**
     * Test get all Tenants
     *
     * @return void
     */
    public function testGetAllTenants()
    {
        // cria 1 Tenant
        Tenant::factory()->count(1)->create();

        $response = $this->getJson('/api/v1/tenants');

        $response->dump(); // mostra o retorno do endpoint

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data'); // conta quantos tenants tem no 'data' do JSON
    }

    /**
     * Test get error single Tenant
     *
     * @return void
     */
    public function testErrorGetTenant()
    {
        $tenant = 'fake_value';

        $response = $this->getJson("/api/v1/tenants/{$tenant}");

        $response->assertStatus(404);
    }

     /**
     * Test get single Tenant
     *
     * @return void
     */
    public function testGetTenantByIdentify()
    {
        $tenant = Tenant::factory()->create();

        $response = $this->getJson("/api/v1/tenants/{$tenant->uuid}");

        $response->assertStatus(200);
    }
}
