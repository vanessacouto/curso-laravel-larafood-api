<?php

namespace App\Services;

use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryService
{
    private $tenantRepository;
    private $categoryRepository;
    
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        TenantRepositoryInterface $tenantRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->tenantRepository = $tenantRepository;
    }

    public function getCategoriesByUuid($uuid) 
    {
        $tenant = $this->tenantRepository->getTenantByUuid($uuid);
        
        return $this->categoryRepository->categoriesByTenanId($tenant->id);
    }

    public function getCategoryByUuid(string $uuid) 
    {
        return $this->categoryRepository->getCategoryByUuid($uuid);
    }
}
