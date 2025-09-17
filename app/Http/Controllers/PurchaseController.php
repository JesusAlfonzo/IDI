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
public function index(Request $request): View
    {
        $product_id = $request->get('product_id');
        $supplier_id = $request->get('supplier_id');
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        $query = Purchase::with(['product', 'supplier']);

        if ($product_id) {
            $query->where('product_id', $product_id);
        }

        if ($supplier_id) {
            $query->where('supplier_id', $supplier_id);
        }
        
        if ($start_date) {
            $query->whereDate('created_at', '>=', $start_date);
        }
        
        if ($end_date) {
            $query->whereDate('created_at', '<=', $end_date);
        }

        $purchases = $query->latest()->paginate(10);
        
        // Obtenemos el producto y el proveedor seleccionados (si existen) para mantener su valor en el formulario
        $selectedProduct = $product_id ? Product::find($product_id) : null;
        $selectedSupplier = $supplier_id ? Supplier::find($supplier_id) : null;

        return view('purchases.index', compact('purchases', 'selectedProduct', 'selectedSupplier'))
                ->with('i', ($request->input('page', 1) - 1) * $purchases->perPage());
    }


    // Muestra el formulario para registrar una nueva compra.
    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('purchases.create', compact('products', 'suppliers'));
    }

    // Procesa el formulario y guarda la compra y el movimiento de inventario.
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id', // Mantenemos nullable según tu migración
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            // Cambiamos la validación de tax_rate para que acepte números decimales
            // Puedes usar un rango más amplio o el valor específico si solo manejas 3% y 16%
            'tax_rate' => 'required|numeric|min:0',
        ]);

        // 1. Calcular los costos de la compra
        $quantity = $validatedData['quantity'];
        $unit_cost = $validatedData['unit_cost'];
        $tax_rate_percentage = $validatedData['tax_rate']; // Este es el valor recibido (ej. 16)

        // Convertimos el porcentaje a formato decimal para los cálculos
        // Ejemplo: 16 -> 0.16
        $tax_rate_decimal = $tax_rate_percentage / 100;

        $subtotal = $quantity * $unit_cost;

        // Usamos el tax_rate_decimal para calcular el monto del impuesto
        $tax_amount = $subtotal * $tax_rate_decimal;
        $total_cost = $subtotal + $tax_amount;

        // 2. Crear el registro de la compra en la base de datos
        Purchase::create([
            'product_id' => $validatedData['product_id'],
            'supplier_id' => $validatedData['supplier_id'],
            'quantity' => $quantity,
            'unit_cost' => $unit_cost,
            'subtotal' => $subtotal,
            // Guardamos el tax_rate como lo recibimos (el porcentaje)
            'tax_rate' => $tax_rate_decimal,
            'tax_amount' => $tax_amount, // Guardamos el monto calculado del impuesto
            'total_cost' => $total_cost,
        ]);

        // 3. Crear el registro del movimiento de inventario (ENTRADA)
        // Asegúrate de que InventoryMovement esté importado: use App\Models\InventoryMovement;
        // y que el User esté autenticado.
        if (Auth::check()) {
            InventoryMovement::create([
                'product_id' => $request->product_id,
                'type' => 'entrada',
                'quantity' => $quantity,
                'reason' => 'Compra a proveedor',
                'user_id' => Auth::id(),
            ]);
        } else {
            // Opcional: manejar caso donde el usuario no está autenticado, aunque la ruta debería requerirlo.
            // Podrías loggear un error o redirigir.
        }


        return redirect()->route('purchases.index')
            ->with('success', 'Compra registrada y stock actualizado con éxito.');
    }
    /**
     * Get the purchase prices and suppliers for a given product.
     */
    public function getProductData(Request $request, $productId): JsonResponse
    {
        $product = Product::with(['purchasePrices.supplier'])->findOrFail($productId);

        $purchasePrices = $product->purchasePrices;

        // Find the lowest price and the supplier with that price
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
                           ->limit(10) // Limita los resultados para mejor rendimiento
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

    //     /**
    //  * Busca proveedores para el Select2.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function searchSuppliers(Request $request)
    // {
    //     $search = $request->input('search');
    //     $suppliers = Supplier::where('name', 'LIKE', "%{$search}%")
    //                          ->select('id', 'name')
    //                          ->limit(10) // Limita los resultados
    //                          ->get();

    //     $results = [];
    //     foreach ($suppliers as $supplier) {
    //         $results[] = [
    //             'id' => $supplier->id,
    //             'text' => $supplier->name,
    //         ];
    //     }

    //     return response()->json(['results' => $results]);
    // }
}
