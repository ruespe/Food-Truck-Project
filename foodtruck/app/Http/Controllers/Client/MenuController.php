<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class MenuController extends Controller
{
    public function index(): Response
    {
        $categoryId = request()->integer('category') ?: null;

        $products = Product::with('category')
            ->where('available', true)
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->orderBy('name')
            ->paginate(6, ['id', 'category_id', 'name', 'description', 'price', 'image', 'available', 'stock'])
            ->withQueryString();

        return Inertia::render('client/Menu', [
            'categories'       => Category::orderBy('name')->get(['id', 'name']),
            'products'         => $products,
            'selectedCategory' => $categoryId,
        ]);
    }

    public function show(Product $product): Response
    {
        abort_unless($product->available, 404);

        return Inertia::render('client/ProductDetail', [
            'product' => $product->load('category'),
        ]);
    }
}
