<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;


class SupplierController extends Controller
{
 /**
     * Display a listing of the resource with filters.
     */
    public function index(Request $request): View
    {
        // Obtener los parámetros de búsqueda del request
        $name = $request->get('name');
        $address = $request->get('address');
        $phone = $request->get('phone');

        // Construir la consulta base
        $query = Supplier::query();

        // Aplicar filtros si existen
        if ($name) {
            // Manejar 'name' como un array si es una selección múltiple
            if (is_array($name)) {
                $query->whereIn('name', $name);
            } else {
                $query->where('name', 'like', '%' . $name . '%');
            }
        }

        if ($address) {
            // Manejar 'address' como un array si es una selección múltiple
            if (is_array($address)) {
                $query->whereIn('address', $address);
            } else {
                $query->where('address', 'like', '%' . $address . '%');
            }
        }

        if ($phone) {
            // Manejar 'phone' como un array si es una selección múltiple
            if (is_array($phone)) {
                $query->whereIn('phone', $phone);
            } else {
                $query->where('phone', 'like', '%' . $phone . '%');
            }
        }


        $suppliers = $query->paginate();

        // Pasamos los valores de los filtros a la vista para que se mantengan
        return view('supplier.index', compact('suppliers'))
            ->with('i', ($request->input('page', 1) - 1) * $suppliers->perPage())
            ->with('request', $request->all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $supplier = new Supplier();

        return view('supplier.create', compact('supplier'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        Supplier::create($request->validated());

        return Redirect::route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $supplier = Supplier::find($id);

        return view('supplier.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $supplier = Supplier::find($id);

        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->validated());

        return Redirect::route('suppliers.index')
            ->with('success', 'Supplier updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Supplier::find($id)->delete();

        return Redirect::route('suppliers.index')
            ->with('success', 'Supplier deleted successfully');
    }

    // public function searchSuppliers(Request $request): JsonResponse
    // {
    //     $search = $request->get('search');
        
    //     $suppliers = Supplier::where('name', 'like', '%' . $search . '%')
    //                          ->orWhere('phone', 'like', '%' . $search . '%')
    //                          ->limit(20) // Limita los resultados para optimizar
    //                          ->get(['id', 'name']); // Devuelve solo los campos necesarios

    //     $formattedSuppliers = $suppliers->map(function($supplier) {
    //         return [
    //             'id' => $supplier->id,
    //             'text' => $supplier->name, // El texto que se mostrará en Select2
    //         ];
    //     });

    //     return response()->json([
    //         'results' => $formattedSuppliers,
    //     ]);
    // }
}
