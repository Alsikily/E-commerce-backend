<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('categories', function (Blueprint $table) {
            $table -> smallIncrements('id');
            $table -> string('en_cat_name');
            $table -> string('ar_cat_name');
        });
    }

    public function down(): void {
        Schema::dropIfExists('categories');
    }

};
