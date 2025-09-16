<?php
// app/Http/Controllers/InventoryMovementController.php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryMovementController extends Controller
{
    public function index()
    {
        $movements = \App\Models\InventoryMovement::with(['product', 'user'])->orderByDesc('created_at')->get();
        return view('inventory.index', compact('movements'));
    }
    // Muestra el formulario para registrar una salida.
    public function createOut()
    {
        $products = Product::all();
        return view('inventory.create_out', compact('products'));
    }

    // Procesa el formulario y guarda la salida de inventario.
    public function storeOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        // 1. Opcional: Validar que hay suficiente stock antes de la salida
        $product = Product::find($request->product_id);
        $currentStock = $this->calculateCurrentStock($product);
        if ($request->quantity > $currentStock) {
            return back()->withErrors(['quantity' => 'No hay suficiente stock.'])->withInput();
        }

        // 2. Crear el registro del movimiento de inventario (SALIDA)
        InventoryMovement::create([
            'product_id' => $request->product_id,
            'type' => 'salida',
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('home')->with('success', 'Salida de inventario registrada con éxito.');
    }

    // Método para calcular el stock actual de un producto.
    private function calculateCurrentStock($product)
    {
        $entradas = $product->inventoryMovements()->where('type', 'entrada')->sum('quantity');
        $salidas = $product->inventoryMovements()->where('type', 'salida')->sum('quantity');

        return $entradas - $salidas;
    }


    public function salidas()
    {
        $salidas = \App\Models\InventoryMovement::with('product', 'user')
            ->where('type', 'salida')
            ->orderByDesc('created_at')
            ->get();

        return view('inventory.salidas', compact('salidas'));
    }
}
