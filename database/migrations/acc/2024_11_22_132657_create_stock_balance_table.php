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
        Schema::connection('mysql2')->create('stock_balance', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('stock');
            $table->uuid('period');
            $table->uuid('supplies_goods');
            $table->decimal('number_open', total: 11, places: 2);
            $table->decimal('amount_open', total: 11, places: 2);
            $table->decimal('number', total: 11, places: 2);
            $table->decimal('amount', total: 11, places: 2);
            $table->decimal('number_close', total: 11, places: 2);
            $table->decimal('amount_close', total: 11, places: 2);
            $table->timestamps();
            $table->primary('id');
            $table->index(['supplies_goods', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_balance');
    }
};
