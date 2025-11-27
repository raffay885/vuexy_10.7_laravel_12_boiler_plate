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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('syncro_subdomain')->nullable();
            $table->string('syncro_domain')->nullable();
            $table->string('syncro_api_key')->nullable();
            $table->string('eset_base_url')->nullable();
            $table->string('eset_username')->nullable();
            $table->string('eset_password')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
