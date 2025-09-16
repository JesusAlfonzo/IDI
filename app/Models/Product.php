<?php

// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'description',
        'price',
        'stock_alert_threshold',
        'supplier_id',
        'category_id'
    ];

    // Relación con las compras del producto
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Relación con todos los movimientos de inventario del producto
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purchasePrices()
    {
        return $this->hasMany(PurchasePrice::class);
    }


    public function currentStock()
    {
        $entradas = $this->inventoryMovements()->where('type', 'entrada')->sum('quantity');
        $salidas = $this->inventoryMovements()->where('type', 'salida')->sum('quantity');

        return $entradas - $salidas;
    }
}
