<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\InventoryMovement;
use App\Models\User; 

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // 1. Obtener datos de resumen para las tarjetas
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $totalPurchases = Purchase::sum('total_cost');
        $totalUsers = User::count();

        // 2. Obtener productos con stock bajo (usando el método que ya creaste)
        $lowStockProducts = Product::all()->filter(function ($product) {
            return $product->currentStock() <= $product->stock_alert_threshold;
        });

        // 3. Obtener los 10 movimientos de inventario más recientes
        $recentMovements = InventoryMovement::with('product', 'user')
                                           ->orderByDesc('created_at')
                                           ->take(10)
                                           ->get();
                                           
        return view('home', compact(
            'totalProducts',
            'totalSuppliers',
            'totalPurchases',
            'totalUsers',
            'lowStockProducts',
            'recentMovements'
        ));
    }
}
