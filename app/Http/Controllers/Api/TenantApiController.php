<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\TenantService;
use App\Http\Controllers\Controller;
use App\Http\Resources\TenantResource;

class TenantApiController extends Controller
{
    protected $tenantService;
    
    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function index() 
    {
        // por meio do resource TenantResource define exatamente como 
        // a resposta é dada: quais campos, quais nomes...
        return TenantResource::collection(
            $this->tenantService->getAllTenants()
        );
    }

    public function show($uuid) 
    {
        $tenant = $this->tenantService->getTenantByUuid($uuid);

        // retorna um unico objeto e não uma collection
        return new TenantResource($tenant);
    }
}
