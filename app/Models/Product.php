<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'barcode', 
        'cost', 
        'price', 
        'stock', 
        'alert', 
        'status', 
        'image', 
        'category_id'
    ];

    public function category() {

        return $this->belongsTo(Category::class);

    }

    public function purchases() {

        return $this->hasMany(Purchase::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_items')->withPivot('quantity', 'discount')->orderBy('cart_items.created_at', 'DESC');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('barcode', 'like', '%'.$search.'%')
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', '%'.$search.'%');
                    });
            });
        });
    }
}
