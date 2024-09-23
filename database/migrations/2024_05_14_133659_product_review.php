<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            $table -> unsignedBigInteger('reviews') -> default(0);
            $table -> unsignedBigInteger('stars') -> default(0);
        });
    }

    public function down(): void {
        Schema::table('products', function (Blueprint $table) {

        });
    }

};
