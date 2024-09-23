<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Classes
use Illuminate\Support\Facades\Auth;

// Models
use App\Models\User;
use App\Models\Product;

class UserProduct extends Model {

    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public static function totalInvoiceValue() {

        $invoiceValue = UserProduct::selectRaw('SUM(((products.unit_price - (products.unit_price * (products.discount / 100))) * user_products.quantity) + products.delivery_charge) as total_invoice_value')
                                    -> join('products', 'products.id', 'user_products.product_id')
                                    -> where('user_id', Auth::user() -> id)
                                    -> where('user_products.addToCart', '=', 1)
                                    -> whereNull('transaction_id')
                                    -> value('total_invoice_value');

        return $invoiceValue;

    }

    public static function cartProducts($OrderId) {

        return UserProduct::selectRaw('product_id, quantity, ' . $OrderId . ' as order_id')
                            -> where('user_id', Auth::user() -> id)
                            -> where('addToCart', 1)
                            -> get();

    }

    public function user() {
        return $this -> belongsTo(User::class, 'user_id', 'id');
    }

    public function product() {
        return $this -> belongsTo(Product::class, 'product_id', 'id');
    }

}
