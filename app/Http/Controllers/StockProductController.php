<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductWebRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()->with('category')->orderBy('name');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        $products = $query->paginate(15)->withQueryString();
        return view('stock.products.index', compact('products'));
    }

    public function create(): View
    {
        $product = new Product();
        return view('stock.products.create', compact('product'));
    }

    public function store(ProductWebRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Product model doesn't use timestamps; ensure created_at present
        if (empty($data['created_at'])) {
            $data['created_at'] = now()->format('Y');
        }

        $product = Product::create($data);

        return redirect()->route('stock.products.index')
            ->with('success', "Produit créé: {$product->name}");
    }

    public function edit(Product $product): View
    {
        return view('stock.products.edit', compact('product'));
    }

    public function update(ProductWebRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();
        if (empty($data['created_at'])) {
            $data['created_at'] = $product->created_at ?: now()->format('Y');
        }
        $product->update($data);

        return redirect()->route('stock.products.index')
            ->with('success', "Produit mis à jour: {$product->name}");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();
        return redirect()->route('stock.products.index')
            ->with('success', "Produit supprimé: {$name}");
    }
}


