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
        Schema::create('user_services', function (Blueprint $table) {
            $table->id();
            $table->integer('service_id');
            $table->integer('uid');
            $table->string('cover')->nullable();
            $table->string('duration')->nullable();
            $table->string('price')->nullable();
            $table->string('off')->nullable();
            $table->string('discount')->nullable();
            $table->string('descriptions')->nullable();
            $table->string('images')->nullable();
            $table->integer('status')->nullable();
            $table->string('extra_field')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_services');
    }
};
