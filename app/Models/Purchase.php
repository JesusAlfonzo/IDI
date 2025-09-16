<?php

// app/Models/Purchase.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'quantity',
        'unit_cost',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_cost'
    ];

    // Relación con el producto asociado a la compra
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relación con el proveedor de la compra
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
