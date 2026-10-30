<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController
{
    /**
     * Produktu saraksts.
     */
    public function index(): View
    {
        $products = Product::latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    /**
     * Jauna produkta forma.
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Saglabā jaunu produktu.
     */
    public function store(Request $request): RedirectResponse
    {
        $product = Product::create($this->validated($request));

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Produkts izveidots.');
    }

    /**
     * Viena produkta skats (route model binding: {product} -> Product).
     */
    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    /**
     * Produkta rediģēšanas forma.
     */
    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Atjaunina produktu.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validated($request));

        return redirect()
            ->route('products.show', $product)
            ->with('success', 'Produkts atjaunināts.');
    }

    /**
     * Dzēš produktu.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Produkts dzēsts.');
    }

    /**
     * Kopīgie validācijas noteikumi store() un update().
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);
    }
}
