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
        Schema::create('exchange_currency', function (Blueprint $table) {
            $table->uuid('exchange_id');
            $table->uuid('currency_id');
            $table->foreign('exchange_id')->references('exid')->on('exchanges')->onDelete('cascade');
            $table->foreign('currency_id')->references('curid')->on('currencies')->onDelete('cascade');
            $table->primary(['exchange_id', 'currency_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_currency');
    }
};
