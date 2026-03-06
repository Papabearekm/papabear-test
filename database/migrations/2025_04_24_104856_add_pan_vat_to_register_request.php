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
            $table->string('pan')->nullable()->after('team_size');
            $table->string('vat')->nullable()->after('pan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('register_request', function (Blueprint $table) {
            $table->dropColumn('pan');
            $table->dropColumn('vat');
        });
    }
};
