<?php

namespace App\Repositories\Contracts;

interface ProductRepositoryInterface
{
    public function getProductsByTenantId(int $id, array $categories);
    public function getProductByUuid(string $uuid);
}