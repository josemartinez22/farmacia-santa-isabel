<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'quantity', 'discount', 'product_id', 'sale_id'];

    /**
     * Get the product that owns the purchase.
     */
    public function sale() {

        return $this->belongsTo(Product::class);

    }

    /**
     * Get the product that owns the purchase.
     */
    public function product() {

        return $this->belongsTo(Product::class);

    }
}
