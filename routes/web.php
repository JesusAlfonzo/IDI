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
})->name('welcome'); // Agregado nombre a la ruta welcome

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

        // Ruta específica para buscar productos para Select2
        Route::get('/purchases/search-products', 'searchProducts')->name('purchases.search-products');
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

    // Ruta para obtener datos específicos de un producto (si es necesario para la creación de compras)
    Route::get('purchases/get-product-data/{productId}', [PurchaseController::class, 'getProductData'])->name('purchases.get-product-data');

    // Rutas específicas para la búsqueda de proveedores mediante AJAX
    // Se movió la ruta de búsqueda de proveedores a un controlador dedicado para mayor claridad.
    // Si tienes un método 'searchSuppliers' en PurchaseController, debes moverlo a SupplierController
    // o asegurarte de que PurchaseController tenga acceso a esa lógica.
    // Para el Select2 en la vista de compras, la llamada AJAX apunta a 'purchases.search-products'.
    // Para el Select2 de proveedores, la llamada AJAX en tu vista (en el @push('js')) debería apuntar
    // a una ruta que llame a un método de búsqueda de proveedores.
    // He añadido 'purchases.search-suppliers' pero asumo que el método 'searchSuppliers' existe en PurchaseController.
    // Si el método está en SupplierController, debes ajustar la ruta y la llamada JS.

    // Si la lógica de búsqueda de proveedores está en PurchaseController:
    Route::get('/purchases/search-suppliers', [PurchaseController::class, 'searchSuppliers'])->name('purchases.search-suppliers');

    // Si la lógica de búsqueda de proveedores está en SupplierController (esta ruta es más apropiada si la búsqueda
    // se hace desde la vista de proveedores o de forma general):
    Route::get('suppliers/search-suppliers', [SupplierController::class, 'searchSuppliers'])->name('suppliers.search-suppliers');
    Route::get('/suppliers/search', [SupplierController::class, 'search'])->name('suppliers.search');


});