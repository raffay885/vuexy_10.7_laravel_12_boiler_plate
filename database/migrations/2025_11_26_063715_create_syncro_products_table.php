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
        Schema::create('syncro_products', function (Blueprint $table) {
            $table->id();
            $table->string('syncro_product_id')->nullable();
            $table->string('syncro_product_title')->nullable();
            $table->enum('billing_type', ['monthly', 'annual'])->default('annual');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syncro_products');
    }
};
