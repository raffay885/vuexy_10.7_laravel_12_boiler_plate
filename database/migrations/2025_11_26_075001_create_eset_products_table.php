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
        Schema::create('eset_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('syncro_product_id')->nullable();
            $table->string('eset_product_code')->nullable();
            $table->string('eset_product_name')->nullable();
            $table->timestamps();
            $table->foreign('syncro_product_id')->references('id')->on('syncro_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eset_products');
    }
};
