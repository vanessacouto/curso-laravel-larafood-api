<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\Api\TenantFormRequest;

class ProductApiController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function productsByTenant(TenantFormRequest $request)
    {
        $products = $this->productService->getProductsByTenantUuid(
            $request->token_company,
            // valor opcional para filtar os produtos por 'n' url de categorias
            $request->get('categories', []) // se não passar nada envia '[]' 
        );
        
        return ProductResource::collection($products);
    }

    public function show(TenantFormRequest $request, $identify)
    {
        if (!$product = $this->productService->getProductByUuid($identify)) {
            return response()->json(['message' => 'Product Not Found'], 404);
        }
        
        return new ProductResource($product);
    }
}
