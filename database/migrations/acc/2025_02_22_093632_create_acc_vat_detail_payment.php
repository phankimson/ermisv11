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
        Schema::connection('mysql2')->create('acc_vat_detail_payment', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('vat_detail_id');
            $table->uuid('general_id');
            $table->decimal('payment', total: 11, places: 2);
            $table->timestamps();
            $table->primary('id');
            $table->index(['vat_detail_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acc_vat_detail_payment');
    }
};
