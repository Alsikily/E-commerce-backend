<?php

namespace App\Observers;

use App\Models\Product;

class productObserver {

    public function retrieved(Product $product): void {
        $product -> new_price = $product -> unit_price - ($product -> unit_price * ($product -> discount / 100));
        $product -> product_review = $product -> reviews > 0 ? round($product -> stars / $product -> reviews) : 0;
    }

}
