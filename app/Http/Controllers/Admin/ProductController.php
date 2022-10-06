<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreUpdateProduct;

class ProductController extends Controller
{
    private $repository;

    public function __construct(Product $product)
    {
        $this->repository = $product;

        $this->middleware('can:products');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->repository->latest()->paginate();

        return view(
            'admin.pages.products.index', compact('products')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(
            'admin.pages.products.create'
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Requests\StoreUpdateProduct $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateProduct $request)
    {
        $data = $request->all();

        // tenant do usuário autenticado
        $tenant = auth()->user()->tenant;

        // salva a imagem
        if ($request->hasFile('image') && $request->image->isValid()) {
            // organiza as pastas por tenant
            $data['image'] = $request->image->store("tenants/{$tenant->uuid}/products");
        }

        $this->repository->create($data);

        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!$product = $this->repository->find($id)) {
            return redirect()->back();
        }

        return view(
            'admin.pages.products.show', compact('product')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!$product = $this->repository->find($id)) {
            return redirect()->back();
        }

        return view(
            'admin.pages.products.edit', compact('product')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Requests\StoreUpdateProduct $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateProduct $request, $id)
    {
        if (!$product = $this->repository->find($id)) {
            return redirect()->back();
        }

        $data = $request->all();
        // tenant do usuário autenticado
        $tenant = auth()->user()->tenant;

        // salva a imagem
        if ($request->hasFile('image') && $request->image->isValid()) {

            // remove a imagem anterior
            if (Storage::exists($product->image)) {
                Storage::delete($product->image);
            }

            // organiza as pastas por tenant
            $data['image'] = $request->image->store("tenants/{$tenant->uuid}/products");
        }

        $product->update($data);

        return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$product = $this->repository->find($id)) {
            return redirect()->back();
        }

        // remove a imagem anterior
        if (Storage::exists($product->image)) {
            Storage::delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index');
    }

    /**
     * Search results
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $filters = $request->only('filter');

        $products = $this->repository
            ->where(
                function ($query) use ($request) {
                    if ($request->filter) {
                        $query->orWhere('description', 'LIKE', "%{$request->filter}%");
                        $query->orWhere('title', $request->filter);
                    }
                }
            )
            ->latest()
            ->paginate();

        return view('admin.pages.products.index', compact('products', 'filters'));
    }
}
