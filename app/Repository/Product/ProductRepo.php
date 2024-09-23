<?php

namespace App\Repository\Product;

// Classes
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// Interface
use App\Repository\Product\ProductRepoInterface;

// Models
use App\Models\Product;
use App\Models\Order;

// Resources
use App\Http\Resources\Product\ProductResource;

class ProductRepo implements ProductRepoInterface {

    public function getMyProducts() {

        $products = Product::select('id', 'en_name', 'ar_name') -> where("add_by", Auth::user() -> id) -> get();
        return response() -> json([
            'status' => 'success',
            'data' => $products
        ]);

    }

    public function getOrders() {

        $orders = Order::select('InvoiceValue', 'paid', 'payment', 'id', 'user_id', 'address')
        -> with([
            'products' => function($query) {
                $query -> select('en_name', 'ar_name', 'unit_price', 'discount') -> where('add_by', Auth::user() -> id);
            },
            'user:name,id'
        ])
        -> where('status', 'ordered')
        -> get();

        return response() -> json([
            'status' => 'success',
            'data' => $orders
        ]);

    }

    private function storeProductImages($images) {

        $FilesSoted = [];
        foreach($images as $image) {
            $storedPath = Storage::disk('products') -> put('images', $image);
            $FilesSoted[] = asset('storage/products'. '/' . $storedPath);
        }
        return $FilesSoted;

    }

    public function addProduct($request) {

        try {

            $requestData = $request -> except(['images']);

            $imagesStored = json_encode($this -> storeProductImages($request -> images));

            $requestData['images'] = $imagesStored;
            $requestData['add_by'] = Auth::user() -> id;

            $product = Product::create($requestData);

            return response() -> json([
                'status' => 'success',
                'product' => $product
            ], 201);

        } catch (\Throwable $th) {

            return response() -> json([
                'status' => 'error',
                'message' => $th
            ]);

        }

    }

    public function delivered($request) {

        $orderID = $request -> orderID;
        $VerificationCode = $request -> VerificationCode;

        $updated = Order::where('id', $orderID)
                -> where('verification_code', $VerificationCode)
                -> where('user_id', Auth::user() -> id)
                -> where('status', 'ordered')
                -> update([
                    'status' => 'delivered'
                ]);

        return response() -> json([
            'status' => $updated ? 'success' : 'error'
        ]);

    }

}
