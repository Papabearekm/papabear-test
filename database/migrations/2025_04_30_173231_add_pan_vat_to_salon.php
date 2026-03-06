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
            $table->string('pan')->nullable()->after('facilities');
            $table->string('vat')->nullable()->after('pan');
            $table->string('invoice_prefix')->nullable()->after('vat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salon', function (Blueprint $table) {
            $table->dropColumn('pan');
            $table->dropColumn('vat');
            $table->dropColumn('invoice_prefix');
        });
    }
};
