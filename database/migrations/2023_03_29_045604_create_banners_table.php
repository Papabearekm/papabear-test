<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('position')->default('home');
            $table->integer('duration')->default(1);
            $table->string('price')->default(0.00);
            $table->text('cover')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->text('value')->nullable();
            $table->string('link')->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->text('extra_field')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
};
