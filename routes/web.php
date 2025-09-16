<?php

use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Auth::routes();

// ====================================================================
// Rutas de Navegación Principal
// ====================================================================

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// ====================================================================
// Rutas de Maestros (CRUDs Estándar)
// ====================================================================

Route::resource('products', ProductController::class);
Route::resource('suppliers', SupplierController::class);

// ====================================================================
// Rutas de Compras (Lógica Específica)
// ====================================================================

// Historial de compras
Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index');
// Formulario para registrar una compra
Route::get('/purchases/create', [PurchaseController::class, 'create'])->name('purchases.create');
// Proceso para guardar la compra
Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store');

// ====================================================================
// Rutas de Inventario (Lógica Específica)
// ====================================================================

// Resumen de stock actual de todos los productos
Route::get('/inventory/stock', [ProductController::class, 'inventory'])->name('inventory.stock');
// Historial completo de movimientos (entradas y salidas)
Route::get('/inventory/movements', [InventoryMovementController::class, 'index'])->name('inventory.movements.index');
// Historial de solo las salidas
Route::get('/inventory/salidas', [InventoryMovementController::class, 'salidas'])->name('inventory.salidas');
// Formulario para registrar una salida
Route::get('/inventory/out', [InventoryMovementController::class, 'createOut'])->name('inventory.create_out');
// Proceso para guardar la salida
Route::post('/inventory/out', [InventoryMovementController::class, 'storeOut'])->name('inventory.store_out');
