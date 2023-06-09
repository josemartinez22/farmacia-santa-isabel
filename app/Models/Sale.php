<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['total', 'items', 'cash', 'change', 'status', 'name', 'address', 'duiornit', 'user_id'];

    public function saleDetails() {

        return $this->hasMany(SaleDetail::class);
    }

    /**
     * Get the user that owns the Sale.
     */
    public function user() {

        return $this->belongsTo(User::class);

    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('id', 'like', '%'.$search.'%')
                    ->orWhere('created_at', 'like', '%'.$search.'%')
                ->orWhereHas('user', function ($query) use ($search) {
                    $query->where('users.first_name', 'like', '%'.$search.'%')
                    ->orWhere('users.last_name', 'like', '%'.$search.'%');
                });
            });
        });
    }

}
