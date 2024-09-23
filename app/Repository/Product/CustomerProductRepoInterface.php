<?php

namespace App\Repository\Product;

interface CustomerProductRepoInterface {

    public function searchProduct($request);
    public function searchAllProducts($request);
    public function getProducts($request);
    public function getProduct($productID);
    public function getFavourites();
    public function getCart();
    public function removeFromCart($request);
    public function getCartCount();
    public function getMyOrders();
    public function addToFav($productID, $addToFav);
    public function addToCart($productID, $addToFav);
    public function updateQuantity($productID, $quantity);

}