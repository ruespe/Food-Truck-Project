<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/Categories/Index', [
            'categories' => Category::withCount('products')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'           => 'required|array',
            'name.es'        => 'required|string|max:255',
            'name.ca'        => 'nullable|string|max:255',
            'name.en'        => 'nullable|string|max:255',
            'description'    => 'nullable|array',
            'description.es' => 'nullable|string|max:1000',
            'description.ca' => 'nullable|string|max:1000',
            'description.en' => 'nullable|string|max:1000',
        ]);

        Category::create($request->only('name', 'description'));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name'           => 'required|array',
            'name.es'        => 'required|string|max:255',
            'name.ca'        => 'nullable|string|max:255',
            'name.en'        => 'nullable|string|max:255',
            'description'    => 'nullable|array',
            'description.es' => 'nullable|string|max:1000',
            'description.ca' => 'nullable|string|max:1000',
            'description.en' => 'nullable|string|max:1000',
        ]);

        $category->update($request->only('name', 'description'));

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->withErrors(['category' => 'No se puede eliminar una categoría con productos asociados.']);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
