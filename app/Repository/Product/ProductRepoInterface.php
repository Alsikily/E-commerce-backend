<?php

namespace App\Repository\Product;

interface ProductRepoInterface {

    public function getMyProducts();
    public function getOrders();
    public function addProduct($request);
    public function delivered($request);

}