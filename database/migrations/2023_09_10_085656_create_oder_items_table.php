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
        Schema::create('oder_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_product')->nullable();
            $table->unsignedBigInteger('id_oder')->nullable();
            $table->smallInteger('quantity');
            $table->decimal('unit_prices', 10, 2);
            $table->timestamps();
            $table->foreign('id_product')->references('id')->on('products')->cascadeOnDelete();
            $table->foreign('id_oder')->references('id')->on('oders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oder_items');
    }
};
