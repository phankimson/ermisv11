<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection(env('CONNECTION_DB_POS', 'mysql3'))->create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sku', 50)->unique();
            $table->string('barcode', 100)->nullable()->unique();
            $table->string('name', 255);
            $table->string('unit', 50)->default('cai');
            $table->decimal('sale_price', 18, 2)->default(0);
            $table->decimal('cost_price', 18, 2)->default(0);
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection(env('CONNECTION_DB_POS', 'mysql3'))->dropIfExists('products');
    }
};

