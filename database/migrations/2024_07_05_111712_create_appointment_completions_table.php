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
        Schema::create('appointment_completions', function (Blueprint $table) {
            $table->id();
            $table->string('appointment_id');
            $table->string('employee_name');
            $table->text('remarks')->nullable();
            $table->string('reminder_date')->nullable();
            $table->text('reminder_description')->nullable();
            $table->string('status')->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_completions');
    }
};
