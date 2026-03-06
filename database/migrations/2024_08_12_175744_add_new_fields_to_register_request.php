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
        Schema::table('register_request', function (Blueprint $table) {
            $table->string('heard_us_from')->nullable()->after('bank_customer_name');
            $table->string('executive_id')->nullable()->after('heard_us_from');
            $table->string('whatsapp_number')->nullable()->after('executive_id');
            $table->string('team_size')->nullable()->after('whatsapp_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('register_request', function (Blueprint $table) {
            $table->dropColumn('heard_us_from');
            $table->dropColumn('executive_id');
            $table->dropColumn('whatsapp_number');
            $table->dropColumn('team_size');
        });
    }
};
