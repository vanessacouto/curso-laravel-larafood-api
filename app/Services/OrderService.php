<?php

namespace App\Services;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\TableRepositoryInterface;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;


class OrderService
{
    private $orderRepository;
    private $tenantRepository;
    private $tableRepository;
    private $productRepository;
    
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        TenantRepositoryInterface $tenantRepository,
        TableRepositoryInterface $tableRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->tenantRepository = $tenantRepository;
        $this->tableRepository = $tableRepository;
        $this->productRepository = $productRepository;
    }

    public function getOrderByIdentify(string $identify) 
    {
        return $this->orderRepository->getOrderByIdentify($identify);
    }

    public function createNewOrder(array $order)
    {
        $productsOrder = $this->getProductsByOrder($order['products'] ?? []);
        
        $identify = $this->getIdentifyOrder();
        $total = $this->getTotalOrder($productsOrder);
        $status = 'open';
        $tenantId = $this->getTenantIdByOrder($order['token_company']);
        $comment = isset($order['comment']) ?  $order['comment'] : '';
        $clientId = $this->getClientIdByOrder();
        $tableId = $this->getTableIdByOrder($order['table'] ?? '');

        $order = $this->orderRepository->createNewOrder(
            $identify, $total,
            $status, $tenantId,
            $comment,
            $clientId, $tableId
        );
        
        // insere na tabela pivo 'order_product'
        $this->orderRepository->registerProductsOrder($order->id, $productsOrder);
        
        return $order;
    } 

    // cria um identificador unico para o Pedido
    private function getIdentifyOrder(int $qtyCharacters = 8)
    {
        // embaralha um conjunto de letras
        $smallLetters = str_shuffle('abcdefghijklmnopqrstuvwxyz');

        // gera números aleatórios
        $numbers = (((date('Ymd') / 12) * 24) + mt_rand(800, 9999));
        $numbers .= 1234567890;

        // gera caracteres especiais
        // $specialCharacters = str_shuffle('!@#$%*-');

        // $characters = $smallLetters.$numbers.$specialCharacters;
        $characters = $smallLetters.$numbers;

        $identify = substr(str_shuffle($characters), 0, $qtyCharacters);

        // garante que o 'identify' gerado é único
        if ($this->orderRepository->getOrderByIdentify($identify)) {
            // chama o metodo denovo
            $this->getIdentifyOrder($qtyCharacters + 1);
        }

        return $identify;
    }

    private function getProductsByOrder(array $productsOrder):array
    {
        $products = [];

        foreach ($productsOrder as $productOrder) {
            // recupera o produto
            $product = $this->productRepository->getProductByUuid($productOrder['identify']);

            // add no array
            array_push(
                $products, [
                'id' => $product->id,
                'qty' => $productOrder['qty'],
                'price' => $product->price,
                ]
            );
        }

        return $products;
    }

    // soma o total do pedido
    private function getTotalOrder(array $products): float
    {
        $total = 0;

        foreach ($products as $product) {
            $total += ($product['price'] * $product['qty']);
        }

        return (float) $total;
    }

    private function getTenantIdByOrder(string $uuid)
    {
        $tenant = $this->tenantRepository->getTenantByUuid($uuid);
        
        return $tenant->id;
    }

    private function getTableIdByOrder(string $uuid = '')
    {
        if ($uuid) { // se informou a mesa
            $table =  $this->tableRepository->getTableByUuid($uuid);

            return $table->id;
        }

        return '';
    }

    private function getClientIdByOrder()
    {
        $clientId = auth()->check() ? auth()->user()->id : '';
        
        return $clientId;
    }
}
