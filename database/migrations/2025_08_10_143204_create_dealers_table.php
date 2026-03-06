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
        Schema::create('dealers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uid')->constrained('users');
            $table->string('name');
            $table->string('cover');
            $table->string('address')->nullable();
            $table->foreignId('city')->constrained('cities');
            $table->string('zip_code');
            $table->string('id_proof')->nullable();
            $table->string('id_proof_back')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_customer_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};
