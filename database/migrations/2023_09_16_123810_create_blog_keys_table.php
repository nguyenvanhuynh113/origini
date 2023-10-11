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
        Schema::create('blog_keys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_key')->nullable();
            $table->unsignedBigInteger('id_blog')->nullable();
            $table->timestamps();
            $table->foreign('id_key')->references('id')->on('keys')->cascadeOnDelete();
            $table->foreign('id_blog')->references('id')->on('blogs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_keys');
    }
};
