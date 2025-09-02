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
        Schema::create('account_transfer', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('debit');
            $table->uuid('credit');
            $table->string('code', length: 50);
            $table->string('name', length: 100);
            $table->string('name_en', length: 100);
            $table->tinyInteger('type')->default(0);
            $table->tinyInteger('object')->default(0);
            $table->tinyInteger('case_code')->default(0);
            $table->tinyInteger('cost_code')->default(0);
            $table->tinyInteger('statistical_code')->default(0);
            $table->tinyInteger('work_code')->default(0);
            $table->tinyInteger('department')->default(0);
            $table->tinyInteger('position')->default(0);
            $table->tinyInteger('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void    {
        Schema::dropIfExists('account_transfer');
    }
};
