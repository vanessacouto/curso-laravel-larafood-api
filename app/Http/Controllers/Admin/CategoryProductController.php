<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryProductController extends Controller
{
    protected $product, $category;

    public function __construct(Product $product, Category $category)
    {
        $this->product = $product;
        $this->category = $category;

        $this->middleware('can:products');
    }

    // lista as categorias de um produto
    public function categories($idProduct) 
    {
        $product = $this->product->find($idProduct);
        
        if (!$product) {
            return redirect()->back();
        }

        $categories = $product->categories()->paginate();

        return view('admin.pages.products.categories.categories', compact('product', 'categories'));
    }

    // lista as categorias disponiveis para serem vinculadas a um produto
    // esse metodo tambem é chamado ao filtrar as categorias
    public function categoriesAvailable(Request $request, $idProduct)
    {
        if (!$product = $this->product->find($idProduct)) {
            return redirect()->back();
        }

        $filters = $request->except('_token');

        // só exibe as categorias que ainda não estao ligadas ao produto
        $categories = $product->categoriesAvailable($request->filter);

        return view('admin.pages.products.categories.available', compact('product', 'categories', 'filters'));
    }

    public function attachCategoriesProduct(Request $request, $idProduct)
    {
        if (!$product = $this->product->find($idProduct)) {
            return redirect()->back();
        }

        // verifica se selecionou algo
        if (!$request->categories || count($request->categories) == 0) {
            return redirect()
                ->back()
                ->with('info', 'Pelo menos uma categoria deve ser selecionada');
        }

        // cada item selecionado na tabela vai para um array
        $product->categories()->attach($request->categories);
        
        return redirect()->route('products.categories', $product->id);
    }

    public function detachCategoryProduct($idProduct, $idCategory)
    {
        $product = $this->product->find($idProduct);
        $category = $this->category->find($idCategory);

        if (!$product || !$category) {
            return redirect()->back();
        }

        $product->categories()->detach($category);
        
        return redirect()->route('products.categories', $product->id);
    }

    // lista os produtos vinculados a uma categoria
    public function products($idCategory) 
    {
        $category = $this->category->find($idCategory);
        
        if (!$category) {
            return redirect()->back();
        }

        $products = $category->products()->paginate();

        return view('admin.pages.categories.products.products', compact('products', 'category'));
    }
}
