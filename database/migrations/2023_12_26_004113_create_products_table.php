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
            $table->string('name');
            $table->decimal('price', 5, 2);
            $table->string('color')->nullable();
            $table->string('Size')->nullable();
            $table->decimal('Weight', 8, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('Discount', 5, 2)->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('Brand')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(0); // Fixed commission rate
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
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
