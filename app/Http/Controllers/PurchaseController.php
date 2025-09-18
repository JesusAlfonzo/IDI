<?php
// app/Http/Controllers/PurchaseController.php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    /**
     * Muestra la lista de compras con filtros y paginación.
     */
    public function index(Request $request): View
    {
        // Se obtiene el array de IDs de los filtros (Select2 multi-selección)
        $product_ids = $request->get('product_id', []);
        $supplier_ids = $request->get('supplier_id', []);
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $query = Purchase::with(['product', 'supplier']);

        // Se utiliza whereIn() para filtrar por múltiples productos si se seleccionaron IDs
        if (!empty($product_ids)) {
            $query->whereIn('product_id', $product_ids);
        }

        // Se utiliza whereIn() para filtrar por múltiples proveedores si se seleccionaron IDs
        if (!empty($supplier_ids)) {
            $query->whereIn('supplier_id', $supplier_ids);
        }

        // Se mantienen los filtros de fecha
        if ($start_date) {
            $query->whereDate('created_at', '>=', $start_date);
        }

        if ($end_date) {
            $query->whereDate('created_at', '<=', $end_date);
        }

        $purchases = $query->latest()->paginate(10);
        
        // Obtenemos las colecciones de productos y proveedores seleccionados para repoblar el formulario
        $selectedProducts = !empty($product_ids) ? Product::whereIn('id', $product_ids)->get() : collect();
        $selectedSuppliers = !empty($supplier_ids) ? Supplier::whereIn('id', $supplier_ids)->get() : collect();

        return view('purchases.index', compact('purchases', 'selectedProducts', 'selectedSuppliers'))
            ->with('i', ($request->input('page', 1) - 1) * $purchases->perPage());
    }

    /**
     * Muestra el formulario para registrar una nueva compra.
     */
    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('purchases.create', compact('products', 'suppliers'));
    }

    /**
     * Procesa el formulario y guarda la compra y el movimiento de inventario.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0',
        ]);

        // 1. Calcular los costos de la compra
        $quantity = $validatedData['quantity'];
        $unit_cost = $validatedData['unit_cost'];
        $tax_rate_percentage = $validatedData['tax_rate'];
        $tax_rate_decimal = $tax_rate_percentage / 100;
        $subtotal = $quantity * $unit_cost;
        $tax_amount = $subtotal * $tax_rate_decimal;
        $total_cost = $subtotal + $tax_amount;

        // 2. Crear el registro de la compra
        Purchase::create([
            'product_id' => $validatedData['product_id'],
            'supplier_id' => $validatedData['supplier_id'],
            'quantity' => $quantity,
            'unit_cost' => $unit_cost,
            'subtotal' => $subtotal,
            'tax_rate' => $tax_rate_decimal,
            'tax_amount' => $tax_amount,
            'total_cost' => $total_cost,
        ]);

        // 3. Crear el registro del movimiento de inventario (ENTRADA)
        if (Auth::check()) {
            InventoryMovement::create([
                'product_id' => $request->product_id,
                'type' => 'entrada',
                'quantity' => $quantity,
                'reason' => 'Compra a proveedor',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('purchases.index')
            ->with('success', 'Compra registrada y stock actualizado con éxito.');
    }

    /**
     * Obtiene los precios de compra y proveedores para un producto dado.
     */
    public function getProductData(Request $request, $productId): JsonResponse
    {
        $product = Product::with(['purchasePrices.supplier'])->findOrFail($productId);
        $purchasePrices = $product->purchasePrices;
        $lowestPriceRecord = $purchasePrices->sortBy('purchase_price')->first();
        $lowestPrice = $lowestPriceRecord ? $lowestPriceRecord->purchase_price : 0;
        $lowestPriceSupplierId = $lowestPriceRecord ? $lowestPriceRecord->supplier_id : null;

        $suppliers = $purchasePrices->map(function ($price) {
            return [
                'id' => $price->supplier->id,
                'name' => $price->supplier->name,
                'price' => $price->purchase_price,
            ];
        });

        return response()->json([
            'lowest_price' => $lowestPrice,
            'suppliers' => $suppliers,
            'lowest_price_supplier_id' => $lowestPriceSupplierId,
        ]);
    }

    /**
     * Busca productos para el Select2.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchProducts(Request $request)
    {
        $search = $request->input('search');
        $products = Product::where('name', 'LIKE', "%{$search}%")
                           ->orWhere('sku', 'LIKE', "%{$search}%")
                           ->select('id', 'name', 'sku')
                           ->limit(10)
                           ->get();

        $results = [];
        foreach ($products as $product) {
            $results[] = [
                'id' => $product->id,
                'text' => $product->name . ' (' . $product->sku . ')',
            ];
        }

        return response()->json(['results' => $results]);
    }

    /**
     * Busca proveedores para el Select2.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchSuppliers(Request $request)
    {
        $search = $request->input('search');
        $suppliers = Supplier::where('name', 'LIKE', "%{$search}%")
                             ->select('id', 'name')
                             ->limit(10)
                             ->get();

        $results = [];
        foreach ($suppliers as $supplier) {
            $results[] = [
                'id' => $supplier->id,
                'text' => $supplier->name,
            ];
        }

        return response()->json(['results' => $results]);
    }
}
