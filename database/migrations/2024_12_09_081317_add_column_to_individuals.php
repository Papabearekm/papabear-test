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
        Schema::table('individual', function (Blueprint $table) {
            $table->string('id_proof')->nullable()->after('upgrade');
            $table->string('id_proof_back')->nullable()->after('id_proof');
            $table->string('bank_name')->nullable()->after('id_proof_back');
            $table->string('bank_ifsc')->nullable()->after('bank_name');
            $table->string('bank_account_number')->nullable()->after('bank_ifsc');
            $table->string('bank_customer_name')->nullable()->after('bank_account_number');
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
        Schema::table('individual', function (Blueprint $table) {
            $table->dropColumn('id_proof');
            $table->dropColumn('id_proof_back');
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_ifsc');
            $table->dropColumn('bank_account_number');
            $table->dropColumn('bank_customer_name');
            $table->dropColumn('heard_us_from');
            $table->dropColumn('executive_id');
            $table->dropColumn('whatsapp_number');
            $table->dropColumn('team_size');
        });
    }
};
