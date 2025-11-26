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
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('source')->nullable(); 
            $table->string('end_point')->nullable();
            $table->string('method')->nullable();
            $table->integer('http_code')->nullable();
            $table->longText('payload')->nullable();
            $table->longText('error_message')->nullable();
            $table->enum('status', ['success', 'error', 'warning', 'info'])->default('info');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
