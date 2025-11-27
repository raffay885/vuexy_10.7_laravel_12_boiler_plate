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
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->comment('customers');
            $table->string('syncro_estimate_id');
            $table->unsignedBigInteger('syncro_product_id')->nullable()->comment('syncro_products');
            $table->string('syncro_estimate_number');
            $table->float('syncro_estimate_subtotal')->default(0);
            $table->float('syncro_estimate_total')->default(0);
            $table->float('syncro_estimate_tax')->default(0);
            $table->integer('quantity')->default(0);
            $table->dateTime('approved_at')->nullable();
            $table->float('invoice_total')->default(0);
            $table->enum('status', ['Draft', 'Fresh', 'Approved', 'Declined', 'Invoice Made']);
            $table->text('note');
            $table->boolean('is_annual')->default(1)->comment('0 = General, 1 = Annual');
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('syncro_product_id')->references('id')->on('syncro_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};
