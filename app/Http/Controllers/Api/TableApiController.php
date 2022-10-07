<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\TableService;
use App\Http\Controllers\Controller;
use App\Http\Resources\TableResource;
use App\Http\Requests\Api\TenantFormRequest;

class TableApiController extends Controller
{
    protected $tableService;

    public function __construct(TableService $tableService)
    {
        $this->tableService = $tableService;
    }

    public function tablesByTenant(TenantFormRequest $request)
    {
        $tables = $this->tableService->getTablesByUuid($request->token_company);
        
        return TableResource::collection($tables);
    }

    public function show(TenantFormRequest $request, $identify) 
    {
        if (!$table = $this->tableService->getTableByIdentify($identify)) {
            return response()->json(['message' => 'Table Not Found'], 404);
        }
        
        return new TableResource($table);
    }
}
