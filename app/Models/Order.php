<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\User;
use App\Models\Product;
// use App\Models\OrderProduct;

class Order extends Model {

    use HasFactory;
    protected $guarded = [];

    public function products() {
        return $this -> belongsToMany(Product::class, 'order_products')
                    -> withPivot('quantity');
    }

    public function user() {
        return $this -> belongsTo(User::class, 'user_id');
    }

    protected static function boot() {

        parent::boot();

        static::creating(function ($model) {
            $model -> verification_code = rand(1, 9999);
        });

    }
}
