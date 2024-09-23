<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\User;
use App\Models\Category;
use App\Models\UserProduct;

class Product extends Model {

    use HasFactory;
    protected $guarded = [];

    public function category() {
        return $this -> belongsTo(Category::class, 'cat_id', 'id');
    }

    public function UserProduct() {
        return $this -> hasOne(UserProduct::class, 'product_id', 'id');
    }

    public function scopeSearchByAll($query, $searchQuery) {
        $query -> whereAny(
            [
                'en_name',
                'ar_name',
                'en_description',
                'ar_description'
            ],
            'LIKE',
            "%$searchQuery%"
        );
    }

    public function product_reviews() {
        return $this -> hasMany(UserProduct::class, 'product_id', 'id');
    }

}
