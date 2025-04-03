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
        Schema::table('acc_vat_detail', function (Blueprint $table) {
            $table->tinyInteger('payment')->after('total_amount_rate'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acc_vat_detail', function (Blueprint $table) {
            //
        });
    }
};
