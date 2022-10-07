<?php

namespace App\Services;

use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\TableRepositoryInterface;

class TableService
{
    private $tenantRepository;
    private $tableRepository;
    
    public function __construct(
        TableRepositoryInterface $tableRepository,
        TenantRepositoryInterface $tenantRepository
    ) {
        $this->tableRepository = $tableRepository;
        $this->tenantRepository = $tenantRepository;
    }

    public function getTablesByUuid($uuid) 
    {
        $tenant = $this->tenantRepository->getTenantByUuid($uuid);
        
        return $this->tableRepository->tablesByTenanId($tenant->id);
    }

    public function getTableByIdentify(string $identify) 
    {
        return $this->tableRepository->getTableByIdentify($identify);
    }
}
