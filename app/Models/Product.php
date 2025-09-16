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
        'stock_alert_threshold'
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
}