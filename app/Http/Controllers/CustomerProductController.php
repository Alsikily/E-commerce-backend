<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Requests
use App\Http\Requests\Product\GetProducts;
use App\Http\Requests\Product\SearchProduct;
use App\Http\Requests\Product\UpdateQuantity;
use App\Http\Requests\CartPaymentRequest;
use App\Http\Requests\PaymentIdRequest;

// Services
use App\Http\Services\PaymentService;

// Interfaces
use App\Repository\Product\CustomerProductRepoInterface;

class CustomerProductController extends Controller {

    private $productRepo;
    private $PaymentService;

    public function __construct(CustomerProductRepoInterface $productRepo, PaymentService $PaymentService) {
        $this -> productRepo = $productRepo;
        $this -> PaymentService = $PaymentService;
    }

    public function index(GetProducts $request) {
        return $this -> productRepo -> getProducts($request);
    }

    public function getProduct($productID) {
        return $this -> productRepo -> getProduct($productID);
    }

    public function search(SearchProduct $request) {
        return $this -> productRepo -> searchProduct($request);
    }

    public function searchAll(SearchProduct $request) {
        return $this -> productRepo -> searchAllProducts($request);
    }

    public function favourites() {
        return $this -> productRepo -> getFavourites();
    }

    public function cart() {
        return $this -> productRepo -> getCart();
    }

    public function removeFromCart(Request $request) {
        return $this -> productRepo -> removeFromCart($request);
    }

    public function cartCount() {
        return $this -> productRepo -> getCartCount();
    }

    public function addToFav($productID, Request $request) {
        return $this -> productRepo -> addToFav($productID, $request -> addToFav);
    }

    public function addToCart($productID, Request $request) {
        return $this -> productRepo -> addToCart($productID, $request -> addToCart);
    }

    public function updateQuantity($productID, UpdateQuantity $request) {
        return $this -> productRepo -> updateQuantity($productID, $request -> quantity);
    }

    public function cartPayment(CartPaymentRequest $request) {
        return $this -> PaymentService -> cartPayment($request);
    }

    public function paymentCallbackSuccess(PaymentIdRequest $request) {
        return $this -> PaymentService -> paymentCallbackSuccess($request -> PaymentId);
    }

    public function orders() {
        return $this -> productRepo -> getMyOrders();
    }

    // public function paymentCallbackFaild() {
    //     return $this -> PaymentService -> paymentCallbackFaild();
    // }

}
