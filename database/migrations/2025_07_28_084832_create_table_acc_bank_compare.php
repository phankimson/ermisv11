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
        Schema::create('bank_compare', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('bank_account');
            $table->dateTime('accounting_date');
            $table->decimal('debit_amount', total: 11, places: 2);
            $table->decimal('credit_amount', total: 11, places: 2);
            $table->string('transaction_description', length: 100);           
            $table->string('transaction_number', length: 50);
            $table->string('corresponsive_account', length: 100);
            $table->string('corresponsive_name', length: 50);
            $table->tinyInteger('status');
            $table->timestamps();          
            $table->index(['bank_account', 'accounting_date']);
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
