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
        Schema::table('acc_vat_detail_payment', function (Blueprint $table) {
            $table->decimal('rate', total: 11, places: 2);
            $table->decimal('payment_rate', total: 11, places: 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
