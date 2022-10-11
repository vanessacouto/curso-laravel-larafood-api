<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
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
        string $status, int $tenantId,
        string $comment = '',
        $clientId = '', 
        $tableId = ''
    ) {
        $data = [
            'identify' => $identify,
            'total' => $total,
            'status' => $status,
            'tenant_id' => $tenantId,
            'comment' => $comment,
        ];

        if ($clientId) {
            $data['client_id'] = $clientId;
        }

        if ($tableId) {
            $data['table_id'] = $tableId;
        }

        $order = $this->entity->create($data);

        return $order;
    }
    
    public function getOrderByIdentify(string $identify)
    {
        return $this->entity->where('identify', $identify)->first();
    }

    // cadastrar na tabela pivo 'order_product'
    public function registerProductsOrder(int $orderId, array $products)
    {
        $orderProducts = [];
        
        $order = $this->entity->find($orderId);
        
        foreach ($products as $product) {
            $orderProducts[$product['id']] = [
                'qty' => $product['qty'],
                'price' => $product['price']
            ];
        }
        
        $order->products()->attach($orderProducts);

        // ** usando DB
        // $orderProducts = [];
        // foreach ($products as $product) {
        //     array_push($orderProducts, [
        //         'order_id' => $orderId,
        //         'product_id' => $product['id'],
        //         'qty' => $product['qty'],
        //         'price' => $product['price']
        //     ]);
        // }

        // // faz insert na tabela
        // DB::table('order_product')->insert($orderProducts);
        // ** FIM usando DB
    }

    public function getOrdersByClientId($idClient)
    {
        $orders = $this->entity
            ->where('client_id', $idClient)
            ->paginate();

        return $orders;
    }
}
