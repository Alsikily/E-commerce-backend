<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this -> id,
            'en_name' => $this -> en_name,
            'ar_name' => $this -> ar_name,
            'quantity' => $this -> quantity,
            'unit_price' => $this -> unit_price,
            'discount' => $this -> discount,
            'new_price' => $this -> new_price,
            'status' => $this -> status,
            'payment' => $this -> payment,
            'delivery' => $this -> delivery,
            'delivery_charge' => $this -> delivery_charge,
            'en_description' => $this -> en_description,
            'ar_description' => $this -> ar_description,
            'cat_id' => $this -> cat_id,
            'images' => $this -> images,
            'reviews' => $this -> reviews,
            'stars' => $this -> stars,
            'addToFav' => $this -> addToFav,
            'addToCart' => $this -> addToCart,
            'rate' => $this -> rate,
            'comment' => $this -> comment,
            'en_cat_name' => $this ?-> en_cat_name,
            'ar_cat_name' => $this ?-> ar_cat_name,
            'add_by' => $this -> add_by,
            'add_by_name' => $this ?-> name
        ];
    }
}
