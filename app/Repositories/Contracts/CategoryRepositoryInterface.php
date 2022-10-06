<?php

namespace App\Repositories\Contracts;

interface CategoryRepositoryInterface
{
    public function categoriesByTenantUuid(string $uuid);
    public function categoriesByTenanId(int $idTenant);
    public function getCategoryByUrl(string $url);
}