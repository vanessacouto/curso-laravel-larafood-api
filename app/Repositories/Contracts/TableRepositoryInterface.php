<?php

namespace App\Repositories\Contracts;

interface TableRepositoryInterface
{
    public function tablesByTenantUuid(string $uuid);
    public function tablesByTenanId(int $idTenant);
    public function getTableByIdentify(string $identify);
}