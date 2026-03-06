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
        Schema::table('salon', function (Blueprint $table) {
            $table->integer('upgrade')->default(0)->after('extra_field');
            $table->string('id_proof')->nullable()->after('upgrade');
            $table->string('bank_name')->nullable()->after('id_proof');
            $table->string('bank_ifsc')->nullable()->after('bank_name');
            $table->string('bank_account_number')->nullable()->after('bank_ifsc');
            $table->string('bank_customer_name')->nullable()->after('bank_account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salon', function (Blueprint $table) {
            $table->dropColumn('upgrade');
            $table->dropColumn('id_proof');
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_ifsc');
            $table->dropColumn('bank_account_number');
            $table->dropColumn('bank_customer_name');
        });
    }
};
