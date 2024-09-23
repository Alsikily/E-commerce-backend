<?php

namespace App\Repository\Product;

// Classes
use Illuminate\Support\Facades\Auth;

// Interface
use App\Repository\Product\CustomerProductRepoInterface;

// Models
use App\Models\Product;
use App\Models\UserProduct;
use App\Models\Order;

// Resources
use App\Http\Resources\Product\ProductResource;

class CustomerProductRepo implements CustomerProductRepoInterface {

    public function searchProduct($request) {
 
        $searchQuery = $request -> input('ProductName');

        $products = Product::select('id', 'en_name', 'ar_name')
        -> when($searchQuery, function ($query, $searchQuery) {
            return $query->searchByAll($searchQuery);
        })
        -> take(10)
        -> get();

        return response() -> json([
            'status' => 'success',
            'data' => $products
        ]);

    }

    public function searchAllProducts($request) {
 
        $searchQuery = $request -> input('ProductName');

        $products = Product::select('id', 'en_name', 'ar_name', 'images', 'unit_price', 'discount', 'reviews', 'stars')
        -> when($searchQuery, function ($query, $searchQuery) {
            return $query->searchByAll($searchQuery);
        })
        -> take(10)
        -> get();

        return response() -> json([
            'status' => 'success',
            'data' => $products
        ]);

    }

    public function getProducts($request) {

        // Filter
        $price_from = $request -> input('priceFrom');
        $price_to = $request -> input('priceTo');
        $discount_from = $request -> input('discountFrom');
        $discount_to = $request -> input('discountTo');
        $status = $request -> input('status') != null ? explode(',', $request -> input('status')) : null;
        $delivery = $request -> input('delivery') != null ? explode(',', $request -> input('delivery')) : null;
        $category = $request -> input('category') != null ? explode(',', $request -> input('category')) : null;
        $review = $request -> input('review');

        // Sort
        $sort_by = $request -> input('sortBy');
        $sort_dir = $request -> input('sortDir');

        $products = Product::select('products.*', 'user_products.addToFav', 'user_products.addToCart', 'user_products.rate', 'user_products.comment')
            -> leftJoin('user_products', function($join) {
            $join -> on('user_products.product_id', '=', 'products.id')
                    -> where('user_products.user_id', Auth::user() ?-> id);
        });

        if ($price_from) {
            $products -> whereRaw('(unit_price - (unit_price * (discount / 100))) >= ?', [$price_from]);
        }

        if ($price_to) {
            $products -> whereRaw('(unit_price - (unit_price * (discount / 100))) <= ?', [$price_to]);
        }

        if ($discount_from) {
            $products -> where('discount', '>=', $discount_from);
        }

        if ($discount_to) {
            $products -> where('discount', '<=', $discount_to);
        }

        if ($status) {
            $products -> whereIn('status', $status);
        }

        if ($delivery) {
            $products -> whereIn('delivery', $delivery);
        }

        if ($category) {
            $products -> whereIn('cat_id', $category);
        }

        if ($review) {
            $products -> whereRaw('round(nvl((stars / reviews), 0)) = ?', [$review]);
        }

        if ($sort_by && $sort_dir) {
            $products -> orderBy($sort_by, $sort_dir);
        }

        $products = $products -> paginate(10);

        return response() -> json([
            'status' => 'success',
            'data' => ProductResource::collection($products),
            'pagination' => collect($products) -> except('data')
        ]);

    }

    public function getProduct($productID) {

        $product = Product::with(['product_reviews' => function($q) {
                        $q -> with(['user' => function ($nq) {
                            $nq -> select('id', 'name');
                        }]) -> select('product_id', 'user_id', 'comment', 'rate') -> whereNotNull('comment') -> orWhereNotNull('rate');
                    }])
                    -> select('products.*', 'users.name', 'categories.en_cat_name', 'categories.ar_cat_name', 'user_products.addToFav', 'user_products.addToCart', 'user_products.rate', 'user_products.comment')
                    -> leftJoin('user_products', function($join) {
                        $join -> on('user_products.product_id', '=', 'products.id')
                                -> where('user_products.user_id', Auth::user() ?-> id);
                    })
                    -> join('categories', function($join) {
                        $join -> on('categories.id', '=', 'products.cat_id');
                    })
                    -> join('users', function($join) {
                        $join -> on('users.id', '=', 'products.add_by');
                    })
                    -> whereRaw('products.id = ?', $productID)
                    -> first();

        if ($product) {
            return response() -> json([
                'status' => 'success',
                'data' => $product
            ], 200);
        }

        return response() -> json([
            'status' => 'error',
            'message' => 'product not found'
        ], 404);

    }

    public function getFavourites() {

        $favourites = Product::select('products.*', 'user_products.addToFav', 'user_products.addToCart', 'user_products.rate', 'user_products.comment')
            -> join('user_products', function ($join) {
            $join->on('user_products.product_id', '=', 'products.id')
                ->where('user_products.user_id', '=', 1)
                ->where('user_products.addToFav', '=', 1);
            })
            -> get();

        return response() -> json([
            'status' => 'success',
            'data' => ProductResource::collection($favourites)
        ]);

    }

    public function getCart() {

        $favourites = Product::select('products.*', 'user_products.addToFav', 'user_products.addToCart', 'user_products.rate', 'user_products.comment', 'user_products.quantity')
            -> join('user_products', function ($join) {
            $join -> on('user_products.product_id', '=', 'products.id')
                -> where('user_products.user_id', '=', 1)
                -> where('user_products.addToCart', '=', 1);
            }) -> get();

        return response() -> json([
            'status' => 'success',
            'data' => ProductResource::collection($favourites)
        ]);

    }

    public function removeFromCart($request) {

        $removed = UserProduct::where('product_id', $request -> productID)
                                -> where('user_id', Auth::user() -> id)
                                -> where('addToCart', 1)
                                -> update([
                                    'addToCart' => 0
                                ]);

        return response() -> json([
            'status' => $removed ? 'success' : 'error'
        ]);

    }

    public function getCartCount() {

        $CartCount = UserProduct::where('user_id', Auth::user() -> id)
                    -> where('addToCart', 1)
                    -> count();

        return response() -> json([
            'status' => 'success',
            'data' => $CartCount
        ], 200);

    }

    public function addToFav($productID, $addToFav) {

        $product = UserProduct::updateOrCreate(
            ['product_id' => $productID],
            ['product_id' => $productID, 'addToFav' => $addToFav, 'user_id' => Auth::user() -> id]
        );

        return $product;

    }

    public function addToCart($productID, $addToCart) {

        $product = UserProduct::updateOrCreate(
            ['product_id' => $productID],
            ['product_id' => $productID, 'addToCart' => $addToCart, 'user_id' => Auth::user() -> id]
        );

        return $product;

    }

    public function updateQuantity($productID, $quantity) {

        UserProduct::where('product_id', $productID)
                    -> where('user_id', Auth::user() -> id)
                    -> update([
                        'quantity' => $quantity
                    ]);

        return response() -> json([
            'status' => 'sucess'
        ]);

    }

    public function getMyOrders() {

        $orders = Order::select('InvoiceValue', 'paid', 'payment', 'id', 'user_id', 'verification_code')
                        -> with([
                            'products' => function($query) {
                                $query -> select('en_name', 'ar_name', 'unit_price', 'discount') -> where('add_by', Auth::user() -> id);
                            },
                            'user:name,id'
                        ])
                        -> where('user_id', Auth::user() -> id)
                        -> where('status', 'ordered')
                        -> get();

        return response() -> json([
            'status' => 'success',
            'data' => $orders
        ]);

    }

}
