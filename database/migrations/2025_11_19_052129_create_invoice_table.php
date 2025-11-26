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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('estimate_id');
            $table->string('syncro_invoice_id');
            $table->string('syncro_invoice_number');
            $table->date('syncro_invoice_date');
            $table->date('syncro_invoice_due_date');
            $table->float('syncro_invoice_subtotal');
            $table->float('syncro_invoice_total');
            $table->float('syncro_invoice_tax');
            $table->text('syncro_invoice_note')->nullable();
            $table->text('eset_license_key')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('estimate_id')->references('id')->on('estimates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
