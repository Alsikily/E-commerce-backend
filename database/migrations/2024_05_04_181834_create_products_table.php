<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('en_name');
            $table->string('ar_name');
            $table->float('unit_price');
            $table->unsignedTinyInteger('discount');
            $table->text('en_description') -> nullable();
            $table->text('ar_description') -> nullable();
            $table->enum('delivery', ['free', 'paid']);
            $table->float('delivery_charge');
            $table->unsignedInteger('quantity');
            $table->enum('status', ['like new', 'good', 'fair', 'poor', 'damaged', 'refurbished', 'parts only']);
            $table->text('images');
            $table->unsignedInteger('views') -> default(0);
            $table -> unsignedSmallInteger('cat_id') -> nullanble();
            $table -> foreign('cat_id')
                    -> references('id')
                    -> on('categories')
                    -> onDelete('cascade')
                    -> onUpdate('cascade');
            $table -> unsignedBigInteger('add_by') -> nullanble();
            $table -> foreign('add_by')
                    -> references('id')
                    -> on('users')
                    -> onDelete('cascade')
                    -> onUpdate('cascade');
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
