<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection(env('CONNECTION_DB_POS', 'mysql3'))->create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 60)->unique();
            $table->string('type', 30);
            $table->date('transaction_date');
            $table->uuid('warehouse_id')->nullable();
            $table->uuid('warehouse_to_id')->nullable();
            $table->string('cashier_id', 36)->nullable();
            $table->decimal('total_amount', 18, 2)->default(0);
            $table->string('note', 500)->nullable();
            $table->tinyInteger('active')->default(1);
            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->nullOnDelete();
            $table->foreign('warehouse_to_id')->references('id')->on('warehouses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::connection(env('CONNECTION_DB_POS', 'mysql3'))->dropIfExists('transactions');
    }
};

