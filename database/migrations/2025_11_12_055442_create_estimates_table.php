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
            $table->unsignedBigInteger('customer_id');
            $table->string('syncro_estimate_id');
            $table->string('number');
            $table->date('date');
            $table->string('estimate_invoice_id')->nullable();
            $table->float('estimate_subtotal');
            $table->float('estimate_total');
            $table->float('estimate_tax');
            $table->enum('status', ['Draft', 'Fresh', 'Approved', 'Declined', 'Invoice Made']);
            $table->text('note');
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
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
