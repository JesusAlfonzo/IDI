<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePrice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'supplier_id',
        'purchase_price',
    ];

    /**
     * Get the product associated with the purchase price.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the supplier associated with the purchase price.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}