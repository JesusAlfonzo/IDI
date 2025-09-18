<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CategoryController extends Controller
{
/**
     * Display a listing of the resource with filters.
     */
    public function index(Request $request): View
    {
        // Obtener el parámetro de búsqueda del request
        $name = $request->get('name');

        // Construir la consulta base
        $query = Category::query();

        // Aplicar el filtro si existe
        if ($name) {
            // Verifica si $name es un array y usa whereIn()
            if (is_array($name)) {
                $query->whereIn('name', $name);
            } else {
                // Si no es un array, asume que es una cadena y usa 'like'
                $query->where('name', 'like', '%' . $name . '%');
            }
        }

        $categories = $query->paginate();

        // Pasamos el valor del filtro a la vista para que se mantenga
        return view('category.index', compact('categories'))
            ->with('i', ($request->input('page', 1) - 1) * $categories->perPage());
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $category = new Category();

        return view('category.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return Redirect::route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $category = Category::find($id);

        return view('category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $category = Category::find($id);

        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return Redirect::route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Category::find($id)->delete();

        return Redirect::route('categories.index')
            ->with('success', 'Category deleted successfully');
    }
}
