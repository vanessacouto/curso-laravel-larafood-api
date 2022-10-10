<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    protected $entity;

    public function __construct(Order $order)
    {
        $this->entity = $order;
    }

    public function createNewOrder(
        string $identify, float $total,
        string $status, int $tenantId, $clientId = '',
        $tableId = ''
    )
    {

    }
    
    public function getOrderByIdentify(string $identify)
    {
        
    }
}