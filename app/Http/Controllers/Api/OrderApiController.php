<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOrder;
use App\Http\Resources\OrderResource;

class OrderApiController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(StoreOrder $request)
    {
        $order = $this->orderService->createNewOrder($request->all());
        
        return new OrderResource($$order);
    }
}
