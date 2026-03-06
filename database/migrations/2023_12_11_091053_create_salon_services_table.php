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
        Schema::create('salon_services', function (Blueprint $table) {
            $table->id();
            $table->integer('uid');
            $table->integer('service_id');
            $table->string('cover')->nullable();
            $table->double('duration',10,2)->nullable();
            $table->double('price',10,2)->nullable();
            $table->integer('off');
            $table->double('discount',10,2)->nullable();
            $table->text('descriptions')->nullable();
            $table->text('images')->nullable();
            $table->text('extra_field')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salon_services');
    }
};
