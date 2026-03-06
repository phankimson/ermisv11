<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection(env('CONNECTION_DB_POS', 'mysql3'))->create('pos_ermis_inventories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('warehouse_id');
            $table->uuid('product_id');
            $table->decimal('quantity', 18, 3)->default(0);
            $table->tinyInteger('active')->default(1);
            $table->timestamps();

            $table->unique(['warehouse_id', 'product_id'], 'pos_inventory_unique_warehouse_product');
            $table->foreign('warehouse_id')->references('id')->on('pos_ermis_warehouses')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')->on('pos_ermis_products')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::connection(env('CONNECTION_DB_POS', 'mysql3'))->dropIfExists('pos_ermis_inventories');
    }
};

