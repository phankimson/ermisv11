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
        Schema::create('acc_vat_info', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('general_id');
            $table->string('name', length: 100);
            $table->string('address', length: 100);
            $table->string('identity_card', length: 20);
            $table->timestamps();
            $table->primary('id');
            $table->index(['id', 'created_at']);
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
