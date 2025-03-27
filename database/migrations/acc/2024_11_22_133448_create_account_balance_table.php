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
        Schema::connection('mysql2')->create('account_balance', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('period');
            $table->uuid('account_systems');
            $table->decimal('debit_open', total: 11, places: 2);
            $table->decimal('credit_open', total: 11, places: 2);
            $table->decimal('debit', total: 11, places: 2);
            $table->decimal('credit', total: 11, places: 2);
            $table->decimal('debit_close', total: 11, places: 2);
            $table->decimal('credit_close', total: 11, places: 2);
            $table->timestamps();
            $table->primary('id');
            $table->index(['account_systems', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_balance');
    }
};
