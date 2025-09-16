<?php

use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ====================================================================
// Rutas de Autenticación
// ====================================================================
Auth::routes();

// ====================================================================
// Rutas Públicas
// ====================================================================
Route::get('/', function () {
    return view('welcome');
});

// ====================================================================
// Rutas Protegidas (Requieren autenticación)
// ====================================================================
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Rutas para la gestión de productos y proveedores (CRUD)
    Route::resources([
        'products' => ProductController::class,
        'suppliers' => SupplierController::class,
        'categories' => CategoryController::class,
    ]);

    // Rutas de Compras
    Route::controller(PurchaseController::class)->group(function () {
        Route::get('/purchases', 'index')->name('purchases.index');
        Route::get('/purchases/create', 'create')->name('purchases.create');
        Route::post('/purchases', 'store')->name('purchases.store');
    });

    // Rutas de Inventario
    Route::controller(InventoryMovementController::class)->group(function () {
        Route::get('/inventory/movements', 'index')->name('inventory.movements.index');
        Route::get('/inventory/salidas', 'salidas')->name('inventory.salidas');
        Route::get('/inventory/out', 'createOut')->name('inventory.create_out');
        Route::post('/inventory/out', 'storeOut')->name('inventory.store_out');
    });

    // Rutas de Precios de Compra (anidadas bajo un producto)
    Route::prefix('products/{id}')->controller(ProductController::class)->group(function () {
        Route::get('purchase-prices', 'showPurchasePrices')->name('products.show-purchase-prices');
        Route::get('purchase-prices/filter', 'getFilteredPurchasePrices')->name('products.filter-purchase-prices');
        Route::post('purchase-prices', 'addPurchasePrice')->name('products.add-purchase-price');
    });

    // Rutas de Inventario (métodos específicos del ProductController)
    Route::get('/inventory/stock', [ProductController::class, 'inventory'])->name('inventory.stock');
});
