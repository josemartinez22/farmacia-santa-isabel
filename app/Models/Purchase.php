<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['quantity', 'total', 'supplier_id', 'product_id', 'user_id'];

    /**
     * Get the supplier that owns the purchase.
     */
    public function supplier() {

        return $this->belongsTo(Supplier::class);

    }

    /**
     * Get the product that owns the purchase.
     */
    public function product() {

        return $this->belongsTo(Product::class);

    }

    /**
     * Get the user that owns the purchase.
     */
    public function user() {

        return $this->belongsTo(User::class);

    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('created_at', 'like', '%'.$search.'%')
                ->orWhereHas('supplier', function ($query) use ($search) {
                    $query->where('suppliers.name', 'like', '%'.$search.'%');
                })
                ->orWhereHas('product', function ($query) use ($search) {
                    $query->where('products.name', 'like', '%'.$search.'%');
                })
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->where('users.first_name', 'like', '%'.$search.'%')
                    ->orWhere('users.last_name', 'like', '%'.$search.'%');
                });
            });
        });
    }

}
