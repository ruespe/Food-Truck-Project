<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Products/Index', [
            'products' => Product::with('category')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/Products/Form', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $uploaded = cloudinary()->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'foodtruck/products']
            );
            $data['image']     = $uploaded['secure_url'];
            $data['public_id'] = $uploaded['public_id'];
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('admin/Products/Form', [
            'product'    => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, $product);

        if ($request->hasFile('image')) {
            if ($product->public_id) {
                cloudinary()->uploadApi()->destroy($product->public_id);
            }
            $uploaded = cloudinary()->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'foodtruck/products']
            );
            $data['image']     = $uploaded['secure_url'];
            $data['public_id'] = $uploaded['public_id'];
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->public_id) {
            cloudinary()->uploadApi()->destroy($product->public_id);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    public function toggleStock(Product $product): RedirectResponse
    {
        $product->update(['stock' => !$product->stock]);
        $message = $product->stock
            ? "'{$product->name}' marcado con stock."
            : "'{$product->name}' marcado sin stock.";

        return redirect()->route('admin.products.index')->with('success', $message);
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description'    => 'nullable|array',
            'description.es' => 'nullable|string|max:2000',
            'description.ca' => 'nullable|string|max:2000',
            'description.en' => 'nullable|string|max:2000',
            'price'       => 'required|numeric|min:0',
            'image'       => 'nullable|image|max:2048',
            'stock'       => 'boolean',
            'available'   => 'boolean',
        ]);
    }
}
