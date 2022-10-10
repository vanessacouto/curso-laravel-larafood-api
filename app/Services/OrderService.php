<?php

namespace App\Services;

use App\Repositories\Contracts\OrderRepositoryInterface;


class OrderService
{
    private $orderRepository;
    
    public function __construct(OrderRepositoryInterface $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function createNewOrder(array $order)
    {

    } 
}
