<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cart')->nullable();
            $table->unsignedBigInteger('id_product')->nullable();
            $table->smallInteger('quantity');
            $table->foreign('id_cart')->references('id')->on('carts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('id_product')->references('id')->on('products')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
