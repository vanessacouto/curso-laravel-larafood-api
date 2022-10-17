<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    private $repository;

    public function __construct(Order $order)
    {
        $this->repository = $order;
    }

    public function index()
    {
        return view('admin.pages.orders.index');
    }
}
