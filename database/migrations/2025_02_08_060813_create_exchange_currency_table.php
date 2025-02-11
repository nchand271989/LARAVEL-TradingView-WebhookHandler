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
            $table->unsignedBigInteger('exid');
            $table->unsignedBigInteger('curid');
            $table->foreign('exid')->references('exid')->on('exchanges')->onDelete('cascade');
            $table->foreign('curid')->references('curid')->on('currencies')->onDelete('cascade');
            $table->primary(['exid', 'curid']);
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
