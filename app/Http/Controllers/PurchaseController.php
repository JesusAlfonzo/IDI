<?php
// app/Http/Controllers/PurchaseController.php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = \App\Models\Purchase::with(['product', 'supplier'])->orderByDesc('created_at')->get();
        return view('purchases.index', compact('purchases'));
    }
    // Muestra el formulario para registrar una nueva compra.
    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('purchases.create', compact('products', 'suppliers'));
    }

    // Procesa el formulario y guarda la compra y el movimiento de inventario.
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'tax_rate' => 'required|in:0.03,0.16',
        ]);

        // 1. Calcular los costos de la compra
        $quantity = $request->quantity;
        $unit_cost = $request->unit_cost;
        $tax_rate = $request->tax_rate;
        $subtotal = $quantity * $unit_cost;
        $tax_amount = $subtotal * $tax_rate;
        $total_cost = $subtotal + $tax_amount;

        // 2. Crear el registro de la compra en la base de datos
        Purchase::create([
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $quantity,
            'unit_cost' => $unit_cost,
            'subtotal' => $subtotal,
            'tax_rate' => $tax_rate,
            'tax_amount' => $tax_amount,
            'total_cost' => $total_cost,
        ]);

        // 3. Crear el registro del movimiento de inventario (ENTRADA)
        InventoryMovement::create([
            'product_id' => $request->product_id,
            'type' => 'entrada',
            'quantity' => $quantity,
            'reason' => 'Compra a proveedor',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('home')->with('success', 'Compra registrada y stock actualizado con éxito.');
    }
}
