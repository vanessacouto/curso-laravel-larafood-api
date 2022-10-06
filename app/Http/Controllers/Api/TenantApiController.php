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

    public function index(Request $request) 
    {
        //captura o valor 'per-page' do request (se não informado, o valor default é 15)
        // o 'per-page' é o numero de registros a ser exibido por pagina (http://larafood.test/api/tenants?per_page=1)
        $per_page = (int) $request->get('per_page', 15); 
        
        $tenants = $this->tenantService->getAllTenants($per_page);
        
        // por meio do resource TenantResource define exatamente como 
        // a resposta é dada: quais campos, quais nomes...
        return TenantResource::collection($tenants);
    }

    public function show($uuid) 
    {
        // se não encontrar
        if (!$tenant = $this->tenantService->getTenantByUuid($uuid)) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        // retorna um unico objeto e não uma collection
        return new TenantResource($tenant);
    }
}
