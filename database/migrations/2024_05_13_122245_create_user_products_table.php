<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {

        Schema::create('user_products', function (Blueprint $table) {
            $table -> id();
            $table -> boolean('addToFav') -> nullable();
            $table -> boolean('addToCart') -> nullable();
            $table -> unsignedBigInteger('user_id');
            $table -> foreign('user_id')
                    -> references('id')
                    -> on('users')
                    -> onDelete('cascade')
                    -> onUpdate('cascade');
            $table -> unsignedTinyInteger('rate') -> nullable();
            $table -> unsignedSmallInteger('quantity') -> default(1);
            $table -> text('comment') -> nullable();
            $table -> unsignedBigInteger('product_id');
            $table -> foreign('product_id')
                    -> references('id')
                    -> on('products')
                    -> onDelete('cascade')
                    -> onUpdate('cascade');
            $table -> unsignedBigInteger('transaction_id') -> nullable();
            $table -> foreign('transaction_id')
                    -> references('id')
                    -> on('transactions')
                    -> onDelete('set null')
                    -> onUpdate('set null');
        });
    }

    public function down(): void {
        Schema::dropIfExists('user_products');
    }

};
