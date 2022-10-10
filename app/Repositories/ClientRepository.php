<?php

namespace App\Repositories;

use App\Models\Client;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    protected $entity;

    public function __construct(Client $client)
    {
        $this->entity = $client;
    }

    public function createNewClient(array $data)
    {
        return $this->entity->create($data);
    }
    
    public function getClient(int $id)
    {
        
    }
}
