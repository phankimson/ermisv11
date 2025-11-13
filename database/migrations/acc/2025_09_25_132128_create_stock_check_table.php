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
        Schema::create('stock_check', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('type');
            $table->uuid('stock');
            $table->uuid('supplies_goods');
            $table->decimal('quantity', total: 11, places: 2);
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
        Schema::dropIfExists('stock_check');
    }
};
