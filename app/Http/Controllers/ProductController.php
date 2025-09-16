<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\PurchasePrice;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\PurchasePriceRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
/**
     * Display a listing of the resource with filters.
     */
    public function index(Request $request): View
    {
        // Obtener los parámetros de búsqueda del request
        $name = $request->get('name');
        $sku = $request->get('sku');
        $categoryId = $request->get('category_id');
        $supplierId = $request->get('supplier_id');

        // Construir la consulta base
        $query = Product::with(['category', 'supplier']);

        // Aplicar filtros si existen
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($sku) {
            $query->where('sku', 'like', '%' . $sku . '%');
        }
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($supplierId) {
            $query->where('supplier_id', $supplierId);
        }

        $products = $query->paginate();

        $categories = Category::pluck('name', 'id');
        $suppliers = Supplier::pluck('name', 'id');

        // Pasamos los valores de los filtros a la vista para que se mantengan
        return view('product.index', compact('products', 'categories', 'suppliers'))
            ->with('i', ($request->input('page', 1) - 1) * $products->perPage())
            ->with('request', $request->all());
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $product = new Product();
        $categories = Category::pluck('name', 'id');
        $suppliers = Supplier::pluck('name', 'id');

        return view('product.create', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());
        

        return Redirect::route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $product = Product::find($id);

        return view('product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $product = Product::find($id);
        $categories = Category::pluck('name', 'id');
        $suppliers = Supplier::pluck('name', 'id');

        return view('product.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return Redirect::route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Product::find($id)->delete();

        return Redirect::route('products.index')
            ->with('success', 'Product deleted successfully');
    }

    public function inventory()
    {
        $products = Product::all(); // Obtiene todos los productos
        return view('product.inventory', compact('products'));
    }

    /**
     * Display a listing of purchase prices for a specific product.
     */
    public function showPurchasePrices($id): View
    {
        $product = Product::with('purchasePrices.supplier')->findOrFail($id);
        $suppliers = Supplier::all(); // We need all suppliers to show in the dropdown

        return view('product.purchase_prices', compact('product', 'suppliers'));
    }

    /**
     * Store a newly created purchase price in storage.
     */
    public function addPurchasePrice(PurchasePriceRequest $request, $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

         // Get the validated data from the request
        $validatedData = $request->validated();

        $product->purchasePrices()->updateOrCreate(
            [
                'supplier_id' => $validatedData['supplier_id'],
            ],
            [
                'purchase_price' => $validatedData['purchase_price'],
            ]
        );

        return redirect()->route('products.show-purchase-prices', $product->id)
            ->with('success', 'Precio de compra añadido exitosamente.');
    }

    /**
     * Get the purchase prices for a specific product and supplier (via AJAX).
     */
    public function getFilteredPurchasePrices(Request $request, $productId): JsonResponse
    {
        $supplierId = $request->input('supplier_id');

        // Si no se selecciona un proveedor, devolver todos los precios
        if (empty($supplierId)) {
            $purchasePrices = Product::findOrFail($productId)
                                    ->purchasePrices()
                                    ->with('supplier')
                                    ->get();
        } else {
            // Filtrar los precios por el ID del proveedor
            $purchasePrices = Product::findOrFail($productId)
                                    ->purchasePrices()
                                    ->where('supplier_id', $supplierId)
                                    ->with('supplier')
                                    ->get();
        }

        // Devolver los datos en formato JSON
        return response()->json([
            'data' => $purchasePrices,
        ]);
    }
}

