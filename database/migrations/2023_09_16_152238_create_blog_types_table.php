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
        Schema::create('blog_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_type')->nullable();
            $table->unsignedBigInteger('id_blog')->nullable();
            $table->foreign('id_type')->references('id')->on('types')->cascadeOnDelete();
            $table->foreign('id_blog')->references('id')->on('blogs')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_types');
    }
};
