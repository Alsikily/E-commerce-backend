<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

// Requests
use App\Http\Requests\Product\StoreProduct;

// Interfaces
use App\Repository\Product\ProductRepoInterface;

class SalerProductController extends Controller {

    private $productRepo;

    public function __construct(ProductRepoInterface $productRepo) {
        $this -> productRepo = $productRepo;
    }

    public function index() {
        return $this -> productRepo -> getMyProducts();
    }

    public function getOrders() {
        return $this -> productRepo -> getOrders();
    }

    public function delivered(Request $request) {
        return $this -> productRepo -> delivered($request);
    }

    public function store(StoreProduct $request) {
        return $this -> productRepo -> addProduct($request);
    }

    public function show(Product $product) {



    }

    public function update(Request $request, Product $product) {



    }

    public function destroy(Product $product) {

        $product -> delete();
        return response() -> json([
            'status' => 'success'
        ], 201);

    }

}
