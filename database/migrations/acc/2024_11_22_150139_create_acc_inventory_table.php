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
        Schema::create('acc_inventory', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('general_id');
            $table->uuid('detail_id');
            $table->uuid('item_id');
            $table->string('item_code', length: 50);
            $table->string('item_name', length: 100);
            $table->string('item_name_en', length: 100);
            $table->uuid('unit');
            $table->uuid('stock');
            $table->decimal('quantity', total: 11, places: 2);
            $table->decimal('quantity_receipt', total: 11, places: 2);
            $table->decimal('price', total: 11, places: 2);
            $table->decimal('purchase_price', total: 11, places: 2);
            $table->decimal('amount', total: 11, places: 2);
            $table->decimal('purchase_amount', total: 11, places: 2);
            $table->decimal('discount', total: 11, places: 2);
            $table->decimal('discount_percent', total: 11, places: 2);
            $table->decimal('total_discount', total: 11, places: 2);
            $table->date('expiry_date');
            $table->timestamps();
            $table->primary('id');
            $table->index(['item_id', 'created_at']);
        });

      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acc_inventory');
    }
};
