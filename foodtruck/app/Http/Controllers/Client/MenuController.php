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
        return Inertia::render('client/Menu', [
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'products'   => Product::with('category')
                ->where('available', true)
                ->orderBy('name')
                ->get(['id', 'category_id', 'name', 'description', 'price', 'image', 'available', 'stock']),
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
