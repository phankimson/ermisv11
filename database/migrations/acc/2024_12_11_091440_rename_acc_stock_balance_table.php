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
        Schema::connection('mysql2')->table('stock_balance', function (Blueprint $table) {
            $table->renameColumn('amount', 'amount_receipt');
            $table->renameColumn('quantity', 'quantity_receipt');
            $table->decimal('amount_issue', total: 11, places: 2);
            $table->decimal('quantity_issue', total: 11, places: 2); 
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
